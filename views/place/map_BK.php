<!doctype html>
<html>
<head>
<meta charset="windows-874">
<title>Untitled Document</title>
<style>
   html, body {
        height: 100%;
        margin: 0;
        padding: 0;
      }
</style>
</head>

<body>


<div id="map" style="width:100%; height:100%; " ></div>


<script src="../assets/js/jquery/jquery.js"></script> 
<script>
  function initMap() { //13.710546, 100.521511
	var uluru = {lat: 13.710546, lng: 100.521511 };
	var map = new google.maps.Map(document.getElementById('map'), {
	  zoom: 15,
	  center: uluru
	});
	var marker = new google.maps.Marker({
	  position: uluru,
	  map: map,
	  draggable: true //�������ش����͹�� 
	});
	
	// ��˹� event ���Ѻ��� marker ���������ش����ҡ��� marker ���ӧҹ����
	google.maps.event.addListener(marker, 'dragend', function() {
		var my_point = marker.getPosition();  // �ҵ��˹觢ͧ��� marker ����͡��ҡ���ǻ���� 
       var a= my_point.lat()
       	var b=my_point.lng()
		var c=map.getZoom();
        console.info(a+" "+b+" "+c);
	});
	// ��˹� event ���Ѻ���Ἱ��� ������ա������¹�ŧ��� zoom    
	google.maps.event.addListener(map, 'zoom_changed', function() {
		//top.$("#zoom").val(map.getZoom());// ��Ң�Ҵ zoom �ͧἹ����ʴ�� textbox id=zoom   
//		var zoom2=map.getZoom();
//		frm_hide.location="action.php?marker_dragend_session=1&zoom="+zoom2+"";
	});
	
  }
</script>
 <script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDZ25xm-K779KpzBFGI0V6M1nYp9C5LKHY&callback=initMap"
  type="text/javascript"></script>
    
    
    
    

</body>
</html>