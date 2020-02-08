<?php
use Restserver\Libraries\REST_Controller;

defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . 'libraries/REST_Controller.php';
require APPPATH . 'libraries/Format.php';

/**
 * Tabungan
 */
class Tabungan extends REST_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->model('Tabungan_model','tabung');

	}
	public function index_get()
	{

		$data = [
			'tabungan' =>$this->tabung->getDataTabungan(),
			'saldo' => $this->tabung->getSaldo()
		]

		
		$id = $this->get('id');
		if ($id === NULL) {
			if ($data) {
			// Set the response and exit
				$this->response([
					'status' => TRUE,
					'data' => $data
				], REST_Controller::HTTP_OK); 
			}else {
				// Set the response and exit
				$this->response([
					'status' => TRUE,
					'data' => 'Data Not Found'
				], REST_Controller::HTTP_NOT_FOUND);
			}	
		}
		$id = (int) $id;

		if ($id <=0) {
			$this->response([
				'status' => FALSE,
				'message' => 'id not found'
				], REST_Controller::HTTP_BAD_REQUEST); // BAD_REQUEST (400) being the HTTP response code
		}

		$users = NULL;
		if (!empty($data)) {
			foreach ($data as $key => $value) {
				if ($value['id_tabungan'] && $value['id_tabungan'] == $id ) {
					$users = $value;
				}
			}
		}

		if (!empty($users)) {
			$this->response([
				'status' => TRUE,
				'data' => $users
			], REST_Controller::HTTP_OK); 
		}else {
			$this->response([
				'status' => FALSE,
				'data' => 'Data Not Found'
			], REST_Controller::HTTP_NOT_FOUND);
		}

	}


	public function index_post()
	{
		$id = (int)$this->post('user_id');
		$tanggal = $this->post('tanggal');
		$penarikan = (int)$this->post('penarikan');
		$setoran = (int)$this->post('setoran');
		$sal = $this->tabung->getSaldo($id);
		$saldo =$sal +  $setoran - $penarikan;

		if ($id === null) {
			$this->response([
				'status' => FALSE,
				'message' => 'id is Required'
			], REST_Controller::HTTP_NOT_FOUND);
		}elseif ($tanggal === null ) {
			$this->response([
				'status' => FALSE,
				'message' => 'tanggal is Required'
			], REST_Controller::HTTP_NOT_FOUND);
		}elseif ($penarikan === null) {
			$this->response([
				'status' => FALSE,
				'message' => 'penarikan is Required'
			], REST_Controller::HTTP_NOT_FOUND);
		}elseif ($setoran === null) {
			$this->response([
				'status' => FALSE,
				'message' => 'setoran is Required'
			], REST_Controller::HTTP_NOT_FOUND);
		}


		$data = [
			'tanggal'=> $tanggal,
			'setoran' => $setoran,
			'penarikan' => $penarikan,
			'saldo' => $saldo,
			'user_id' => $id

		];
		$insert = $this->tabung->insertData($data);
		if ($insert > 0 ) {
			$this->response([
				'status' => TRUE,
				'message' => 'New Tabungan Created Success'
			], REST_Controller::HTTP_CREATED); 
		}else {
			$this->response([
				'status' => FALSE,
				'message' => 'Failed Created'
			], REST_Controller::HTTP_NOT_FOUND); 
		}
	}

	public function index_delete()
	{
		$id = (int)$this->delete('id');
		if ($id === NULL) {
			$this->response([
				'status' => FALSE,
				'message' => 'id is Required'
			], REST_Controller::HTTP_NOT_FOUND);
		}
		if ($id <=0) {
			$this->response([
				'status' => FALSE,
				'message' => 'id not found'
				], REST_Controller::HTTP_BAD_REQUEST); // BAD_REQUEST (400) being the HTTP response code
		}
		$delete = $this->tabung->deleteData($id);
		if ($delete > 0 ) {
			
			$this->response([
				'status' => TRUE,
				'id' => $id,
				'message' => 'Deleted The Resource'
			], REST_Controller::HTTP_OK); 
		}
		
	}

}