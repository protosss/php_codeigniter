<?php
defined('BASEPATH') OR exit('No direct script access allowed');

			
 include_once APPPATH.'/third_party/mpdf57/mpdf.php';
 
class M_pdf extends mPDF {
 
	public function __construct()
    {
		parent::__construct();
        $this->setting();
    }
	
	public function setting($set_page='A4'){		
		//A4 == �ǵ�� 
		//A4-L == �ǹ͹ 
		$this->mPDF('th', $set_page, 0, 'thsaraban', 10, 10, 15, 15, 5, 5); //Default �͹�� thsaraban ��  margin (����, ���, ��, ��ҧ, header ��, footer ��ҧ)
		$this->charset_in='TIS-620';	
	}

}
