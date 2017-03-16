<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Place extends CI_Controller {
	function __construct(){
		parent::__construct();
		$this->output->set_header('Content-Type:text/html;Charset=TIS-620');
		$this->load->model("place/model_place");

				
				
		/*if($this->session->userdata('user_id')<=0){
			redirect(base_url()."authen/login","refresh");
		}*/
	}
	/////////////////////////////////----------------------------------------------------------------------------------------------------------------------------------------
	public function index()
	{
		redirect('place/page_split');
	}
	
	public function page_split()
	{
		//$this->load->model('model_place/select_place');		
		//--------------------------------------------------------------------------------------------------------------------------------------
		$data['s_name']=$this->input->get('s_name');
		
		$this->theme->base_url=site_url('place/page_split'); //URL หน้า Page
		$this->theme->total_rows=$this->model_place->select_place('total_rows',$data['s_name']); //จำนวนรายการทั้งหมด
		$this->theme->per_page=10; //จำนวนรายการต่อหน้า
		$this->theme->uri_segment=3; //Segment ของหมายเลขหน้า 
		$this->theme->page=$this->uri->segment(3); //หน้าปัจจุบัน
		
		
		$data['per_page']=$this->theme->per_page; 
		$data['page']=$this->theme->page ? $this->theme->page : 1; //ส่งขึ้นไปแสดงลำดับ
		$data['pagination']=$this->theme->splits(); //ส่งขึ้นไป Gen หมายเลขแบ่งหน้า 
		$data['start']=($data['page'] * $data['per_page'])-($data['per_page']); //เริ่มต้นแสดงข้อมูลจาก Record  xx 
		$data['id_table']='id=""';
		//-------------------------------------------------------------------------------------------------------------------------------------
		$data['result']=$this->model_place->select_place('get_data',$data['s_name'], $data['per_page'], $data['start']);
		
		$this->theme->view('place/place_list', $data);
	}
	
	public function index_result_array()
	{
		$data['s_name']='';
		$data['pagination']=''; //ส่งขึ้นไป Gen หมายเลขแบ่งหน้า 
		$data['start']=0; //เริ่มต้นแสดงข้อมูลจาก Record  xx 
		$data['id_table']='id="dataTables"';
		//$s_name = $this->input->post('s_name');		
		$data['result'] =  $this->model_place->select_place_joinh();
		
		$this->theme->view('place/place_list', $data);
	}
	
	public function add_place(){
			$data['place_type_select']  = $this->theme->dropdown_select("SELECT place_type_id, place_type_name FROM place_type WHERE place_type_status = 1 Order by  place_type_id ASC  ",'38', 'place_type_id', 'place_type_name');
		
			///// Dropdown จังหวัด อำเภอ ตำบล 
			$data['province_select']  = $this->theme->dropdown_select("SELECT PROVINCE_ID,PROVINCE_NAME FROM thai_province",'', 'PROVINCE_ID', 'PROVINCE_NAME');
			$data['conv_img'] =  '';
			
			$this->load->view('place/place_form', $data);
		}
		
	public function show_map(){
			$this->load->view('place/map');
		}	
	
	public function convert_img()
	{
		$data['file_img']=$this->input->post('image_url');
		$data['conv_img']='image_to_base64';
		$this->load->view('place/place_form', $data);
	}	
	////////////////////////////////////////////----------------------------------------------------------------------------------------------------
	public function save_place()
    {
						//$upload_data = $this->upload->data();
						$data['place_marker'] 			= $this->input->post('place_marker');
						$data['place_type'] 				= $this->input->post('place_type');
						$data['place_name'] 			=  iconv( 'UTF-8' , 'TIS-620' ,$this->input->post('place_name'));
						$data['place_address'] 		=  iconv( 'UTF-8' , 'TIS-620' ,$this->input->post('place_address'));
						$data['place_tumbon'] 		=  $this->input->post('place_tumbon');
						$data['place_amphur'] 		=  $this->input->post('place_amphur');
						$data['place_province'] 		=  $this->input->post('place_province'); 
						$data['place_zipcode'] 		= $this->input->post('place_zipcode'); 						
						$data['place_latitude'] 			= $this->input->post('place_latitude');
						$data['place_longitude'] 				= $this->input->post('place_longitude');
						$data['place_zoom'] 			=  $this->input->post('place_zoom');
						$data['place_tel'] 		=  $this->input->post('place_tel');
						$data['place_fax'] 		=  $this->input->post('place_fax');
						$data['place_email'] 		=  $this->input->post('place_email');
						$data['place_website'] 		=  $this->input->post('place_website'); 
						$data['place_facebook'] 		= $this->input->post('place_facebook'); 
						$data['place_line'] 			=  $this->input->post('place_zoom');
						$data['create_by'] 		=  'Yota';
				
						$this->model_place->add($data);
						
						//หรือสามารถทำแบบไม่กำหนด input ก็ได้ แต่ input ที่อยุ่ในหน้า form จะต้องมีชื่อเหมือน fields ใน Database
						//$data=$this->input->post();
						//$this->model_animal->add($data);
                      
                }
      	////////////////////////////////////////////----------------------------------------------------------------------------------------------------
		public function update_place(){
			
			$id = $this->uri->segment(3);
			$data = $this->model_place->select_place_update($id);
			$data['conv_img'] =  '';
			
			$data['place_type_select']  = $this->theme->dropdown_select("SELECT place_type_id, place_type_name FROM place_type WHERE place_type_status = 1 Order by  place_type_id ASC  ",$data['place_type'], 'place_type_id', 'place_type_name');
		
			///// Dropdown จังหวัด อำเภอ ตำบล 
			$data['province_select']  = $this->theme->dropdown_select("SELECT PROVINCE_ID,PROVINCE_NAME FROM thai_province",$data['place_province'], 'PROVINCE_ID', 'PROVINCE_NAME');
			$data['amphur_select']  = $this->theme->dropdown_select("SELECT AMPHUR_ID,AMPHUR_NAME FROM thai_amphur",$data['place_amphur'], 'AMPHUR_ID', 'AMPHUR_NAME');
			$data['tumbon_select']  = $this->theme->dropdown_select("SELECT TUMBON_ID,TUMBON_NAME FROM thai_tumbon",$data['place_tumbon'], 'TUMBON_ID', 'TUMBON_NAME');
			
			$this->load->view('place/update_place',$data);
		}
		
			////////////////////////////////////////////----------------------------------------------------------------------------------------------------
		public function update_data()
		{
				$place_id   		= $this->input->post('place_id');
				$data['place_marker'] 			= $this->input->post('place_marker');
				$data['place_type'] 				= $this->input->post('place_type');
				$data['place_name'] 			=  @iconv( 'UTF-8' , 'TIS-620' ,$this->input->post('place_name'));
				$data['place_address'] 		=  @iconv( 'UTF-8' , 'TIS-620' ,$this->input->post('place_address'));
				$data['place_tumbon'] 		=  $this->input->post('place_tumbon');
				$data['place_amphur'] 		=  $this->input->post('place_amphur');
				$data['place_province'] 		=  $this->input->post('place_province'); 
				$data['place_zipcode'] 		= $this->input->post('place_zipcode'); 						
				$data['place_latitude'] 			= $this->input->post('place_latitude');
				$data['place_longitude'] 				= $this->input->post('place_longitude');
				$data['place_zoom'] 			=  $this->input->post('place_zoom');
				$data['place_tel'] 		=  $this->input->post('place_tel');
				$data['place_fax'] 		=  $this->input->post('place_fax');
				$data['place_email'] 		=  $this->input->post('place_email');
				$data['place_website'] 		=  $this->input->post('place_website'); 
				$data['place_facebook'] 		= $this->input->post('place_facebook'); 
				$data['place_line'] 			=  $this->input->post('place_zoom');
				$data['update_by'] 		=  'Yota';
				$data['update_datetime'] 	= date('Y-m-d H:i:s');
				
				$this->model_place->update($data,$place_id);
		 }
		 
		public function delete_place()
		{
			$id = $this->uri->segment(3);
			$data_model = $this->model_place->select_place_update($id);
			if($data_model['place_id'] != "" ){
				$this->model_place->delete_place_id($id);
			}
		}
}


/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */