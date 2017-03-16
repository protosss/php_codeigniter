// JavaScript Document for CI Theme 2016-12-09

$(function(){
	//เมื่อกดปุ่ม Back
   $(window).on("popstate", function(e) { 
      var url=e.originalEvent.state;
	  if(url !=null && url !="" && url !=undefined){
		  console.log("Back: "+url);
		  $("ul#side-menu li").removeClass("active"); 
		  $("a[href='"+url+"']").parent().addClass("active");
		  loadview(url);
	  }
   });

	//เมื่อมีการกด links menu
   $('body').on('click','.links', function(e) {
       e.preventDefault();
       var url=$(this).attr('href');
	   //console.log(url);
	   if(url !='#' && url !=undefined){
		   //var title=$(this).html();
		   //$("title").html(title);
		   window.history.pushState(url, null, url); //เปลี่ยน url 
		   loadview(url); //Load content
	   }
   });

});

//*********************************************************************************************************************************//
function loadview(url){
		$.ajax({
			  type: "GET",
			  url: url,
			  //dataType: "json",
			  cache:false,
			  data: {}, // serializes the form's elements.
			  beforeSend: function(data){	
					createCookie('notheme','1', 1); //สั่งห้ามแสดง Theme (เพราะโหลด Ajax มาแสดงใน #show_detail)
					block(); // #show_detail
			  },
			  success: function(data){
					deleteCookie('notheme'); //ยกเลิกคำสั่งห้ามแสดง Theme
				 	unblock();
					$('#show_detail').html(data);
				 //console.clear();
			  },
			  error: function(data, errorThrown){
				  deleteCookie('notheme'); 
				  unblock();
				/*console.log(data);
				 if(data.status=="404")
				 {
					 console.log("Error !!! 404 555555555");
				}*/
				   //Print Error Codeigniter 
				   	var x=data.responseText.toString();
				   	var l=x.length;
				   	var y=x.indexOf('<body>');
				   	var s=x.slice(y,l);
				   	var error_msg='<h1><strong class="text-danger">Error : '+data.status+' '+data.statusText+'</strong></h1>'+s;
				   	$('#show_detail').html(error_msg);
					$("html,body").animate({ scrollTop: 0 }, 100);
			  },
			  statusCode: {
					0: function() {
						deleteCookie('notheme'); 
				  		unblock();
						alert("Error กรุณาตรวจสอบการเชื่อมต่ออินเทอร์เน็ต "); 
					}
				}			  
		});
}

//*********************************************************************************************************************************//

function block(id,msg){
	msg = (msg !=undefined) ? msg : 'Loading...';
	var z_index99='z_index99';
	if(id=='body' || id=='' || id==undefined){ //ถ้าไม่ได้ใส่ชื่อ ID ให้แสดง block คลุมทั้งหน้า  
		id='body';
		z_index99='';
	}
	$(id).children('#div_loading').remove();
	$(id).prepend('<div id="div_loading" class="preload '+z_index99+' "><div align="center"> <div class="loader"></div> <div class="loader_msg">'+msg+'</div>  </div></div>');	
}
function unblock(id){
	if(id=='body' || id=='' || id==undefined){
		id='body';
	}
	$(id).children('#div_loading').remove();	
}

//*********************************************************************************************************************************//

window.alert = function(message,alert_type){
	console.log( $('.sweet-alert').hasClass('showSweetAlert') +": sweet-alert ");
	
	alert_type = (alert_type !=undefined) ? alert_type : 'warning';		
	if($('.sweet-alert').hasClass('showSweetAlert')==true){ //ถ้า SweetAlert เปิดอยู่ ให้ Delay 
		setTimeout(function(){
			swal({
			  title: 'แจ้งเตือน',
			  text: message,
			  type: alert_type,
			  showCancelButton: false,
			  confirmButtonText: "ตกลง"
			});
		},150);
	}else{	
		swal({
			  title: 'แจ้งเตือน',
			  text: message,
			  type: alert_type,
			  showCancelButton: false,
			  confirmButtonText: "ตกลง"
		});
	}
}

window.confirm = function (message, callback) {
	swal({
			  title: "ยืนยัน",
			  text: message, // "กรุณายืนยันอีกครั้ง",
			  type: "warning", 
			  confirmButtonText: 'ยืนยัน',
			  cancelButtonText: 'ยกเลิก',
			  showCancelButton: true,
			  showLoaderOnConfirm: true
		 }, function (isConfirm) {		 	  
			  callback(isConfirm);
		 });
}


//*********************************************************************************************************************************//
function AutocompleteReturn2Values(url,idKeyUp,idKeyShow,fieldKeyUp,fieldShow,idShowStatus){
	
		
		if(url.indexOf(".php?")==-1){
			url += "?fieldKeyUp="+fieldKeyUp+"&fieldShow="+fieldShow
		}else{
			url += "&fieldKeyUp="+fieldKeyUp+"&fieldShow="+fieldShow
		}
		$( "#"+idKeyUp ).autocomplete({
	  	minLength:0,
		delay:0,
		search:function(e,u){
			
			$( "#"+idKeyUp ).autocomplete({ 		
				source: url
			});
		},
      	select: function( event, ui ) {
        	$( "#"+idKeyUp ).val( ui.item.label );
        	$( "#"+idKeyShow ).val( ui.item.value );
			
        	return false;
      	},
	  	change : function(event,ui){
				if(!ui.item){
					$( "#"+idKeyUp ).val("");
        			$( "#"+idKeyShow ).val("");

				}
		}
    })
	.data( "ui-autocomplete" )._renderItem = function( ul, item ) 
       {
		if(idShowStatus == true){
						return $( "<li>" )
						.append( "<div>" + item.label + " :: " + item.value + "</div>" )
						.appendTo( ul );
					}else{
						 return $( "<li>" )
						.append( "<div>" + item.label + "</div>" )
						.appendTo( ul );
				}
       };
	 $( "#"+idKeyUp ).click(function(){ $( "#"+idKeyUp ).autocomplete('search'); });

	}// END AutocompleteReturn2Values
	
//*********************************************************************************************************************************//	
function AutoCompleteAjax(url,objAuto){
		var fieldShow = ""
		if(objAuto.elementOther.length>0){
			$.each(objAuto.elementOther,function(){
				fieldShow += "&fShow[]="+this.fieldName;
			});
		}
		if(url.indexOf(".php?")==-1){
			url += "?fKey="+objAuto.elementKeyUp.fieldName+fieldShow
		}else{
			url += "&fKey="+objAuto.elementKeyUp.fieldName+fieldShow
		}
		$( "#"+objAuto.elementKeyUp.elementId).autocomplete({
				minLength:0,
				delay:0,
				search:function(e,u){
					$('#'+objAuto.elementKeyUp.elementId).autocomplete({ 		
						source: url
					});
				},
				select: function( event, ui ) {
									if(objAuto.elementOther.length>0){
										$.each(objAuto.elementOther,function(i){
											$("#"+this.elementId).val($.trim(html_entity_decode(ui.item.fShow[i])));
										});
									}
							},
				change : function(event,ui){
								if(!ui.item){
										$(this).val("");
										if(objAuto.elementOther.length>0){
											$.each(objAuto.elementOther,function(i){
												$("#"+this.elementId).val("");
											});
										}
								}
							}
			}).data( "ui-autocomplete" )._renderItem = function( ul, item ) {
					if(objAuto.elementOther.length>0){
						var fShow = "";
						$.each(objAuto.elementOther,function(i){
							if(this.showDetail==true){
								fShow += " :: "+item.fShow[i];
							}
						});
						/*return $( "<li></li>" )
						.data( "item.autocomplete", item )
						.append( "<a>" + item.fKey + fShow + "</a>" )
						.appendTo( ul );*/
						
						return $( "<li>" )
						.append( "<div>" + item.fKey + fShow + "</div>" )
						.appendTo( ul );
					}else{
						/*return $( "<li></li>" )
						.data( "item.autocomplete", item )
						.append( "<a>" + item.fieldKeyUp+"</a>" )
						.appendTo( ul );*/
						
						return $( "<li>" )
						.append( "<div>" + item.fieldKeyUp + "</div>" )
						.appendTo( ul );
						
						
					}
			};	
			$("#"+objAuto.elementKeyUp.elementId).click(function(){ $("#"+objAuto.elementKeyUp.elementId).autocomplete('search'); });
	}



//*********************************************************************************************************************************//	
function html_entity_decode(string, quote_style) {
		 var hash_map = {},symbol = '',tmp_str = '',entity = '';
		  tmp_str = string.toString();
		  if (false === (hash_map = this.get_html_translation_table('HTML_ENTITIES', quote_style))) {
			return false;
		  }
		  delete(hash_map['&']);
		  hash_map['&'] = '&amp;';
		  for (symbol in hash_map) {
			entity = hash_map[symbol];
			tmp_str = tmp_str.split(entity).join(symbol);
		  }
		  tmp_str = tmp_str.split('&#039;').join("'");
		  return tmp_str;
	}
//*********************************************************************************************************************************//		
function get_html_translation_table (table, quote_style) {
	  // http://kevin.vanzonneveld.net
	  // +   original by: Philip Peterson
	  // +    revised by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
	  // +   bugfixed by: noname
	  // +   bugfixed by: Alex
	  // +   bugfixed by: Marco
	  // +   bugfixed by: madipta
	  // +   improved by: KELAN
	  // +   improved by: Brett Zamir (http://brett-zamir.me)
	  // +   bugfixed by: Brett Zamir (http://brett-zamir.me)
	  // +      input by: Frank Forte
	  // +   bugfixed by: T.Wild
	  // +      input by: Ratheous
	  // %          note: It has been decided that we're not going to add global
	  // %          note: dependencies to php.js, meaning the constants are not
	  // %          note: real constants, but strings instead. Integers are also supported if someone
	  // %          note: chooses to create the constants themselves.
	  // *     example 1: get_html_translation_table('HTML_SPECIALCHARS');
	  // *     returns 1: {'"': '&quot;', '&': '&amp;', '<': '&lt;', '>': '&gt;'}
	  var entities = {},
		hash_map = {},
		decimal;
	  var constMappingTable = {},
		constMappingQuoteStyle = {};
	  var useTable = {},
		useQuoteStyle = {};
	
	  // Translate arguments
	  constMappingTable[0] = 'HTML_SPECIALCHARS';
	  constMappingTable[1] = 'HTML_ENTITIES';
	  constMappingQuoteStyle[0] = 'ENT_NOQUOTES';
	  constMappingQuoteStyle[2] = 'ENT_COMPAT';
	  constMappingQuoteStyle[3] = 'ENT_QUOTES';
	
	  useTable = !isNaN(table) ? constMappingTable[table] : table ? table.toUpperCase() : 'HTML_SPECIALCHARS';
	  useQuoteStyle = !isNaN(quote_style) ? constMappingQuoteStyle[quote_style] : quote_style ? quote_style.toUpperCase() : 'ENT_COMPAT';
	
	  if (useTable !== 'HTML_SPECIALCHARS' && useTable !== 'HTML_ENTITIES') {
		throw new Error("Table: " + useTable + ' not supported');
		// return false;
	  }
	
	  entities['38'] = '&amp;';
	  if (useTable === 'HTML_ENTITIES') {
		entities['160'] = '&nbsp;';
		entities['161'] = '&iexcl;';
		entities['162'] = '&cent;';
		entities['163'] = '&pound;';
		entities['164'] = '&curren;';
		entities['165'] = '&yen;';
		entities['166'] = '&brvbar;';
		entities['167'] = '&sect;';
		entities['168'] = '&uml;';
		entities['169'] = '&copy;';
		entities['170'] = '&ordf;';
		entities['171'] = '&laquo;';
		entities['172'] = '&not;';
		entities['173'] = '&shy;';
		entities['174'] = '&reg;';
		entities['175'] = '&macr;';
		entities['176'] = '&deg;';
		entities['177'] = '&plusmn;';
		entities['178'] = '&sup2;';
		entities['179'] = '&sup3;';
		entities['180'] = '&acute;';
		entities['181'] = '&micro;';
		entities['182'] = '&para;';
		entities['183'] = '&middot;';
		entities['184'] = '&cedil;';
		entities['185'] = '&sup1;';
		entities['186'] = '&ordm;';
		entities['187'] = '&raquo;';
		entities['188'] = '&frac14;';
		entities['189'] = '&frac12;';
		entities['190'] = '&frac34;';
		entities['191'] = '&iquest;';
		entities['192'] = '&Agrave;';
		entities['193'] = '&Aacute;';
		entities['194'] = '&Acirc;';
		entities['195'] = '&Atilde;';
		entities['196'] = '&Auml;';
		entities['197'] = '&Aring;';
		entities['198'] = '&AElig;';
		entities['199'] = '&Ccedil;';
		entities['200'] = '&Egrave;';
		entities['201'] = '&Eacute;';
		entities['202'] = '&Ecirc;';
		entities['203'] = '&Euml;';
		entities['204'] = '&Igrave;';
		entities['205'] = '&Iacute;';
		entities['206'] = '&Icirc;';
		entities['207'] = '&Iuml;';
		entities['208'] = '&ETH;';
		entities['209'] = '&Ntilde;';
		entities['210'] = '&Ograve;';
		entities['211'] = '&Oacute;';
		entities['212'] = '&Ocirc;';
		entities['213'] = '&Otilde;';
		entities['214'] = '&Ouml;';
		entities['215'] = '&times;';
		entities['216'] = '&Oslash;';
		entities['217'] = '&Ugrave;';
		entities['218'] = '&Uacute;';
		entities['219'] = '&Ucirc;';
		entities['220'] = '&Uuml;';
		entities['221'] = '&Yacute;';
		entities['222'] = '&THORN;';
		entities['223'] = '&szlig;';
		entities['224'] = '&agrave;';
		entities['225'] = '&aacute;';
		entities['226'] = '&acirc;';
		entities['227'] = '&atilde;';
		entities['228'] = '&auml;';
		entities['229'] = '&aring;';
		entities['230'] = '&aelig;';
		entities['231'] = '&ccedil;';
		entities['232'] = '&egrave;';
		entities['233'] = '&eacute;';
		entities['234'] = '&ecirc;';
		entities['235'] = '&euml;';
		entities['236'] = '&igrave;';
		entities['237'] = '&iacute;';
		entities['238'] = '&icirc;';
		entities['239'] = '&iuml;';
		entities['240'] = '&eth;';
		entities['241'] = '&ntilde;';
		entities['242'] = '&ograve;';
		entities['243'] = '&oacute;';
		entities['244'] = '&ocirc;';
		entities['245'] = '&otilde;';
		entities['246'] = '&ouml;';
		entities['247'] = '&divide;';
		entities['248'] = '&oslash;';
		entities['249'] = '&ugrave;';
		entities['250'] = '&uacute;';
		entities['251'] = '&ucirc;';
		entities['252'] = '&uuml;';
		entities['253'] = '&yacute;';
		entities['254'] = '&thorn;';
		entities['255'] = '&yuml;';
	  }
	
	  if (useQuoteStyle !== 'ENT_NOQUOTES') {
		entities['34'] = '&quot;';
	  }
	  if (useQuoteStyle === 'ENT_QUOTES') {
		entities['39'] = '&#39;';
	  }
	  entities['60'] = '&lt;';
	  entities['62'] = '&gt;';
	
	
	  // ascii decimals to real symbols
	  for (decimal in entities) {
		if (entities.hasOwnProperty(decimal)) {
		  hash_map[String.fromCharCode(decimal)] = entities[decimal];
		}
	  }
	
	  return hash_map;
	}
//*********************************************************************************************************************************//		
function ajaxPopup(url,size,title){
	block();
	jQuery.ajax({
                type: 'GET',
                url: url,
                success: function (data) {
					unblock();
                    bootbox.dialog({
                        message: data,
						//size: size,
                        title:title,
                        className: "modal-blue"
                    });
					$(".modal-dialog").css("width",size);
                }
            });
	}
	
//*********************************************************************************************************************************//		
function notifys(message, type, align, valign, second){
	//  http://bootstrap-notify.remabledesigns.com/  	
	if(type==undefined || type==''){ type='warning'; }
	if(second==undefined || second==''){ second=3000; } //3 วินาที
	if(align==undefined || align==''){ align='right'; }
	if(valign==undefined || valign==''){ valign='top'; }
	$.notify({
		// options
		icon: 'glyphicon glyphicon-alert',
		message: message,
	},{
		// settings
		type: type,
		delay: second,
		allow_dismiss: true, //โชว์ปุ่ม Close
		mouse_over: 'pause', //เมื่อเมาส์ Hover ไม่ให้ซ่อน 	
		placement: {
			from: valign,
			align: align
		},
	});
}


//*********************************************************************************************************************************//		
//File Input 
function readURL(input,i) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();            
            reader.onload = function (e) {
                $("img.file_preview[data-id='"+i+"']").attr('src', e.target.result);
				//console.log(e.target.result);
            }			            
            reader.readAsDataURL(input.files[0]);
        }
    }

$(function(){
    
    $(".input_file").change(function(){
      	var i=$(this).data('id');
	  	console.log(i);
        readURL(this,i);
    });

});
//*********************************************************************************************************************************//


