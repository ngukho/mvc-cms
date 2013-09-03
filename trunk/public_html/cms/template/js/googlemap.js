// JavaScript Document
//google map backend 
var map; 
var markers = [];
var marker;
function initialize() {
	
	var str = document.getElementById('input_position').value.split(',');			
	var latlng = new google.maps.LatLng(parseFloat(str[0]), parseFloat(str[1]));		
	var options = {
	 zoom: 14,
	 center: latlng,
	 mapTypeId: google.maps.MapTypeId.ROADMAP
	};
	map = new google.maps.Map(document.getElementById("map_canvas"), options);	
	placeMarker(latlng, null);									
}
function showAddress(form) {	    		
	form = document.getElementById(form);
	var address  = form.address.value;	
	if(form.name.value == ''){alert('Please enter the store name'); form.name.focus(); return false;}
	if(form.address.value == ''){alert('Please enter the address'); form.address.focus(); return false;}							
	if (form.countryid.value=='' || form.countryid.value<=0){alert('Please enter the Country'); form.countryid.focus(); return false;}				
	if (form.city.value!=''){address += ', '+form.city.value}		
	if (form.prov.value!=''){address += ' '+form.prov.value}			
	if (form.zipcode.value!=''){address += ', '+form.zipcode.value}					
	address += ','+form.countryid.options[form.countryid.selectedIndex].text;
	document.getElementById("address_simple").innerHTML =address;
	var possition = searchLocations(address);	
	$("#is_getmap").val("1");
}

function searchLocations(address) {		 	
	 var geocoder = new google.maps.Geocoder();
	 geocoder.geocode({address: address}, function(results, status) {
	   if (status == google.maps.GeocoderStatus.OK) {
			placeMarker(results[0].geometry.location, null);
			document.getElementById('input_position').value = results[0].geometry.location.lat()+","+results[0].geometry.location.lng();		
			return results[0].geometry.location;
	   } else {			   	
		  return null;
	   }
	 });
 }
function placeMarker(location, obj) 
{	
	clearMarkers();		
	marker = new google.maps.Marker({
		  position: location, 
		  map: map,
		  clickable: true,
		  draggable:true
	  });			
	markers.push(marker);
	google.maps.event.addListener(marker, 'drag', function() {
		document.getElementById('input_position').value = marker.getPosition().lat()+","+marker.getPosition().lng();
	});
  
	google.maps.event.addListener(marker, 'dragend', function() {			  
		document.getElementById('input_position').value = marker.getPosition().lat()+","+marker.getPosition().lng();
	});
	map.setZoom(14);  
	map.setZoom(14);
	map.setCenter(location);
}
function clearMarkers() 
{
	 for (var i = 0; i < markers.length; i++) {
	   markers[i].setMap(null);
	 }
	 markers.length = 0;
}