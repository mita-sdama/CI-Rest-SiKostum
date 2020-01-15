<?php
defined('BASEPATH') OR exit('No direct script access allowed');
// Jika ada pesan "REST_Controller not found"
require APPPATH . '/libraries/REST_Controller.php';
require APPPATH . 'libraries/Format.php';
class sewa extends REST_Controller{

	public function sewa_get(){
		$id_sewa = $this->get('id_sewa');
		if ($id_sewa == '') {

			$sewa = $this->db->query("SELECT * FROM sewa JOIN user ON sewa.id_user=user.id_user")->result();
			
		}else{
			$sewa = $this->db->query("SELECT * FROM sewa JOIN user ON sewa.id_user=user.id_user WHERE sewa.id_sewa='$id_sewa'")->result();
		} 
		$this->response($sewa,200);
	}

	public function sewaMenunggu_get(){
		$sewa= $this->db->query("SELECT count(id_sewa) as jumlah FROM sewa WHERE status_sewa='proses'")->result();
		$this->response($sewa,200);
		
	}

	public function detailSewa_get(){
		$id_sewa = $this->get('id_sewa');
		if ($id_sewa == '') {

			$sewa = $this->db->query("SELECT * FROM sewa JOIN detail ON sewa.id_sewa = detail.id_Sewa JOIN log ON log.id_detail=detail.id_detail JOIN kostum ON kostum.id_kostum = detail.id_kostum JOIN user ON sewa.id_user = user.id_user JOIN tempat_sewa ON tempat_sewa.id_tempat = kostum.id_tempat ")->result();
			
		}else{
			$sewa = $this->db->query("SELECT * FROM sewa JOIN detail ON sewa.id_sewa = detail.id_Sewa JOIN log ON log.id_detail=detail.id_detail JOIN kostum ON kostum.id_kostum = detail.id_kostum JOIN user ON sewa.id_user = user.id_user JOIN tempat_sewa ON tempat_sewa.id_tempat = kostum.id_tempat WHERE sewa.id_sewa='$id_sewa' GROUP BY tempat_sewa.id_tempat  ")->result();
		} 
		$this->response($sewa,200);

	}

	public function totalPesan_get(){
		$id_sewa = $this->get('id_sewa');
		if ($id_sewa == '') {

			$sewa = $this->db->query("SELECT * FROM sewa JOIN detail ON sewa.id_sewa = detail.id_Sewa JOIN log ON log.id_detail=detail.id_detail JOIN kostum ON kostum.id_kostum = detail.id_kostum JOIN user ON sewa.id_user = user.id_user JOIN tempat_sewa ON tempat_sewa.id_tempat = kostum.id_tempat ")->result();
			
		}else{
			$sewa = $this->db->query("SELECT * FROM sewa JOIN detail ON sewa.id_sewa = detail.id_Sewa JOIN log ON log.id_detail=detail.id_detail JOIN kostum ON kostum.id_kostum = detail.id_kostum JOIN user ON sewa.id_user = user.id_user JOIN tempat_sewa ON tempat_sewa.id_tempat = kostum.id_tempat WHERE sewa.id_sewa='$id_sewa'")->result();
		} 
		$this->response($sewa,200);

	}


		public function detailPesan_get(){
		$id_sewa = $this->get('id_sewa');
		$id_tempat = $this->get('id_tempat');
		if ($id_sewa == '') {

			$sewa = $this->db->query("SELECT * FROM sewa JOIN detail ON sewa.id_sewa = detail.id_Sewa JOIN log ON log.id_detail=detail.id_detail JOIN kostum ON kostum.id_kostum = detail.id_kostum JOIN user ON sewa.id_user = user.id_user JOIN tempat_sewa ON tempat_sewa.id_tempat = kostum.id_tempat ")->result();
			
		}else{
			$sewa = $this->db->query("SELECT * FROM sewa JOIN detail ON sewa.id_sewa = detail.id_Sewa JOIN log ON log.id_detail=detail.id_detail JOIN kostum ON kostum.id_kostum = detail.id_kostum JOIN user ON sewa.id_user = user.id_user JOIN tempat_sewa ON tempat_sewa.id_tempat = kostum.id_tempat WHERE sewa.id_sewa='$id_sewa' AND tempat_sewa.id_tempat='$id_tempat'")->result();
		} 
		$this->response($sewa,200);

	}

	public function statusTransaksi_put(){
		$id_sewa = $this->put('id_sewa');
        $data = array(
        			'id_sewa'	  =>$this->put('id_sewa'),
                    'status_sewa' => $this->put('status_sewa'));
        $this->db->where('id_sewa', $id_sewa);
        $update = $this->db->update('sewa', $data);
        if ($update) {
            $this->response($data, 200);
        } else {
            $this->response(array('status' => 'fail', 502));
        }

    }


    public function validSewa_put(){
	$id_log = $this->put('id_log');
	$valid = "valid";
        $data = array(
        			'id_log' => $this->put('id_log'),
                    'status_log' => $valid);
        $this->db->where('id_log', $id_log);
        $update = $this->db->update('log', $data);
        if ($update) {
            $this->response($data, 200);
        } else {
            $this->response(array('status' => 'fail', 502));
        }


    }

    public function tidakSewa_put(){
	$id_log = $this->put('id_log');
	$valid = "tidak valid";
        $data = array(
        			'id_log' => $this->put('id_log'),
                    'status_log' => $valid);
        $this->db->where('id_log', $id_log);
        $update = $this->db->update('log', $data);
        if ($update) {
            $this->response($data, 200);
        } else {
            $this->response(array('status' => 'fail', 502));
        }


    }


    public function transferSewa_put(){
	$id_log = $this->put('id_log');
	$valid = "transfer";
        $data = array(
        			'id_log' =>$this->put('id_log'),
                    'status_log' => $valid);
        $this->db->where('id_log', $id_log);
        $update = $this->db->update('log', $data);
        if ($update) {
            $this->response($data, 200);
        } else {
            $this->response(array('status' => 'fail', 502));
        }
    	
    }

}

