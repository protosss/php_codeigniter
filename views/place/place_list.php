 <?php 
$ci =& get_instance(); 
?>
<script>
	$(function(){		
			$('#dataTables').DataTable({
				responsive: true
			});
			////////////////// Search
			//$('#dataTables tbody').on( 'click', '.btn_search', function () {
			$("#btn_search").click(function(e) {
					$.ajax({
						url:'<?php echo site_url();?>/place/page_split',
						type:'POST',
						cache:false,
						data: $("#form_search").serialize(), 
						success:function(data){
							loadview('<?php echo site_url('place/page_split');?>')
						}
					})
			})
			/////////////////// Add Data
			$("#btn_add").click(function(e) {
				//alert();
				ajaxPopup('<?php echo site_url('place/add_place/add/1');?>','1200px','บันทึกข้อมูลสถานที่');
				//loadview('<?php echo site_url('place/add_place');?>')
			});
			/////////////////// Update Data
			$('.btn_edit').click(function(e) {
				var id = $(this).attr('data-place_id');
				ajaxPopup('<?php echo site_url();?>/place/update_place/'+id,'1200px','บันทึกข้อมูลสถานที่');
			});
			/////////////////// Del Data
			$(".btn_del").on("click",function(){
				var id = $(this).attr('data-place_id');
				
				swal({
								title: "ยืนยัน",
								text: "กรุณายืนยันอีกครั้ง",
								type: "info", //info warning success
								confirmButtonText: 'ยืนยัน',
								cancelButtonText: 'ยกเลิก',
								showCancelButton: true,
								showLoaderOnConfirm: true
							}, function (isConfirm) {
								 if (isConfirm) {
										$.ajax({
											url:'<?php echo site_url();?>/place/delete_place/'+id,
											type:'POST',
											cache:false,
											success:function(data){
												loadview('<?php echo site_url('place/index');?>')
											}
									})
								  } 
								  else 
								 {
									console.log('Cancel');
								  }			  	
						});
		
			})
	})
</script>
    
    

	<div class="row">
          <div class="col-lg-12">
              <h1 class="page-header font-thsan font-size-30"> <strong>สถานที่</strong>  </h1>
          </div>        
      </div>
      
      
      <div class="row" style="margin-top:-3px; margin-bottom:3px; ">
      	<form class=" form-horizontal" id="form_search" name="form_search">
      
            <div class="col-lg-10">            
            	<div class="form-group">
                        <label class="col-xm-12 col-lg-2 control-label nopadding_right text-right" for="">ค้นหาชื่อสถานที่</label>
                        <div class="col-xm-12 col-lg-5">
                            <input name="s_name" required="required" class="form-control" id="s_name" value="<?php echo $s_name;?>" type="text">
                        </div> 
                        <label class="col-xm-12 col-lg-1 control-label nopadding_right text-right hide" for="">จังหวัด</label>
                        <div class="col-xm-12 col-lg-2 hide">
                            <select name="place_province" class="form-control" id="place_province">
                                 <option value="">เลือกจังหวัด</option>
                                <?php //echo $disp->ddw_list_selected("SELECT PROVINCE_ID, PROVINCE_NAME FROM thai_province", "PROVINCE_NAME", "PROVINCE_ID", $rec['place_province'] ); ?>
                             </select>
                        </div> 
                        
                        <div class="col-xm-12 col-lg-3" style="padding-left:0px;">
                            <button id="btn_search" type="submit" class="btn btn-primary"><span class="fa fa-search "></span> ค้นหา</button>
                            <button id="btn_search_cancel" type="button" class="btn btn-danger"><span class="fa fa-times "></span>  ยกเลิก</button>
                        </div>                      	
                  </div> 
                       
            </div>
            <div class="col-lg-2" align="right">
                <button id="btn_add" type="button" class="btn btn-success"><span class="glyphicon glyphicon-plus"></span> เพิ่มสถานที่</button>
            </div>
            
            </form>  
        </div>
        

        
            
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                จัดการข้อมูลสถานที่
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">
            
                <div class="table-responsive">
                    <table width="100%" class="table table-striped table-bordered table-hover" <?php echo $id_table?>>
                        <thead>
                            <tr>
                                <th width="4%" nowrap="nowrap">ลำดับ</th>
                                <th width="6%" nowrap="nowrap">ไอคอน</th>
                                <th width="14%" nowrap="nowrap">ประเภท</th>
                                <th width="42%" nowrap="nowrap">ชื่อสถานที่</th>
                                <th width="12%" nowrap="nowrap">จังหวัด</th>
                                <th width="14%" nowrap="nowrap">ละติจูด, ลองติจูด</th>
                                <th width="8%" nowrap="nowrap">จัดการ</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php
                            $i=$start;
							//$query = $list;
                            foreach ($result as $row)
                            {
                                $i++;
                        ?>
                            <tr>
                                <td><?php echo $i;?>.</td>
                                <td align="center"><img src="<?php echo $row['place_marker'];?>"></td>
                                <td><?php echo $row['place_type_name'];?></td>
                              <td><?php echo $row['place_name'];?></td>
                                <td nowrap="nowrap"><?php echo !empty($row['province_name']) ? $row['province_name'] : '<div align="center">-</div>';?></td>
                                <td nowrap="nowrap"><?php echo $row['place_latitude'];?>, <?php echo $row['place_longitude'];?></td>
                                <td align="center" nowrap >        
                                                        
                                    <button class="btn btn-warning btn_edit" title="แก้ไข" data-place_id="<?php echo $row['place_id'];?>" data-place_latitude="<?php echo $row['place_latitude'];?>" data-place_longitude="<?php echo $row['place_longitude'];?>" data-place_zoom="<?php echo $row['place_zoom'];?>"><span class="glyphicon glyphicon-pencil"></span></a></button>                                
                                    <button class="btn btn-danger btn_del" title="ลบ" data-place_id="<?php echo $row['place_id'];?>"><span class="glyphicon glyphicon-trash"></span></a></button> 
                                    
                                </td>
                            </tr>
                            <?php
								}
							 ?>
                        </tbody>
                    </table>
					<div align="right"><?php echo $pagination;?></div>
                </div>
                <!-- /.table-responsive -->
            </div>
            <!-- /.panel-body -->
        </div>
        <!-- /.panel -->
        
        <br>
       	
    </div>

		

</div>