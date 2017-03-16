<?php
/*	session_start(); */
	@header("Content-Type:text/plain;Charset=TIS-620");
/*	$path = "../";   	
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
	
	$proc=($_GET['proc']=='edit') ? $proc : 'add';*/
	
	$icon_base='http://maps.google.com/mapfiles/ms/micons/';
	$place_marker=image_to_base64($icon_base.'red-dot.png');
/*	if($proc=='edit'){
		$sql="SELECT place_id	
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
								,place_status
				FROM place 
				WHERE place_status<>0
							AND place_id='".$_GET['place_id']."'
		";
		$rec=$db->get_data_rec($sql);
		$place_marker=$rec['place_marker'].$rec['place_marker2'];		
	}*/
	
	if($conv_img=='image_to_base64'){
		echo $place_marker=image_to_base64($file_img);
		$conv_img = '';
		exit;
	}
?>
<style>
.dropdown-menu{
	padding:5px;
}
#tbl_marker tr td{
	padding:5px;
	cursor:pointer;
}
#tbl_marker tr td:hover, #tbl_marker tr td.active{
	background-color:#F7EA92;	
}

</style>

<script>
var icon_base = "<?php echo $icon_base; ?>";
$(function(){	
	$('.marker').on('click',function(e){
		block();
		var icon_name=$(this).attr('data-name');
		//console.log(icon_urlname+' | '+icon_name);
		$('img#show_marker').attr('src',icon_name);
		//Ajax Image to base64 in input 
		//var url='place/place_proc.php';
		var url='<?php echo site_url('place/convert_img')?>'
		$.post(url,{ image_url: icon_name },function(data){
			console.log(data);
			$('#place_marker').val(data);
			window.frames['iframe_map'].change_marker( icon_name );
			unblock();
		});
		
	});
	
	//-----------------------------------------------------------------------
	
	$('#zoomin').on('click',function(e){
		window.frames['iframe_map'].change_zoomin();
	});
	
	$('#zoomout').on('click',function(e){
		window.frames['iframe_map'].change_zoomout();
	});
	
	//----------------------------------------------------------------------------------------------------------------------------
	$("#frm_place_update").ajaxForm({
				type:'POST',
				dataType:'html',
				cache:false,
				beforeSend:function(){
				//blockUI();
			},
				success:function(data){
					if(data){
						//$("#err_upload").html("[Upload File ERROR !!]"+data);
						alert(data,'error');
						return false;
					}
					bootbox.hideAll();
					alert('บันทึกเรียบร้อย','success');
					//unblockUI();
					loadview('<?php echo site_url('place/index');?>')
				}//end success
		})//end submit ajaxForm
})

function get_amphur(obj)
{
	//console.log('xxx');
	var value = $(obj).val();
	$.get( "<?php echo site_url('frontend/get_amphur')?>/"+value, function( data ) 
	{ $("#amphur").html(data); });
}

function get_tumbon(obj)
{
	var value = $(obj).val();
	$.get( "<?php echo site_url('frontend/get_tumbon')?>/"+value, function( data ) 
	{ $("#tumbon").html(data); });
}
</script>

<?php
echo form_open('place/update_data', array('id' => 'frm_place_update'));
?>
 <div class="row">
          <div class="col-lg-12">
              <h1 class="page-header font-thsan font-size-30"> <strong>สถานที่</strong>  </h1>
          </div>        
      </div>
      
            
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                เพิ่มข้อมูลสถานที่
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">
                
                <div class="col-lg-7" style="padding-left:0px; padding-right:0px; ">
                	<!--<iframe id="iframe_map" name="iframe_map" src="place/map.php?proc=<?php echo $proc;?>&place_id=<?php echo $rec['place_id'];?>" frameborder="0" scrolling="no" style="display:block; width:100%; height:810px;  " ></iframe>-->
                    <iframe id="iframe_map" name="iframe_map" src="<?php echo site_url('place/show_map/add/1');?>" frameborder="0" scrolling="no" style="display:block; width:100%; height:510px;  " ></iframe>
                    <br>
                </div>
                
                <div class="col-lg-5">
                
                <div class="form-group">
                    <label class="col-xm-12 col-lg-3 control-label nopadding_right text-right" for="">ไอคอน </label>
                    <div class="col-xm-12 col-lg-9">
                    	                        
                        <div class="dropdown">
                          <button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                            <img id="show_marker" src="<?php echo $place_marker.$place_marker2;?>">
                            <span class="caret"></span>
                          </button>
                          <ul class="dropdown-menu col-lg-12" aria-labelledby="dropdownMenu1" >
                            <li>
                            <div class="table-responsive table-list-marker">
                            	<table width="100%" id="tbl_marker">
                                <tr>
                                <?php
																		
									$marker=array('red-dot','red','blue-dot','blue','green-dot','green','yellow-dot','yellow','ltblue-dot','lightblue','orange-dot','orange','pink-dot','pink','purple-dot','purple','ylw-pushpin','blue-pushpin','grn-pushpin','ltblu-pushpin','pink-pushpin','purple-pushpin','red-pushpin'           ,'water','waterfalls','webcam','man','woman','wheel_chair_accessible','yen','arts','bar','bus','cabs','camera','campfire','campground','caution','coffeehouse','convienancestore','cycling','dollar','drinking_water','earthquake','electronics','euro','fallingrocks','ferry','firedept','fishing','flag','gas','golfer','groecerystore','helicopter','hiker','homegardenbusiness','horsebackriding','hospitals','hotsprings','info','info_circle','landmarks-jp','lodging','marina','mechanic','motorcycling','movies','parkinglot','partly_cloudy','pharmacy-us','phone','picnic','plane','POI','police','postoffice-jp','postoffice-us','question','rail','rainy','rangerstation','realestate','recycle','restaurant', 'sailing','salon','shopping','ski','snack_bar','snowflake_simple','sportvenue','subway','sunny','swimming','toilets','trail','tram','tree','truck','volcano'  );
									$i=0;
									$cc=7; // จำนวนคอลัมน์ 
									$rr=0;
									$tt=$cc;
									while($i<count($marker)){
										$rr++;
										$tt--;
								?>                                	
                                    	<td align="center" class="marker" data-name="<?php echo $icon_base.$marker[$i].'.png';?>"><img src="<?php echo $icon_base.$marker[$i].'.png';?>" /></td>
                                    	<?php
											if ($tt==0){ //ถ้า $tt วนลูบจนเท่ากับ $cc ให้โชว์ </tr> คือปิดแถว 
												$tt=$cc;
												echo "</tr>";
											} //if 
										?>
                                    <?php
										$i++;
									}
									?>
                                    </tr>
                                </table>
                                </div>
                            </li>
                          </ul>
                        </div>                        
                        
                        <input type="hidden" id="place_marker" name="place_marker" value="<?php echo $place_marker;?>">
                        
                        
                    </div>
                  </div>
                  
                   <div class="form-group">
                    <label class="col-xm-12 col-lg-3 control-label nopadding_right text-right" for="">ประเภท</label>
                    <div class="col-xm-12 col-lg-9">
                    	 <select name="place_type" class="form-control" id="place_type">
                         <option value="">กรุณาเลือกประเภท</option>
                        <?php echo $place_type_select; ?>
                         </select>
                    </div>                      
                  </div>
                
                  <div class="form-group">
                    <label class="col-xm-12 col-lg-3 control-label nopadding_right text-right" for="">ชื่อสถานที่</label>
                    <div class="col-xm-12 col-lg-9">
                    	<input name="place_id" type="hidden" value="<?php echo trim($place_id);?>">
                    	<input name="place_name" type="text" required="required" class="form-control" id="place_name" value="<?php echo $place_name;?>">
                    </div>                      
                  </div>
                  
                  <div class="form-group">                      
                    <label class="col-xm-12 col-lg-3 control-label nopadding_right text-right  text-nowrap" for="">ละติจูด, ลองติจูด</label>
                    <div class="col-xm-12 col-lg-9">
                    	  <input name="lat_lon" type="text" class="form-control" id="lat_lon" value="<?php echo trim($place_latitude);?>, <?php echo trim($place_longitude);?>" readonly>
                          <input name="place_latitude" type="hidden" class="form-control" id="place_latitude" value="<?php echo trim($place_latitude);?>">
                          <input name="place_longitude" type="hidden" class="form-control" id="place_longitude" value="<?php echo trim($place_longitude);?>">
                    </div> 
                  </div>     
                  
                  <div class="form-group">                      
                    <label class="col-xm-12 col-lg-3 control-label nopadding_right text-right  text-nowrap" for="">ซูม</label>
                    <div class="col-xm-12 col-lg-9">
                    
                            <div class="input-group">
                                  <input name="place_zoom" type="text" class="form-control" id="place_zoom" value="<?php echo trim($place_zoom);?>" readonly>
                                  <span class="input-group-addon" style="padding-top:0px; padding-bottom:0px;">
                                  	<button id="zoomin" type="button" class="btn btn-primary" style="padding:1px 10px; text-align:center;" title="Zoom In"><span class="glyphicon glyphicon-plus"></span></button> 
                                    <button id="zoomout" type="button" class="btn btn-primary" style="padding:1px 10px; text-align:center;" title="Zoom Out"><span class="glyphicon glyphicon-minus"></span></button>  
                                  </span>
                              </div>
                          
                    </div> 
                  </div>                  
                  

                           
                  
                  <div class="form-group">                      
                    <label class="col-xm-12 col-lg-3 control-label nopadding_right text-right" for="">ที่อยู่</label>
                    <div class="col-xm-12 col-lg-9">
                    	  <textarea name="place_address" class="form-control" id="place_address"><?php echo $place_address;?></textarea>
                    </div> 
                  </div>
                  
                  <div class="form-group">                      
                    <label class="col-xm-12 col-lg-3 control-label nopadding_right text-right" for="">จังหวัด</label>
                    <div class="col-xm-12 col-lg-9">
                   	  <select name="place_province" class="form-control" onchange="get_amphur(this);" id="province">
                         <option value="">กรุณาเลือกจังหวัด</option>
                        <?php echo $province_select; ?>
                         </select>
                    </div> 
                  </div>
                    
                   <div class="form-group">                      
                    <label class="col-xm-12 col-lg-3 control-label nopadding_right text-right" for="">อำเภอ/เขต</label>
                    <div class="col-xm-12 col-lg-9">
                    	  <select name="place_amphur" class="form-control" id="amphur" onchange="get_tumbon(this);">
                         	<option value="">กรุณาเลือกอำเภอ/เขต</option>
                            <?php echo $amphur_select; ?>
                         </select>
                    </div> 
                  </div>
                    
                  <div class="form-group">                      
                    <label class="col-xm-12 col-lg-3 control-label nopadding_right text-right" for="">ตำบล/แขวง</label>
                    <div class="col-xm-12 col-lg-9">
                    	 <select name="place_tumbon" class="form-control" id="tumbon">
							<option value="">กรุณาเลือกตำบล/แขวง</option>
                            <?php echo $tumbon_select; ?>
                         </select>
                    </div> 
                  </div>
                    
                  <div class="form-group">                      
                    <label class="col-xm-12 col-lg-3 control-label nopadding_right text-right" for="">รหัสไปรษณีย์</label>
                    <div class="col-xm-12 col-lg-9">
                    	 <input name="place_zipcode" type="text" class="form-control" id="place_zipcode" maxlength="5" value="<?php echo trim($place_zipcode);?>">
                    </div> 
                  </div>
                    
                   <div class="form-group">                      
                    <label class="col-xm-12 col-lg-3 control-label nopadding_right text-right" for="">เบอร์โทร</label>
                    <div class="col-xm-12 col-lg-9">
                    	 <input id="place_tel" name="place_tel" type="text" class="form-control"  value="<?php echo trim($place_tel);?>">
                    </div> 
                  </div>
                    
                  <div class="form-group">                      
                    <label class="col-xm-12 col-lg-3 control-label nopadding_right text-right" for="">แฟกซ์</label>
                    <div class="col-xm-12 col-lg-9">
                    	<input id="place_fax" name="place_fax" type="text" class="form-control" value="<?php echo trim($place_fax);?>">
                    </div> 
                  </div>
                    
                  <div class="form-group">                      
                    <label class="col-xm-12 col-lg-3 control-label nopadding_right text-right" for="">อีเมล์</label>
                    <div class="col-xm-12 col-lg-9">
                    	<input id="place_email" name="place_email" type="text" class="form-control" value="<?php echo trim($place_email);?>">
                    </div> 
                  </div>
                    
                  <div class="form-group">                      
                    <label class="col-xm-12 col-lg-3 control-label nopadding_right text-right" for="">WebSite</label>
                    <div class="col-xm-12 col-lg-9">
                    	 <input id="place_website" name="place_website" type="text" class="form-control"  value="<?php echo trim($place_website);?>">
                    </div> 
                  </div>
                    
                  <div class="form-group">                      
                    <label class="col-xm-12 col-lg-3 control-label nopadding_right text-right" for="">Facebook</label>
                    <div class="col-xm-12 col-lg-9">
                    	 <input id="place_facebook" name="place_facebook" type="text" class="form-control"  value="<?php echo trim($place_facebook);?>">
                    </div> 
                  </div>
                    
                  <div class="form-group">                      
                    <label class="col-xm-12 col-lg-3 control-label nopadding_right text-right" for="">Line</label>
                    <div class="col-xm-12 col-lg-9">
                    	 <input id="place_line" name="place_line" type="text" class="form-control"  value="<?php echo trim($place_line);?>">
                    </div> 
                  </div>
                    
                 
                
              </div>
                
                <div class="col-lg-12" align="center">
                
                	<button id="btnSave" type="submit" class="btn btn-primary"><span class="glyphicon glyphicon-floppy-disk"> </span> บันทึก</button> &nbsp; 
                    <button id="btnCancel" type="button" class="btn btn btn-danger"><span class="glyphicon glyphicon-remove"></span> ยกเลิก</button>
                
                </div>
              
            </div>
            <!-- /.panel-body -->
        </div>
        <!-- /.panel -->
        
        <br>
        
        
    </div>

		

</div>
<input type="hidden" name="proc" value="update">

<?php
	echo form_close();
?>