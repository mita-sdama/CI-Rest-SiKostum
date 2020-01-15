<?php
defined('BASEPATH') OR exit('No direct script access allowed');
// Jika ada pesan "REST_Controller not found"
require APPPATH . '/libraries/REST_Controller.php';
require APPPATH . 'libraries/Format.php';
class User extends REST_Controller{

	public function all_get(){
		$id_user = $this->get('id_user');
		if ($id_user == '') {
		$user= $this->db->query("SELECT * FROM user WHERE level<>'Admin'")->result();
		}else{
			$user= $this->db->query("SELECT * FROM user WHERE id_user='$id_user'")->result();
		}
		$this->response($user,200);
	}
	
	public function user_get(){
		$id_user = $this->get('id_user');
		if ($id_user == '') {
		$user= $this->db->query("SELECT user.id_user as id_user, nama, jenis_kelamin, email, no_hp, foto_user, username, password, level, id_identitas,foto_ktp, status FROM user JOIN identitas ON user.id_user = identitas.id_user")->result();
		}else{
			$user= $this->db->query("SELECT user.id_user as id_user, nama, jenis_kelamin, email, no_hp, foto_user, username, password, level, id_identitas,foto_ktp, status FROM user JOIN identitas ON user.id_user = identitas.id_user WHERE user.id_user='$id_user'")->result();
		}
		$this->response($user,200);
		
	}

	public function userMenunggu_get(){
		$id_user = $this->get('id_user');
		$user= $this->db->query("SELECT count(id_identitas) as jumlah FROM user JOIN identitas ON user.id_user = identitas.id_user WHERE status='menunggu'")->result();
		$this->response($user,200);
		
	}

	public function profil_get(){
		$id_user = $this->get('id_user');
		if ($id_user == '') {
			$user = $this->db->get('user')->result();
		}else{
			$this->db->where('id_user',$id_user);
			$user = $this->db->get('user')->result();
		}
		$this->response($user,200);
	
	}

	public function alamat_get(){
		$id_user = $this->get('id_user');
		if ($id_user == '') {
			$alamat= $this->db->query("SELECT * FROM user JOIN alamat ON user.id_user =alamat.id_user")->result();
		}else{
			$alamat= $this->db->query("SELECT * FROM user JOIN alamat ON user.id_user =alamat.id_user WHERE user.id_user='$id_user'")->result();
		}

		$this->response($alamat,200);
	}

	public function profil_put(){

		$id_user = $this->put('id_user');
                    $data = array('nama' => $this->put('nama'),
                        'jenis_kelamin' => $this->put('jenis_kelamin'),
                        'email' => $this->put('email'),
                        'no_hp' => $this->put('no_hp'),
                        'username' => $this->put('username'),
                        'password' => $this->put('password'));
              $this->db->where('id_user',$id_user);
            $do_update = $this->db->update('user', $data);
           
            if ($do_update){
                $this->response(
                    array(
                        "status" => "success",
                        "result" => array($data),
                        "message" => $do_update
                    )
                );
            }
           
        


	}

		public function verifikasi_put(){
		$id_identitas = $this->put('id_identitas');
        $data = array(
        			'id_identitas'	  =>$this->put('id_identitas'),
                    'status'          => $this->put('status'));
        $this->db->where('id_identitas', $id_identitas);
        $update = $this->db->update('identitas', $data);
        if ($update) {
            $this->response($data, 200);
        } else {
            $this->response(array('status' => 'fail', 502));
        }
    }


}
