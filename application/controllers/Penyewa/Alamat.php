<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';
require APPPATH . 'libraries/Format.php';

class Alamat extends REST_Controller {
 // Konfigurasi letak folder untuk upload image
 private $folder_upload = 'uploads/';
	function __construct() {
        parent::__construct();
        
	}
	function cekAlamat_post(){
    $id_user = $this->post('id_user');
    $cek_alamat = $this->db->query("
      SELECT * from alamat where id_user= $id_user")->result();
    if(empty($cek_alamat)){
      $this->response(array(
        "status" =>"success",
        "result" => $cek_alamat)
    );
    }
  }

	 function myAlamat_post(){
        $id_user = $this->post('id_user');
        $get_alamat = $this->db->query("
            SELECT id_alamat,id_user, label_alamat,alamat,provinsi, kota, kecamatan, desa
            FROM alamat WHERE id_user=$id_user")->result();
        $this->response(
           array(
               "status" => "success",
               "result" => $get_alamat
           )
       );
    }
    
    function all_post() {
                $action  = $this->post("action");
                $data_alamat = array(
                    'id_alamat' => $this->post('id_alamat'),
                    'id_user' =>$this->post('id_user'),
                    'label_alamat'   => $this->post('label_alamat'),
                    'alamat' =>$this->post('alamat'), 
                    'provinsi'=>$this->post('provinsi'),
                    'kota'=>$this->post('kota'),
                    'kecamatan'=>$this->post('kecamatan'),
                    'desa'=>$this->post('desa')
                
                );
        
                switch ($action) {
                    case 'insert':
                    $this->inputAlamat($data_alamat);
                    break;
                    
                    case 'update':
                    $this->updateAlamat($data_alamat);
                    break;
                    
                    case 'delete':
                    $this->deleteAlamat($data_alamat);
                    break;
                    
                    default:
                    $this->response(
                        array(
                            "status"  =>"failed",
                            "message" => "action harus diisi"
                        )
                    );
                    break;
                }
            }
    function inputAlamat($data_alamat){
        if (empty($data_alamat['id_user']) ||  empty($data_alamat['label_alamat']) ||
            empty($data_alamat['alamat']) ||  empty($data_alamat['provinsi']) ||
            empty($data_alamat['kota']) ||  empty($data_alamat['kecamatan']) ||
           empty($data_alamat['desa'])) {
            $this->response(
                array(
                    "status" => "failed",
                    "message" => "Data tidak lengkap"
                )
            );
        }else{
            $do_insert = $this->db->insert('alamat', $data_alamat);
            if ($do_insert){
            $this->response(
                array(
                    "status" => "success",
                    "result" => array($data_alamat),
                    "message" => $do_insert
                )
            );
            }
        }
    }
    function updateAlamat($data_alamat){
        $update = $this ->db->query("
        UPDATE alamat SET
        label_alamat = '{$data_alamat['label_alamat']}',
        alamat ='{$data_alamat['alamat']}',
        provinsi ='{$data_alamat['provinsi']}',
        kota  = '{$data_alamat['kota']}',
        kecamatan ='{$data_alamat['kecamatan']}',
        desa ='{$data_alamat['desa']}'
        WHERE id_alamat = {$data_alamat['id_alamat']}"
            );
            if ($update){
                $this->response(
                    array(
                        "status"    => "success",
                        "result"    => array($data_alamat),
                        "message"   => $update
                    )
                );
            }else{
                $this->response(
                    array(
                        "status"    => "failed",
                        "message" => "gagal update"
                    )
                );
            }
    }
    function deletemy_post()
    {
      $id_alamat = $this->post('id_alamat');
      $alamatDisable = $this->db->query("SELECT sewa.id_alamat FROM sewa JOIN alamat ON alamat.id_alamat=sewa.id_alamat JOIN detail ON detail.id_sewa = sewa.id_sewa JOIn log ON log.id_detail = detail.id_detail WHERE sewa.id_alamat='$id_alamat' AND log.status_log<>'selesai' ")->result(); 
      if (empty($alamatDisable)) {
        $this->db->where('id_alamat', $id_alamat);
        $delete = $this->db->delete('alamat');  
        $this->response(array('status' => 'success','message' =>"Berhasil delete dengan id_alamat = ".$id_alamat));
      } else{
         $this->response(array('status' => 'failed', 'message' =>"id_alamat tidak dalam database"));
      }
   
   
      
    } 
   
    function editalamat_post()
    {
      $data_alamat = array(
       'id_alamat'    => $this->post('id_alamat'),
       'label_alamat'    => $this->post('label_alamat'),
       'alamat' =>$this->post('alamat'),
        'provinsi' =>$this->post('provinsi'),
       'kota'     => $this->post('kota'),
        'kecamatan' =>$this->post('kecamatan'),
         'desa' =>$this->post('desa')
     );
      $this->db->where('id_alamat',$data_alamat['id_alamat']);
      $update= $this->db->update('alamat',$data_alamat);
      if ($update){
       $this->response(array('status'=>'success',"message"=>$update));
    
     }else{
       $this->response(array('status'=>'fail',"message"=>$message));   
     }
    }


    
}
