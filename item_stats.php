<?php
	require_once('includes/globals.php');
	isset($_GET['itemId']) ? $itemId = $_GET['itemId'] : header("Location: /market/");
	isset($_GET['itemName']) ? $itemName = $_GET['itemName'] : header("Location: /market/");
	// header("Content-Type: text/plain");

	//Create our list of the last six months (including this month)
	$first  = strtotime('first day this month');
	$months = array();
	for ($i = -3; $i <= 3; $i++) {
		$months[date('F', strtotime("-$i month", $first))] = array();
	}

	//Go through our list of items and sort them into our month data sets.
	foreach($globals->dataHandler->getSellOrderInformation($itemId) as $index => $item){
		$months[date('F', strtotime($item->timeLeft))][] = $item;
	}

?>


	<?php //exit; ?>
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
	$(function () {
			/**
			 * Dark theme for Highcharts JS
			 * @author Torstein Honsi
			 */

			// Load the fonts
			Highcharts.createElement('link', {
				 href: 'http://fonts.googleapis.com/css?family=Unica+One',
				 rel: 'stylesheet',
				 type: 'text/css'
			}, null, document.getElementsByTagName('head')[0]);

			Highcharts.theme = {
				 colors: ["#2b908f", "#90ee7e", "#f45b5b", "#7798BF", "#aaeeee", "#ff0066", "#eeaaee",
						"#55BF3B", "#DF5353", "#7798BF", "#aaeeee"],
				 chart: {
						backgroundColor: {
							 linearGradient: { x1: 0, y1: 0, x2: 1, y2: 1 },
							 stops: [
									[0, '#2a2a2b'],
									[1, '#3e3e40']
							 ]
						},
						style: {
							 fontFamily: "'Unica One', sans-serif"
						},
						plotBorderColor: '#606063'
				 },
				 title: {
						style: {
							 color: '#E0E0E3',
							 textTransform: 'uppercase',
							 fontSize: '20px'
						}
				 },
				 subtitle: {
						style: {
							 color: '#E0E0E3',
							 textTransform: 'uppercase'
						}
				 },
				 xAxis: {
						gridLineColor: '#707073',
						labels: {
							 style: {
									color: '#E0E0E3'
							 }
						},
						lineColor: '#707073',
						minorGridLineColor: '#505053',
						tickColor: '#707073',
						title: {
							 style: {
									color: '#A0A0A3'

							 }
						}
				 },
				 yAxis: {
						gridLineColor: '#707073',
						labels: {
							 style: {
									color: '#E0E0E3'
							 }
						},
						lineColor: '#707073',
						minorGridLineColor: '#505053',
						tickColor: '#707073',
						tickWidth: 1,
						title: {
							 style: {
									color: '#A0A0A3'
							 }
						}
				 },
				 tooltip: {
						backgroundColor: 'rgba(0, 0, 0, 0.85)',
						style: {
							 color: '#F0F0F0'
						}
				 },
				 plotOptions: {
						series: {
							 dataLabels: {
									color: '#B0B0B3'
							 },
							 marker: {
									lineColor: '#333'
							 }
						},
						boxplot: {
							 fillColor: '#505053'
						},
						candlestick: {
							 lineColor: 'white'
						},
						errorbar: {
							 color: 'white'
						}
				 },
				 legend: {
						itemStyle: {
							 color: '#E0E0E3'
						},
						itemHoverStyle: {
							 color: '#FFF'
						},
						itemHiddenStyle: {
							 color: '#606063'
						}
				 },
				 labels: {
						style: {
							 color: '#707073'
						}
				 },

				 drilldown: {
						activeAxisLabelStyle: {
							 color: '#F0F0F3'
						},
						activeDataLabelStyle: {
							 color: '#F0F0F3'
						}
				 },

				 navigation: {
						buttonOptions: {
							 symbolStroke: '#DDDDDD',
							 theme: {
									fill: '#505053'
							 }
						}
				 },

				 // scroll charts
				 rangeSelector: {
						buttonTheme: {
							 fill: '#505053',
							 stroke: '#000000',
							 style: {
									color: '#CCC'
							 },
							 states: {
									hover: {
										 fill: '#707073',
										 stroke: '#000000',
										 style: {
												color: 'white'
										 }
									},
									select: {
										 fill: '#000003',
										 stroke: '#000000',
										 style: {
												color: 'white'
										 }
									}
							 }
						},
						inputBoxBorderColor: '#505053',
						inputStyle: {
							 backgroundColor: '#333',
							 color: 'silver'
						},
						labelStyle: {
							 color: 'silver'
						}
				 },

				 navigator: {
						handles: {
							 backgroundColor: '#666',
							 borderColor: '#AAA'
						},
						outlineColor: '#CCC',
						maskFill: 'rgba(255,255,255,0.1)',
						series: {
							 color: '#7798BF',
							 lineColor: '#A6C7ED'
						},
						xAxis: {
							 gridLineColor: '#505053'
						}
				 },

				 scrollbar: {
						barBackgroundColor: '#808083',
						barBorderColor: '#808083',
						buttonArrowColor: '#CCC',
						buttonBackgroundColor: '#606063',
						buttonBorderColor: '#606063',
						rifleColor: '#FFF',
						trackBackgroundColor: '#404043',
						trackBorderColor: '#404043'
				 },

				 // special colors for some of the
				 legendBackgroundColor: 'rgba(0, 0, 0, 0.5)',
				 background2: '#505053',
				 dataLabelsColor: '#B0B0B3',
				 textColor: '#C0C0C0',
				 contrastTextColor: '#F0F0F3',
				 maskColor: 'rgba(255,255,255,0.3)'
			};

			// Apply the theme
			Highcharts.setOptions(Highcharts.theme);
		$('#container').highcharts({
				credits: {
				 	enabled: false,
				},
				chart: {
						type: 'scatter',
						zoomType: 'xy',
						selectionMarkerFill: 'rgb(69,114,167)',
				},
				title: {
						text: 'Price trend for <?php echo(htmlspecialchars(addslashes($itemName))); ?>'
				},
				xAxis: {
						allowDecimals: false,
						title: {
								enabled: true,
								text: 'Quality Level'
						},
						startOnTick: true,
						endOnTick: true,
						showLastLabel: true
				},
				yAxis: {
						title: {
								text: 'Price'
						}
				},
				legend: {
						// layout: 'vertical',
						// align: 'left',
						// verticalAlign: 'top',
						title: {
							text: 'Expiration',
							style: {
								color: 'rgb(255, 255, 255)',
								},
						},
						// x: 100,
						// y: 70,
						// floating: false,
						backgroundColor: (Highcharts.theme && Highcharts.theme.legendBackgroundColor) || '#FFFFFF',
						borderWidth: 1
				},
				plotOptions: {
						scatter: {
								marker: {
										radius: 4,
										states: {
												hover: {
														enabled: true,
														lineColor: 'rgb(100,100,100)'
												}
										}
								},
								states: {
										hover: {
												marker: {
														enabled: false
												}
										}
								},
								tooltip: {
										headerFormat: '<b>{series.name}</b><br>',
										pointFormat: '{point.name}<br />QL: {point.x}<br />Price: {point.y}'
								}
						}
				},
				series: [<?php foreach($months as $monthName => $monthOrders){ ?>{
					name: '<?php echo $monthName ?>',
					data: [<?php foreach($monthOrders as $sellOrder){ ?>{x: <?php echo $sellOrder->ql ?>, y:<?php echo $sellOrder->price ?>, name: '<?php echo($sellOrder->user); ?>'}, <?php } ?>],
					<?php echo date('n') > date('n', strtotime($monthName)) ? 'visible: false,' : ''; ?> 
					},
					<?php } ?>
				],
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
					<img src="/staticLIVE/images/stats.png"/>
				</div>
				<div class="pageName">
					<h1>Item Statistics</h1>
				</div>
			</div>
			<div class="header">
				<h2><?php echo($itemName); ?></h2>
			</div>
			<div>
				<div id="container" style="min-width: 310px; height: 400px; max-width: 800px; margin: 0 auto"></div>
				<button id="reload" onclick="location.reload()">Reload</button>
			</div>
		</div>
	</body>
</html>

