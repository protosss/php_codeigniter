<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Theme {
   function view($view_name,$data_array=null ){
		$ci =& get_instance();
		//get_cookie �繢ͧ Codeigniter   | system/helpers/cookie_helper.php
		//���� get ��� cookie 'notheme' �ҡ�դ�� ����Ŵ���� view content ��ҹ�� ����ҡ����դ�ҡ�� laod view header, menu left, content ��� footer ����ӴѺ
		  if(get_cookie('notheme')==1)
		  {
			 	$ci->load->view($view_name,$data_array);		 
		  }
		  else
		  {
				//// Load header 
				$data['username']  = $ci->session->userdata($ci->config->item('sys_project').'user_name'); 		//�Ҩҡ��� Login  $_SESSION['user_name']
				 $ci->load->view('theme/header',$data); 									//Load view header ����� $data ����� session
				 //// Load menu 
				 $ci->load->model("Theme/Theme_model"); 								//���¡ Model
				 $data['menu'] = $ci->Theme_model->_get_menu(); 					//��ҹ Model ���� Query ����
				 $ci->load->view('theme/menu_left',$data); 								//Load view  menu left ����� $data ��������ٷ����ҡ��� Query �Ҩҡ DB
				  //// Load Content 
				 $ci->load->view($view_name,$data_array);								//Load view content ����� $data ����ա���Ѻ��Ҩҡ Controller
				  //// Load footer
				 $ci->load->view('theme/footer');												//Load view footer
		  }
	  
   }

   /***************************************************************************************************************************************/
   function is_logged_in(){ //Check Login
   		$ci =& get_instance();		//// ????
		if(!$ci->session->userdata($ci->config->item('sys_project').'user_id')){ //Check Logged 
			$method=$ci->config->item('method_not_login'); //Method Not Check Login 
			
			$ctl_mtd =  $ci->router->fetch_class().'/'.$ci->router->fetch_method();
			if(!in_array($ctl_mtd, $method)){
				echo '<script>
						window.parent.location.href="'.site_url('authen/login').'";
						</script>
				';
				exit();
			}
		}
	}
   
   /***************************************************************************************************************************************/
   
  public function dropdown_select($sql,$id_selected,$valueFiled,$nameField)
	{
		$ci =& get_instance();		
		 $ci->load->model("Theme/Theme_model"); //���¡ Model
		$dd = $ci->Theme_model->dropdown_select($sql,$id_selected,$valueFiled,$nameField);
		
		$row_set="";
		foreach ($dd as $dropdown)
		{
			$value = $dropdown[$valueFiled];
			$name  = $dropdown[$nameField] ;
			$selected =  ($id_selected==$value)?"selected='selected' ":"";
			$row_set.=  '<option value="'.$value.'"   '.$selected.' >'.$name.'</option>';
		}
		return  $row_set;
	}
   /***************************************************************************************************************************************/
   
    function splits(){
	
		$ci =& get_instance();
		//----------------------------------------------
      	$config['base_url'] = $this->base_url; //�������ͧ˹�� Page 
		$config['total_rows'] = $this->total_rows; //�ӹǹ��¡�÷�����
		$config['per_page'] = $this->per_page; //�ӹǹ�ʴ���¡�õ��˹�� 
		$config['use_page_numbers'] = TRUE;
		$config["uri_segment"] = $this->uri_segment; //Segment �ͧ�����Ţ˹�� 
		$config['num_links'] = $this->total_rows;
		$config['full_tag_open'] = "<ul class='pagination'>";
		$config['full_tag_close'] ="</ul>";
		$config['num_tag_open'] = '<li>';
		$config['num_tag_close'] = '</li>';
		$config['anchor_class']='class="links"';
		$config['cur_tag_open'] = "<li class='disabled'><li class='active'><span>";
		$config['cur_tag_close'] = "</span></li>";
		$config['next_tag_open'] = "<li>";
		$config['next_tagl_close'] = "</li>";
		$config['prev_tag_open'] = "<li>";
		$config['prev_tagl_close'] = "</li>";
		$config['first_tag_open'] = "<li>";
		$config['first_tagl_close'] = "</li>";
		$config['last_tag_open'] = "<li>";
		$config['last_tagl_close'] = "</li>";
		$config['prev_link'] = 'Prev';
		$config['next_link'] = 'Next';	
		$config['constant_num_links'] = TRUE;
		$ci->pagination->initialize($config);
		
		//$data['page']=($page) ? $page : 1;
		//$data['per_page']=$config['per_page'];
		//$data['pagination'] = $ci->pagination->create_links();
		return $ci->pagination->create_links();
	  
   }
   
      /***************************************************************************************************************************************/

}