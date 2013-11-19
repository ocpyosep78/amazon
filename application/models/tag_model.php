<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Tag_model extends CI_Model {
    function __construct() {
        parent::__construct();
		
        $this->field = array( 'id', 'alias', 'name' );
    }

    function update($param) {
        $result = array();
       
        if (empty($param['id'])) {
            $insert_query  = GenerateInsertQuery($this->field, $param, TAG);
            $insert_result = mysql_query($insert_query) or die(mysql_error());
           
            $result['id'] = mysql_insert_id();
            $result['status'] = '1';
            $result['message'] = 'Data berhasil disimpan.';
        } else {
            $update_query  = GenerateUpdateQuery($this->field, $param, TAG);
            $update_result = mysql_query($update_query) or die(mysql_error());
           
            $result['id'] = $param['id'];
            $result['status'] = '1';
            $result['message'] = 'Data berhasil diperbaharui.';
        }
       
        return $result;
    }

    function get_by_id($param) {
        $array = array();
		$param['force_insert'] = (isset($param['force_insert'])) ? $param['force_insert'] : false;
       
        if (isset($param['id'])) {
            $select_query  = "SELECT * FROM ".TAG." WHERE id = '".$param['id']."' LIMIT 1";
        } else if (isset($param['alias'])) {
            $select_query  = "SELECT * FROM ".TAG." WHERE alias = '".$param['alias']."' LIMIT 1";
        } 
       
        $select_result = mysql_query($select_query) or die(mysql_error());
        if (false !== $row = mysql_fetch_assoc($select_result)) {
            $array = $this->sync($row);
        }
		
		if ($param['force_insert'] && count($array) == 0) {
			$result = $this->update($param);
			$array = $this->get_by_id(array( 'id' => $result['id'] ));
		}
		
        return $array;
    }
	
    function get_array($param = array()) {
        $array = array();
		
		$string_namelike = (!empty($param['namelike'])) ? "AND Tag.name LIKE '%".$param['namelike']."%'" : '';
		$string_filter = GetStringFilter($param, @$param['column']);
		$string_sorting = GetStringSorting($param, @$param['column'], 'name ASC');
		$string_limit = GetStringLimit($param);
		
		$select_query = "
			SELECT SQL_CALC_FOUND_ROWS Tag.id, Tag.alias, Tag.name, (SELECT COUNT(*) FROM ".POST_TAG." PostTag WHERE tag_id = Tag.id) total_tag
			FROM ".TAG." Tag
			WHERE 1 $string_namelike $string_filter
			GROUP BY id, alias, name
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
		$delete_query[] = "DELETE FROM ".TAG." WHERE id = '".$param['id']."' LIMIT 1";
		$delete_query[] = "DELETE FROM ".POST_TAG." WHERE tag_id = '".$param['id']."'";
		
		// loop
        foreach ($delete_query as $query) {
            $delete_result = mysql_query($query) or die(mysql_error());
        }
		
		$result['status'] = '1';
		$result['message'] = 'Data berhasil dihapus.';

        return $result;
    }
	
	function sync($row, $column = array()) {
		$row = StripArray($row);
		$row['tag_link'] = base_url('tag/'.$row['alias']);
		
		return $row;
	}
	
	function get_name($title) {
		$result = get_name($title);
		return $result;
	}
}