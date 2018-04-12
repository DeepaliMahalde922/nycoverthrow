  google.load("visualization", "1", {packages:["corechart"]});
  google.load('visualization', '1', {packages:['gauge']});
  google.load('visualization', '1', {packages:['bar']});
  google.load("visualization", "1", {packages:["geochart"]});

  google.setOnLoadCallback(function() { this_week_Sales_Amount(weekgraph); });
  google.setOnLoadCallback(function() { Today_Order_Count_gauge(todaygraph); });
  google.setOnLoadCallback(function() { Top_products(maxsale); });
  google.setOnLoadCallback(function() { sale_summary(salesorderamt); });
  google.charts.load('current', {callback: sale_summary, packages: ['controls', 'corechart']});
  google.setOnLoadCallback(function() { drawRegionsMap(topBilling); });


  function drawRegionsMap(topBilling) {
    var options = {};

        var chart = new google.visualization.GeoChart(document.getElementById('top_billing_country'));

        chart.draw(topBilling, options);
  }

  function Today_Order_Count_gauge(todaygraph) {

    var options = {
      width: 300, 
      height: 150,
      redFrom: 90, 
      redTo: 100,
      yellowFrom:75, 
      yellowTo: 90,
      minorTicks: 5
    };

    new google.visualization.Gauge(
    document.getElementById('today_order_count_meter_gauge')).draw(todaygraph,options);
  
  }
  
  function this_week_Sales_Amount(weekgraph) {
  
    var options = {
		  height: 400,
      is3D: false,
      pieHole: 0.4
    };

    new google.visualization.PieChart(
    document.getElementById('last_7_days_sales_order_amount')).draw(weekgraph,options);
  
  }
  
  function Top_products(maxsale) {
    var options = {
          is3D: true,
		  width:350,
		  height:200
        };
   new google.visualization.PieChart(
   document.getElementById('top_product_pie_chart')).draw(maxsale,options);
  }

  function sale_summary(salesorderamt) {
    var options = {
      width: 700,
      height: 300,
      series: {
        0: { axis: 'amount' }, 
        1: { axis: 'count' } 
      },

      axes: {
        y: {
          distance: {label: 'Amount'}, 
          brightness: {side: 'right', label: 'Count'} 
        }
      }            
    };

    var chart = new google.visualization.ChartWrapper({
      chartType: 'Bar',
      containerId: 'sales_order_count_value',
      dataTable: salesorderamt,
      options: options
    });

    chart.draw();    
  }