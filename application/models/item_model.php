<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Item_model extends CI_Model {
    function __construct() {
        parent::__construct();
		
        $this->field = array(
			'id', 'brand_id', 'scrape_id', 'category_sub_id', 'alias', 'name', 'code', 'store', 'desc', 'link_source', 'link_replace', 'price_old', 'price_show', 'price_range',
			'status_stock', 'date_update', 'image', 'item_status_id', 'total_view', 'total_link_out'
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
		
		$this->update_tag($param);
		
        return $result;
    }
	
	function update_tag($param) {
		if (isset($param['tag'])) {
			$this->Item_Tag_model->delete(array( 'item_id' => $param['id'] ));
			$array_tag = explode(',', $param['tag']);
			foreach ($array_tag as $tag_queue) {
				$tag_name = trim($tag_queue);
				if (empty($tag_name)) {
					continue;
				}
				
				$tag_alias = $this->Tag_model->get_name($tag_name);
				$tag = $this->Tag_model->get_by_id(array( 'alias' => $tag_alias, 'name' => $tag_name, 'force_insert' => true ));
				
				// insert
				$param_tag['item_id'] = $param['id'];
				$param_tag['tag_id'] = $tag['id'];
				$this->Item_Tag_model->update($param_tag);
			}
		}
	}

	function update_complex($param) {
		return $this->update($param);
	}
	
	function update_view($param) {
		$item = $this->get_by_id($param);
		
		$param_update['id'] = $item['id'];
		$param_update['total_view'] = $item['total_view'] + 1;
		$this->update($param_update);
	}
	
	function update_link_out($param) {
		$item = $this->get_by_id($param);
		
		$param_update['id'] = $item['id'];
		$param_update['total_link_out'] = $item['total_link_out'] + 1;
		$this->update($param_update);
	}
	
    function get_by_id($param) {
        $array = array();
		$param['tag_include'] = (isset($param['tag_include'])) ? $param['tag_include'] : false;
       
        if (isset($param['id'])) {
            $select_query  = "
				SELECT Item.*,
					Brand.id brand_id, Brand.name brand_name,
					CategorySub.id category_sub_id, CategorySub.name category_sub_name, CategorySub.alias category_sub_alias,
					Category.id category_id, Category.name category_name, Category.alias category_alias
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
        } else if (isset($param['link_replace'])) {
            $select_query  = "
				SELECT Item.*
				FROM ".ITEM." Item
				WHERE
					link_source LIKE '%".$param['link_source']."%'
					OR link_replace LIKE '%".$param['link_replace']."%'
				LIMIT 1
			";
        }
       
        $select_result = mysql_query($select_query) or die(mysql_error());
        if (false !== $row = mysql_fetch_assoc($select_result)) {
            $array = $this->sync($row);
        }
		
		if ($param['tag_include']) {
			$array['array_tag'] = $this->Item_Tag_model->get_array(array( 'item_id' => $array['id'] ));
		}
		
       
        return $array;
    }
	
    function get_array($param = array()) {
        $array = array();
		
		$string_brand = (!empty($param['brand_id'])) ? "AND Item.brand_id = '".$param['brand_id']."'" : '';
		$string_category = (!empty($param['category_id'])) ? "AND CategorySub.category_id = '".$param['category_id']."'" : '';
		$string_category_sub = (!empty($param['category_sub_id'])) ? "AND Item.category_sub_id = '".$param['category_sub_id']."'" : '';
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
			WHERE 1 $string_namelike $string_brand $string_category $string_category_sub $string_item_status $string_filter
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
		if (isset($row['category_alias'])) {
			$row['category_link'] = base_url($row['category_alias']);
		}
		if (isset($row['category_alias']) && isset($row['category_sub_alias'])) {
			$row['category_sub_link'] = base_url($row['category_alias'].'/'.$row['category_sub_alias']);
		}
		
		// link out
		if (!empty($row['link_replace'])) {
			$row['link_out'] = $row['link_replace'];
		} else {
			$row['link_out'] = $row['link_source'];
		}
		$row['link_redirect'] = base_url('url?q='.$row['link_out']);
		
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