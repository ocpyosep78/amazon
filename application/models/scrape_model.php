<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Scrape_model extends CI_Model {
    function __construct() {
        parent::__construct();
		
        $this->field = array( 'id', 'category_sub_id', 'name', 'store', 'link', 'library', 'is_active' );
    }

    function update($param) {
        $result = array();
       
        if (empty($param['id'])) {
            $insert_query  = GenerateInsertQuery($this->field, $param, SCRAPE);
            $insert_result = mysql_query($insert_query) or die(mysql_error());
           
            $result['id'] = mysql_insert_id();
            $result['status'] = '1';
            $result['message'] = 'Data berhasil disimpan.';
        } else {
            $update_query  = GenerateUpdateQuery($this->field, $param, SCRAPE);
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
				SELECT Scrape.*,
					CategorySub.id category_sub_id, CategorySub.name category_sub_name,
					Category.id category_id, Category.name category_name
				FROM ".SCRAPE." Scrape
				LEFT JOIN ".CATEGORY_SUB." CategorySub ON CategorySub.id = Scrape.category_sub_id
				LEFT JOIN ".CATEGORY." Category ON Category.id = CategorySub.category_id
				WHERE Scrape.id = '".$param['id']."'
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
		
		$string_namelike = (!empty($param['namelike'])) ? "AND Scrape.name LIKE '%".$param['namelike']."%'" : '';
		$string_active = (isset($param['is_active'])) ? "AND Scrape.is_active = '".$param['is_active']."'" : '';
		$string_filter = GetStringFilter($param, @$param['column']);
		$string_sorting = GetStringSorting($param, @$param['column'], 'name ASC');
		$string_limit = GetStringLimit($param);
		
		$select_query = "
			SELECT
				SQL_CALC_FOUND_ROWS Scrape.*,
				CategorySub.id category_sub_id, CategorySub.name category_sub_name,
				Category.id category_id, Category.name category_name
			FROM ".SCRAPE." Scrape
			LEFT JOIN ".CATEGORY_SUB." CategorySub ON CategorySub.id = Scrape.category_sub_id
			LEFT JOIN ".CATEGORY." Category ON Category.id = CategorySub.category_id
			WHERE 1 $string_namelike $string_active $string_filter
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
		$delete_query  = "DELETE FROM ".SCRAPE." WHERE id = '".$param['id']."' LIMIT 1";
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