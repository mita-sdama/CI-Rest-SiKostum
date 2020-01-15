<?php
defined('BASEPATH') OR exit('No direct script access allowed');
// Jika ada pesan "REST_Controller not found"
require APPPATH . '/libraries/REST_Controller.php';
require APPPATH . 'libraries/Format.php';
class Beranda extends REST_Controller{

	public function user_get(){
		$user = $this->db->query("SELECT count(user.id_user) as jml_user FROM user WHERE level<>'Admin'")->result();
		$this->response($user,200);
	}
	public function tempatSewa_get(){
		$tempatSewa = $this->db->query("SELECT count(id_tempat) as jml_tempat FROM tempat_sewa")->result();
		$this->response($tempatSewa,200);
	}
	public function sewa_get(){
		$sewa = $this->db->query("SELECT count(id_sewa) as jml_sewa FROM sewa")->result();
		$this->response($sewa,200);
	}
	public function komentar_get(){
		$komentar = $this->db->query("SELECT count(id_komentar) as jml_komentar FROM komentar")->result();
		$this->response($komentar,200);
	}

}
