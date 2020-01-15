<?php
defined('BASEPATH') OR exit('No direct script access allowed');
// Jika ada pesan "REST_Controller not found"
require APPPATH . '/libraries/REST_Controller.php';
require APPPATH . 'libraries/Format.php';
class Identitasku extends REST_Controller{
 // Konfigurasi letak folder untuk upload image
 private $folder_upload = 'uploads/';


    
    function all_get(){
        $get_identitas = $this->db->query("
            SELECT * FROM identitas")->result();
        $this->response(
           array(
               "status" => "success",
               "result" => $get_identitas
           )
       );
    }
    
    function all_post() {
$status= 'menunggu';
        $action  = $this->post("action");
        $data_identitas = array(
            'id_user' => $this->post('id_user'),
            'id_identitas' =>$this->post('id_identitas'),
            'foto_ktp'   => $this->post('foto_ktp'),
            'status' => ($status)
        );

        switch ($action) {
            case 'insert':
            $this->identitas($data_identitas);
            break;
            
            case 'update':
            $this->myidentitas($data_identitas);
            break;
            
            case 'delete':
            $this->deleteuser($data_identitas);
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

    function identitas($data_identitas){
        $status= 'menunggu';

        if (empty($data_identitas['id_user']) ||  empty($data_identitas['status'])){
            $this->response(
                array(
                    "status" => "failed",
                    "message" => "Data tidak lengkap"
                )
            );
        } else{
            $data_identitas['foto_ktp']= $this->uploadPhoto();
            $do_insert = $this->db->insert('identitas', $data_identitas);
            if ($do_insert){
            $this->response(
                array(
                    "status" => "success",
                    "result" => array($data_identitas),
                    "message" => $do_insert
                )
            );
            }
        }
        }
        function editidentitas_post(){
            $status = 'menunggu';
            $data_identitas= array(
                'id_identitas' => $this->post('id_identitas'),
                'foto_ktp' => $this->post('foto_ktp'),
                'status' =>($status)
            );
            // Cek apakah ada di database
            $get_user_baseID = $this->db->query("
            SELECT 1
            FROM identitas
            WHERE id_identitas = {$data_identitas['id_identitas']}")->num_rows();

        if($get_user_baseID === 0){
            // Jika tidak ada
            $this->response(
                array(
                    "status"  => "failed",
                    "message" => "ID identitas tidak ditemukan"
                )
            );
        } else {
            // Jika ada
            $data_identitas['foto_ktp'] = $this->uploadPhoto();

            if ($data_identitas['foto_ktp']){
                $get_photo_ktp =$this->db->query("
                    SELECT foto_ktp
                    FROM identitas
                    WHERE id_identitas = {$data_identitas['id_identitas']}")->result();

                if(!empty($get_photo_ktp)){

                    // Dapatkan nama_user file
                    $photo_nama_user_file = basename($get_photo_ktp[0]->foto_ktp);
                    // Dapatkan letak file di folder upload
                    $photo_lokasi_file = realpath(FCPATH . $this->folder_upload . $photo_nama_user_file);

                    // Jika file ada, hapus
                    if(file_exists($photo_lokasi_file)) {
                        // Hapus file
                        unlink($photo_lokasi_file);
                    }
                }
                // Jika upload foto berhasil, eksekusi update
                $update = $this->db->query("
                    UPDATE identitas SET
                    foto_ktp = '{$data_identitas['foto_ktp']}',
                    status = '{$data_identitas['status']}'
                    WHERE id_identitas = '{$data_identitas['id_identitas']}'");

            } else {
                // Jika foto kosong atau upload foto tidak berhasil, eksekusi update
                $update = $this->db->query("
                    UPDATE identitas
                    SET
                    status    = '{$data_identitas['status']}'
                    WHERE id_identitas = {$data_identitas['id_identitas']}"
                );
            }

            if ($update){
                $this->response(
                    array(
                        "status"    => "success",
                        "result"    => array($data_identitas),
                        "message"   => $update
                    )
                );
            }
        }
        }

  

function myidentitas_post(){
    $id_user = $this->post('id_user');
    $get_identitas = $this->db->query("
    SELECT
   id_identitas,id_user, foto_ktp, status
       FROM identitas WHERE id_user=$id_user")->result();
       if(!empty($get_identitas)){
        $this->response(
            array(
                "status" => "success",
                "result" => $get_identitas
            )
         );
       }else{
        $this->response(
            array(
                "status" => "kosong"
            )
         );
       }
}    

    function uploadPhoto() {
        
                // Apakah user upload gambar?
                if ( isset($_FILES['foto_ktp']) && $_FILES['foto_ktp']['size'] > 0 ){
        
                    // Foto disimpan di android-api/uploads
                    $config['upload_path'] = realpath(FCPATH . $this->folder_upload);
                    $config['allowed_types'] = 'jpg|png';
        
                    // Load library upload & helper
                    $this->load->library('upload', $config);
                    $this->load->helper('url');
        
                    // Apakah file berhasil diupload?
                    if ( $this->upload->do_upload('foto_ktp')) {
        
                       // Berhasil, simpan nama_user file-nya
                       $img_data = $this->upload->data();
                       $post_image = $img_data['file_name'];
        
                   } else {
        
                        // Upload gagal, beri nama_user image dengan errornya
                        // Ini bodoh, tapi efektif
                    $post_image = $this->upload->display_errors();
        
                }
            } else {
                    // Tidak ada file yang di-upload, kosongkan nama_user image-nya
                $post_image = '';
            }
        
            return $post_image;
        }

}
