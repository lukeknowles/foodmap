<?php
require_once("../_config/config.php");
$kfbMap = file_get_contents("kfb.json");
?>

	<!DOCTYPE html>
	<html>

	<head>
		<title>FoodMap - KFB</title>
		<script src="../_assets/jquery/jquery-3.2.1.min.js"></script>
		<script src="../_assets/semantic/semantic.min.js"></script>
		<link rel="stylesheet" type="text/css" href="../_assets/semantic/semantic.min.css">
		<style>
			#map {
				height: 50vh;
				width: 100vw;
			}
			
			body {
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

			var kentsd;

			function initMap() {
				var middle = {
					lat: 47.377896,
					lng: -122.233722
				};
				map = new google.maps.Map(document.getElementById('map'), {
					zoom: 10,
					center: middle
				});
				geocoder = new google.maps.Geocoder();
			}

			function loadGeoJsonString(geoString) {
				var geojson = (geoString);
				map.data.addGeoJson(geojson);
				map.data.setStyle(function(feature) {
					var id = feature.getProperty('id');
					var color;

					switch (id) {
						case "kentsd":
							color = "#ed0707";

							map.data.forEach(function(feature) {
								if ((feature.getGeometry().getType() == "MultiPolygon") && (feature.getProperty('id') == "kentsd")) {
									var bounds = [];
									feature.getGeometry().forEachLatLng(function(path) {
										bounds.push(path);
									});

									kentsd = new google.maps.Polygon({
										paths: bounds
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
					}
				});

			}

			function geocode(address) {
				geocoder.geocode({
					'address': address
				}, function(results, status) {
					if (status == 'OK') {
						if (markers.length > 0) {
							markers[0].setMap(null);
							markers = [];
						}

						map.setCenter(results[0].geometry.location);
						var marker = new google.maps.Marker({
							map: map,
							position: results[0].geometry.location
						});

						markers.push(marker);

						$("#resultsLabel").text("Results for " + results[0]["formatted_address"]);

						if (google.maps.geometry.poly.containsLocation(results[0].geometry.location, kentsd)) {
							$("#kentsdLabel").text("IS inside Kent School District");
							$("#kentsdLabel").css("color", "green");
						} else {
							$("#kentsdLabel").text("IS NOT inside Kent School District");
							$("#kentsdLabel").css("color", "red");
						}

						$("#results").fadeIn("fast", function() {});
					} else {
						alert("ERROR: " + status)
					}
				});
			}

			function initialize() {
				initMap();
				loadGeoJsonString(<?php echo($kfbMap); ?>);
			}
		</script>

		<script async defer src=<?php echo( "https://maps.googleapis.com/maps/api/js?key=" . MAPS_API_KEY . "&callback=initialize") ?>
			>
		</script>

		<div class="ui segment" style="margin: 2.5em">
			<form class="ui form" onkeypress="return event.keyCode != 13;">
				<div class="four wide field">
					<label>Address</label>
					<input type="text" name="address" id="address" placeholder="12345 67th ST N, Kent, WA">
				</div>
				<button class="ui button" type="button" id="btn" onclick="return false;">Search</button>
			</form>
		</div>

		<div class="ui segments" id="results" style="margin: 2.5em; display: none;">
			<div class="ui segment">
				<h4 class="ui header" id="resultsLabel">Results</h4>
			</div>
			<div class="ui red segment">
				<p id="kentsdLabel">Kent School District</p>
			</div>
		</div>
	</body>

	<script>
		$(document).keypress(function(e) {
			if (e.which == 13) {
				geocode($("#address").val());
			}
		});

		$("#btn").click(function(e) {
			geocode($("#address").val());
		});
	</script>

	</html>