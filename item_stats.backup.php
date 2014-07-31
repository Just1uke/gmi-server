<?php
	require_once('includes/globals.php');
	isset($_GET['itemId']) ? $itemId = $_GET['itemId'] : header("Location: /market/");
	isset($_GET['itemName']) ? $itemName = $_GET['itemName'] : header("Location: /market/");
	$itemHistory = $globals->dataHandler->getSellOrderInformation($itemId);
	// header("Content-Type: text/plain");
	// var_dump($itemHistory);
?>

	<!doctype html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-15" />
		<meta http-equiv="X-UA-Compatible" content="IE=edge" />
		
		<!-- styles used on all pages -->
		<link href="/staticLIVE/css/style.css" rel="stylesheet" type="text/css" />

		<!-- styles needed by DataTables -->
		<link href="/staticLIVE/css/jquery.dataTables.css" rel="stylesheet" type="text/css" />
		
		<!-- styles needed by jScrollPane -->
		<link type="text/css" href="/staticLIVE/css/jquery.jscrollpane.css" rel="stylesheet" media="all" />

		<!-- JQuery -->
		<script type="text/javascript" src="/staticLIVE/js/jquery-1.6.4.min.js"></script>
		
		 <!-- the mousewheel plugin - optional to provide mousewheel support -->
		<script type="text/javascript" src="/staticLIVE/js/jquery.mousewheel.js"></script>

		<!-- the DataTables script -->
		<script type="text/javascript" src="/staticLIVE/js//datatables/jquery.dataTables.min.js"></script>

		<!-- the jScrollPane script -->
		<script type="text/javascript" src="/staticLIVE/js/jquery.jscrollpane.min.js"></script>
		
		<!-- Globalize (https://github.com/jquery/globalize) -->
		<script type="text/javascript" src="/staticLIVE/js/globalize/globalize.js" charset="utf-8"></script>
		<script type="text/javascript" src="/staticLIVE/js/globalize/globalize.cultures.js" charset="utf-8"></script>
		
		<!-- Market specific JavaScript -->
		<script type="text/javascript" src="/staticLIVE/js/market.js"></script>
		
		<script type="text/javascript">
			$(document).ready(function() {
				if (typeof AnarchyOnline == "undefined") {
					AnarchyOnline = new Object();
					AnarchyOnline.AcceptLanguage = [];
					AnarchyOnline.IsInGame = false;
				}
				Globalize.culture(AnarchyOnline.AcceptLanguage);
			})
		</script>
		
		<link rel="alternate" media="print" href="item_view.html">
		<title>Deep Stats</title>

		<script type="text/javascript">
			var myLineChart;
			var test =  { label: "My First dataset",
							fillColor: "rgba(220,220,220,0.2)",
							strokeColor: "rgba(220,220,220,1)",
							pointColor: "rgba(220,220,220,1)",
							pointStrokeColor: "#fff",
							pointHighlightFill: "#fff",
							pointHighlightStroke: "rgba(220,220,220,1)",
							data: [53, 10, 30, "", 90, 27, 50] };
			var data = {
					labels: ["January", "February", "March", "April", "May", "June", "July"],
					datasets: [
						{
							label: "My First dataset",
							fillColor: "rgba(220,220,220,0.2)",
							strokeColor: "rgba(220,220,220,1)",
							pointColor: "rgba(220,220,220,1)",
							pointStrokeColor: "#fff",
							pointHighlightFill: "#fff",
							pointHighlightStroke: "rgba(220,220,220,1)",
							data: [65, 59, 80, 81, 56, 55, 40]
						},
						{
							label: "My Second dataset",
							fillColor: "rgba(151,187,205,0.2)",
							strokeColor: "rgba(151,187,205,1)",
							pointColor: "rgba(151,187,205,1)",
							pointStrokeColor: "#fff",
							pointHighlightFill: "#fff",
							pointHighlightStroke: "rgba(151,187,205,1)",
							data: [28, 48, 40, 19, 86, 27, 90]
						}
					]
			};
			var options= {
					///Boolean - Whether grid lines are shown across the chart
					scaleShowGridLines : true,
					//String - Colour of the grid lines
					scaleGridLineColor : "rgba(0,0,0,.05)",
					//Number - Width of the grid lines
					scaleGridLineWidth : 1,
					//Boolean - Whether the line is curved between points
					bezierCurve : true,
					//Number - Tension of the bezier curve between points
					bezierCurveTension : 0.4,
					//Boolean - Whether to show a dot for each point
					pointDot : true,
					//Number - Radius of each point dot in pixels
					pointDotRadius : 4,
					//Number - Pixel width of point dot stroke
					pointDotStrokeWidth : 1,
					//Number - amount extra to add to the radius to cater for hit detection outside the drawn point
					pointHitDetectionRadius : 20,
					//Boolean - Whether to show a stroke for datasets
					datasetStroke : true,
					//Number - Pixel width of dataset stroke
					datasetStrokeWidth : 2,
					//Boolean - Whether to fill the dataset with a colour
					datasetFill : true,
					//String - A legend template
					legendTemplate : "<ul class=\"<%=name.toLowerCase()%>-legend\"><% for (var i=0; i<datasets.length; i++){%><li><span style=\"background-color:<%=datasets[i].lineColor%>\"></span><%if(datasets[i].label){%><%=datasets[i].label%><%}%></li><%}%></ul>"
			};
			var testOptions;
			$(function(){
				$('#myChart').attr('width', $('#page').width());
				$('#myChart').attr('height', $('#page').height() / 2);
				var ctx = document.getElementById("myChart").getContext("2d");
				myLineChart = new Chart(ctx);
				testOptions = myLineChart.Line(data, options);

			});

			$(function(){
				$('#testButton').click(function(event){
					console.log("Starting...");
					data.datasets.push(test);
					testOptions.destroy();
					var ctx = document.getElementById("myChart").getContext("2d");
					myLineChart = new Chart(ctx);
					testOptions = myLineChart.Line(data, options);
					console.log("Done");
				});
			});
		</script>      
		</head>
	<body onkeyup="hotKeys(event)">
		<div id="menu">
					<div class="picture_tab"><img src="/staticLIVE/images/tab_right.png" /></div>
					<div class="url_tab"><a href="/marketLIVE/stats"><p class="pHeader">Statistics</p></a></div>
					<div class="picture_tab"><img src="/staticLIVE/images/tab_left.png" /></div>
					<div class="picture_tab"><img src="/staticLIVE/images/tab_right.png" /></div>
					<div class="url_tab"><a href="/marketLIVE/mail"><p class="pHeader">Deliveries</p></a></div>
					<div class="picture_tab"><img src="/staticLIVE/images/tab_left.png" /></div>
					<div class="picture_tab"><img src="/staticLIVE/images/tab_right.png" /></div>
					<div class="url_tab"><a href="/marketLIVE/search"><p class="pHeader">Search</p></a></div>
					<div class="picture_tab"><img src="/staticLIVE/images/tab_left.png" /></div>
					<div class="picture_tab"><img src="/staticLIVE/images/tab_right.png" /></div>
					<div class="url_tab"><a href="/marketLIVE/log"><p class="pHeader">Log</p></a></div>
					<div class="picture_tab"><img src="/staticLIVE/images/tab_left.png" /></div>
					<div class="picture_tab"><img src="/staticLIVE/images/tab_right.png" /></div>
					<div class="url_tab"><a href="/marketLIVE/my_orders"><p class="pHeader">My Orders</p></a></div>
					<div class="picture_tab"><img src="/staticLIVE/images/tab_left.png" /></div>
					<div class="picture_tab"><img src="/staticLIVE/images/tab_right.png" /></div>
					<div class="url_tab"><a href="/marketLIVE/inventory"><p class="pHeader">Inventory</p></a></div>
					<div class="picture_tab"><img src="/staticLIVE/images/tab_left.png" /></div>
		</div>
		<div id="page">
			<div class="marketHeader">
				<div class="icon">
					<img src="/staticLIVE/images/log.png"/>
				</div>
				<div class="pageName">
					<h1>Item Statistics</h1>
				</div>
			</div>
			<div class="header">
				<h2><?php echo($itemName); ?></h2>
			</div>
			<div>
				<canvas id="myChart"></canvas>
				<button id="testButton">Test</button>
				<input type="textbox" id="minQL">
				<input type="textbox" id="maxQL">
			</div>
		</div>
	</body>
</html>

