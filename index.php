<!DOCTYPE html>
<html>
	<head>
		<!-- Standard Meta -->
		<meta charset="utf-8" />
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">

		<!-- Site Properties -->
		<title>FoodMap</title>
		<link rel="stylesheet" type="text/css" href="_assets/semantic/semantic.min.css">
		<link rel="stylesheet" type="text/css" href="_assets/semantic/components/dropdown.css">


		<script src="_assets/jquery/jquery-3.2.1.min.js"></script>
		<script src="_assets/semantic/semantic.min.js"></script>
		<script src="_assets/semantic/components/dropdown.js"></script>

		<script>
			$(document).ready(function() 
			{
				$('.ui.dropdown').dropdown();
				
				$("#goBtn").click(function()
				{
					var id = ($("#foodBankID").attr("value"));
					
					if(id == 0)
					{
						$(location).attr('href', 'https://monixlabs.com/foodmap/maplevalley/');
					}
					
					if(id == 1)
					{
						$(location).attr('href', 'https://monixlabs.com/foodmap/tukwila/');
					}
					
					if(id == 2)
					{
						$(location).attr('href', 'https://monixlabs.com/foodmap/kent/');
					}
					
					if(id == 3)
					{
						$(location).attr('href', 'https://monixlabs.com/foodmap/covington/');
					}
				});
			});
		</script>

		<style type="text/css">
			body
			{
			  background: url("_media/map.jpg") no-repeat center center fixed; 
			  -webkit-background-size: cover; 
			  -moz-background-size: cover; 
			  -o-background-size: cover;
			  background-size: cover;
			}
			
			body > .grid
			{
			  height: 100%;
			}
			.image
			{
			  margin-top: -100px;
			}
			.column
			{
			  max-width: 450px;
			}
		</style>
	</head>

	<body>

		<div class="ui middle aligned center aligned grid">
			<div class="column">
				<div class="ui large blue header">
					Monix Labs - FoodMap
				</div>

				<form class="ui large form" return false;>
					<div class="ui stacked segment">
						<div class="field">
							<div class="ui selection dropdown">
								<input type="hidden" name="foodBankID" id="foodBankID">
								<i class="dropdown icon"></i>
								<div class="default text">Select a Food Bank</div>
								<div class="menu">
									<div class="item" data-value="0">Maple Valley Food Bank</div>
									<div class="item" data-value="1">Tukwila Food Pantry</div>
									<div class="item" data-value="2">Kent Food Bank</div>
									<div class="item" data-value="3">Covington Storehouse</div>
								</div>
							</div>
						</div>
						<div class="ui fluid large blue button" id="goBtn">Go<i class="icon arrow right"></i></div>
					</div>

				</form>
			</div>
		</div>
	</body>

</html>