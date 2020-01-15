<?php
defined('BASEPATH') OR exit('No direct script access allowed');
// Jika ada pesan "REST_Controller not found"
require APPPATH . '/libraries/REST_Controller.php';
require APPPATH . 'libraries/Format.php';
class Denda extends REST_Controller{

	public function komentar_get(){
		$id_komentar= $this->get('id_komentar');
		$id_tempat =$this->get('id_tempat');
		if ($id_komentar== '') {
			$komentar= $this->db->get('komentar')->result();
		}else{
			$this->db->where('id_tempat',$id_tempat);
			$this->db->where('id_komentar',$id_komentar);
			$komentar= $this->db->get('komentar')->result();
		}
		$this->response($komentar,200);
	} 

	
	function getRiwayat_post(){
        $id_user = $this->post('id_user');
        $riwayat = $this->db->query("
            SELECT denda.jumlah_denda,log.id_detail,alamat.alamat,user.id_user ,
            user.nama,sewa.tgl_transaksi,kostum.nama_kostum,detail.jumlah,kostum.harga_kostum,log.status_log, tempat_sewa.nama_tempat, denda.jumlah_denda
             FROM sewa JOIN detail ON sewa.id_sewa = detail.id_sewa 
            JOIN log ON detail.id_detail = log.id_detail
            JOIN kostum ON kostum.id_kostum = detail.id_kostum
            JOIN tempat_sewa ON tempat_sewa.id_tempat = kostum.id_tempat
            JOIN user ON user.id_user = tempat_sewa.id_user
            JOIN alamat ON sewa.id_alamat = alamat.id_alamat
			join denda on denda.id_detail=log.id_detail
            WHERE sewa.id_user='$id_user' AND status_log='selesai';
		  ")->result();
	
	   $this->response(array('status'=>'success',"result"=>$riwayat)); 
	}
	function tambahDenda_post(){
		$data_denda= array(
			"id_detail"=>$this->post("id_detail"),
			"jumlah_denda" =>$this->post("jumlah_denda"),
			"keterangan" =>$this->post("keterangan")
		);
		if(empty($data_denda['id_detail'])){
			$this->response(array('status'=>'fail',"message"=>"id_detail kosong"));
		}else{
			$getId_detail = $this->db->query("SELECT id_detail from detail WHERE id_detail='".$data_denda['id_detail']."'")->result();
			$message="";
			if(empty($getId_detail)) $message.="id detail tidak ada";		
		}
		if(empty($message)){
			$insert= $this->db->insert('denda', $data_denda);
			$update= $this->db->query("UPDATE log SET status_log='selesai' WHERE id_detail='".$data_denda['id_detail']."'");
			if($insert && $update){
                $this->response(
                    array(
                        "status"    => "success",
                        "result"    => array($data_denda),
                        "message"   => $insert
                    )
                );
            }else{
                $this->response(array('status'=>'fail',"message"=>$message));
            }
		}
	} 
	function cekKomentar_post(){
		$id_user = $this->post('id_user');
		$get_cek = $this->db->query("SELECT * from komentar JOIN detail ON komentar.id_detail =detail.id_detail where id_user=$id_user")->result();
		$message="";
		if(empty($get_cek)) $message.="komentar kosong";
		if((!empty($get_cek))){
			$this->response(array(
				"status" =>"success",
				"result" =>array($get_cek)
			));
		}
	}
}