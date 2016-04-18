function init() {	
	// Load the Visualization API and the corechart package.
    google.charts.load('current', {'packages':['corechart', 'bar']});    

    // Set a callback to run when the Google Visualization API is loaded.       
    google.charts.setOnLoadCallback(drawChart);
}
	
function drawChart() {	
	$.ajax({
	    type: "POST",
	    dataType : "json",
	    success: function(data) {
            $.each(data, function(key, value) {            	
            	
            	var assignedresultArr = new Array();
            	var completedresultArr = new Array();
            	var datesArr = new Array();
            	
            	assignedresultArr = value.assignedresult;
            	completedresultArr = value.completedresult;
            	datesArr = value.testdates;            	
            	
            	// Create the data table.
            	var dataTable = new google.visualization.DataTable();
	    		dataTable.addColumn('string', 'Result');
	    		dataTable.addColumn('number', 'Total');
	    		
	    		dataTable.addRows([
	    			['Completed', parseInt(value.completed)],
	    			['Not Started', parseInt(value.notstarted)],	    				    
	    			['In Review', parseInt(value.inreview)],
	    			['In Progress', parseInt(value.inprogress)],
	    			['Descoped', parseInt(value.descoped)],
	    			['Blocked', parseInt(value.blocked)],
	    			
	    		]);	    		  		
    		
	    		// Set chart options
	    		var options = {
	    			width: 400,
	    			height: 200,	    		
	    			colors: ['#037D50', '#E60000', '#5E2750', 'orange', 'black', '#800080'],
	    			is3D: true,

	    			// Non-VF Green, Vodafone Red, Vodafone Aubergine, Vodafone Charcoal	    		
	    		
	    		};	    		

    			var chart = new google.visualization.PieChart(document.getElementById(key));
    			chart.draw(dataTable, options);
    			
    			// FUNCTION CALL TO DRAW BAR CHART
	    		drawBarChart(assignedresultArr, completedresultArr, datesArr, 'project_barchart');
	    		
            });
	    },
	    error: function(xhr, status, errorThrown) {
	        console.log("Error: " + errorThrown);
	        console.log("Status: " + status);
	        console.dir(xhr);
	    }
	});	
}

function drawBarChart(aArr, cArr, dateArr, key){	

	var arg = new Array();
	
	var dArr = new Array();
	var assignedArr = new Array();
	var comArr = new Array();	
	
	
	$.each(dateArr, function (k, v){		
		dArr.push(v);		
	})
	
	$.each(aArr, function (k, v){		
		assignedArr.push(v);		
	})
	
	$.each(cArr, function (k, v){		
		comArr.push(v);		
	})
	
	var dataTable = new google.visualization.DataTable();
	
	dataTable.addColumn('string', 'Date');
	dataTable.addColumn('number', 'Assigned');   
	dataTable.addColumn('number', 'Completed');	

	for(var i=0;i<dArr.length;i++){
		dataTable.addRows([
			[dArr[i], parseInt(assignedArr[i]), parseInt(comArr[i])],		
			
		]);	  
	}	
	
	var options = {
	   colors: ['#5E2750','#037D50'],
	   chart: {
	     title: '',
	     subtitle: 'Test Case Performance',	     
	     width : '100%',
	     height : '100%'
	   },
	   bars: 'vertical' 
	};	
	
	var chart = new google.charts.Bar(document.getElementById(key));
	chart.draw(dataTable, options);	                                               
}
