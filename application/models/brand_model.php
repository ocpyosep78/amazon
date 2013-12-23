<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Brand_model extends CI_Model {
    function __construct() {
        parent::__construct();
		
        $this->field = array( 'id', 'name', 'alias' );
    }

    function update($param) {
        $result = array();
       
        if (empty($param['id'])) {
            $insert_query  = GenerateInsertQuery($this->field, $param, BRAND);
            $insert_result = mysql_query($insert_query) or die(mysql_error());
           
            $result['id'] = mysql_insert_id();
            $result['status'] = '1';
            $result['message'] = 'Data berhasil disimpan.';
        } else {
            $update_query  = GenerateUpdateQuery($this->field, $param, BRAND);
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
            $select_query  = "SELECT * FROM ".BRAND." WHERE id = '".$param['id']."' LIMIT 1";
        } else if (isset($param['alias'])) {
            $select_query  = "SELECT * FROM ".BRAND." WHERE alias = '".$param['alias']."' LIMIT 1";
        } else if (isset($param['name'])) {
			$param['name'] = trim($param['name']);
            $select_query  = "SELECT * FROM ".BRAND." WHERE name = '".mysql_real_escape_string($param['name'])."' LIMIT 1";
        } 
		
        $select_result = mysql_query($select_query) or die(mysql_error());
        if (false !== $row = mysql_fetch_assoc($select_result)) {
            $array = $this->sync($row);
        }
		
		if (count($array) == 0 && isset($param['is_force']) && $param['is_force'] && !empty($param['name'])) {
			$insert_param['name'] = $param['name'];
			$insert_param['alias'] = get_name($param['name']);
			$result = $this->update($insert_param);
			
			$array = $this->get_by_id(array( 'id' => $result['id'] ));
		}
       
        return $array;
    }
	
    function get_array($param = array()) {
        $array = array();
		
		$string_namelike = (!empty($param['namelike'])) ? "AND Brand.name LIKE '%".$param['namelike']."%'" : '';
		$string_filter = GetStringFilter($param, @$param['column']);
		$string_sorting = GetStringSorting($param, @$param['column'], 'name ASC');
		$string_limit = GetStringLimit($param);
		
		$select_query = "
			SELECT SQL_CALC_FOUND_ROWS Brand.*
			FROM ".BRAND." Brand
			WHERE 1 $string_namelike $string_filter
			ORDER BY $string_sorting
			LIMIT $string_limit
		";
        $select_result = mysql_query($select_query) or die(mysql_error());
		while ( $row = mysql_fetch_assoc( $select_result ) ) {
			$array[] = $this->sync($row, @$param['column']);
		}
		
        return $array;
    }
	
    function get_array_with_count($param = array()) {
        $array = array();
		
		$string_category = (!empty($param['category_id'])) ? "AND CategorySub.category_id = '".$param['category_id']."'" : '';
		$string_category_sub = (!empty($param['category_sub_id'])) ? "AND Item.category_sub_id = '".$param['category_sub_id']."'" : '';
		$string_item_status = (isset($param['item_status_id'])) ? "AND Item.item_status_id = '".$param['item_status_id']."'" : '';
		$string_namelike = (!empty($param['namelike'])) ? "AND Brand.name LIKE '%".$param['namelike']."%'" : '';
		$string_filter = GetStringFilter($param, @$param['column']);
		$string_sorting = GetStringSorting($param, @$param['column'], 'name ASC');
		$string_limit = GetStringLimit($param);
		
		$select_query = "
			SELECT *
			FROM (
				SELECT COUNT(*) total, Brand.alias, Brand.name
				FROM ".BRAND." Brand
				LEFT JOIN ".ITEM." Item ON Brand.id = Item.brand_id
				LEFT JOIN ".CATEGORY_SUB." CategorySub ON CategorySub.id = Item.category_sub_id
				LEFT JOIN ".CATEGORY." Category ON Category.id = CategorySub.category_id
				WHERE 1 $string_namelike $string_category $string_category_sub $string_item_status $string_filter
				GROUP BY Brand.alias, Brand.name
				ORDER BY total DESC
				LIMIT 15
			) table_brand
			ORDER BY name ASC 
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
	
	function get_category($param = array()) {
        $result = array();
		
		$select_query = "
			SELECT SUM(brand_id) total,
				CategorySub.id, category_sub_id, CategorySub.name category_sub_name,
				Category.id, category_id, Category.name category_name
			FROM ".ITEM." Item
			LEFT JOIN ".CATEGORY_SUB." CategorySub ON CategorySub.id = Item.category_sub_id
			LEFT JOIN ".CATEGORY." Category ON Category.id = CategorySub.category_id
			WHERE brand_id = '".$param['id']."'
			ORDER BY total DESC
			LIMIT 25
		";
        $select_result = mysql_query($select_query) or die(mysql_error());
		while ( $row = mysql_fetch_assoc( $select_result ) ) {
			$result = $row;
		}
		
        return $result;
    }
	
    function delete($param) {
		$delete_query  = "DELETE FROM ".BRAND." WHERE id = '".$param['id']."' LIMIT 1";
		$delete_result = mysql_query($delete_query) or die(mysql_error());
		
		$result['status'] = '1';
		$result['message'] = 'Data berhasil dihapus.';

        return $result;
    }
	
	function sync($row, $column = array()) {
		$row = StripArray($row);
		$row['link'] = base_url('brand/'.$row['alias']);
		
		return $row;
	}
}