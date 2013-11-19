<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Item_Review_model extends CI_Model {
    function __construct() {
        parent::__construct();
		
        $this->field = array( 'id', 'item_id', 'alias', 'name', 'desc', 'rating', 'user', 'date_update' );
    }

    function update($param) {
        $result = array();
       
        if (empty($param['id'])) {
            $insert_query  = GenerateInsertQuery($this->field, $param, ITEM_REVIEW);
            $insert_result = mysql_query($insert_query) or die(mysql_error());
           
            $result['id'] = mysql_insert_id();
            $result['status'] = '1';
            $result['message'] = 'Data berhasil disimpan.';
        } else {
            $update_query  = GenerateUpdateQuery($this->field, $param, ITEM_REVIEW);
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
            $select_query  = "SELECT * FROM ".ITEM_REVIEW." WHERE id = '".$param['id']."' LIMIT 1";
		} else if (isset($param['item_id']) && isset($param['alias'])) {
			$select_query  = "SELECT * FROM ".ITEM_REVIEW." WHERE item_id = '".$param['item_id']."' && alias = '".$param['alias']."' LIMIT 1";
        }
       
        $select_result = mysql_query($select_query) or die(mysql_error());
        if (false !== $row = mysql_fetch_assoc($select_result)) {
            $array = $this->sync($row);
        }
		
        return $array;
    }
	
    function get_array($param = array()) {
        $array = array();
		
		// replace
		$param['field_replace']['name'] = 'ItemReview.name';
		$param['field_replace']['item_name'] = 'Item.name';
		
		$string_item = (!empty($param['item_id'])) ? "AND ItemReview.item_id = '".$param['item_id']."'" : '';
		$string_namelike = (!empty($param['namelike'])) ? "AND ItemReview.name LIKE '%".$param['namelike']."%'" : '';
		$string_filter = GetStringFilter($param, @$param['column']);
		$string_sorting = GetStringSorting($param, @$param['column'], 'ItemReview.name ASC');
		$string_limit = GetStringLimit($param);
		
		$select_query = "
			SELECT SQL_CALC_FOUND_ROWS ItemReview.*, Item.name item_name, Item.alias item_alias
			FROM ".ITEM_REVIEW." ItemReview
			LEFT JOIN ".ITEM." Item ON Item.id = ItemReview.item_id
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
		$delete_query  = "DELETE FROM ".ITEM_REVIEW." WHERE id = '".$param['id']."' LIMIT 1";
		$delete_result = mysql_query($delete_query) or die(mysql_error());
		
		$result['status'] = '1';
		$result['message'] = 'Data berhasil dihapus.';

        return $result;
    }
	
	function sync($row, $column = array()) {
		$row = StripArray($row);
		$row['desc_limit'] = get_length_char(strip_tags($row['desc']), 1000, ' ...');
		
		// link
		if (isset($row['item_alias'])) {
			$row['link'] = base_url('item/'.$row['item_alias'].'/review/'.$row['alias']);
		}
		
		return $row;
	}
}