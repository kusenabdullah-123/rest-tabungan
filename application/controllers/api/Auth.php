<?php
use Restserver\Libraries\REST_Controller;
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . 'libraries/REST_Controller.php';
require APPPATH . 'libraries/Format.php';


class Auth extends REST_Controller
{
    public function __construct()
    {
    	parent::__construct();
    	$this->load->model('Auth_model','auth');
    }

    public function index_post()
    {
    	$username = htmlspecialchars($this->post('username'));
    	$pass = htmlspecialchars ($this->post('password'));
    	$user = $this->auth->getUsers($username);
    	if ($user) {
    		if (password_verify($pass, $user['password'])) {

    			$this->set_response([
                'status' => TRUE,
                'nama'=> $user['nama'],
                'id' => $user['id_user'],
                'password'=> $user['password'],
                'message' => 'Success Validate Users'
            ], REST_Controller::HTTP_OK); //Success Http Response code
    		}else {
    			$this->set_response([
                'status' => FALSE,
                'message' => 'Wrong Password'
            ], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code
    		}
    	}else {
            $message = [
                'status' => FALSE,
                'message' => 'User could not be found'
            ];
    		$this->set_response($message, REST_Controller::HTTP_OK); 
    	}
    }
}