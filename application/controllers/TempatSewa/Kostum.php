<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';
require APPPATH . 'libraries/Format.php';

class Kostum extends REST_Controller {
 // Konfigurasi letak folder untuk upload image
 private $folder_upload = 'uploads/';

    function tampilKostum_post(){
        $id_user = $this->post('id_user');
        $get_tampil_kostum = $this->db->query("
        SELECT u.id_user,t.id_user,t.id_tempat,k.id_tempat, k.id_kategori,k.id_kostum, k.nama_kostum,
        k.jumlah_kostum, k.harga_kostum, k.deskripsi_kostum,k.foto_kostum, ka.nama_kategori
        FROM kostum k JOIN kategori ka ON k.id_kategori=ka.id_kategori 
        JOIN tempat_sewa t ON t.id_tempat=k.id_tempat join user u ON u.id_user=t.id_user WHERE t.id_user=$id_user")->result();
        $this->response(array(
            "status" =>"success",
            "result"=>$get_tampil_kostum
        )
        );
    }
    function getKategori_get(){
        $getKategori= $this->db->query("
        SELECT id_kategori,nama_kategori FROM kategori")->result();
        $this->response(
            array(
                "status"=> "success", 
                "result" =>$getKategori
            )
            );
    }
    function getKostum_post(){
        $id_tempat= $this->post('id_tempat');
        $getKostum = $this->db->query("
        SELECT * FROM kostum WHERE id_tempat=$id_tempat")->result();
        if(!empty($getKostum)){
            $this->response(
                array(
                    "status" => "success",
                    "result" => array($getKostum)
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
    function insertKostum_post(){
        $data_kostum = array(
            "id_tempat" =>$this->post("id_tempat"),
            "id_kategori" =>$this->post("id_kategori"),
            "nama_kostum" =>$this->post("nama_kostum"),
            "jumlah_kostum" =>$this->post("jumlah_kostum"),
            "harga_kostum" => $this->post("harga_kostum"),
            "deskripsi_kostum"=>$this->post("deskripsi_kostum"),
            "foto_kostum" =>$this->post("foto_kostum")
        );
        if(empty($data_kostum['id_kategori'])){
            $this->response(array('status'=>'fail',"message"=>"id_kategori kosong"));
        }else{
            $getId_tempat = $this->db->query("SELECT id_tempat from tempat_sewa WHERE id_tempat='".$data_kostum['id_tempat']."'")->result();
            $getId_kategori= $this->db->query("SELECT id_kategori from kategori WHERE id_kategori='".$data_kostum['id_kategori']."'")->result();
            $message="";
            if(empty($getId_tempat)) $message.="id tempat tidak ada";
            if(empty($getId_kategori)){
                if (empty($message)) {
                $message.="id kategori tidak ada";
            }else{
                $message.="id kategori tidak ada";
            }
        }
        if(empty($message)){
            $data_kostum['foto_kostum']= $this->uploadPhoto();
            $insert= $this->db->insert('kostum', $data_kostum);
            if($insert){
                $this->response(
                    array(
                        "status"    => "success",
                        "result"    => array($data_kostum),
                        "message"   => $insert
                    )
                );
            }else{
                $this->response(array('status'=>'fail',"message"=>$message));
            }
        }
    }
}

 
 function deleteKategori($data_kategori){
                    if (empty($data_kategori['id_kategori'])){
                        $this->response(
                            array(
                                "status" =>"failed",
                                "message" =>"ID kategori harus diisi"
                            )
                            );
                    }else{
                        $get_kategori_baseID = $this->db->query("
                        SELECT 1
                        FROM kategori 
                        WHERE id_kategori = {$data_kategori['id_kategori']}")->num_rows(); 
                        if($get_kategori_baseID>0){
                            $get_photo_url = $this->db->query("
                            SELECT photo_url
                            FROM kategori
                            WHERE id_kategori = {$data_kategori['id_kategori']}")->result();
            
                            if(!empty($get_photo_url)){
                                $photo_nama_file = basename($get_photo_url[0]->photo_url);
                                $photo_lokasi_file = realpath(FCPATH . $this->folder_upload . $photo_nama_file);
            
                            if(file_exists($photo_lokasi_file)){
                                unlink($photo_lokasi_file);
                            }
                            $this->db->query("
                                DELETE FROM kategori 
                                WHERE id_kategori = {$data_kategori['id_kategori']}");
                                $this->response(
                                    array(
                                        "status" =>"success", 
                                        "message" => "Data ID = " .$data_kategori['id_kategori']. "berhasil dihapus"
                                    )
                                    );
                            }
                        }else{
                            $this->response(
                                array(
                                    "status" => "failed",
                                    "message" => "ID kategori tidak ditemukan"
                                )
                                );
                        }
                    }
                }
function updateKostum_post(){
    $data_kostum= array(
        'id_kostum' =>$this->post('id_kostum'),
        'id_kategori'=>$this->post('id_kategori'),
        'nama_kostum' =>$this->post('nama_kostum'),
        'jumlah_kostum' =>$this->post('jumlah_kostum'),
        'harga_kostum' =>$this->post('harga_kostum'),
        'deskripsi_kostum' =>$this->post('deskripsi_kostum'),
        'foto_kostum' =>$this->post('foto_kostum')
    );
    //cek apakah data ada di database
    $get_user_baseID = $this->db->query("
    SELECT * FROM kostum WHERE id_kostum ={$data_kostum['id_kostum']}")->num_rows();
    if($get_user_baseID ===0){
        //jika tidak ada
        $this->response(
            array(
                "status"=>"failed",
                "message" =>"id kostum tidak ditemukan"
            )
            );
    }else{
        //jika ada
        $data_kostum['foto_kostum'] = $this->uploadPhoto();
        if($data_kostum['foto_kostum']){
            $get_photo_kostum= $this->db->query("
            SELECT foto_kostum FROM kostum where id_kostum={$data_kostum['id_kostum']}")->result();
            if(!empty($get_photo_kostum)){
                //dapatkan nama user file
                $photo_nama_user_file = basename($get_photo_kostum[0]->foto_kostum);
                //dapatkan letak file di folder upload
                $photo_lokasi_file= realpath(FCPATH. $this->folder_upload . $photo_nama_user_file);
                //jika file ada, hapus
                if(file_exists($photo_lokasi_file)){
                    //hapus file
                    unlink($photo_lokasi_file);
                }
            }
            //jika upload foto berhasil 
            $update = $this->db->query("
            UPDATE kostum set
            id_kategori= '{$data_kostum['id_kategori']}',
            nama_kostum= '{$data_kostum['nama_kostum']}',
            jumlah_kostum= '{$data_kostum['jumlah_kostum']}',
            harga_kostum= '{$data_kostum['harga_kostum']}',
            deskripsi_kostum= '{$data_kostum['deskripsi_kostum']}',
            foto_kostum= '{$data_kostum['foto_kostum']}'
            WHERE id_kostum ='{$data_kostum['id_kostum']}'
             ");
        }else{
            //jika foto kosong / tidak berhasil di upload
            $update = $this->db->query("
            UPDATE kostum set
            id_kategori= '{$data_kostum['id_kategori']}',
            nama_kostum= '{$data_kostum['nama_kostum']}',
            jumlah_kostum= '{$data_kostum['jumlah_kostum']}',
            harga_kostum= '{$data_kostum['harga_kostum']}',
            deskripsi_kostum= '{$data_kostum['deskripsi_kostum']}'
            WHERE id_kostum ='{$data_kostum['id_kostum']}'
             ");
        }
        if($update){
            $this->response(
                array(
                    "status" =>"success",
                    "result" =>array($data_kostum),
                    "message"=>$update
                )
                );
        }
    }
}


function hapusKostum_post(){
    $data_kostum = array(
        "id_kostum" =>$this->post("id_kostum"),
        "id_tempat" =>$this->post("id_tempat"),
        "id_kategori" =>$this->post("id_kategori"),
        "nama_kostum" =>$this->post("nama_kostum"),
        "jumlah_kostum" =>$this->post("jumlah_kostum"),
        "harga_kostum" => $this->post("harga_kostum"),
        "deskripsi_kostum"=>$this->post("deskripsi_kostum"),
        "foto_kostum" =>$this->post("foto_kostum")
    );
            $this->db->query("
                DELETE FROM kostum 
                WHERE id_kostum = {$data_kostum['id_kostum']}");
                $this->response(
                    array(
                        "status" =>"success", 
                        "message" => "Data ID = " .$data_kostum['id_kostum']. "berhasil dihapus"
                    )
                    );
       
    
}
  
          
    function uploadPhoto() {
        
                // Apakah user upload gambar?
                if ( isset($_FILES['foto_kostum']) && $_FILES['foto_kostum']['size'] > 0 ){
        
                    // Foto disimpan di android-api/uploads
                    $config['upload_path'] = realpath(FCPATH . $this->folder_upload);
                    $config['allowed_types'] = 'jpg|png';
        
                    // Load library upload & helper
                    $this->load->library('upload', $config);
                    $this->load->helper('url');
        
                    // Apakah file berhasil diupload?
                    if ( $this->upload->do_upload('foto_kostum')) {
        
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