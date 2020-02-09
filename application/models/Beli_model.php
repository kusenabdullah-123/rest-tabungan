<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Beli_model extends CI_Model {

	private function _tabel()
	{
		return 'beli';
	}
	public function getDataBeli()
	{
		return $this->db->get($this->_tabel())->result_array();
	}
	public function insertBeli($data)
	{
		$this->db->insert( $this->_tabel() , $data);
		return $this->db->affected_rows();
	}
	public function updateBeli($id,$data)
	{
		$this->db->where('id_beli', $id);
		$this->db->update( $this->_tabel() , $data);
		return $this->db->affected_rows();
	}
	public function deleteBeli($id)
	{
		$this->db->where('id_beli', $id);
		$this->db->delete($this->_tabel());
		return $this->db->affected_rows();
	}
}

/* End of file Beli_model.php */
/* Location: ./application/models/Beli_model.php */