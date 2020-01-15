<?php
defined('BASEPATH') OR exit('No direct script access allowed');
// Jika ada pesan "REST_Controller not found"
require APPPATH . '/libraries/REST_Controller.php';
require APPPATH . 'libraries/Format.php';
class Sewa extends REST_Controller{
	private $folder_upload = 'uploads/';
	public function sewa_get(){
		$id_user = $this->get('id_user');
		if ($id_user == '') {
			$sewa = $this->db->get('sewa')->result();
		}else{
			$this->db->where('id_user',$id_user);
			$sewa = $this->db->get('sewa')->result();
		}
		$this->response($sewa,200);
	} 


	public function updateSewa_post(){
		$data_sewa=array(
			'id_sewa' => $this->post('id_sewa'),
			'bukti_sewa' =>$this->post('bukti_sewa')
		);
		//cek apakah data ada di dtabase
		$getBaseId=$this->db->query("
		SELECT 1 FROM sewa where id_sewa={$data_sewa['id_sewa']}")->num_rows();
		if($getBaseId ===0){
			//jika tidak ada
			$this->response(
				array(
					"status"=>"failed",
					"message" =>"id sewa tidak ditemukan"
				)
				);
		}else{

        //jika ada
        $data_sewa['bukti_sewa'] = $this->uploadPhoto();
        if($data_sewa['bukti_sewa']){
            $get_photo_bukti= $this->db->query("
            SELECT bukti_sewa FROM sewa where id_sewa={$data_sewa['id_sewa']}")->result();
            if(!empty($get_photo_sewa)){
                //dapatkan nama user file
                $photo_nama_user_file = basename($get_photo_sewa[0]->foto_sewa);
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
            UPDATE sewa set
			bukti_sewa= '{$data_sewa['bukti_sewa']}'
            WHERE id_sewa ='{$data_sewa['id_sewa']}'
             ");
		}else{
			$this->response(array(
				"status" =>"failed",
				"message" =>"upload bukti sewa dulu dong"
			));
		}
        if($update){
            $this->response(
                array(
                    "status" =>"success",
                    "result" =>array($data_sewa),
                    "message"=>$update
                )
                );
        }
	}
}
function uploadPhoto() {
	
			// Apakah user upload gambar?
			if ( isset($_FILES['bukti_sewa']) && $_FILES['bukti_sewa']['size'] > 0 ){
	
				// Foto disimpan di android-api/uploads
				$config['upload_path'] = realpath(FCPATH . $this->folder_upload);
				$config['allowed_types'] = 'jpg|png';
	
				// Load library upload & helper
				$this->load->library('upload', $config);
				$this->load->helper('url');
	
				// Apakah file berhasil diupload?
				if ( $this->upload->do_upload('bukti_sewa')) {
	
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