<?php
use Restserver\Libraries\REST_Controller;
defined('BASEPATH') OR exit('No direct script access allowed');


require APPPATH . 'libraries/REST_Controller.php';
require APPPATH . 'libraries/Format.php';

class Beli extends REST_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('Beli_model','beli');

	}

	public function index_get()
	{
		$belis =$this->beli->getDataBeli();
		(int)$id = $this->get('id');
		if ($id == NULL) {
			if ($belis) {
				$this->response([
					'status'=>TRUE,
					'Data'=> $belis
				], REST_Controller::HTTP_OK);
			}else {
				$this->response([
					'status'=>TRUE,
					'Data'=> 'Beli Not Found'
				],REST_Controller::HTTP_OK);
			}
		}

		if ($id <=0 ) {
			$this->response([
				'status'=>FALSE
			],REST_Controller::HTTP_BAD_REQUEST);
		}
		$beli = NULL;
		if (!empty($belis)) {
			foreach ($belis as $key => $value) {
				if (isset($value['id_beli']) && $value['id_beli'] == $id) {
					$beli = $value;
				}
			}
		}
		if (!empty($beli)) {
			$this->response([
				'status'=>TRUE,
				'Data'=> $beli
			], REST_Controller::HTTP_OK);
		}else {
			$this->response([
				'status'=>TRUE,
				'Data'=> 'Beli Not Found'
			],REST_Controller::HTTP_OK);
		}


	}
	public function index_post()
	{
		$data = [
			'nama_barang'=> $this->post('nama_barang'),
			'harga'=> $this->post('harga')

		];
		if ($this->beli->insertBeli($data) > 0 ) {
			$this->set_response([
				'Status' => TRUE,
				'Message' => 'Succes Insert Data Beli'
			],REST_Controller::HTTP_CREATED);
		}else {
			$this->set_response([
				'Status' => FALSE,
				'Message' => 'Failed Insert Data Beli'
			],REST_Controller::HTTP_NOT_FOUND);
		}
	}
	public function index_delete()
	{
		$id = (int)$this->delete('id');
		if ($id == NULL) {
			$this->set_response([
				'Status' => FALSE,
				'Message' => 'ID is Required'
			],REST_Controller::HTTP_OK);
		}else {
			if ($this->beli->deleteBeli($id) > 0) {
				$this->set_response([
					'Status' => TRUE,
					'Message' => 'Succes Delete Data Beli'
				],REST_Controller::HTTP_OK);
			}else {
				$this->set_response([
					'Status' => FALSE,
					'Message' => 'Failed Delete Data Beli'
				],REST_Controller::HTTP_NOT_FOUND);
			}

		}
	}
	public function index_put()
	{
		$id = (int)$this->put('id');
		$data = [
			'nama_barang'=>$this->put('nama_barang'),
			'harga'=>$this->put('harga')
		];
		if ($id == NULL) {
			$this->set_response([
				'Status' => FALSE,
				'Message' => 'ID is Required'
			],REST_Controller::HTTP_OK);
		}else {
			if ($this->beli->updateBeli($id,$data) > 0) {
				$this->set_response([
					'Status' => TRUE,
					'Message' => 'Succes Update Data Beli'
				],REST_Controller::HTTP_OK);
			}else {
				$this->set_response([
					'Status' => FALSE,
					'Message' => 'Failed Update Data Beli'
				],REST_Controller::HTTP_OK);
			}
		}
	}

}

/* End of file Beli.php */
/* Location: ./application/controllers/api/Beli.php */