<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';
require APPPATH . 'libraries/Format.php';

class Login extends REST_Controller {

	
 function login_post(){
	 $username = $this->post('username');
	$password = $this->post('password');

	$get_user = $this->db->query("SELECT * FROM user WHERE username='$username' AND password='$password'");
	if($get_user->row()->level=="Tempat Sewa"){
		$this->response(
			array(
				"status"=>"Tempat Sewa",
				"result"=>$get_user->row()->id_user
			)
			);
	}else if($get_user->row()->level=="Penyewa"){
		$this->response(
			array(
				"status"=>"Penyewa",
				"result" =>$get_user->row()->id_user
			)
			);

	}
	
	else {
		$this->response(
			array(
				"status"=>"gagal"
			)
			);
	}
 }


 function loginEmail_post(){
	 $email = $this->post('email');

	$get_user = $this->db->query("SELECT * FROM user WHERE email='$email'");
	if($get_user->row()->level=="Tempat Sewa"){
		$this->response(
			array(
				"status"=>"Tempat Sewa",
				"result"=>$get_user->row()->id_user
			)
			);
	}
	else {
		$this->response(
			array(
				"status"=>"gagal",
				"result" =>null
			)
			);
	}
 }

}
