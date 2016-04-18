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
            	
            	// Create the data table.
            	var dataTable = new google.visualization.DataTable();
	    		dataTable.addColumn('string', 'Result');
	    		dataTable.addColumn('number', 'Total');		    		

	    		dataTable.addRows([
	    			['Complete', parseInt(value.complete)],
	    			['Delayed', parseInt(value.delay)],	    				    
	    			['New', parseInt(value.newrequest)],
	    			['In Progress', parseInt(value.inprogress)],
	    		]);	    		  		
    		
	    		// Set chart options
	    		var options = {
	    			width: 400,
	    			height: 200,
	    			colors: ['#037D50', '#E60000', '#5E2750', '#4A4D4E'],
	    			is3D: true,

	    			// Non-VF Green, Vodafone Red, Vodafone Aubergine, Vodafone Charcoal	    		
	    		
	    		};
	    		
	    		if(key == 'tdm_requeststatus'){
	    			var chart = new google.visualization.PieChart(document.getElementById(key));
	    			chart.draw(dataTable, options);
	    		}

    			var data = google.visualization.arrayToDataTable([
                      ['Quater', 'Count'],
                      ['Q1',  parseInt(value.Q1)],
                      ['Q2',  parseInt(value.Q2)],
                      ['Q3',  parseInt(value.Q3)],
                      ['Q4',  parseInt(value.Q4)]
                    ]);
    			
				var year = new Date().getFullYear();
				
                var options = {                      
                  vAxis: { minValue: 0, maxValue: 100 },
                  pointSize: 8,
                  title : 'For year '+year,
                  curveType: 'function',
                  legend: { position: 'bottom' }
                };
             
               if(key == 'tdm_yearlystatus'){
	   			   var chart = new google.visualization.LineChart(document.getElementById(key));
	   			   chart.draw(data, options);
               }
	    		
            });
	    },
	    error: function(xhr, status, errorThrown) {
	        console.log("Error: " + errorThrown);
	        console.log("Status: " + status);
	        console.dir(xhr);
	    }
	});
}
