google.load("visualization", "1", {
	packages: ["corechart"]
});

google.setOnLoadCallback(drawCharts);

function drawCharts() {
	$.ajax({
	    type: "POST",
	    dataType : "json",
	    success: function(data) {
            $.each(data, function() {
                $.each(this, function(key, value) {
                	var dataTable = new google.visualization.DataTable();
    	    		
    	    		dataTable.addColumn('string', 'Result');
    	    		dataTable.addColumn('number', 'Total');
    	    		
    	    		dataTable.addRows([
    	    			['Passed', value.passed],
    	    			['Failed', value.failed],
    	    			['In Progress', value.in_progress],
    	    			['No Result', value.no_result]
    	    		]);
    	    		
    	    		var options = {
    	    			width: 400,
    	    			height: 200,
    	    			colors: ['#037D50', '#E60000', '#5E2750', '#4A4D4E']
    	    			// Non-VF Green, Vodafone Red, Vodafone Aubergine, Vodafone Charcoal
    	    		};
    	    		
    	    		var chart = new google.visualization.PieChart(document.getElementById(key + "-chart_div"));
    	    		chart.draw(dataTable, options);
                });
            });
	    },
	    error: function(xhr, status, errorThrown) {
	        console.log("Error: " + errorThrown);
	        console.log("Status: " + status);
	        console.dir(xhr);
	    }
	});
}