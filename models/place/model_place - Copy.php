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
	public function select_place($s_name)
	{
		/*$this->db->select("*")->from("place")->where("place_type",$id)->where("place_status",'1');
		$row = $this->db->get()->row_array();
		if($row['place_id'] != "")
		{
			return $row;		
		}
		else
		{
			return "ERROR !!! No ID";
		}*/
		$wh="";
		if(!empty($s_name)){
			$wh="AND place.place_name like '%".$s_name."%' ";
		}
		
		$query = $this->db->query("SELECT place.place_id,
																	place.place_marker,
																	place_type.place_type_name,
																	place.place_name,
																	thai_province.province_name,
																	place.place_latitude,
																	place.place_longitude,
																	place.place_zoom
														FROM place
														INNER JOIN place_type ON place.place_type = place_type.place_type_id
														INNER JOIN thai_province ON place.place_province = thai_province.PROVINCE_ID
														WHERE 1 = 1 $wh ");
		$rs = $query->result_array();
		if(count($rs) > 0)
		{
			return $rs;		
		}
		else
		{
			return "ERROR !!! No ID";
		}
		
	}
	/////////////////////////////////------------------------------------------------------
	
	public function update($data,$id)
	{
		$this->db->select("*")->from("animals")->where("animal_id",$id)->where("active_status",'1');
		$row = $this->db->get()->row_array();
			if($row['animal_id']!="")
			{
				$this->db->where('animal_id', $id);
				$this->db->update('animals', $data);
			}else{
				echo "ERROR !!! No ID";
			}
	}
	/////////////////////////////////------------------------------------------------------
	function delete_animal_id($id){
		$this->db->select("*")->from("animals")->where("animal_id",$id)->where("active_status",'1');
		$row = $this->db->get()->row_array();
		if($row['animal_id']!="")
		{
			//ลบออกจากตาราง
			/*
			$this->db->where('animal_id', $id);
			$this->db->delete('animals');
			*/
			
			//Update สถานะ เป็น ไม่ใช้งาน
			$data['active_status'] = '0';
			$this->db->where('animal_id', $row['animal_id']);
		   	$this->db->update('animals', $data);
		}
		else
		{
			echo "ERROR !!! No ID";
		}
	}
	
	
}