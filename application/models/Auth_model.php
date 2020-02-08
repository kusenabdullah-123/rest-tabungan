<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth_model extends CI_Model {

	private function _tabel()
	{
		return 'user';
	}

public function getUsers($username)
	{
		$this->db->where('username', $username);
		return $this->db->get($this->_tabel())->row_array();
	}	

}

/* End of file Auth_model.php */
/* Location: ./application/models/Auth_model.php */