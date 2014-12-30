<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class mdl_quotation_request_selected extends CI_Model {

	function __construct(){
    parent::__construct();
  }

  function getdata($plimit=true){
	# get parameter from easy grid
		$page = isset($_POST['page']) ? intval($_POST['page']) : 1;  
		$limit = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
		$sort = isset($_POST['sort']) ? strval($_POST['sort']) : 'id_ro';  
		$order = isset($_POST['order']) ? strval($_POST['order']) : 'asc';  
		$offset = ($page-1)*$limit;
		
		# create query
		$this->db->flush_cache();
		$this->db->start_cache();
			$this->db->select('*, b.full_name, c.departement_name');
			$this->db->from('tr_pr a');
			$this->db->join('sys_user b', 'b.user_id = a.user_id');
			$this->db->join('ref_departement c', 'c.departement_id = b.departement_id');

			$this->db->where('a.status','2');

			$this->db->order_by($sort, $order);
		$this->db->stop_cache();
		
		# get count
		$tmp['row_count'] = $this->db->get()->num_rows();
		
		# get data
		if($plimit == true){
			$this->db->limit($limit, $offset);
		}
		$tmp['row_data'] = $this->db->get();
		
		return $tmp;
	}

	function togrid($data, $count){
		$response->total = $count;
		$response->rows = array();
		if($count>0){
			$i=0;
			foreach($data->result_array() as $row){
				foreach($row as $key => $value){
					$response->rows[$i][$key]=$value;
				}
				$i++;
			}
		}
		return json_encode($response);
	}

	function check_tr_qr()
	{
		$this->db->select('id_vendor');
		$query = $this->db->get('tr_qr');
		return $query->result();

	}

	function list_vendor()
	{
		$id_vendor = $this->mdl_quotation_request_selected->check_tr_qr();
		$item ='';
		foreach ($id_vendor as $l) {
			$item = $l->id_vendor;
			// echo $item;
			$this->db->where('a.id_vendor !=', $item);
		}

		$this->db->select('a.id_vendor,a.name_vendor');
		$query = $this->db->get('ref_vendor a');
		return $query->result();
	}

	function list_pr($id_pr)
	{
		$this->db->select('a.id_qr,a.id_pr,a.id_vendor,top,a.ETD,a.status,b.kode_barang,b.price,c.nama_barang,d.name_vendor,b.id_detail_qr');
		$this->db->join('tr_qr_detail b', 'b.id_qr = a.id_qr');
		$this->db->join('ref_barang c', 'c.kode_barang = b.kode_barang');
		$this->db->join('ref_vendor d', 'd.id_vendor = a.id_vendor');
		$this->db->join('tr_pr e', 'e.id_pr = a.id_pr');
		$this->db->order_by('a.id_vendor asc,  b.kode_barang asc');
		$this->db->where('e.id_pr',$id_pr);
		$this->db->where('e.status', 2);

		$query = $this->db->get('tr_qr a');
		return $query->result_array();
	}

	function update($id,$data)
	{
		$harga = str_replace(",","",$data);
		$this->db->where('id_detail_qr', $id);
		$this->db->set('price',$harga);
		$this->db->update('tr_qr_detail');
	}
	
	function update_qr_vendor($id_pr)
	{
		$this->db->set('status','1');
		$this->db->where('id_pr', $id_pr);
		return $this->db->update('tr_qr');
	}
	function cek_price($kode)
	{
		$this->db->select('price,id_pr,id_detail_qr');
		$this->db->where('id_qr', $kode);
		$query = $this->db->get('tr_qr_detail');
		return $query->result();
	}

	function selected($kode,$id_pr){
		$cek = $this->mdl_quotation_request_selected->cek_price($kode);
			foreach ($cek as $l) {
				$price = $l->price;
			}
				if($price !=0)
				{
					$this->mdl_quotation_request_selected->update_qr_vendor($id_pr);
					//cek id_qr dan status menjadi 2
					$this->db->flush_cache();

					$this->db->set('status', "2");

					$this->db->where('id_qr', $kode);

					$result = $this->db->update('tr_qr');
				}else{
					return FALSE;
				}
				
		//return
		if($result) {
				return TRUE;
		}else {
				return FALSE;
		}
	}

	function Delete($kode)
	{
		$this->db->flush_cache();
		$this->db->where('id_qr', $kode);
		$result = $this->db->delete('tr_qr');

		if($result) {
				return TRUE;
		}else {
				return FALSE;
		}
	}

	function done($kode){
		
		$this->db->flush_cache();

		$this->db->set('status', "3");

		$this->db->where('id_pr', $kode);
		$result = $this->db->update('tr_pr');

		//return
		if($result) {
				return TRUE;
		}else {
				return FALSE;
		}
	}

	function Insert_vendor($data)
	{
		$this->db->flush_cache();
		// insert data
    $this->db->set('id_pr', $data['id_pr']);
    $this->db->set('id_vendor',$data['id_vendor']);
    $this->db->set('top',$data['top']);
    $this->db->set('ETD', $data['date_create']);
    $this->db->set('status',$data['status'] );

		$result = $this->db->insert('tr_qr');
		
		//return
		if($result) {
			return TRUE;
		}else {
			return FALSE;
		}
	}

}

/* End of file mdl_quotation_request_selected.php */
/* Location: ./application/models/mdl_quotation_request_selected.php */