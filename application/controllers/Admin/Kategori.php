<?php
defined('BASEPATH') OR exit('No direct script access allowed');
// Jika ada pesan "REST_Controller not found"
require APPPATH . '/libraries/REST_Controller.php';
require APPPATH . 'libraries/Format.php';
class Kategori extends REST_Controller{
	

	public function kategori_get(){
		$id_kategori = $this->get('id_kategori');
		if ($id_kategori == '') {
			$kategori = $this->db->get('kategori')->result();
		}else{
			$this->db->where('id_kategori',$id_kategori);
			$kategori = $this->db->get('kategori')->result();
		}
		$this->response($kategori,200);
            
	}

	public function kategori_post(){
		$data = array(
			'id_kategori' => $this->post('id_kategori'),
			'nama_kategori' => $this->post('nama_kategori'));
		$insert=$this->db->insert('kategori',$data);
		if ($insert) {
			$this->response($data,200);
		}else{
			$this->response(array('status' => 'fail',502));
		}
		
	}

	public function kategori_put(){
		$id_kategori = $this->put('id_kategori');
		$data = array( 'nama_kategori' => $this->put('nama_kategori'));
		$this->db->where('id_kategori',$id_kategori);
		$update = $this->db->update('kategori',$data);

		if ($update) {
			$this->response($data,200);
		}else{
			$this->response(array('status' =>'fail',502));
		}
	}

	public function kategori_delete(){
		$id_kategori = $this->delete('id_kategori');
		$this->db->where('id_kategori',$id_kategori);
		$delete = $this->db->delete('kategori');
		if ($delete) {
			$this->response(array('status' => 'success'),201);
		}else{
			$this->response(array('status' => 'fail'), 502);
		}
	}


}