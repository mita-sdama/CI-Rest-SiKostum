<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';
require APPPATH . 'libraries/Format.php';

class Pendaftaran extends REST_Controller {
	private $folder_upload='uploads/';

	function all_post(){
		$level = 'Tempat Sewa';
		$Photo = 'photo.png';
		$action = $this->post('action');
		$data_pendaftaran = array(
			'id_user' =>$this->post('id_user'),
			'nama' => $this->post('nama'),
			'jenis_kelamin' => $this->post('jenis_kelamin'),
			'email' => $this->post('email'),
			'no_hp' =>$this->post('no_hp'),
			'foto_user'=>($Photo),
			'username' =>$this->post('username'),
			'password' =>$this->post('password'),
			'level' => ($level)
		);
		switch ($action){
			case 'insert';
			$this->insertPendaftaran($data_pendaftaran);
			break;

			default: 
			$this->response (
				array(
					"status" =>"failed", 
					"message" =>"action harus diisi"
				)
				);
				break;
		}
	}
	function insertPendaftaran($data_pendaftaran){
				$use = $data_pendaftaran['username'];
				$em = $data_pendaftaran['email'];
				// Cek validasi
				if (empty($data_pendaftaran['nama'])||empty($data_pendaftaran['jenis_kelamin'])||empty($data_pendaftaran['email']) || empty($data_pendaftaran['no_hp']) ||
				empty($data_pendaftaran['username']) || empty($data_pendaftaran['password'])){
					$this->response(
						array(
							"status" => "failed",
							"message" => "Lengkapi Data"
						)
					);
				} 
				else {
				$select= $this->db->query("SELECT username from user where username='$use' ")->result();
				$emaill = $this->db->query("SELECT email from user where email='$em'")->result();
		
					if(empty($select) && empty($emaill)){
					$do_insert = $this->db->insert('user', $data_pendaftaran);

						if ($do_insert){
						$this->response(
							array(
								"status" => "success",
								"result" => array($data_pendaftaran),
								"message" => $do_insert
							)
						);
					}
				}else if(!empty($emaill)){
					$this->response(
							array(
								"status" => "fail"
							)
						);

				}
					else{
						$this->response(
							array(
								"status" => "gagal"
							)
						);
					}
					
				   
					
				}
	}

}
