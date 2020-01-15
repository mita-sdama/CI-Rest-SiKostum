<?php
defined('BASEPATH') OR exit('No direct script access allowed');
// Jika ada pesan "REST_Controller not found"
require APPPATH . '/libraries/REST_Controller.php';
require APPPATH . 'libraries/Format.php';
class tempatSewa extends REST_Controller{

	public function tempatSewa_get(){
		$id_tempat = $this->get('id_tempat');
		if ($id_tempat == '') {

			$tempat = $this->db->query("SELECT * FROM tempat_sewa JOIN alamat ON alamat.id_alamat = tempat_Sewa.id_alamat")->result();
			
		}else{
			$tempat = $this->db->query("SELECT * FROM tempat_sewa JOIN alamat ON alamat.id_alamat = tempat_Sewa.id_alamat WHERE id_tempat = '$id_tempat'")->result();
		}
		$this->response($tempat,200);
	}

	public function kostum_get(){
		$id_tempat = $this->get('id_tempat');
		if ($id_tempat == '') {

			$kostum = $this->db->query("SELECT * FROM tempat_sewa JOIN kostum ON kostum.id_tempat = tempat_sewa.id_tempat")->result();
			
		}else{
			$kostum = $this->db->query("SELECT * FROM tempat_sewa JOIN kostum ON kostum.id_tempat = tempat_sewa.id_tempat WHERE kostum.id_tempat = '$id_tempat'")->result();
		}
		$this->response($kostum,200);
	}


	public function izin_put(){
			$id_tempat = $this->put('id_tempat');
        $data = array(
        			'id_tempat'	  =>$this->put('id_tempat'),
                    'izin'           => $this->put('izin'));
        $this->db->where('id_tempat', $id_tempat);
        $update = $this->db->update('tempat_sewa', $data);
        if ($update) {
            $this->response($data, 200);
        } else {
            $this->response(array('status' => 'fail', 502));
        }

			}

}