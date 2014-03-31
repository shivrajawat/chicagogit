<?php
  /**
   * Main
   *  
   */
  if (!defined("_VALID_PHP"))
      die('Direct access to this location is not allowed.');
?>
<?php $monthly = $core->monthlyStatsOrders(); ?>
<?php $stats = $core->getStatsOrders(); ?>
<?php $new_customer = $core->getNewCusmersByDate();   //New customers by each date ?>
<?php $order_byLocation = $core->geOrdersByLocation();   //Total Customers by Locations ?>
<?php $order_byDayOfWeek = $core->geOrdersByDayOfWeek();   //Total Orders by day of week like Sunday, Monday etc. ?>
<?php $cust_byCity = $core->getCustomerByCity();  //Total customers by city address   ?>
<?php 
//Month list display
if(isset($_GET['month']) && $_GET['month']=='01'){ echo $mon = "January"; }
if(isset($_GET['month']) && $_GET['month']=='02'){ echo $mon = "February"; }
if(isset($_GET['month']) && $_GET['month']=='03'){ echo $mon = "March"; }
if(isset($_GET['month']) && $_GET['month']=='04'){ echo $mon = "April"; }
if(isset($_GET['month']) && $_GET['month']=='05'){ echo $mon = "May"; }
if(isset($_GET['month']) && $_GET['month']=='06'){ echo $mon = "June"; }
if(isset($_GET['month']) && $_GET['month']=='07'){ echo $mon = "July"; }
if(isset($_GET['month']) && $_GET['month']=='08'){ echo $mon = "August"; }
if(isset($_GET['month']) && $_GET['month']=='09'){ echo $mon = "September"; }
if(isset($_GET['month']) && $_GET['month']=='10'){ echo $mon = "October"; }
if(isset($_GET['month']) && $_GET['month']=='11'){ echo $mon = "November"; }
if(isset($_GET['month']) && $_GET['month']=='12'){ echo $mon = "December"; }
?>

<script language="javascript" type="text/javascript" src="assets/jquery.jqplot.min.js"></script>
<script language="javascript" type="text/javascript" src="assets/jqplot.barRenderer.min.js"></script>
<script language="javascript" type="text/javascript" src="assets/jqplot.categoryAxisRenderer.min.js"></script>
<script language="javascript" type="text/javascript" src="assets/jqplot.pointLabels.min.js"></script>

<!--Previous scripts files upto 18Ocotber,2013, starts here-->
<script type="text/javascript" src="assets/jquery.flot.js"></script>
<!---Previous scripts files upto 18Ocotber,2013, ends here-->
<div class="block-top-header">
  <h1><img src="images/home-sml.png" alt="" />Welcome to admin panel</h1>
  <div class="divider"><span></span></div>
</div>
<p class="info"><span><?php echo $core->langIcon();?></span>This is the control panel for your online food order solution. You can manager your website efficiently.</p>
<!---------------------------------------------------------This is div for latest statistics of visits,starts here------------------------------------------->
<div class="block-border" style="display:none;">
  <div class="block-header">
    <h2>
    	<span class="dropdown-menu"> 
            <a href="javascript:void(0);" class="menu-toggle"><img src="images/options.png" alt="" /></a> 
            <em class="drop-wrap" id="range"> <a href="javascript:void(0);" data-range="day">Today</a> 
                <a href="javascript:void(0);" data-range="week">This Week</a> 
                <a href="javascript:void(0);" data-range="month">This Month</a>
                <a href="javascript:void(0);" data-range="year">This Year</a> 
        	</em>
       </span>Latest Statistics
    </h2>
  </div>
  <div class="block-content">
    <table class="display">
      <tfoot>
        <tr>
          <td><a href="javascript:void(0)" class="button-orange delete">Empty Stats Table</a></td>
        </tr>
      </tfoot>
      <tr>
        <td><div id="chartdata" style="height:400px;width:100%;overflow:hidden"></div></td>
      </tr>
    </table>
  </div>
</div>
<script type="text/javascript"> 
// <![CDATA[
function getVisitsChart(range) {
    $.ajax({
        type: 'GET',
        url: 'ajax.php?getVisitsStats=1&timerange=' + range,
        dataType: 'json',
        async: false,
        success: function (json) {
			
            var option = {
                shadowSize: 0,
                lines: {
                    show: true,
                    fill: true,
                    lineWidth: 1
                },
                grid: {
					aboveData: true,
                    backgroundColor: '#fff'
                },
                xaxis: {
                    ticks: json.xaxis
                }
            }
            $.plot($('#chartdata'), [json.hits, json.visits], option);
        }
    });
}

 $(document).ready(function () {
  getVisitsChart('month');
  $('#range a').on('click',  function () {
	  var val = $(this).attr('data-range');
	  getVisitsChart(val);
	});
		
  $('.container').on('click', 'a.delete', function () {
	  var text = '<div><span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 20px 0;"></span>Are you sure you want to delete this record?<br /><strong>This action cannot be undone!!!</strong></div>';
	  $.confirm({
		  title: '<?php echo _MN_EMPTY_STATS;?>',
		  message: text,
		  buttons: {
			  '<?php echo _DELETE;?>': {
				  'class': 'yes',
				  'action': function () {
					  $.ajax({
						  type: 'post',
						  url: 'ajax.php',
						  data: 'deleteStats=1',
						  success: function (msg) {
							$('body').fadeOut(1200, function () {
								window.location.href = "index.php";
							});
						  }
					  });
				  }
			  },
			  '<?php echo _CANCEL;?>': {
				  'class': 'no',
				  'action': function () {}
			  }
		  }
	  });
  });
});
// ]]>
</script>
<!---------------------------------------------------------This is div for latest statistics of visits,ends here--------------------------------------------->
<!---------------------------------------------------------This is div for Total orders graph,starts here---------------------------------------------------->
<?php if($monthly):?>
<script type="text/javascript">
  $(document).ready(function(){
    var s2 = [
	<?php
		$res = '';
		foreach($stats as $row) {
			if(strlen($res) > 0) {
				$res .= ",";
			}
			$res .= $row['total_order'];
		}
		echo $res;
		?>
	];
   
    var ticks = [
	<?php
		$res3 = '';
		$i=0;
		foreach($stats as $rep) {
			
			if(strlen($res3) > 0) {
				$res3 .= ",";
			}
			//$res3 .= "[". date("d", strtotime($rep['order_date'])) . "]";
			$res3 .= "['".$rep['order_date']."']"; 
			
			$i++;
			
			//$res3 .= $i;
		}
		?>
		<?php print $res3;?>
	];
    <?php unset($res,$res3);?>
    plot1 = $.jqplot('chart', [s2], {
	  seriesDefaults: {
	  renderer: $.jqplot.BarRenderer,
	  pointLabels:{show:true}
		  },
	  title: '<?php echo 'Monthly statistics for Orders &rsaquo; '.doDate('%b',date("M", mktime(0, 0, 0, $core->month, 10))).' / '.$core->year;?>',
      series:[
        {label:'Total Orders = <?php echo ($monthly['monthly_total_order']) ? $monthly['monthly_total_order'].' ' : ''; ?>'}
      ],
      legend: {
        show: true,
		location: 'ne'
      },
      axes: {
        xaxis: {
          renderer: $.jqplot.CategoryAxisRenderer,
          ticks: ticks
        }
      }
    });
  });
</script>
<h2>Latest Statics for Total Orders</h2>
<div id="chart"></div>
<?php endif;?>
<table cellspacing="0" cellpadding="0" class="formtable">
  <tfoot>
    <tr style="background-color:transparent">
      <td nowrap="nowrap"><form method="get" action="" name="admin_form">
          <select name="month" class="custombox" style="width:120px">
            <?php echo $core->monthList();?>
          </select>
          <select name="year" class="custombox" style="width:70px">
            <?php echo $core->yearList(2013, strftime('%Y')); ?>
          </select>
          <input name="submit" value="Submit" type="submit" class="button-blue"/>
        </form></td>
      <td align="right" style="display:none;"><a href="javascript:void(0)" class="button-alt-sml delete">Empty Stats Table</a></td>
    </tr>
  </tfoot>
  <?php if(!$monthly):?>
  <tr>
    <td colspan="2"><div class="msgAlert"><span>Alert!</span>There are no statistics for selected Month/Year...</div></td>
  </tr>
  <?php else:?>
  <tr>
    <td></td>
    <td style="width:100%"><?php //echo $monthly['total_order'];?></td>
  </tr>  
  <?php endif;?>
</table>
<!--------------------------------------------------------This is div for Total orders graph,ends here------------------------------------------------------>

<!--------------------------------------------------------This is div for Total orders amount graph,starts here--------------------------------------------->
<?php if($monthly):?>
<script type="text/javascript">
  $(document).ready(function(){
	  
     var s1 = [
	<?php
		$res2 = '';
		foreach($stats as $row) {
			if(strlen($res2) > 0) {
				$res2 .= ",";
			}
			$res2 .= $row['total_order_amount'];
		}
		echo $res2;
		?>
	];
   
    var ticks = [
	<?php
		$res3 = '';
		$i=0;
		foreach($stats as $rep) {
			
			if(strlen($res3) > 0) {
				$res3 .= ",";
			}
			
			//$res3 .= "[". date("d", strtotime($rep['order_date'])) . "]";
			
			$res3 .= "['".$rep['order_date']."']";
			$i++;
			
			//$res3 .= $i;
		}
		?>
		<?php print $res3;?>
	];
    <?php unset($res2,$res3);?>
    plot1 = $.jqplot('chart2', [s1], {
	  seriesDefaults: {
	  renderer: $.jqplot.BarRenderer,
	  pointLabels:{show:true}
		  },
	  title: '<?php echo 'Monthly statistics for Orders Amount &rsaquo; '.doDate('%b',date("M", mktime(0, 0, 0, $core->month, 10))).' / '.$core->year;?>',
      series:[
        {label:'Total Orders Amount = <?php echo ($monthly['total_order_amount']) ? '$'.$monthly['total_order_amount'].' ' : ''; ?>'}
      ],
      legend: {
        show: true,
		location: 'ne'
      },
      axes: {
        xaxis: {
          renderer: $.jqplot.CategoryAxisRenderer,
          ticks: ticks
        }
      }
    });
  });
</script>
<div class="divider"><span></span></div>
<hr size="2" />
<div style="margin-top:10px;">
<h2>Latest Statics for Orders Amount</h2>
<div id="chart2"></div>
<?php endif;?>
<table cellspacing="0" cellpadding="0" class="formtable" style="display:none;">
  <tfoot>
    <tr style="background-color:transparent">
      <td nowrap="nowrap"><form method="get" action="" name="admin_form">
          <select name="month" class="custombox" style="width:120px">
            <?php echo $core->monthList();?>
          </select>
          <select name="year" class="custombox" style="width:70px">
            <?php echo $core->yearList(2012, strftime('%Y')); ?>
          </select>
          <input name="submit" value="Submit" type="submit" class="button-blue"/>
        </form></td>
      <td align="right" style="display:none;"><a href="javascript:void(0)" class="button-alt-sml delete">Empty Stats Table</a></td>
    </tr>
  </tfoot>
  <?php if(!$monthly):?>
  <tr>
    <td colspan="2"><div class="msgAlert"><span>Alert!</span>There are no statistics for selected Month/Year...</div></td>
  </tr>
  <?php else:?>
  <tr>
    <td></td>
    <td><?php //echo $monthly['total_order_amount'];?></td>
  </tr>
  <?php endif;?>
</table>
</div>
<div class="divider"><span></span></div>
<hr size="2" />
<!-------------------------------------------------------This is div for Total orders amount graph,ends here------------------------------------------------>

<!-------------------------------------------------------This is div for Payment method graph,starts here--------------------------------------------------->
<div>
<script type="text/javascript" src="js/graphps_chart.js"></script>
<script type="text/javascript" src="js/graph_chart_export.js"></script>

<div id="container_pie1" style="min-width: 310px; height: 400px; margin: 0 auto"></div>
<script type="text/javascript">

$(function () {
	
	 var cash_payment = '<?php echo $monthly['cash_payment'];  ?>';
     var online_payment = '<?php echo $monthly['online_payment'];  ?>';
	
	
    $('#container_pie1').highcharts({
        chart: {
            plotBackgroundColor: null,
            plotBorderWidth: null,
            plotShadow: false
        },
        title: {
            text: 'Payment Methods'
        },
        tooltip: {
    	    pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
        },
        plotOptions: {
            pie: {
                allowPointSelect: true,
                cursor: 'pointer',
                dataLabels: {
                    enabled: true,
                    color: '#000000',
                    connectorColor: '#000000',
                    format: '<b>{point.name}</b>: {point.percentage:.1f} %'
                }
            }
        },
        series: [{
            type: 'pie',
            name: 'Payment Method',
            data: [
                ['Cash(<?php echo $monthly['cash_payment'];?>])',<?php echo $monthly['cash_payment'];?>],
                ['Online(<?php echo $monthly['online_payment'];?>)',<?php echo $monthly['online_payment'];?>]                
            ]
        }]
    });
});
</script>
    
</div>
<!-------------------------------------------------------This is div for Payment method graph,ends here----------------------------------------------------->
<!-------------------------------------------------------This is div for Order Type ,starts here------------------------------------------------------------>
<div>

<div id="container_pie2" style="min-width: 310px; height: 400px; margin: 0 auto"></div>
<script type="text/javascript">

$(function () {
	
	 var pickup_order = '<?php echo $monthly['pickup_order'];  ?>';
     var delivery_order = '<?php echo $monthly['delivery_order'];  ?>';
	//alert(pickup_order+delivery_order);
	
    $('#container_pie2').highcharts({ 
        chart: {
            plotBackgroundColor: null,
            plotBorderWidth: null,
            plotShadow: false
        },
        title: {
            text: 'Order Type'
        },
        tooltip: {
    	    pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
        },
        plotOptions: {
            pie: {
                allowPointSelect: true,
                cursor: 'pointer',
                dataLabels: {
                    enabled: true,
                    color: '#000000',
                    connectorColor: '#000000',
                    format: '<b>{point.name}</b>: {point.percentage:.1f} %'
                }
            }
        },
        series: [{
            type: 'pie',
            name: 'Order Type',
            data: [
                ['Pick Up(<?php echo $monthly['pickup_order'];?>)',<?php echo $monthly['pickup_order'];?>],
                ['Delivery(<?php echo $monthly['delivery_order'];?>)',<?php echo $monthly['delivery_order'];?>]                
            ]
        }]
    });
});
</script>
    
</div>
<!------------------------------------------------------This is div for Order Type ,ends here--------------------------------------------------------------->

<!------------------------------------------------------This is div for Total Order by customer address city ,stats here------------------------------------>
<div>
<div id="containerByCity" style="min-width: 310px; height: 400px; margin: 0 auto"></div>


<script type="text/javascript">
$(function () {
    $('#containerByCity').highcharts({
        chart: {
            plotBackgroundColor: null,
            plotBorderWidth: null,
            plotShadow: false
        },
        title: {
            text: 'Customers by City Address'
        },
        tooltip: {
    	    pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
        },
        plotOptions: {
            pie: {
                allowPointSelect: true,
                cursor: 'pointer',
                dataLabels: {
                    enabled: true,
                    color: '#000000',
                    connectorColor: '#000000',
                    format: '<b>{point.name}</b>: {point.percentage:.1f} %'
                }
            }
        },
        series: [{
            type: 'pie',
            name: 'Total Customers',
            data: [ 
			
			     <?php  foreach($cust_byCity as $city_row){  ?>
					['<?php echo $city_row['city_name']. "(".$city_row['total_customers'].")";?>', <?php echo $city_row['total_customers'];?>],					
					 
				 <?php  }  ?>
              
               
            ]
        }]
    });
});
</script>
</div>
<!----------------------------------------------------This is div for Total Order by customer address city ,ends here-------------------------------------->


<!----------------------------------------------------This is div for New Customer ,starts here------------------------------------------------------------>
<div>
<div id="container_NewCustomers" style="min-width: 310px; height: 400px; margin: 0 auto"></div>

<script type="text/javascript">
$(function () {
	
        $('#container_NewCustomers').highcharts({
            title: {
                text: 'New Customers',
                x: -20 //center
            },
            subtitle: {
                text: '',
                x: -20
            },
            xAxis: {
				
				<!--categories: [s5]-->
				
               categories: [<?php
							$res6 = '';
							foreach($new_customer as $row6) {
								if(strlen($res6) > 0) {
									$res6 .= ",";
								}
								$res6 .= date("d",strtotime($row6['cus_register_date']));
							}
							echo $res6;
					?>]
            },
            yAxis: {
                title: {
                    text: 'Customers<?php //echo ($monthly['new_customers']) ? $monthly['new_customers'] : ""; ?>'
                },
                plotLines: [{
                    value: 0,
                    width: 1,
                    color: '#808080'
                }]
            },
            tooltip: {
                valueSuffix: ''
            },
            legend: {
                layout: 'vertical',
                align: 'right',
                verticalAlign: 'middle',
                borderWidth: 0
            },
            series: [{
                name: 'New Customers',
                data: [<?php
							$res4 = '';
							foreach($new_customer as $row5) {
								if(strlen($res4) > 0) {
									$res4 .= ",";
								}
								$res4 .= $row5['new_customers'];
							}
							echo $res4;
					?>]
            }]
        });
    });
    
</script>    
</div>
<!---------------------------------------------------This is div for New Customer,ends here--------------------------------------------------------------->
<!---------------------------------------------------This is div for Total Order by location ,starts here------------------------------------------------->
<div>
<div id="containerOrderByLocation" style="min-width: 310px; height: 400px; margin: 0 auto"></div>
<script type="text/javascript">
$(function () {
        $('#containerOrderByLocation').highcharts({
            chart: {
                type: 'bar'
            },
            title: {
                text: 'Total Orders By Locations'
            },
            subtitle: {
                text: null
            },
            xAxis: {
                categories: [<?php
								$res8 = '';
								foreach($order_byLocation as $row8) {
									if(strlen($res8) > 0) {
										$res8 .= ",";
									}
									$res8 .= "'".$row8['location_name']."'";
								}
								echo $res8;
							?>],
                title: {
                    text: null
                }
            },
            yAxis: {
                min: 0,
                title: {
                    text: 'Orders',
                    align: 'high'
                },
                labels: {
                    overflow: 'justify'
                }
            },
            tooltip: {
                valueSuffix: ' Orders'
            },
            plotOptions: {
                bar: {
                    dataLabels: {
                        enabled: true
                    }
                }
            },
            legend: {
                layout: 'vertical',
                align: 'right',
                verticalAlign: 'top',
                x: -40,
                y: 100,
                floating: true,
                borderWidth: 1,
                backgroundColor: '#FFFFFF',
                shadow: true
            },
            credits: {
                enabled: false
            },
            series: [{
                name: 'Month <?php if(isset($_GET['month']) && !empty($_GET['month'])) { echo $mon; }else { echo date("M");} ?>',
                data: [<?php
							$res7 = '';
							foreach($order_byLocation as $row7) {
								if(strlen($res7) > 0) {
									$res7 .= ",";
								}
								$res7 .= $row7['total_order'];
							}
							echo $res7;
					?>]
            }/*, {
                name: 'Year 2014',
                data: [133, 156, 947, 408, 6]
            }, {
                name: 'Year 2015',
                data: [973, 914, 4054, 732, 34]
            }*/]
        });
    });
</script>
</div>
<!--------------------------------------------------This is div for Total Order by location ,ends here---------------------------------------------------->
<!--------------------------------------------------This is div for Total Order by day of week ,stats here------------------------------------------------>
<div>
<div id="containerOrderByWeek" style="min-width: 310px; height: 400px; margin: 0 auto"></div>

<script type="text/javascript">
$(function () {
        $('#containerOrderByWeek').highcharts({
            chart: {
                type: 'bar'
            },
            title: {
                text: 'Total Orders By Day of Week'
            },
            subtitle: {
                text: null
            },
            xAxis: {
                categories: [<?php
								$res9 = '';
								foreach($order_byDayOfWeek as $row9) {
									if(strlen($res9) > 0) {
										$res9 .= ",";
									}
									$res9 .= "'".$row9['order_day']."'";
								}
								echo $res9;
							?>],
                title: {
                    text: null
                }
            },
            yAxis: {
                min: 0,
                title: {
                    text: 'Orders',
                    align: 'high'
                },
                labels: {
                    overflow: 'justify'
                }
            },
            tooltip: {
                valueSuffix: ' Orders'
            },
            plotOptions: {
                bar: {
                    dataLabels: {
                        enabled: true
                    }
                }
            },
            legend: {
                layout: 'vertical',
                align: 'right',
                verticalAlign: 'top',
                x: -40,
                y: 100,
                floating: true,
                borderWidth: 1,
                backgroundColor: '#FFFFFF',
                shadow: true
            },
            credits: {
                enabled: false
            },
            series: [{
                name: 'Month <?php if(isset($_GET['month']) && !empty($_GET['month'])) { echo $mon; }else { echo date("M");} ?>',
                data: [<?php
							$res10 = '';
							foreach($order_byDayOfWeek as $row10) {
								if(strlen($res10) > 0) {
									$res10 .= ",";
								}
								$res10 .= $row10['total_order'];
							}
							echo $res10;
					?>]
            }/*, {
                name: 'Year 2014',
                data: [133, 156, 947, 408, 6]
            }, {
                name: 'Year 2015',
                data: [973, 914, 4054, 732, 34]
            }*/]
        });
    });
</script>
</div>
<!--------------------------------------------------This is div for Total Order by day of week ,ends here------------------------------------------------->

