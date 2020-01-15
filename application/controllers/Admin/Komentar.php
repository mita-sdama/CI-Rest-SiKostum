<?php
defined('BASEPATH') OR exit('No direct script access allowed');
// Jika ada pesan "REST_Controller not found"
require APPPATH . '/libraries/REST_Controller.php';
require APPPATH . 'libraries/Format.php';
class Komentar extends REST_Controller{

	public function komentar_get(){
		$komentar = $this->db->query("SELECT * FROM komentar JOIN user ON user.id_user=komentar.id_user JOIN detail ON komentar.id_detail = detail.id_detail JOIN kostum ON kostum.id_kostum = detail.id_kostum JOIN tempat_sewa ON tempat_sewa.id_tempat=kostum.id_tempat")->result();
			$this->response($komentar,200);

	}

}