<?php
class item extends XX_Controller {
    function __construct() {
        parent::__construct();
    }
    
    function index() {
		$this->load->view( 'panel/product/item' );
    }
	
	function grid() {
		$result['rows'] = $this->Item_model->get_array($_POST);
		$result['count'] = $this->Item_model->get_count();
		
		echo json_encode($result);
	}
	
	function action() {
		$action = (isset($_POST['action'])) ? $_POST['action'] : '';
		unset($_POST['action']);
		
		$result = array();
		if ($action == 'update') {
			$result = $this->Item_model->update_complex($_POST);
		} else if ($action == 'get_by_id') {
			$result = $this->Item_model->get_by_id(array( 'id' => $_POST['id'], 'tag_include' => true ));
		} else if ($action == 'delete') {
			$result = $this->Item_model->delete($_POST);
		}
		
		echo json_encode($result);
	}
	
	function view() {
		$form_name = (isset($_POST['form_name'])) ? $_POST['form_name'] : 'item';
		$this->load->view( 'panel/product/popup/'.$form_name );
	}
	
	function do_scrape() {
		$scrape_id = (isset($_GET['scrape_id'])) ? $_GET['scrape_id'] : 0;
		if (empty($scrape_id)) {
			exit;
		}
		
		// scrape info
		$scrape = $this->Scrape_model->get_by_id(array( 'id' => $scrape_id ));
		$this->load->library('scrape/'.$scrape['library']);
		
		// get data
		$is_refresh = true;
		$item_incomplete = $this->Item_model->get_item_incomplete(array( 'scrape_id' => $scrape_id ));
		$page_incomplete = $this->Scrape_Page_model->get_page_incomplete(array( 'scrape_id' => $scrape_id ));
		$item_request_rescrape = (isset($_GET['item_request_rescrape'])) ? $this->Item_model->get_by_id(array( 'id' => $_GET['item_request_rescrape'] )) : array();
		
		// get item with incomplete data
		if (count($item_request_rescrape) > 0 || count($item_incomplete) > 0) {
			if (count($item_request_rescrape) > 0) {
				$is_refresh = false;
				$is_rescrape = true;
				$item_id = $item_request_rescrape['id'];
				$scrape_result = $this->$scrape['library']->scrape_item(array( 'link' => $item_request_rescrape['link_source'] ));
			} else {
				$is_rescrape = false;
				$item_id = $item_incomplete['id'];
				$scrape_result = $this->$scrape['library']->scrape_item(array( 'link' => $item_incomplete['link_source'] ));
			}
			
			// data
			$brand = $this->Brand_model->get_by_id(array( 'name' => $scrape_result['brand_name'], 'is_force' => true ));
			
			// item
			$item_param = $scrape_result;
			$item_param['id'] = $item_id;
			$item_param['brand_id'] = @$brand['id'];
			$item_param['category_sub_id'] = $scrape['category_sub_id'];
			$item_param['store'] = $scrape['store'];
			$item_param['item_status_id'] = ITEM_STATUS_REVIEW;
			$item_param['date_update'] = $this->config->item('current_datetime');
			
			if (!$is_rescrape) {
				$item_param['alias'] = $this->Item_model->get_name($scrape_result['name']);
			}
			
			// execute
			$result = $this->Item_model->update_complex($item_param);
			$item = $this->Item_model->get_by_id($result);
			
			$message  = "<div>1 Halaman Item berhasil diperbaharui.</div>";
			$message .= '<div><a href="'.$item['item_link'].'">'.$item['name'].'</a></div>';
		}
		
		// get link from link page scrape
		else {
			if (!empty($_GET['scrape_page'])) {
				$scrape_result = $this->$scrape['library']->scrape_page(array( 'link' => $_GET['scrape_page'] ));
			} else if (count($page_incomplete) > 0) {
				$scrape_result = $this->$scrape['library']->scrape_page(array( 'link' => $page_incomplete['link'] ));
			} else {
				$scrape_result = $this->$scrape['library']->scrape_page(array( 'link' => $scrape['link'] ));
			}
			
			// set message
			$message = '';
			
			// insert page
			if (count($scrape_result['array_page']) > 0) {
				foreach ($scrape_result['array_page'] as $link) {
					// check
					$record = $this->Scrape_Page_model->get_by_id(array( 'link' => $link ));
					if (count($record) > 0) {
						continue;
					}
					
					// insert
					$param_scrape_page = array( 'scrape_id' => $scrape['id'], 'link' => $link );
					$this->Scrape_Page_model->update($param_scrape_page);
					
					// message
					$page_count = (isset($page_count)) ? $page_count + 1 : 1;
				}
				
				if (!empty($page_count)) {
					$message .= "<div>$page_count Link Halaman ditemukan</div>";
				}
			}
			
			// insert item
			if (count($scrape_result['array_item']) > 0) {
				foreach ($scrape_result['array_item'] as $link_source) {
					// check
					$record = $this->Item_model->get_by_id(array( 'link_source' => $link_source ));
					if (count($record) > 0) {
						continue;
					}
					
					// insert
					$param_item = array( 'scrape_id' => $scrape['id'], 'link_source' => $link_source, 'item_status_id' => ITEM_STATUS_INCOMPLETE );
					$this->Item_model->update_complex($param_item);
					
					// message
					$item_count = (isset($item_count)) ? $item_count + 1 : 1;
				}
				
				if (!empty($item_count)) {
					$message .= "<div>$item_count Item Halaman ditemukan</div>";
				}
			}
			
			// update page incomplete
			if (count($page_incomplete) > 0) {
				$param_update['id'] = $page_incomplete['id'];
				$param_update['is_finish'] = 1;
				$this->Scrape_Page_model->update($param_update);
			}
		}
		
		// show view status
		$this->load->view( 'panel/product/scrape_info', array( 'message' => $message, 'is_refresh' => $is_refresh ) );
	}
}