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
			$result = $this->Item_model->update($_POST);
		} else if ($action == 'get_by_id') {
			$result = $this->Item_model->get_by_id(array( 'id' => $_POST['id'] ));
		} else if ($action == 'delete') {
			$result = $this->Item_model->delete($_POST);
		}
		
		echo json_encode($result);
	}
	
	function view() {
		$this->load->view( 'panel/product/popup/item' );
	}
	
	function do_scrape() {
		$scrape_id = (isset($_GET['scrape_id'])) ? $_GET['scrape_id'] : 0;
		if (empty($scrape_id)) {
			exit;
		}
		
		// scrape info
		$scrape = $this->Scrape_model->get_by_id(array( 'id' => $scrape_id ));
		
		// get item with incomplete data
		if (false) {
		}
		
		// get link page from scrape page with status incomplete
		if (false) {
		}
		
		// get link from link main scrape
		else {
			$this->load->library('scrape/'.$scrape['library']);
			$scrape_result = $this->$scrape['library']->scrape_page(array( 'link' => $scrape['link'] ));
			
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
					$param_item = array( 'scrape_id' => $scrape['id'], 'link_source' => $link_source );
					$this->Item_model->update($param_item);
					
					// message
					$item_count = (isset($item_count)) ? $item_count + 1 : 1;
				}
				
				if (!empty($item_count)) {
					$message .= "<div>$item_count Item Halaman ditemukan</div>";
				}
			}
		}
		
		// show view status
		$this->load->view( 'panel/product/scrape_info', array( 'message' => $message ) );
	}
}