<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User_model extends CI_Model {
    function __construct() {
        parent::__construct();
		
        $this->field = array( 'id', 'user_type_id', 'email', 'fullname', 'passwd', 'address', 'register_date', 'is_active' );
    }

    function update($param) {
        $result = array();
       
        if (empty($param['id'])) {
			// default value
			$param['register_date'] = (isset($param['register_date'])) ? $param['register_date'] : $this->config->item('current_datetime');
			
            $insert_query  = GenerateInsertQuery($this->field, $param, USER);
            $insert_result = mysql_query($insert_query) or die(mysql_error());
           
            $result['id'] = mysql_insert_id();
            $result['status'] = '1';
            $result['message'] = 'Data berhasil disimpan.';
        } else {
            $update_query  = GenerateUpdateQuery($this->field, $param, USER);
            $update_result = mysql_query($update_query) or die(mysql_error());
           
            $result['id'] = $param['id'];
            $result['status'] = '1';
            $result['message'] = 'Data berhasil diperbaharui.';
        }
       
        return $result;
    }

    function get_by_id($param) {
        $array = array();
		$param['auto_insert'] = (isset($param['auto_insert'])) ? $param['auto_insert'] : false;
       
        if (isset($param['id'])) {
            $select_query  = "SELECT * FROM ".USER." WHERE id = '".$param['id']."' LIMIT 1";
        } else if (isset($param['email'])) {
            $select_query  = "SELECT * FROM ".USER." WHERE email = '".$param['email']."' LIMIT 1";
        } 
       
        $select_result = mysql_query($select_query) or die(mysql_error());
        if (false !== $row = mysql_fetch_assoc($select_result)) {
            $array = $this->sync($row);
        }
		
		if (count($array) == 0 && $param['auto_insert']) {
			$result = $this->update($param);
			$array = $this->get_by_id($result);
		}
		
        return $array;
    }
	
    function get_array($param = array()) {
        $array = array();
		
		$string_namelike = (!empty($param['namelike'])) ? "AND User.email LIKE '%".$param['namelike']."%'" : '';
		$string_filter = GetStringFilter($param, @$param['column']);
		$string_sorting = GetStringSorting($param, @$param['column'], 'name ASC');
		$string_limit = GetStringLimit($param);
		
		$select_query = "
			SELECT SQL_CALC_FOUND_ROWS User.*, UserType.name user_type_name
			FROM ".USER." User
			LEFT JOIN ".USER_TYPE." UserType ON UserType.id = User.user_type_id
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

    function get_count($param = array()) {
		$select_query = "SELECT FOUND_ROWS() TotalRecord";
		$select_result = mysql_query($select_query) or die(mysql_error());
		$row = mysql_fetch_assoc($select_result);
		$TotalRecord = $row['TotalRecord'];
		
		return $TotalRecord;
    }
	
    function delete($param) {
		$delete_query  = "DELETE FROM ".USER." WHERE id = '".$param['id']."' LIMIT 1";
		$delete_result = mysql_query($delete_query) or die(mysql_error());
		
		$result['status'] = '1';
		$result['message'] = 'Data berhasil dihapus.';

        return $result;
    }
	
	function sync($row, $column = array()) {
		$row = StripArray($row);
		
		return $row;
	}
	
	function get_menu($user) {
		$menu_all = array(
			array(
				'Title' => 'Product Management',
				'Child' => array(
					array( 'Title' => 'Item', 'Link' => base_url('panel/product/item'), 'user_type_id' => array(1, 2) )
				)
			),
			array(
				'Title' => 'User Management',
				'Child' => array(
					array( 'Title' => 'User', 'Link' => base_url('panel/user/user'), 'user_type_id' => array(1) ),
					array( 'Title' => 'Subscribe', 'Link' => base_url('panel/user/subscribe'), 'user_type_id' => array(1) )
				)
			),
			array(
				'Title' => 'Master',
				'Child' => array(
					array( 'Title' => 'Page Static', 'Link' => base_url('panel/master/page_static'), 'user_type_id' => array(1) ),
					array( 'Title' => 'Configuration', 'Link' => base_url('panel/master/configuration'), 'user_type_id' => array(1) ),
					array( 'Title' => 'Category', 'Link' => base_url('panel/master/category'), 'user_type_id' => array(1) ),
					array( 'Title' => 'Sub Category', 'Link' => base_url('panel/master/category_sub'), 'user_type_id' => array(1) ),
					array( 'Title' => 'Scrape', 'Link' => base_url('panel/master/scrape'), 'user_type_id' => array(1) ),
				)
			)
		);
		
		$menu = array();
		foreach ($menu_all as $key => $menu_parent) {
			$temp = array();
			foreach ($menu_parent['Child'] as $module_info) {
				if (in_array($user['user_type_id'], $module_info['user_type_id'])) {
					$temp[] = $module_info;
				}
			}
			
			if (count($temp) > 0) {
				$menu_parent['Child'] = $temp;
				$menu[] = $menu_parent;
			}
		}
		
		return $menu;
	}
	
	/*	Region Session */
	
	function is_login($admin_level = false) {
		$user = $this->get_session();
		$result = (count($user) > 0 && @$user['is_login']) ? true : false;
		
		if ($result && $admin_level) {
			if ($user['user_type_id'] != USER_TYPE_ADMINISTRATOR) {
				$result = false;
			}
		}
		
		return $result;
	}
	
	function required_login($admin_level = false) {
		$is_login = $this->is_login($admin_level);
		if (!$is_login) {
			header("Location: ".base_url('panel'));
			exit;
		}
	}
	
	function set_session($user) {
		$user['is_login'] = true;
		
		// set session
		$_SESSION['user_login'] = $user;
		
		// set cookie
		$cookie_value = mcrypt_encode(json_encode($user));
		setcookie("user_login", $cookie_value, time() + (60 * 60 * 5), '/');
	}
	
	function get_session() {
		$user = (isset($_SESSION['user_login'])) ? $_SESSION['user_login'] : array();
		if (! is_array($user)) {
			$user = array();
		}
		
		// check from cookie
		if (count($user) == 0) {
			$user = $this->get_cookies();
		}
		
		// renew session if user already login
		if (count($user) > 0 && isset($user['is_login']) && $user['is_login']) {
			// set session
			$_SESSION['user_login'] = $user;
			
			// set cookie
			$cookie_value = mcrypt_encode(json_encode($user));
			setcookie("user_login", $cookie_value, time() + (60 * 60 * 5), '/');
		}
		
		return $user;
	}
	
	function get_cookies() {
		$user = array( 'is_login' => false );
		if (isset($_COOKIE["user_login"])) {
			$user = json_decode(mcrypt_decode($_COOKIE["user_login"]));
			$user = object_to_array($user);
			$user['is_login'] = true;
		}
		
		return $user;
	}
	
	function del_session() {
		// delete session
		if (isset($_SESSION['user_login'])) {
			unset($_SESSION['user_login']);
		}
		
		// delete cookie
		setcookie("user_login", '', time() + 0, '/');
	}
	
	/*	End Region Session */
}