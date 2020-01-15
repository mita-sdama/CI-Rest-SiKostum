<?php
defined('BASEPATH') OR exit('No direct script access allowed');
// Jika ada pesan "REST_Controller not found"
require APPPATH . '/libraries/REST_Controller.php';
require APPPATH . 'libraries/Format.php';
class Kostum extends REST_Controller{

	public function kostum_get(){
		$id_kostum = $this->get('id_kostum');
		$id_tempat = $this->get('id_tempat');
		if ($id_kostum == '') {
			$kostum = $this->db->get('kostum')->result();
		}else{
			$this->db->where('id_kostum',$id_kostum);
			$this->db->where('id_tempat',$id_tempat);
			$kostum = $this->db->get('kostum')->result();
		}
		$this->response($kostum,200);
	}

	public function allKostum_get(){
		$get_all_kostum = $this->db->query("
		SELECT * FROM kostum join tempat_sewa on tempat_sewa.id_tempat=kostum.id_tempat
							join alamat on tempat_sewa.id_alamat=alamat.id_alamat")->result();
		$this->response(array(
			"status" =>"success",
			"result"=>$get_all_kostum
		)
		);
	} 

	public function kostumBelanja_post(){
		$id_tempat= $this->post('id_tempat');
		$get_kostum_belanja = $this->db->query("SELECT * FROM tempat_sewa WHERE status_tempat='tutup'
			AND id_tempat='$id_tempat'")->result();
		if ($get_kostum_belanja) {
			$this->response(array("status" =>"success","result" => $get_kostum_belanja));
		}
		
		
	}
}