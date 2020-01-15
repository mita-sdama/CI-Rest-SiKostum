<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';
require APPPATH . 'libraries/Format.php';

class Pemesanan extends REST_Controller {
 // Konfigurasi letak folder untuk upload image
 private $folder_upload = 'uploads/';

    function tampilPemesanan_post(){
        $id_user = $this->post('id_user');
        $pemesanan = $this->db->query(
            "SELECT alamat.alamat, alamat.provinsi, alamat.kecamatan, alamat.kota, alamat.desa,tempat_sewa.id_user ,user.nama, user.email, user.no_hp,sewa.tgl_transaksi,kostum.nama_kostum,detail.jumlah,kostum.harga_kostum,log.status_log,kostum.foto_kostum,sewa.tgl_sewa,sewa.tgl_kembali FROM sewa JOIN detail ON sewa.id_sewa = detail.id_sewa 
            JOIN log ON detail.id_detail = log.id_detail
            JOIN kostum ON kostum.id_kostum = detail.id_kostum
            JOIN tempat_sewa ON tempat_sewa.id_tempat = kostum.id_tempat
            JOIN user ON user.id_user = sewa.id_user
            JOIN alamat ON sewa.id_alamat = alamat.id_alamat
            WHERE tempat_sewa.id_user='$id_user' AND status_log='valid';
          ")->result();
       $this->response(array('status'=>'success',"result"=>$pemesanan));
        
    }
   

    function getSewa_post(){
        $id_user=$this->post('id_user');
        $sewa= $this->db->query("
       SELECT detail.id_detail,alamat.alamat, alamat.provinsi, alamat.kota, alamat.kecamatan, alamat.desa,tempat_sewa.id_user ,user.nama, user.email, user.no_hp,sewa.tgl_transaksi,kostum.nama_kostum,detail.jumlah,kostum.harga_kostum,log.status_log,kostum.foto_kostum,sewa.tgl_sewa,sewa.tgl_kembali FROM sewa JOIN detail ON sewa.id_sewa = detail.id_sewa 
            JOIN log ON detail.id_detail = log.id_detail
            JOIN kostum ON kostum.id_kostum = detail.id_kostum
            JOIN tempat_sewa ON tempat_sewa.id_tempat = kostum.id_tempat
            JOIN user ON user.id_user = sewa.id_user
            JOIN alamat ON sewa.id_alamat = alamat.id_alamat
            WHERE tempat_sewa.id_user='$id_user' AND status_log='ambil' OR status_log='transfer';
        ")->result();
        $this->response(array('status'=>'success',"result"=>$sewa));
    }

    function updateSewaSelesai_post(){
        $status_log='selesai';
        $id_log= $this->post('id_log');
        $array_data_log = array(
            'id_log' => $this->post('id_log'),
            'id_detail'=> $this->post('id_detail'),
            'status_log'=>($status_log)
        );
            //cek apakah data ada di database
    $get_user_baseID = $this->db->query("
    SELECT 1 FROM log WHERE id_log ={$array_data_log['id_log']}")->num_rows();
    if($get_user_baseID ===0){
        //jika tidak ada
        $this->response(
            array(
                "status"=>"failed",
                "message" =>"id log tidak ditemukan"
            )
            );
    }else{
        $updateSewa = $this->db->query("
        UPDATE log set
       status_log ='{$array_data_log['status_log']}'
        WHERE id_log ='{$array_data_log['id_log']}'
         ");
    }     if($updateSewa){
        $this->response(
            array(
                "status" =>"success",
                "result" =>array($array_data_log),
                "message"=>$updateSewa
            )
            );
    }
    }
    function getRiwayat_post(){
         $id_user = $this->post('id_user');
        $pemesanan = $this->db->query(
            "SELECT alamat.alamat, alamat.provinsi, alamat.kota, alamat.kecamatan, alamat.desa, tempat_sewa.id_user ,user.nama, user.no_hp, user.email,sewa.tgl_transaksi,kostum.nama_kostum,detail.jumlah,kostum.harga_kostum,log.status_log,kostum.foto_kostum,denda.jumlah_denda, denda.keterangan,sewa.tgl_sewa,sewa.tgl_kembali FROM sewa JOIN detail ON sewa.id_sewa = detail.id_sewa 
            JOIN denda  ON denda.id_detail = detail.id_detail
            JOIN log ON detail.id_detail = log.id_detail
            JOIN kostum ON kostum.id_kostum = detail.id_kostum
            JOIN tempat_sewa ON tempat_sewa.id_tempat = kostum.id_tempat
            JOIN user ON user.id_user = sewa.id_user
            JOIN alamat ON sewa.id_alamat = alamat.id_alamat
            WHERE tempat_sewa.id_user='$id_user' AND status_log='selesai';
          ")->result();
       $this->response(array('status'=>'success',"result"=>$pemesanan));

    }
    function getKomentar_post(){
        $id_user  = $this->post('id_user');
        $get_komentar= $this->db->query("
        SELECT sewa.tgl_transaksi,user.nama,kostum.nama_kostum,komentar.komentar FROM 
        komentar join detail on komentar.id_detail = detail.id_detail
        join sewa on sewa.id_sewa = detail.id_sewa
        join kostum on kostum.id_kostum= detail.id_kostum
        join tempat_sewa on kostum.id_tempat=tempat_sewa.id_tempat
        join user on user.id_user= sewa.id_user where tempat_sewa.id_user= $id_user")->result();
        $this->response(array('status'=>'success',"result"=>$get_komentar));
    }

    public function sewaPesan_post(){
        $id_user=$this->post('id_user');
        $sewa= $this->db->query("SELECT count(log.id_log) as jumlahPesan FROM sewa JOIN detail ON sewa.id_sewa = detail.id_sewa 
            JOIN log ON detail.id_detail = log.id_detail
            JOIN kostum ON kostum.id_kostum = detail.id_kostum
            JOIN tempat_sewa ON tempat_sewa.id_tempat = kostum.id_tempat
            JOIN user ON user.id_user = sewa.id_user
            JOIN alamat ON sewa.id_alamat = alamat.id_alamat
            WHERE tempat_sewa.id_user='$id_user' AND status_log='valid'")->result();
        $this->response(array(
            'status'=>'success',
            'result'=> $sewa));
        
    }

    public function sewaSelesai_post(){
        $id_user=$this->post('id_user');
        $sewa= $this->db->query("SELECT count(log.id_log) as jumlahTransfer FROM sewa JOIN detail ON sewa.id_sewa = detail.id_sewa 
            JOIN log ON detail.id_detail = log.id_detail
            JOIN kostum ON kostum.id_kostum = detail.id_kostum
            JOIN tempat_sewa ON tempat_sewa.id_tempat = kostum.id_tempat
            JOIN user ON user.id_user = sewa.id_user
            JOIN alamat ON sewa.id_alamat = alamat.id_alamat
            WHERE tempat_sewa.id_user='$id_user' AND status_log='transfer'")->result();
        $this->response(array(
            'status'=>'success',
            'result'=> $sewa));
        
    }
 
 

    }