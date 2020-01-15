<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';
require APPPATH . 'libraries/Format.php';

class Profil extends REST_Controller {
private $folder_upload = 'uploads/';

function all_get(){
        $get_user = $this->db->query("
            SELECT
            id_user,
            nama,
            jenis_kelamin,
            no_hp,
            email,
            username,
            password,
            level,
            foto_user
            FROM user")->result();
        $this->response(
           array(
               "status" => "success",
               "result" => $get_user
           )
       );
    }
function myProfil_post(){
    $id_user = $this->post('id_user');
        $get_user = $this->db->query("
            SELECT
            id_user,
      nama,
      jenis_kelamin,
       no_hp,
           foto_user,
           email,
            username,
            password,
            level
            FROM user WHERE id_user=$id_user")->result();
        $this->response(
           array(
               "status" => "success",
               "result" => $get_user
           )
       );
    }
    
    function all_post() {

        $action  = $this->post('action');
        $data_user = array(
            'id_user' => $this->post('id_user'),
            'nama'       => $this->post('nama'),
            'jenis_kelamin'     => $this->post('jenis_kelamin'),
            'no_hp' =>$this->post('no_hp'),
            'email' =>$this->post('email'),
            'username'      => $this->post('username'),
            'password'      => $this->post('password'),
            'level'      => $this->post('level'),
            'foto_user'   => $this->post('foto_user')
        );

        switch ($action) {
            case 'insert':
            $this->insertuser($data_user);
            break;
            
            case 'update':
            $this->updateuser($data_user);
            break;
            
            case 'delete':
            $this->deleteuser($data_user);
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
    function myedit_post(){
       
       $data_user= array(
            'id_user' =>$this->post('id_user'),
            'nama' => $this->post('nama'),
            'jenis_kelamin' => $this->post('jenis_kelamin'),
            'email' => $this->post('email'),
            'no_hp' => $this->post('no_hp'),
            'foto_user' => $this->post('foto_user'),
            'username' => $this->post('username'),
            'password' =>$this->post('password')
        );
        // Cek apakah ada di database
        $get_user_baseID = $this->db->query("
        SELECT 1
        FROM user
        WHERE id_user = {$data_user['id_user']}")->num_rows();

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
        $data_user['foto_user'] = $this->uploadPhoto();

        if ($data_user['foto_user']){
            $get_photo_user =$this->db->query("
                SELECT foto_user
                FROM user
                WHERE id_user = {$data_user['id_user']}")->result();

            if(!empty($get_photo_user)){

                // Dapatkan nama_user file
                $photo_nama_user_file = basename($get_photo_user[0]->foto_user);
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
                UPDATE user SET
                nama = '{$data_user['nama']}',
                jenis_kelamin = '{$data_user['jenis_kelamin']}',
                email = '{$data_user['email']}',
                no_hp = '{$data_user['no_hp']}',
                foto_user = '{$data_user['foto_user']}',
                username = '{$data_user['username']}',
                password = '{$data_user['password']}'
                WHERE id_user = '{$data_user['id_user']}'");

        } else {
            // Jika foto kosong atau upload foto tidak berhasil, eksekusi update
            $update = $this->db->query("
                UPDATE user
                SET
                nama = '{$data_user['nama']}',
                jenis_kelamin = '{$data_user['jenis_kelamin']}',
                email = '{$data_user['email']}',
                no_hp = '{$data_user['no_hp']}',
                username = '{$data_user['username']}',
                password = '{$data_user['password']}'
                WHERE id_user = '{$data_user['id_user']}'");
        }

        if ($update){
            $this->response(
                array(
                    "status"    => "success",
                    "result"    => array($data_user),
                    "message"   => $update
                )
            );
        }
    }
    }

    function uploadPhoto() {
        
                // Apakah user upload gambar?
                if ( isset($_FILES['foto_user']) && $_FILES['foto_user']['size'] > 0 ){
        
                    // Foto disimpan di android-api/uploads
                    $config['upload_path'] = realpath(FCPATH . $this->folder_upload);
                    $config['allowed_types'] = 'jpg|png';
        
                    // Load library upload & helper
                    $this->load->library('upload', $config);
                    $this->load->helper('url');
        
                    // Apakah file berhasil diupload?
                    if ( $this->upload->do_upload('foto_user')) {
        
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
