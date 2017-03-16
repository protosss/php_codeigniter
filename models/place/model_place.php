<?php 
class Model_place extends CI_Model {
	function __construct(){
		parent::__construct();
		
	}
	/////////////////////////////////----------------------------------------------------------------------------------------------------------------------------------------
	public function add($data)
	{
		//print_r($data);
		$this->db->insert('place', $data);
		return $this->db->insert_id();
	}
	/////////////////////////////////----------------------------------------------------------------------------------------------------------------------------------------
	public function update($data,$id)
	{
		$this->db->select("*")->from("place")->where("place_id",$id)->where("place_status",'1');
		$row = $this->db->get()->row_array();
			if($row['place_id']!="")
			{
				$this->db->where('place_id', $id);
				$this->db->update('place', $data);
			}else{
				echo "ERROR !!! No ID";
			}
	}
	/////////////////////////////////------------------------------------------------------
	function delete_place_id($id){
		$this->db->select("*")->from("place")->where("place_id",$id)->where("place_status",'1');
		$row = $this->db->get()->row_array();
		if($row['place_id']!="")
		{
			//ลบออกจากตาราง
			/*
			$this->db->where('animal_id', $id);
			$this->db->delete('animals');
			*/
			
			//Update สถานะ เป็น ไม่ใช้งาน
			$data['place_status'] = '0';
			$this->db->where('place_id', $row['place_id']);
		   	$this->db->update('place', $data);
		}
		else
		{
			echo "ERROR !!! No ID";
		}
	}
	
	/////////////////////////////////---------------------------------------------- Query แบบ Joinh Page Split------------------------------------------------------------------------------------------
	public function select_place($type='', $con='', $limit='', $start='')
	{
		$wh="";
		if(!empty($s_name)){
			//$wh="$this->db->where('place.place_name', $s_name, 'left outer');";
		}
		
		$this->db->select('place.place_id, place.place_marker, place_type.place_type_name, place.place_name, thai_province.province_name, place.place_latitude, place.place_longitude, place.place_zoom');
		$this->db->from('place');
		$this->db->join('place_type', 'place_type.place_type_id = place.place_type');
		$this->db->join('thai_province', 'thai_province.PROVINCE_ID = place.place_province');
		$this->db->where('place.place_status', 1);
		if($con){
		$this->db->like('place.place_name', $con);
		}
		
		//ดึงจำนวนแถวทั้งหมด 
		if($type=='total_rows')
		{ 
			$query = $this->db->get();
			return $query->num_rows();
		}
		//get_data //ดึงข้อมูลเฉพาะหน้า ตามจำนวนรายการต่อหน้า 
		else
		{ 
			$this->db->limit($limit, $start);
			$this ->db->order_by("place.place_id ", "ASC");
			$query = $this->db->get();
		//echo $this->db->last_query(); //Print Query 
					if($query->num_rows() >0){
						return $query->result_array();
					}
					else
					{
						return false;
					}
		}
		
	}	
	/////////////////////////////////---------------------------------------------- Query แบบ Joinh Return result_array------------------------------------------------------------------------------------------
	public function select_place_joinh($s_name='')
	{
		$wh="";
		if(!empty($s_name)){
			$wh="$this->db->where('place.place_name', $s_name, 'left outer');";
		}
		
		$this->db->select('place.place_id, place.place_marker, place_type.place_type_name, place.place_name, thai_province.province_name, place.place_latitude, place.place_longitude, place.place_zoom');
		$this->db->from('place');
		$this->db->join('place_type', 'place_type.place_type_id = place.place_type');
		$this->db->join('thai_province', 'thai_province.PROVINCE_ID = place.place_province');
		$this->db->where('place.place_status', 1);
		
		$query = $this->db->get();

		if($query->num_rows() >0)
		{
			$rs = $query->result_array();
			return $rs;
		}
		else
		{
			return "ERROR !!! No ID";
		}
		
	}
	/////////////////////////////////---------------------------------------------- Query Select Data To Update------------------------------------------------------------------------------------------
	public function select_place_update($id='')
	{

		$this->db->select('place_id	
									,CAST(SUBSTRING(place_marker,1,4000) AS varchar(MAX)) AS place_marker
									,CAST(SUBSTRING(place_marker,4001,8000) AS varchar(MAX)) AS place_marker2				
									,place_type
									,place_name
									,place_address
									,place_tumbon
									,place_amphur
									,place_province
									,place_zipcode
									,place_latitude
									,place_longitude
									,place_zoom	
									,place_tel
									,place_fax
									,place_email
									,place_website
									,place_facebook
									,place_line
									,place_status');
		$this->db->from('place');
		$this->db->join('place_type', 'place_type.place_type_id = place.place_type');
		$this->db->join('thai_province', 'thai_province.PROVINCE_ID = place.place_province');
		$this->db->where('place.place_id', $id);
		$this->db->where('place.place_status', 1);
		//$query = $this->db->get();
		$row = $this->db->get()->row_array();
		
		if($row['place_id'] != "")
		{
			//$rs = $query->result_array();
			return $row;
		}
		else
		{
			return "ERROR !!! No ID";
		}
		
	}
}