<?php

class item_scrape extends CI_Controller {
    function __construct() {
        parent::__construct();
    }
    
    function index() {
		$param_item = array( 'is_finish' => 0, 'limit' => 25 );
		$array_item = $this->Scrape_Item_model->get_array($param_item);
		
		$message = '';
		$item_update = 0;
		foreach ($array_item as $key => $row) {
			$item = $this->Item_model->get_by_id(array( 'id' => $row['id'] ));
			$scrape = $this->Scrape_model->get_by_id(array( 'id' => $item['scrape_id'] ));
			
			// load library
			$this->load->library('scrape/'.$scrape['library']);
			
			// scrape it
			$scrape_result = $this->$scrape['library']->scrape_item(array( 'link' => $item['link_source'] ));
			
			// update item
			$param_update['id'] = $item['id'];
			$param_update['price_show'] = $scrape_result['price_show'];
			$param_update['price_range'] = $scrape_result['price_range'];
			$param_update['status_stock'] = $scrape_result['status_stock'];
			$param_update['date_update'] = $this->config->item('current_datetime');
			$result = $this->Item_model->update_complex($param_update);
			
			// update scrape item
			$param_scrape['id'] = $row['id'];
			$param_scrape['is_finish'] = 1;
			$this->Scrape_Item_model->update($param_scrape);
			
			// log scrape
			$item_update++;
			$message .= '<div><a href="'.$item['item_link'].'">'.$item['name'].'</a></div>';
		}
		
		echo "<div>$item_update Halaman Item berhasil diperbaharui.</div>";
		echo $message;
    }
}