<?php
	//session_start();
	//@header("Content-Type:text/plain;Charset=TIS-620");
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
	
	
	$sql="SELECT place_id	
							,CAST(SUBSTRING(place_marker,1,4000) AS varchar(MAX)) AS place_marker
							,CAST(SUBSTRING(place_marker,4001,8000) AS varchar(MAX)) AS place_marker2				
							,place_type
							,place_name
							,place_latitude
							,place_longitude
							,place_zoom
			FROM place 
			WHERE place_status<>0
						AND place_id='".$_GET['place_id']."'
	";
	$rec=$db->get_data_rec($sql);
	
	
?>

<!DOCTYPE html>
<html>
  <head>
    <meta name="viewport" content="initial-scale=1.0, user-scalable=no">
    <meta charset="utf-8">
    <title>Places Searchbox</title>
    <style>
      /* Always set the map height explicitly to define the size of the div
       * element that contains the map. */
      #map {
        height: 100%;
      }
      /* Optional: Makes the sample page fill the window. */
      html, body {
        height: 100%;
        margin: 0;
        padding: 0;
      }
      .controls {
        margin-top: 10px;
        border: 1px solid transparent;
        border-radius: 2px 0 0 2px;
        box-sizing: border-box;
        -moz-box-sizing: border-box;
        height: 32px;
        outline: none;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.3);
      }

      #pac-input {
        background-color: #fff;
        font-family: Roboto;
        font-size: 15px;
        font-weight: 300;
        margin-left: 12px;
        padding: 0 11px 0 13px;
        text-overflow: ellipsis;
        width: 450px;
      }

      #pac-input:focus {
        border-color: #4d90fe;
      }

      .pac-container {
        font-family: Roboto;
      }

      #type-selector {
        color: #fff;
        background-color: #4d90fe;
        padding: 5px 11px 0px 11px;
      }

      #type-selector label {
        font-family: Roboto;
        font-size: 13px;
        font-weight: 300;
      }
      #target {
        width: 345px;
      }
    </style>
  </head>
  <body>
    <input id="pac-input" class="controls" type="text" placeholder="Search Box">
    <div id="map"></div>
    
    
    <script src="../assets/js/jquery/jquery.js"></script> 
    <script>
	var proc='<?php echo $_GET['proc'];?>';
	var my_point="";  // หาตำแหน่งของตัว marker เมื่อกดลากแล้วปล่อย 
	var p_name="";
	var p_lat;
	var p_lon;
	var p_zoom;
	//-------------------------------------------------------
	var marker;
	var map;
	var uluru;
	var icon_base = 'http://maps.google.com/mapfiles/ms/micons/';
	var icon_marker=icon_base+'red-dot.png';
	var infowindow;
	var place_name="";
	var latitude=13.710546;
	var longitude =100.521511;
	var zoom =15;
	
      // This example adds a search box to a map, using the Google Place Autocomplete
      // feature. People can enter geographical searches. The search box will return a
      // pick list containing a mix of places and predicted search terms.

      // This example requires the Places library. Include the libraries=places
      // parameter when you first load the API. For example:
      // <script src="https://maps.googleapis.com/maps/api/js?key=YOUR_API_KEY&libraries=places">
	<?php if($_GET['proc']=='edit'){?>
	latitude=<?php echo $rec['place_latitude'];?>;
	longitude =<?php echo $rec['place_longitude'];?>;
	zoom=<?php echo $rec['place_zoom']; ?>;
	icon_marker="<?php echo $rec['place_marker'].$rec['place_marker2']; ?>"; //marker default 	
	<?php }?>
	
      function initAutocomplete() {
		uluru = { lat: latitude, lng: longitude }; //Default Location 
        map = new google.maps.Map(document.getElementById('map'), {
			center: uluru,
			zoom: zoom,
			zoomControl: false, //เอกตัว zoom ออก
			mapTypeControl: false, //ประเภทแผนที่ 
			mapTypeId: 'roadmap',
			streetViewControl: false, //เอาตัวว่างถนน(รูปคน)ออก 
			streetViewControlOptions: {
				position: google.maps.ControlPosition.RIGHT_CENTER
			}
			
        });
		infowindow = new google.maps.InfoWindow();
		
		

        // Create the search box and link it to the UI element.
        var input = document.getElementById('pac-input');
        var searchBox = new google.maps.places.SearchBox(input);
        map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);


        // Bias the SearchBox results towards current map's viewport.
        map.addListener('bounds_changed', function() {
          searchBox.setBounds(map.getBounds());
        });		

        var markers = [];
		
		
		// Create a marker for each place.
		
		marker = new google.maps.Marker({
		  map: map,
		  icon: icon_marker,
		  shadow: icon_marker,
		  //title: place.name,
		  position: uluru,
		  draggable: true //ทำให้หมุดเลื่อนได้
		});
		markers.push(marker);
		
		//-- Get data เมื่อเข้ามาครั้งแรก -------------------------------------------------------------------------------
		my_point = marker.getPosition();  // หาตำแหน่งของตัว marker เมื่อกดลากแล้วปล่อย 
		p_lat= my_point.lat();
		p_lon=my_point.lng();
		p_zoom=map.getZoom();
		//--ใส่ข้อมูลใน Input --------------------------------------------------------------------------------------------
		if(proc !='edit'){
			fnc_value_latlon(p_lat,p_lon);
		}
		fnc_value_zoom(p_zoom);
		
        // Listen for the event fired when the user selects a prediction and retrieve
        // more details for that place.
        searchBox.addListener('places_changed', function() { //ค้นหา 
          var places = searchBox.getPlaces();

          if (places.length == 0) {
            return;
          }
		  
          // Clear out the old markers.
          markers.forEach(function(marker) {
            marker.setMap(null);
          });
          markers = [];

          // For each place, get the icon, name and location.
          var bounds = new google.maps.LatLngBounds();
          places.forEach(function(place) {
            if (!place.geometry) {
              console.log("Returned place contains no geometry");
              return;
            }
            /*var icon = {
              url: place.icon,
              size: new google.maps.Size(71, 71),
              origin: new google.maps.Point(0, 0),
              anchor: new google.maps.Point(17, 34),
              scaledSize: new google.maps.Size(25, 25)
            };	*/		
			
            // Create a marker for each place.
			uluru = place.geometry.location;
			marker = new google.maps.Marker({
              map: map,
              icon: icon_marker,
              title: place.name,
              position: uluru,
			  draggable: true //ทำให้หมุดเลื่อนได้ 
            });
            markers.push(marker);
			
			
			//-- Get data เมื่อค้นหา แล้วเลือกสถานที่ ----------------------------------------------------------------------
			my_point = marker.getPosition();  // หาตำแหน่งของตัว marker เมื่อกดลากแล้วปล่อย 
			p_name=place.name;
			p_lat= my_point.lat();
			p_lon=my_point.lng();
			p_zoom=map.getZoom();
			//--ใส่ข้อมูลใน Input --------------------------------------------------------------------------------------------
			fnc_value_latlon(p_lat,p_lon);
			fnc_value_zoom(p_zoom);
			//console.log(place.name);
			//console.log(place.address_components);			
			marker.addListener('dragend', fnc_dragend);
			marker.addListener('click', fnc_click);
						
			place_name=place.name; //ชื่อสถานที่ 
			window.parent.$('#place_name').val(place_name);
			fnc_infowindow(place_name);
			
			
            if (place.geometry.viewport) {
              // Only geocodes have viewport.
              bounds.union(place.geometry.viewport);
            } else {
              bounds.extend(place.geometry.location);
            }
          });
          map.fitBounds(bounds);
		  //map.setZoom(18); //ให้ซูม default 
		  
        });
				
				
		//Handle Event  -----------------------------------------------------------------
		google.maps.event.addListener(marker, 'dragend', fnc_dragend);		//
		google.maps.event.addListener(marker, 'click', fnc_click);	
		google.maps.event.addListener(map, 'zoom_changed', fnc_zoom);
		
				
      }
	  
	  
	  function fnc_dragend(){		  
			my_point = marker.getPosition();
			p_lat= my_point.lat();
			p_lon=my_point.lng();
			uluru = {lat: p_lat, lng: p_lon }; //Default Location 
			fnc_value_latlon(p_lat,p_lon);
	  }
	  
	  function fnc_zoom(){		  
			p_zoom=map.getZoom();
			fnc_value_zoom(p_zoom);
	  }
	  
	  function fnc_value_latlon(p_lat,p_lon){		  
			window.parent.$('#lat_lon').val(p_lat.toFixed(6)+", "+p_lon.toFixed(6));
			window.parent.$('#place_latitude').val(p_lat.toFixed(6));
			window.parent.$('#place_longitude').val(p_lon.toFixed(6));
	  }
	  
	  function fnc_value_zoom(p_zoom){		  
			window.parent.$('#place_zoom').val(p_zoom);
	  } 
	  
	  
	  function change_marker(icon_name){
			marker.setMap(null); //Clear Marker
			//-----------------------------------------
			var markers = [];
			icon_marker=icon_name;
			// Create a marker for each place.
			marker = new google.maps.Marker({
			  map: map,
			  icon: icon_marker,
			  //title: place.name,
			  position: uluru,
			  draggable: true //ทำให้หมุดเลื่อนได้ 
			});
			markers.push(marker);
			marker.addListener('dragend', fnc_dragend);
			marker.addListener('click', fnc_click);
			fnc_infowindow(place_name);
	  }
	  
	  function fnc_click(){
		 	 console.log('marker click');
			fnc_infowindow(place_name);
	  }
	  
	  function fnc_infowindow(message){
			if(message !="" && message !=undefined){
				infowindow.setContent('<div align="center" style="font-weight:bold; color:#00F;">'+message+'</div>');
				infowindow.open(map, marker);
			}
	  }
	  
	  function change_zoomin(){
			p_zoom=map.getZoom();
			map.setZoom(p_zoom+1);
	  }
	   function change_zoomout(){
			p_zoom=map.getZoom();
			map.setZoom(p_zoom-1);
	  }
	  
	  
    </script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDZ25xm-K779KpzBFGI0V6M1nYp9C5LKHY&libraries=places&language=th&region=th&callback=initAutocomplete"
         async defer></script>
         
         
         
  </body>
</html>