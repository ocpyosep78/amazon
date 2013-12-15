<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Category_Sub_model extends CI_Model {
    function __construct() {
        parent::__construct();
		
        $this->field = array( 'id', 'category_id', 'name', 'alias', 'desc', 'tag', 'force_link' );
    }

    function update($param) {
        $result = array();
       
        if (empty($param['id'])) {
            $insert_query  = GenerateInsertQuery($this->field, $param, CATEGORY_SUB);
            $insert_result = mysql_query($insert_query) or die(mysql_error());
           
            $result['id'] = mysql_insert_id();
            $result['status'] = '1';
            $result['message'] = 'Data berhasil disimpan.';
        } else {
            $update_query  = GenerateUpdateQuery($this->field, $param, CATEGORY_SUB);
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
            $select_query  = "
				SELECT CategorySub.*,
					Category.id category_id, Category.alias category_alias, Category.name category_name
				FROM ".CATEGORY_SUB." CategorySub
				LEFT JOIN ".CATEGORY." Category ON Category.id = CategorySub.category_id
				WHERE CategorySub.id = '".$param['id']."'
				LIMIT 1
			";
        } else if (isset($param['alias'])) {
            $select_query  = "
				SELECT CategorySub.*,
					Category.id category_id, Category.alias category_alias, Category.name category_name
				FROM ".CATEGORY_SUB." CategorySub
				LEFT JOIN ".CATEGORY." Category ON Category.id = CategorySub.category_id
				WHERE CategorySub.alias = '".$param['alias']."'
				LIMIT 1
			";
        }
		
        $select_result = mysql_query($select_query) or die(mysql_error());
        if (false !== $row = mysql_fetch_assoc($select_result)) {
            $array = $this->sync($row);
        }
       
        return $array;
    }
	
    function get_array($param = array()) {
        $array = array();
		
		$string_namelike = (!empty($param['namelike'])) ? "AND CategorySub.name LIKE '%".$param['namelike']."%'" : '';
		$string_category = (!empty($param['category_id'])) ? "AND CategorySub.category_id = '".$param['category_id']."'" : '';
		$string_filter = GetStringFilter($param, @$param['column']);
		$string_sorting = GetStringSorting($param, @$param['column'], 'name ASC');
		$string_limit = GetStringLimit($param);
		
		$select_query = "
			SELECT
				SQL_CALC_FOUND_ROWS CategorySub.*,
				Category.id category_id, Category.alias category_alias, Category.name category_name
			FROM ".CATEGORY_SUB." CategorySub
			LEFT JOIN ".CATEGORY." Category ON Category.id = CategorySub.category_id
			WHERE 1 $string_namelike $string_category $string_filter
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
		$delete_query  = "DELETE FROM ".CATEGORY_SUB." WHERE id = '".$param['id']."' LIMIT 1";
		$delete_result = mysql_query($delete_query) or die(mysql_error());
		
		$result['status'] = '1';
		$result['message'] = 'Data berhasil dihapus.';

        return $result;
    }
	
	function sync($row, $column = array()) {
		$row = StripArray($row);
		
		// link
		if (!empty($row['force_link'])) {
			$row['link'] = $row['force_link'];
		} else if (isset($row['category_alias'])) {
			$row['link'] = base_url($row['category_alias'].'/'.$row['alias']);
		}
		
		return $row;
	}
	
	function get_meta($param) {
		$result = '';
		$category_sub = $this->get_by_id($param);
		
		// get from tag
		$result .= $category_sub['tag'];
		
		// get brand
		$select_query = "
			SELECT Brand.name
			FROM ".ITEM." Item
			LEFT JOIN ".BRAND." Brand ON Brand.id = Item.brand_id
			LEFT JOIN ".CATEGORY_SUB." CategorySub ON CategorySub.id = Item.category_sub_id
			WHERE CategorySub.id = '".$category_sub['id']."'
			GROUP BY Brand.name
			ORDER BY rand()
			LIMIT 10
		";
        $select_result = mysql_query($select_query) or die(mysql_error());
		while ( $row = mysql_fetch_assoc( $select_result ) ) {
			$result .= (empty($result)) ? $row['name'] : ', '.$row['name'];
		}
		
		return $result;
	}
}