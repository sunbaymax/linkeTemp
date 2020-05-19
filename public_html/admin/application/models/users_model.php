<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Users_model extends CI_Model {
	
	function check() {
	    $myLoginName= $this->input->post('loginname');
	    $myLoginpassword= $this->input->post('password');
	    $myLoginpassword= sha1($myLoginpassword);
		$this->db->where('loginname',$myLoginName);
		$this->db->where('password', $myLoginpassword);
		
		$q = $this->db->get('users');
		if ($q->num_rows() > 0) {
			return $q->row();
		}
	}	
	
	function add($table, $data) {	
		return $this->db->insert($table, $data);
	}

	// 查询整个数据表
	function show($table, $limit = FALSE, $offset = 1) {
		if (! $limit) {
			return $this->db->get ( $table );
		} else {
			$this->db->limit ( $limit, $offset );
			return $this->db->get ( $table );
		}
	}

	function show_join($table, $sql, $join_table = array(), $join_field = array(), $join_type = array(), $where = FALSE, $order = FALSE, $limit = FALSE, $offset = 1) {
		$this->db->select($sql);
		if (count($join_table) > 0) {
			foreach ($join_table as $key => $value) {
				$this->db->join($value, $join_field[$key], $join_type[$key]);
			}
		}
		if ($where !== FALSE) {
			$this->db->where ($where['key'], $where['value']);
		}	
		if ($order !== FALSE) {
			$this->db->order_by($order['field'], $order['type']);
		}	
		if ($limit === FALSE) {
			return $this->db->get ( $table );
		} else {
			$this->db->limit ( $limit, $offset );
			return $this->db->get ( $table );
		}
	}
	
    //zxg add $where 可自定义
	function show_join_zxg($table, $sql, $join_table = array(), $join_field = array(), $join_type = array(), $where = FALSE, $order = FALSE, $limit = FALSE, $offset = 1) {
	    $this->db->select($sql);
	    if (count($join_table) > 0) {
	        foreach ($join_table as $key => $value) {
	            $this->db->join($value, $join_field[$key], $join_type[$key]);
	        }
	    }
	    if ($where !== FALSE) {
	        $this->db->where ($where);
	    }
	    if ($order !== FALSE) {
	        $this->db->order_by($order['field'], $order['type']);
	    }
	    if ($limit === FALSE) {
	        return $this->db->get ( $table );
	    } else {
	        $this->db->limit ( $limit, $offset );
	        return $this->db->get ( $table );
	    }
	}
	
	// 当 id 为 $id $this->db->get() 不能用 array()，必须用 whre
	function show_where($table, $field, $data, $limit = FALSE, $offset = 1) {
		$this->db->where ($field, $data);
		if ($limit !== FALSE) {
			$this->db->limit ( $limit, $offset );
		}
		return $this->db->get ($table);
	}

	function where_in($table, $field, $data, $limit = FALSE, $offset = 1) {
		$this->db->where_in ($field, $data);
		if ($limit !== FALSE) {
			$this->db->limit ( $limit, $offset );
		}
		return $this->db->get ($table);
	}

  function query($sql) {
		return $this->db->query($sql);
	}

	function check_exist($table, $field, $data) {
		$query = $this->db->query ( 'SELECT COUNT(*) AS num FROM ' . $table . " WHERE $field = '" . $data . "'" );
		$row = $query->row ();
		if ($row->num > 0) {
			return TRUE;
		} else {
			return FALSE;
		}
	}
	
	// 查询表的记录，分页类
	function count_table($table = 'message') {
		return $this->db->count_all ( $table );
	}
	
	// 向数据表插入数据 $data 数组或者对象
	function insert($table, $data) {
		return $this->db->insert ( $table, $data );
	}
	
	// 更新 id 为 $id 的记录
	function update($table, $condition, $data) {
		$this->db->where ( $condition['key'], $condition['val']);
		return $this->db->update ( $table, $data );
	}
	
	// 删除 id 为 $id 的记录
	function delete($table, $key, $data) {
		$this->db->where ( $key, $data);
		return $this->db->delete ( $table );
	}
	
	
	//zxg add
	function add_sendComToHost($ComNum,$HostNum,$lableNum,$tickNum) {
	    $sql = "insert into zcomsend(send_com_num,send_host_code,send_lable_code,send_parms,send_state,add_time)";
        $sql = $sql." values ('".$ComNum."','".$HostNum."','".$lableNum."','".$tickNum."','指令形成','".date('Y-m-d H:i:s')."')";
	    //if($ComNum==1)
	    //{
	    //    //[主机参数查询] 
	    //}
	    return $this->db->query($sql);
	}

}