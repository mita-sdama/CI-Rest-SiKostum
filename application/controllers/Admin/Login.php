<?php
defined('BASEPATH') OR exit('No direct script access allowed');
// Jika ada pesan "REST_Controller not found"
require APPPATH . '/libraries/REST_Controller.php';
require APPPATH . 'libraries/Format.php';
class Login extends REST_Controller{

	public function login_post(){
		$username = $this->post('username');
		$password = $this->post('password');
		$get_user = $this->db->query("SELECT * FROM user WHERE username='$username' AND password = '$password'");
		if ($get_user->num_rows() == 1) {
			if ($get_user->row()->level == "Admin") {
				$this->response(array("status" => "success", "result" => $get_user->row()->id_user));
			}
			else{
				$this->response(array("status" => "fail", "result" =>null));
			}
		}else{
			$this->response(array("status" => "fail", "result" =>null));
		}
	}

	public function login_get(){
		$username = $this->get('username');
		$password = $this->get('password');
		$get_user = $this->db->query("SELECT * FROM user WHERE username='$username' AND password = '$password'");
		if ($get_user->num_rows() == 1) {
			if ($get_user->row()->level == "Admin") {
				$this->response(array("status" => "Success", "result" => $get_user->row()->id_user));
			}
	}
}

	

}