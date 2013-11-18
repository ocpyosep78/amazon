<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Item_model extends CI_Model {
    function __construct() {
        parent::__construct();
		
        $this->field = array(
			'id', 'brand_id', 'scrape_id', 'category_sub_id', 'alias', 'name', 'code', 'store', 'desc', 'link_source', 'price_old', 'price_show', 'price_range',
			'status_stock', 'date_update', 'image', 'item_status_id'
		);
    }

    function update($param) {
        $result = array();
       
        if (empty($param['id'])) {
            $insert_query  = GenerateInsertQuery($this->field, $param, ITEM);
            $insert_result = mysql_query($insert_query) or die(mysql_error());
           
            $result['id'] = mysql_insert_id();
            $result['status'] = '1';
            $result['message'] = 'Data berhasil disimpan.';
        } else {
            $update_query  = GenerateUpdateQuery($this->field, $param, ITEM);
            $update_result = mysql_query($update_query) or die(mysql_error());
           
            $result['id'] = $param['id'];
            $result['status'] = '1';
            $result['message'] = 'Data berhasil diperbaharui.';
        }
       
        return $result;
    }

	function update_complex($param) {
		return $this->update($param);
	}
	
    function get_by_id($param) {
        $array = array();
       
        if (isset($param['id'])) {
            $select_query  = "
				SELECT Item.*,
					Brand.id brand_id, Brand.name brand_name,
					CategorySub.id category_sub_id, CategorySub.name category_sub_name,
					Category.id category_id, Category.name category_name
				FROM ".ITEM." Item
				LEFT JOIN ".BRAND." Brand ON Brand.id = Item.brand_id
				LEFT JOIN ".CATEGORY_SUB." CategorySub ON CategorySub.id = Item.category_sub_id
				LEFT JOIN ".CATEGORY." Category ON Category.id = CategorySub.category_id
				WHERE Item.id = '".$param['id']."'
				LIMIT 1
			";
        } else if (isset($param['alias'])) {
            $select_query  = "SELECT * FROM ".ITEM." WHERE alias = '".$param['alias']."' LIMIT 1";
        } else if (isset($param['link_source'])) {
			// fix link source before quesy
			$link_source = preg_replace('/\/ref=.+$/i', '', $param['link_source']);
            $select_query  = "SELECT * FROM ".ITEM." WHERE link_source LIKE '".$link_source."%' LIMIT 1";
        }
       
        $select_result = mysql_query($select_query) or die(mysql_error());
        if (false !== $row = mysql_fetch_assoc($select_result)) {
            $array = $this->sync($row);
        }
       
        return $array;
    }
	
    function get_array($param = array()) {
        $array = array();
		
		$string_brand = (!empty($param['brand_id'])) ? "AND Item.brand_id = '".$param['brand_id']."'" : '';
		$string_item_status = (isset($param['item_status_id'])) ? "AND Item.item_status_id = '".$param['item_status_id']."'" : '';
		$string_namelike = (!empty($param['namelike'])) ? "AND Item.name LIKE '%".$param['namelike']."%'" : '';
		$string_filter = GetStringFilter($param, @$param['column']);
		$string_sorting = GetStringSorting($param, @$param['column'], 'name ASC');
		$string_limit = GetStringLimit($param);
		
		$select_query = "
			SELECT
				SQL_CALC_FOUND_ROWS Item.*,
				Brand.id brand_id, Brand.name brand_name,
				CategorySub.id category_sub_id, CategorySub.name category_sub_name,
				Category.id category_id, Category.name category_name,
				ItemStatus.id item_status_id, ItemStatus.name item_status_name
			FROM ".ITEM." Item
			LEFT JOIN ".BRAND." Brand ON Brand.id = Item.brand_id
			LEFT JOIN ".ITEM_STATUS." ItemStatus ON ItemStatus.id = Item.item_status_id
			LEFT JOIN ".CATEGORY_SUB." CategorySub ON CategorySub.id = Item.category_sub_id
			LEFT JOIN ".CATEGORY." Category ON Category.id = CategorySub.category_id
			WHERE 1 $string_namelike $string_brand $string_item_status $string_filter
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
		$delete_query  = "DELETE FROM ".ITEM." WHERE id = '".$param['id']."' LIMIT 1";
		$delete_result = mysql_query($delete_query) or die(mysql_error());
		
		$result['status'] = '1';
		$result['message'] = 'Data berhasil dihapus.';

        return $result;
    }
	
	function sync($row, $column = array()) {
		$row = StripArray($row);
		$row['desc_limit'] = get_length_char(strip_tags($row['desc']), 250, ' ...');
		
		// link
		$row['item_link'] = base_url('item/'.$row['alias']);
		
		// price
		$row['price_old_text'] = '$ '.$row['price_old'];
		$row['price_show_text'] = '$ '.$row['price_show'];
		
		return $row;
	}
	
	function get_item_incomplete($param = array()) {
        $array = array();
		
        if (isset($param['scrape_id'])) {
            $select_query  = "SELECT * FROM ".ITEM." WHERE scrape_id = '".$param['scrape_id']."' AND date_update = '0000-00-00' LIMIT 1";
        } 
       
        $select_result = mysql_query($select_query) or die(mysql_error());
        if (false !== $row = mysql_fetch_assoc($select_result)) {
            $array = $this->sync($row);
        }
		
        return $array;
	}
	
	function get_name($post_name) {
		$post_name = get_name($post_name);
		
		$result = '';
		for ($i = 0; $i <= 10; $i++) {
			if (empty($i)) {
				$name_check = $post_name;
			} else {
				$name_check = $post_name.'-'.$i;
			}
			
			$post = $this->get_by_id(array( 'alias' => $name_check ));
			if (count($post) == 0) {
				$result = $name_check;
				break;
			}
		}
		
		if (empty($result)) {
			$result = $post_name.'-'.time();
		}
		
		return $result;
	}
}