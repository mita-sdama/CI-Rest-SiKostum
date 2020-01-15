<?php
defined('BASEPATH') OR exit('No direct script access allowed');
// Jika ada pesan "REST_Controller not found"
require APPPATH . '/libraries/REST_Controller.php';
require APPPATH . 'libraries/Format.php';
class tempatSewa extends REST_Controller{

	public function tempatSewa_get(){
		$id_tempat = $this->get('id_tempat');
		if ($id_tempat == '') {

			$tempat = $this->db->query("SELECT * FROM tempat_sewa WHERE izin='ya'")->result();
		}else{
			$this->db->where('id_tempat',$id_tempat);
			$tempat = $this->db->query("SELECT * FROM tempat_sewa WHERE izin='ya' AND id_tempat='$id_tempat'")->result();
		}
		$this->response($tempat,200);
	} 

	public function detailTempat_post(){
		$id_kostum = $this->post('id_kostum');
		$detail_kostum = $this->db->query("SELECT * FROM tempat_sewa join kostum on kostum.id_tempat=tempat_sewa.id_tempat
		join alamat on tempat_sewa.id_alamat= alamat.id_alamat
		join user on user.id_user = tempat_sewa.id_user
		 where id_kostum=$id_kostum")->result();
		   $this->response(
            array(
                "status" => "success",
                "result" => $detail_kostum
            )
        );
	} 



}