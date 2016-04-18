function init() {	
	// Load the Visualization API and the corechart package.
    google.charts.load('current', {'packages':['corechart']});

    // Set a callback to run when the Google Visualization API is loaded.    
   
   	google.charts.setOnLoadCallback(drawCharts);    
}
	
function drawCharts() {	
	$.ajax({
	    type: "POST",
	    dataType : "json",
	    success: function(data) {
            $.each(data, function(key, value) {
            	console.log(key + value);
            	// Create the data table.
            	var dataTable = new google.visualization.DataTable();
	    		dataTable.addColumn('string', 'Result');
	    		dataTable.addColumn('number', 'Total');		    		

	    		dataTable.addRows([
	    			['Passed', parseInt(value.passed)],
	    			['Failed', parseInt(value.failed)],	    			   			
	    		]);
	    		
	    		// Set chart options
	    		var options = {
	    			width: 400,
	    			height: 200,
	    			//colors: ['#037D50', '#E60000', '#5E2750', '#4A4D4E']
	    			colors: ['#037D50', '#E60000']
	    			// Non-VF Green, Vodafone Red, Vodafone Aubergine, Vodafone Charcoal
	    		};
	    		
    			var chart = new google.visualization.PieChart(document.getElementById(key));
    			chart.draw(dataTable, options);	    		
            });
	    },
	    error: function(xhr, status, errorThrown) {
	        console.log("Error: " + errorThrown);
	        console.log("Status: " + status);
	        console.dir(xhr);
	    }
	});
}
