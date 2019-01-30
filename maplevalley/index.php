<?php
require_once("../_config/config.php");
$mvfbMap = file_get_contents("mvfb.json");
?>

<!DOCTYPE html>
<html>

<head>
	<title>FoodMap - MVFB</title>
    <script src="../_assets/jquery/jquery-3.2.1.min.js"></script>
	<script src="../_assets/semantic/semantic.min.js"></script>
	<link rel="stylesheet" type="text/css" href="../_assets/semantic/semantic.min.css">
    <style>
        #map
    	   {
			height: 50vh;
            width: 100vw;
           }
    	   
    	   body
    	   {
    		   margin: 0;
    	   }
    </style>
</head>

<body>
    <div id="map"></div>
    <script>
        var map;
		var geocoder;
		var markers = [];
		
		var tsd;
		var maplevalley;
		var covington;
		
		var blackdiamondA;
		var blackdiamondB;

		function initMap() 
		{
			var middle = {lat: 47.389843, lng: -121.929022};
            map = new google.maps.Map(document.getElementById('map'), {
				zoom: 11,
                center: middle
            });
			
			geocoder = new google.maps.Geocoder();
        }
        	  
        function loadGeoJsonString(geoString) 
		{
			var geojson = (geoString);
            map.data.addGeoJson(geojson);
			map.data.setStyle(function(feature) 
			{
				var id = feature.getProperty('id');
        		var color;
        			
        		switch(id)
        		{
        			case "tsd":
        				color = "#262626";
						
						map.data.forEach(function(feature)
						{
							if ((feature.getGeometry().getType() == "MultiPolygon") && (feature.getProperty('id') == "tsd"))
							{
								var bounds=[];
								feature.getGeometry().forEachLatLng(function(path)
								{
									bounds.push(path);
								});
								
								tsd = new google.maps.Polygon({
									paths: bounds
								});
							}
						});
        			break;
        				
        			case "maplevalley":
        				color = "#0032bf";
						
						map.data.forEach(function(feature)
						{
							if ((feature.getGeometry().getType() == "MultiPolygon") && (feature.getProperty('id') == "maplevalley"))
							{
								var bounds=[];
								feature.getGeometry().forEachLatLng(function(path)
								{
									bounds.push(path);
								});
								
								maplevalley = new google.maps.Polygon({
									paths: bounds,
								});
							}
						});
						
						
        			break;
        				
       				case "covington":
       					color = "#ce8200";
						
						map.data.forEach(function(feature)
						{
							if ((feature.getGeometry().getType() == "MultiPolygon") && (feature.getProperty('id') == "covington"))
							{
								var bounds=[];
								feature.getGeometry().forEachLatLng(function(path)
								{
									bounds.push(path);
								});
								
								covington = new google.maps.Polygon({
									paths: bounds
								});
							}
						});
       				break;
        				
       				case "blackdiamond":
        				color = "#db002b";
						
						map.data.forEach(function(feature)
						{
							if ((feature.getGeometry().getType() == "MultiPolygon") && (feature.getProperty('id') == "blackdiamond"))
							{
								var boundsA=[];
								var boundsB=[];
								var geomA = feature.getGeometry()['j'][0];
								var geomB = feature.getGeometry()['j'][1];
								
								geomA.forEachLatLng(function(path)
								{
									boundsA.push(path);
								});
								
								blackdiamondA = new google.maps.Polygon({
									paths: boundsA
								});
								
								geomB.forEachLatLng(function(path)
								{
									boundsB.push(path);
								});
								
								blackdiamondB = new google.maps.Polygon({
									paths: boundsB
								});
							}
						});
        			break;
        				
        			default:
        				color = "grey";
       				break;
       			}
        			
        		return {
        			strokeColor: color,
       				fillColor: color,
       				fillOpacity: 0.15,
       				strokeWeight: 1.5
				}});
        }
		
		function geocode(address)
		{
			geocoder.geocode({'address': address}, function(results, status)
			{
				if(status == 'OK')
				{
					if(markers.length > 0 ) { markers[0].setMap(null); markers = []; }
					
					map.setCenter(results[0].geometry.location);
					var marker = new google.maps.Marker({
						map: map,
						position: results[0].geometry.location
					});
					
					markers.push(marker);
					
					$("#resultsLabel").text("Results for " + results[0]["formatted_address"]);
					
					if(google.maps.geometry.poly.containsLocation(results[0].geometry.location, tsd))
					{
						$("#tsdLabel").text("IS inside Tahoma School District");
						$("#tsdLabel").css("color", "green");
					}
					else
					{
						$("#tsdLabel").text("IS NOT inside Tahoma School District");
						$("#tsdLabel").css("color", "red");
					}
					
					if(google.maps.geometry.poly.containsLocation(results[0].geometry.location, maplevalley))
					{
						$("#mapleValleyLabel").text("IS inside Maple Valley");
						$("#mapleValleyLabel").css("color", "green");
					}
					else
					{
						$("#mapleValleyLabel").text("IS NOT inside Maple Valley");
						$("#mapleValleyLabel").css("color", "red");
					}
					
					if(google.maps.geometry.poly.containsLocation(results[0].geometry.location, covington))
					{
						$("#covingtonLabel").text("IS inside Covington");
						$("#covingtonLabel").css("color", "green");
					}
					else
					{
						$("#covingtonLabel").text("IS NOT inside Covington");
						$("#covingtonLabel").css("color", "red");
					}
					
					if((google.maps.geometry.poly.containsLocation(results[0].geometry.location, blackdiamondA)) || (google.maps.geometry.poly.containsLocation(results[0].geometry.location, blackdiamondB)))
					{
						$("#blackDiamondLabel").text("IS inside Black Diamond");
						$("#blackDiamondLabel").css("color", "green");
					}
					else
					{
						$("#blackDiamondLabel").text("IS NOT inside Black Diamond");
						$("#blackDiamondLabel").css("color", "red");
					}
					
					$("#results").fadeIn("fast", function()
					{
				
					});
				}
				else
				{
					alert("ERROR: " + status)
				}
			});
		}
        	  
        function initialize()
        {
        	initMap();
        	loadGeoJsonString(<?php echo($mvfbMap); ?>);
        }
    </script>
    <script async defer src=<?php echo("https://maps.googleapis.com/maps/api/js?key=" . MAPS_API_KEY ."&callback=initialize") ?>>
    </script>

	<div class="ui segment" style="margin: 2.5em">
		<form class="ui form" onkeypress="return event.keyCode != 13;">
			<div class="four wide field">
				<label>Address</label>
				<input type="text" name="address" id="address" placeholder="12345 67th ST N, Maple Valley, WA">
			</div>
			<button class="ui button" type="button" id="btn" onclick="return false;">Search</button>
		</form>
	</div>
	
	<div class="ui segments" id="results" style="margin: 2.5em; display: none;">
		<div class="ui segment">
			<h4 class="ui header" id="resultsLabel">Results</h4>
		</div>
		
		<div class="ui black segment">
			<p id="tsdLabel">Tahoma School District</p>
		</div>
		
		<div class="ui blue segment">
			<p id="mapleValleyLabel">Maple Valley</p>
		</div>
		
		<div class="ui red segment">
			<p id="blackDiamondLabel">Black Diamond</p>
		</div>
		
		<div class="ui yellow segment">
			<p id="covingtonLabel">Covington</p>
		</div>
	</div>
</body>

<script>
$(document).keypress(function(e)
{
	if(e.which == 13)
	{
		geocode($("#address").val());
	}
});

$("#btn").click(function(e) {
	geocode($("#address").val());
});
</script>
</html>