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
		$tabungans = [
			'tabungan'=> $this->tabung->getDataTabungan(),
			'saldo' => $this->tabung->getSaldo()
		];
		$id = (int) $this->get('id');
		if ($id == NULL) {
			if ($tabungans) {
				$this->response([
					'status'=>TRUE,
					'Data'=> $tabungans
				], REST_Controller::HTTP_OK);
			}else {
				$this->response([
					'status'=>FALSE,
					'Message'=> 'No Tabungan Found'
				], REST_Controller::HTTP_NOT_FOUND);
			}
		}
		if ($id <=0) {
			$this->response(NULL, REST_Controller::HTTP_BAD_REQUEST);
		}
		$tabungan = NULL;
		if (!empty($tabungans['tabungan'])) {
			foreach ($tabungans['tabungan'] as $key => $value) {
				if (isset($value['id_tabungan']) && $value['id_tabungan'] ==$id) {
					$tabungan = $value;
				}
			}			
		}

		if (!empty($tabungan)) {
			$this->set_response($tabungan, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
		}else {
			$this->set_response([
				'status' => FALSE,
				'message' => 'Tabungan could not be found'
            ], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
		}
	}

	// Insert Method
	public function index_post()
	{
		(int)$saldo = $this->tabung->getSaldo() + $this->post('setoran') - $this->post('penarikan');
		$data = [
			'tanggal' => $this->post('tanggal'),
			'setoran'=> $this->post('setoran'),
			'penarikan' => $this->post('penarikan'),
			'saldo' => $saldo,
			'user_id' => 1
		];
		if ($saldo < 0 ) {
			$this->set_response([
				'Status' => FALSE,
				'Message' => 'Saldo Kurang'
			],REST_Controller::HTTP_BAD_REQUEST);
		}else {
			if ($this->tabung->insertData($data) > 0) {
				$this->set_response([
					'Status' => TRUE,
					'Message' => 'Succes Insert Data'
				],REST_Controller::HTTP_CREATED);
			}else {
				$this->set_response([
					'Status' => FALSE,
					'Message' => 'Failed Insert Data'
				],REST_Controller::HTTP_BAD_REQUEST);
			}	
		}
	}

	// Delete Method
	public function index_delete()
	{
		$id = (int) $this->delete('id');

        // Validate the id.
		if ($id <= 0)
		{
            // Set the response and exit
            $this->response(NULL, REST_Controller::HTTP_BAD_REQUEST); // BAD_REQUEST (400) being the HTTP response code
        }

        // $this->some_model->delete_something($id);

        if ($this->tabung->deleteData($id) > 0) {
        	$message = [
        		'id' => $id,
        		'message' => 'Deleted the resource'
        	];

        	$this->set_response($message, REST_Controller::HTTP_OK); 
        }else {
        	$message = [
        		'id' => $id,
        		'message' => 'Not Found'
        	];

        	$this->set_response($message, REST_Controller::HTTP_OK); 
        }

    }

    public function index_put()
    {
    	$id = (int) $this->put('id');
    	if ($id == NULL) {
    		$message = [
    			'status'=>FALSE,
    			'id' => $id,
    			'message' => ' ID is Required'
    		];

    		$this->set_response($message, REST_Controller::HTTP_OK); 
    	}
    	if ($id <=0 ) {
    		$message = [
    			'status'=>FALSE,
    			'id' => $id,
    			'message' => ' ID Must Integer'
    		];

    		$this->set_response($message, REST_Controller::HTTP_OK); 
    	}else {



    		(int)$saldo = $this->tabung->getSaldo() + $this->put('setoran') - $this->put('penarikan');
    		$data = [
    			'tanggal'=> $this->put('tanggal'),
    			'setoran'=> $this->put('setoran'),
    			'penarikan' => $this->put('penarikan'),
    			'saldo'=>$saldo,
    			'user_id' => 1
    		];
    		if ($this->tabung->updateData($id,$data) > 0) {
    			$message = [
    				'status'=>TRUE,
    				'id' => $id,
    				'message' => ' Succes Update Data'
    			];

    			$this->set_response($message, REST_Controller::HTTP_OK);     			
    		}else {
    			$message = [
    				'status'=>FALSE,
    				'id' => $id,
    				'message' => ' ID NOT FOUD'
    			];

    			$this->set_response($message, REST_Controller::HTTP_OK); 
    		}

    	}

    }


}