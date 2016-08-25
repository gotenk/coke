<!-- Add an extra div to allow the elements within it to be sortable! -->
<div id="sortable">

	<!-- Dashboard Widgets -->
	{{ widgets:area slug="dashboard" }}
	
	
	<?php if ((isset($analytic_visits) OR isset($analytic_views)) AND $theme_options->maxcms_analytics_graph == 'yes'): ?>
	<script type="text/javascript">
	
	$(function($) {
			var visits = <?php echo isset($analytic_visits) ? $analytic_visits : 0 ?>;
			var views = <?php echo isset($analytic_views) ? $analytic_views : 0 ?>;
	
			var buildGraph = function() {
				$.plot($('#analytics'), [{ label: 'Visits', data: visits },{ label: 'Page views', data: views }], {
					lines: { show: true },
					points: { show: true },
					grid: { hoverable: true, backgroundColor: '#fefefe' },
					series: {
						lines: { show: true, lineWidth: 1 },
						shadowSize: 0
					},
					xaxis: { mode: "time" },
					yaxis: { min: 0},
					selection: { mode: "x" }
				});
			}
			// create the analytics graph when the page loads
			buildGraph();
	
			// re-create the analytics graph on window resize
			$(window).resize(function(){
				buildGraph();
			});
			
			function showTooltip(x, y, contents) {
				$('<div id="tooltip">' + contents + '</div>').css( {
					position: 'absolute',
					display: 'none',
					top: y + 5,
					left: x + 5,
					'border-radius': '3px',
					'-moz-border-radius': '3px',
					'-webkit-border-radius': '3px',
					padding: '3px 8px 3px 8px',
					color: '#ffffff',
					background: '#000000',
					opacity: 0.80
				}).appendTo("body").fadeIn(500);
			}
		 
			var previousPoint = null;
			
			$("#analytics").bind("plothover", function (event, pos, item) {
				$("#x").text(pos.x.toFixed(2));
				$("#y").text(pos.y.toFixed(2));
		 
					if (item) {
						if (previousPoint != item.dataIndex) {
							previousPoint = item.dataIndex;
							
							$("#tooltip").remove();
							var x = item.datapoint[0],
								y = item.datapoint[1];
							
							showTooltip(item.pageX, item.pageY,
										item.series.label + " : " + y);
						}
					}
					else {
						$("#tooltip").fadeOut(500);
						previousPoint = null;            
					}
			});
		
		});
	</script>
	<div class="one_full">
		<section class="title">
			<h4>Statistics</h4>
		</section>	
		<section class="item">
			<div class="content">
				<div id="analytics"></div>
			</div>
		</section>
	</div>
	
	<?php endif ?>
	<!-- End Analytics -->

		<script type="text/javascript">
		(function ($) {

			$('#remove_installer_directory').on('click', function (e) {
				e.preventDefault();
				var $parent = $(this).parent();
				$.get(SITE_URL + ADMIN_URL+'/remove_installer_directory', function (data) {
					$parent.removeClass('warning').addClass(data.status).html(data.message);
				});
			});
		})(jQuery);
	</script>
</div>
<!-- End sortable div -->