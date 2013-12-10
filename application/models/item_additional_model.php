<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Item_Additional_model extends CI_Model {
    function __construct() {
        parent::__construct();
		
        $this->field = array( 'id', 'item_id', 'desc_short', 'desc_long_1', 'desc_long_2', 'link_aff', 'sign' );
    }
	
    function update($param) {
        $result = array();
       
        if (empty($param['id'])) {
            $insert_query  = GenerateInsertQuery($this->field, $param, ITEM_ADDITIONAL);
            $insert_result = mysql_query($insert_query) or die(mysql_error());
           
            $result['id'] = mysql_insert_id();
            $result['status'] = '1';
            $result['message'] = 'Data berhasil disimpan.';
        } else {
            $update_query  = GenerateUpdateQuery($this->field, $param, ITEM_ADDITIONAL);
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
            $select_query  = "SELECT * FROM ".ITEM_ADDITIONAL." WHERE id = '".$param['id']."' LIMIT 1";
		} else if (isset($param['item_id'])) {
			$select_query  = "SELECT * FROM ".ITEM_ADDITIONAL." WHERE item_id = '".$param['item_id']."' LIMIT 1";
        }
       
        $select_result = mysql_query($select_query) or die(mysql_error());
        if (false !== $row = mysql_fetch_assoc($select_result)) {
            $array = $this->sync($row);
        }
		
		if (isset($param['force_insert']) && $param['force_insert'] && count($array) == 0) {
			$param_insert['item_id'] = $param['item_id'];
			$result = $this->update($param_insert);
			
			// get data
			$array = $this->get_by_id(array( 'id' => $result['id'] ));
		}
		
        return $array;
    }
	
    function get_array($param = array()) {
        $array = array();
		
		$string_item = (!empty($param['item_id'])) ? "AND ItemAdditional.item_id = '".$param['item_id']."'" : '';
		$string_filter = GetStringFilter($param, @$param['column']);
		$string_sorting = GetStringSorting($param, @$param['column'], 'ItemAdditional.item_id ASC');
		$string_limit = GetStringLimit($param);
		
		$select_query = "
			SELECT SQL_CALC_FOUND_ROWS ItemAdditional.*
			FROM ".ITEM_ADDITIONAL." ItemAdditional
			WHERE 1 $string_namelike $string_item $string_filter
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
		$delete_query  = "DELETE FROM ".ITEM_ADDITIONAL." WHERE id = '".$param['id']."' LIMIT 1";
		$delete_result = mysql_query($delete_query) or die(mysql_error());
		
		$result['status'] = '1';
		$result['message'] = 'Data berhasil dihapus.';

        return $result;
    }
	
	function sync($row, $column = array()) {
		$row = StripArray($row);
		
		return $row;
	}
}