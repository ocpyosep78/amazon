<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Scrape_Item_model extends CI_Model {
    function __construct() {
        parent::__construct();
		
        $this->field = array( 'id', 'item_id', 'is_finish' );
    }

    function update($param) {
        $result = array();
       
        if (empty($param['id'])) {
            $insert_query  = GenerateInsertQuery($this->field, $param, SCRAPE_ITEM);
            $insert_result = mysql_query($insert_query) or die(mysql_error());
           
            $result['id'] = mysql_insert_id();
            $result['status'] = '1';
            $result['message'] = 'Data berhasil disimpan.';
        } else {
            $update_query  = GenerateUpdateQuery($this->field, $param, SCRAPE_ITEM);
            $update_result = mysql_query($update_query) or die(mysql_error());
           
            $result['id'] = $param['id'];
            $result['status'] = '1';
            $result['message'] = 'Data berhasil diperbaharui.';
        }
       
        return $result;
    }

    function get_by_id($param) {
        $array = array();
       
        if (isset($param['id'])) {
            $select_query  = "SELECT * FROM ".SCRAPE_ITEM." WHERE id = '".$param['id']."' LIMIT 1";
        } 
       
        $select_result = mysql_query($select_query) or die(mysql_error());
        if (false !== $row = mysql_fetch_assoc($select_result)) {
            $array = $this->sync($row);
        }
       
        return $array;
    }
	
    function get_array($param = array()) {
        $array = array();
		
		$string_item = (!empty($param['item_id'])) ? "AND ScrapeItem.item_id = '".$param['item_id']."'" : '';
		$string_finish = (isset($param['is_finish'])) ? "AND ScrapeItem.is_finish = '".$param['is_finish']."'" : '';
		$string_filter = GetStringFilter($param, @$param['column']);
		$string_sorting = GetStringSorting($param, @$param['column'], 'item_id ASC');
		$string_limit = GetStringLimit($param);
		
		$select_query = "
			SELECT SQL_CALC_FOUND_ROWS ScrapeItem.*
			FROM ".SCRAPE_ITEM." ScrapeItem
			WHERE 1 $string_item $string_finish $string_filter
			ORDER BY $string_sorting
			LIMIT $string_limit
		";
        $select_result = mysql_query($select_query) or die(mysql_error());
		while ( $row = mysql_fetch_assoc( $select_result ) ) {
			$array[] = $this->sync($row, @$param['column']);
		}
		
        return $array;
    }

    function get_count($param = array()) {
		$select_query = "SELECT FOUND_ROWS() TotalRecord";
		$select_result = mysql_query($select_query) or die(mysql_error());
		$row = mysql_fetch_assoc($select_result);
		$TotalRecord = $row['TotalRecord'];
		
		return $TotalRecord;
    }
	
    function delete($param) {
		$delete_query  = "DELETE FROM ".SCRAPE_ITEM." WHERE id = '".$param['id']."' LIMIT 1";
		$delete_result = mysql_query($delete_query) or die(mysql_error());
		
		$result['status'] = '1';
		$result['message'] = 'Data berhasil dihapus.';

        return $result;
    }
	
	function sync($row, $column = array()) {
		$row = StripArray($row);
		
		return $row;
	}
	
	function need_scrape($param) {
		$item = $this->Item_model->get_by_id($param);
		$time_update = ConvertToUnixTime($item['date_update']);
		$time_current = ConvertToUnixTime(date("Y-m-d H:i:s"));
		
		// time diff
		$day_diff = floor(($time_current - $time_update) / (60 * 60 * 24));
		
		// config rescrape
		$config = $this->Configuration_model->get_by_id(array( 'name' => 'Waktu Delay Rescrape' ));
		
		// check on pending
		$param_item['item_id'] = $item['id'];
		$param_item['is_finish'] = 0;
		$array_item = $this->get_array($param_item);
		
		$insert = false;
		if ($config['value'] < $day_diff && count($array_item) == 0) {
			$insert = true;
		}
		
		if ($insert) {
			$param_update['item_id'] = $item['id'];
			$param_update['is_finish'] = 0;
			$this->update($param_update);
		}
	}
}