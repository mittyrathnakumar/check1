/**
 *	COMMON FUNCTION TO DRAW A GOOGLE CHART 
 */

function drawChart() {
	var a = $("#totalpass").val();
	var b = $("#totalfail").val();
	
	alert(a+'===='+b);
	//var url = 'http://localhost/ART/web/app_dev.php/getFusionExecutionChartResults/'+pageUrl+'/'+params;
	//$.ajax({
		//url: 'http://localhost/ART/web/app_dev.php/'+pageUrl+'/'+params,
	  // // type: "POST",
	  //  dataType: 'JSON', 
	  //  success: function(data) {
	    			//alert(data);
          //  $.each(data, function() {
               // $.each(this, function(key, value) {
                	var data = new google.visualization.DataTable();
    	    		
                	data.addColumn('string', 'Result');
                	data.addColumn('number', 'Total');
    	    		    	    		
    	    		data.addRows([
    	    			['Passed', 100],
    	    			['Failed', 50]
    	    			//['In Progress', ''],
    	    			//['No Result', value.no_result]
    	    		]);
    	    		
    	    		var options = {
    	    			width: 400,
    	    			height: 200,
    	    			//colors: ['#037D50', '#E60000', '#5E2750', '#4A4D4E']
    	    			colors: ['#037D50', '#E60000']
    	    			
    	    			// Non-VF Green, Vodafone Red, Vodafone Aubergine, Vodafone Charcoal
    	    		};
    	    		
    	    		var chart = new google.visualization.PieChart(document.getElementById('chart_div_execution'));
    	    		chart.draw(data, options);
               //});
            //});
	   /* },
	    error: function(xhr, status, errorThrown) {
	        console.log("Error: " + errorThrown);
	        console.log("Status: " + status);
	        console.dir(xhr);
	    }
	});*/
}


function drawCharts() {
    // Create the data table.
	  var data = new google.visualization.DataTable();
      data.addColumn('string', 'Topping');
      data.addColumn('number', 'Slices');
      data.addRows([
        ['Mushrooms', 3],
        ['Onions', 1],
        ['Olives', 1],
        ['Zucchini', 1],
        ['Pepperoni', 2]
      ]);

      // Set chart options
      var options = {'title':'How Much Pizza I Ate Last Night',
                     'width':400,
                     'height':300};

    // Instantiate and draw our chart, passing in some options.
    var chart = new google.visualization.PieChart(document.getElementById('chart_div_execution'));
    chart.draw(data, options);
  }