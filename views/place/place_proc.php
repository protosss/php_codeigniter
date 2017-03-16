<?php
	session_start();
	@header("Content-Type:text/plain;Charset=TIS-620");
	$path = "../";   	
	include($path.'include/config.inc.php');
	include($path.'include/class_db.php');
	include($path.'include/class_display.php');
	include($path.'include/class_application.php');
	//print_r ($_GET); 
	$CLASS['db']=new db();
	$CLASS['db']->connect();	
	$CLASS['disp']=new display();
	$CLASS['app']=new application();
	
	$db=$CLASS['db'];
	$disp=$CLASS['disp'];
	$app=$CLASS['app'];
		
	$data=$_POST;
	foreach(array_keys($data) as $key){
		$data[$key] = @iconv('UTF-8','TIS-620',trim($data[$key]));
	};
	
	
	if($data['proc']=='get_amphur'){
		
		$sql="SELECT AMPHUR_ID
								,AMPHUR_NAME
				FROM thai_amphur
				WHERE PROVINCE_ID='".$data['place_province']."'
		";
		$query=$db->query($sql);
		//$num_rows = $db->num_rows($query);
		echo '<option value="">กรุณาเลือกอำเภอ/เขต</option>';
		while($rec=$db->fetch_array($query)){
			echo '<option value="'.$rec['AMPHUR_ID'].'">'.$rec['AMPHUR_NAME'].'</option>';
		}
		
	}
	
	if($data['proc']=='get_tumbon'){
		
		$sql="SELECT TUMBON_ID
								,TUMBON_NAME
				FROM thai_tumbon
				WHERE AMPHUR_ID='".$data['place_amphur']."'
		";
		$query=$db->query($sql);
		//$num_rows = $db->num_rows($query);
		echo '<option value="">กรุณาเลือกตำบล/แขวง</option>';
		while($rec=$db->fetch_array($query)){
			echo '<option value="'.$rec['TUMBON_ID'].'">'.$rec['TUMBON_NAME'].'</option>';
		}
		
	}
	
	if($data['proc']=='image_to_base64'){
		echo $app->image_to_base64($_POST['image_url']);
	}
	
	
	if($data['proc']=='add'){
		
		$db->beginTransaction();
			$fields['place_marker']=$data['place_marker'];
			$fields['place_type']=$data['place_type'];
			$fields['place_name']=$data['place_name'];
			$fields['place_address']=$data['place_address'];		
			$fields['place_tumbon']=$data['place_tumbon'];		
			$fields['place_amphur']=$data['place_amphur'];		
			$fields['place_province']=$data['place_province'];		
			$fields['place_zipcode']=$data['place_zipcode'];
			$fields['place_latitude']=$data['place_latitude'];		
			$fields['place_longitude']=$data['place_longitude'];		
			$fields['place_zoom']=$data['place_zoom'];		
			$fields['place_tel']=$data['place_tel'];
			$fields['place_fax']=$data['place_fax'];
			$fields['place_email']=$data['place_email'];
			$fields['place_website']=$data['place_website'];
			$fields['place_facebook']=$data['place_facebook'];
			$fields['place_line']=$data['place_line'];
			$fields['create_by']='Admin';
			$process=$db->add_data('place',$fields,"");
			if($process==1){
				$db->commit(); //------------------------------------------------------
				$arr_print['status']='success';
				$arr_print['msg']=iconv("tis-620","utf-8","บันทึกข้อมูลเรียบร้อยแล้ว"); 
			}else{
				$db->rollback();
				$arr_print['status']='error';
				$arr_print['msg']=iconv("tis-620","utf-8","บันทึกข้อมูลไม่สำเร็จ Error : ".$process ); 
			}
			print json_encode($arr_print);
		
	}
	
	if($data['proc']=='edit'){
		
		$db->beginTransaction();
			$fields['place_marker']=$data['place_marker'];
			$fields['place_type']=$data['place_type'];
			$fields['place_name']=$data['place_name'];
			$fields['place_address']=$data['place_address'];		
			$fields['place_tumbon']=$data['place_tumbon'];		
			$fields['place_amphur']=$data['place_amphur'];		
			$fields['place_province']=$data['place_province'];	
			$fields['place_zipcode']=$data['place_zipcode'];
			$fields['place_latitude']=$data['place_latitude'];
			$fields['place_longitude']=$data['place_longitude'];
			$fields['place_zoom']=$data['place_zoom'];			
			$fields['place_tel']=$data['place_tel'];
			$fields['place_fax']=$data['place_fax'];
			$fields['place_email']=$data['place_email'];
			$fields['place_website']=$data['place_website'];
			$fields['place_facebook']=$data['place_facebook'];
			$fields['place_line']=$data['place_line'];
			$fields['update_by']='Admin';
			$fields['update_datetime']=date('Y-m-d H:i:s');
			$process=$db->update_data('place',$fields,"","WHERE place_id='".$data['place_id']."' ");
			if($process==1){
				$db->commit(); //------------------------------------------------------
				$arr_print['status']='success';
				$arr_print['msg']=iconv("tis-620","utf-8","แก้ไขข้อมูลเรียบร้อยแล้ว"); 
			}else{
				$db->rollback();
				$arr_print['status']='error';
				$arr_print['msg']=iconv("tis-620","utf-8","แก้ไขข้อมูลไม่สำเร็จ Error : ".$process ); 
			}
			print json_encode($arr_print);
		
	}
	
	
	if($data['proc']=='delete'){
		
		$db->beginTransaction();
			$fields['place_status']=0;
			$fields['update_by']='Admin';
			$fields['update_datetime']=date('Y-m-d H:i:s');
			$process=$db->update_data('place',$fields,"","WHERE place_id='".$data['place_id']."' ");
			if($process==1){
				$db->commit(); //------------------------------------------------------
				$arr_print['status']='success';
				$arr_print['msg']=iconv("tis-620","utf-8","ลบข้อมูลเรียบร้อยแล้ว"); 
			}else{
				$db->rollback();
				$arr_print['status']='error';
				$arr_print['msg']=iconv("tis-620","utf-8","ลบข้อมูลไม่สำเร็จ Error : ".$process ); 
			}
			print json_encode($arr_print);
		
	}
	
	
	
		
	
	
	
?>

