<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Item_Tag_model extends CI_Model {
    function __construct() {
        parent::__construct();
		
        $this->field = array( 'id', 'item_id', 'tag_id' );
    }

    function update($param) {
        $result = array();
       
        if (empty($param['id'])) {
            $insert_query  = GenerateInsertQuery($this->field, $param, ITEM_TAG);
            $insert_result = mysql_query($insert_query) or die(mysql_error());
           
            $result['id'] = mysql_insert_id();
            $result['status'] = '1';
            $result['message'] = 'Data berhasil disimpan.';
        } else {
            $update_query  = GenerateUpdateQuery($this->field, $param, ITEM_TAG);
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
            $select_query  = "SELECT * FROM ".ITEM_TAG." WHERE id = '".$param['id']."' LIMIT 1";
        } 
       
        $select_result = mysql_query($select_query) or die(mysql_error());
        if (false !== $row = mysql_fetch_assoc($select_result)) {
            $array = $this->sync($row);
        }
       
        return $array;
    }
	
    function get_array($param = array()) {
        $array = array();
		
		$string_tag = (!empty($param['tag_id'])) ? "AND ItemTag.tag_id = '".$param['tag_id']."'" : '';
		$string_item = (!empty($param['item_id'])) ? "AND ItemTag.item_id = '".$param['item_id']."'" : '';
		$string_brand = (!empty($param['brand_id'])) ? "AND Item.brand_id = '".$param['brand_id']."'" : '';
		$string_namelike = (!empty($param['namelike'])) ? "AND Item.name LIKE '%".$param['namelike']."%'" : '';
		$string_item_status = (isset($param['item_status_id'])) ? "AND Item.item_status_id = '".$param['item_status_id']."'" : '';
		$string_filter = GetStringFilter($param, @$param['column']);
		$string_sorting = GetStringSorting($param, @$param['column'], 'Tag.name ASC');
		$string_limit = GetStringLimit($param);
		
		$select_query = "
			SELECT SQL_CALC_FOUND_ROWS ItemTag.*,
				Tag.name tag_name, Tag.alias tag_alias,
				Item.alias item_alias, Item.name item_name, Item.desc item_desc, Item.image item_image,
				Item.price_old, Item.price_show
			FROM ".ITEM_TAG." ItemTag
			LEFT JOIN ".TAG." Tag ON Tag.id = ItemTag.tag_id
			LEFT JOIN ".ITEM." Item ON Item.id = ItemTag.item_id
			LEFT JOIN ".BRAND." Brand ON Brand.id = Item.brand_id
			LEFT JOIN ".ITEM_STATUS." ItemStatus ON ItemStatus.id = Item.item_status_id
			WHERE 1
				$string_namelike $string_brand
				$string_tag $string_item $string_item_status $string_filter
			ORDER BY $string_sorting
			LIMIT $string_limit
		";
		
        $select_result = mysql_query($select_query) or die(mysql_error());
		while ( $row = mysql_fetch_assoc( $select_result ) ) {
			$array[] = $this->sync($row);
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
		if (isset($param['id'])) {
			$delete_query  = "DELETE FROM ".ITEM_TAG." WHERE id = '".$param['id']."' LIMIT 1";
			$delete_result = mysql_query($delete_query) or die(mysql_error());
		} else if (isset($param['item_id'])) {
			$delete_query  = "DELETE FROM ".ITEM_TAG." WHERE item_id = '".$param['item_id']."'";
			$delete_result = mysql_query($delete_query) or die(mysql_error());
		}
		
		$result['status'] = '1';
		$result['message'] = 'Data berhasil dihapus.';

        return $result;
    }
	
	function sync($row) {
		$row = StripArray($row, array( 'item_create_date' ));
		$row['tag_link'] = base_url('tag/'.$row['tag_alias']);
		
		// link
		$row['item_link'] = base_url('item/'.$row['item_alias']);
		$row['desc_limit'] = get_length_char(strip_tags($row['item_desc']), 250, ' ...');
		
		// price
		$row['price_old_text'] = '$ '.$row['price_old'];
		$row['price_show_text'] = '$ '.$row['price_show'];
		
		return $row;
	}
}