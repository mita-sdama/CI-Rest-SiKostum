<?php
defined('BASEPATH') OR exit('No direct script access allowed');
// Jika ada pesan "REST_Controller not found"
require APPPATH . '/libraries/REST_Controller.php';
require APPPATH . 'libraries/Format.php';
class Keranjang extends REST_Controller{

	public function sewa_post(){
			$status_sewa = "proses";
			$tgl_sewa = $this->post('tgl_sewa');
			$tgl_kembali = date('Y-m-d',strtotime($tgl_sewa.'+2 day'));
			$data_sewa = array(
				'id_user' =>$this->post('id_user'),
				'id_alamat' =>$this->post('id_alamat'),
				'tgl_sewa' => $tgl_sewa,
				'tgl_kembali' => $tgl_kembali,
				'status_sewa' =>($status_sewa)
			);
			if (empty($data_sewa['tgl_sewa'])) {
				$this->response(
						array(
							"status" => "failed",
							"message" => "Lengkapi Data"
						)
					);
			}else{
				$insert=$this->db->insert('sewa',$data_sewa);
			$insertId = $this->db->insert_id();

			if ($insertId) {
				$dataProduk = array(
				'id_sewa' => ($insertId),
				'id_alamat' =>$this->post('id_alamat'),
				'tgl_sewa' => $tgl_sewa,
				'tgl_kembali' =>$tgl_kembali,
				'status_sewa' =>($status_sewa)
				); 	
			}


			 $this->response(
                array(
                    "status" => "success",
                    "result" => array($dataProduk),
                    "message" => $insert
                )
            );		
			}
			}
	

	public function detail_post(){
		$data_detail = array(
				'id_sewa' =>$this->post('id_sewa'),
				'id_kostum' =>$this->post('id_kostum'),
				'jumlah' => $this->post('jumlah')
			);
		$insert=$this->db->insert('detail',$data_detail);

			 $this->response(
                array(
                    "status" => "success",
                    "result" => array($data_detail),
                    "message" => $insert
                )
            );
	}


}