<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Tabungan_model extends CI_Model {

		private function _tabel()
	{
		return 'tabungan';
	}

	public function getDataTabungan()
	{
		return $this->db->get( $this->_tabel())->result_array();
	}

	private function _saldo()
	{
		$this->db->select('SUM(`tabungan`.`setoran`) as jml_setoran, SUM(`tabungan`.`penarikan`) as jml_penarikan');
		$this->db->from($this->_tabel());
		return $this->db->get()->row_array();
	}

	public function getSaldo()
	{
		$saldo = $this->_saldo();
		return $saldo['jml_setoran'] - $saldo['jml_penarikan']; 
	}
	public function insertData($data)
	{
		 $this->db->insert($this->_tabel(), $data);
		 return $this->db->affected_rows();
	}

	public function deleteData($id)
	{	
		$this->db->where('id_tabungan', $id);
		$this->db->delete($this->_tabel());
		return $this->db->affected_rows();
	}
}

/* End of file Tabungan_model.php */
/* Location: ./application/models/Tabungan_model.php */