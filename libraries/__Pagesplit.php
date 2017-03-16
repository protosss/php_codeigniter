<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pagesplit {
	
	var $base_url='';
	var $total_rows='';
	var $per_page=15;
	var $uri_segment=3;
	var $page=1;
		
   function splits(){
	
		$ci =& get_instance();
		//----------------------------------------------
      	$config['base_url'] = $this->base_url; //ที่อยู่ของหน้า Page 
		$config['total_rows'] = $this->total_rows; //จำนวนรายการทั้งหมด
		$config['per_page'] = $this->per_page; //จำนวนแสดงรายการต่อหน้า 
		$config['use_page_numbers'] = TRUE;
		$config["uri_segment"] = $this->uri_segment; //Segment ของหลายเลขหน้า 
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




}