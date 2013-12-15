<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Category_model extends CI_Model {
    function __construct() {
        parent::__construct();
		
        $this->field = array( 'id', 'name', 'alias', 'desc', 'tag', 'image' );
    }

    function update($param) {
        $result = array();
       
        if (empty($param['id'])) {
            $insert_query  = GenerateInsertQuery($this->field, $param, CATEGORY);
            $insert_result = mysql_query($insert_query) or die(mysql_error());
           
            $result['id'] = mysql_insert_id();
            $result['status'] = '1';
            $result['message'] = 'Data berhasil disimpan.';
        } else {
            $update_query  = GenerateUpdateQuery($this->field, $param, CATEGORY);
            $update_result = mysql_query($update_query) or die(mysql_error());
           
            $result['id'] = $param['id'];
            $result['status'] = '1';
            $result['message'] = 'Data berhasil diperbaharui.';
        }
       
		$this->resize_image($param);
	   
        return $result;
    }

    function get_by_id($param) {
        $array = array();
       
        if (isset($param['id'])) {
            $select_query  = "SELECT * FROM ".CATEGORY." WHERE id = '".$param['id']."' LIMIT 1";
        } else if (isset($param['alias'])) {
            $select_query  = "SELECT * FROM ".CATEGORY." WHERE alias = '".$param['alias']."' LIMIT 1";
        } 
       
        $select_result = mysql_query($select_query) or die(mysql_error());
        if (false !== $row = mysql_fetch_assoc($select_result)) {
            $array = $this->sync($row);
        }
       
        return $array;
    }
	
    function get_array($param = array()) {
        $array = array();
		
		$string_namelike = (!empty($param['namelike'])) ? "AND Category.name LIKE '%".$param['namelike']."%'" : '';
		$string_filter = GetStringFilter($param, @$param['column']);
		$string_sorting = GetStringSorting($param, @$param['column'], 'name ASC');
		$string_limit = GetStringLimit($param);
		
		$select_query = "
			SELECT SQL_CALC_FOUND_ROWS Category.*
			FROM ".CATEGORY." Category
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

    function get_array_with_sub($param = array()) {
		$array = $this->get_array($param);
		
		// loop
		foreach ($array as $key => $row) {
			$array_category_sub = $this->Category_Sub_model->get_array(array( 'category_id' => $row['id'] ));
			$array[$key]['category_sub'] = $array_category_sub;
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
		$delete_query  = "DELETE FROM ".CATEGORY." WHERE id = '".$param['id']."' LIMIT 1";
		$delete_result = mysql_query($delete_query) or die(mysql_error());
		
		$result['status'] = '1';
		$result['message'] = 'Data berhasil dihapus.';

        return $result;
    }
	
	function sync($row, $column = array()) {
		$row = StripArray($row);
		$row['link'] = base_url($row['alias']);
		
		// image
		if (!empty($row['image'])) {
			$row['image_link'] = base_url('static/upload/'.$row['image']);
		}
		
		return $row;
	}
	
	function resize_image($param) {
		if (!empty($param['image'])) {
			$image_path = $this->config->item('base_path') . '/static/upload/';
			$image_source = $image_path . $param['image'];
			$image_result = $image_source;
			
			ImageResize($image_source, $image_result, 300, 250, 1);
		}
	}
	
	function get_meta($param) {
		$result = '';
		$category = $this->get_by_id($param);
		
		// get from tag
		$result .= $category['tag'];
		
		// get brand
		$select_query = "
			SELECT Brand.name
			FROM ".ITEM." Item
			LEFT JOIN ".BRAND." Brand ON Brand.id = Item.brand_id
			LEFT JOIN ".CATEGORY_SUB." CategorySub ON CategorySub.id = Item.category_sub_id
			WHERE CategorySub.category_id = '".$category['id']."'
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