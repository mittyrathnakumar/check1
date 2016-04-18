function init(app) {	
	// Load the Visualization API and the corechart package.
    google.charts.load('current', {'packages':['corechart']});

    // Set a callback to run when the Google Visualization API is loaded.
    
    if(app == 'Siebel')
    	google.charts.setOnLoadCallback(drawSiebelCharts);    
    else if(app == 'Oracle')
    	google.charts.setOnLoadCallback(drawOracleCharts);
    else if(app == 'Tallyman')
    	google.charts.setOnLoadCallback(drawTallymanCharts);
}
	
function drawSiebelCharts() {	
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
	    			['In Progress', parseInt(value.in_progress)],
	    			['No Result', parseInt(value.no_result)],	    			
	    		]);
	    		dataTable.addRows([
	    			['Core Orders', parseInt(value.coreOrder)],
	    			['TBUI Orders', parseInt(value.tbUIOrder)],
	    			['Retreivals', parseInt(value.retreivals)],
	    			['Open UI Orders', parseInt(value.openUIOrder)],	    			
	    		]);
	    		
	    		dataTable.addRows([
	    			['Total Orders', parseInt(value.orderPassed)],
	    			['Total Failed', parseInt(value.orderFailed)],
	    			['Total No Result', parseInt(value.orderNoRun)],
	    			['Total in Progress', parseInt(value.orderProgressed)],	    			
	    		]);
	    		dataTable.addRows([
	    			['Total Retrieved', parseInt(value.orderRetreivedPassed)],
	    			['Total Failed', parseInt(value.orderRetreivedFailed)],
	    			['Total In Progress', parseInt(value.orderRetreivedProgress)],
	    			['Total No Result', parseInt(value.orderRetreivedNoRun)],	    			
	    		]);		    		
    		
	    		// Set chart options
	    		var options = {
	    			width: 400,
	    			height: 200,
	    			colors: ['#037D50', '#E60000', '#5E2750', '#4A4D4E'],
	    			is3D: true,
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

function drawOracleCharts() {	
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
	    		console.log(parseInt(value.passed));
				dataTable.addRows([
					['Passed', parseInt(value.passed)],
					['Failed', parseInt(value.failed)],
					['In Progress', parseInt(value.in_progress)],
					['No Result', parseInt(value.no_result)],	    			
				]);
				dataTable.addRows([
					['Accounts Payable', parseInt(value.totAccPay)],
					['Accounts Receivable', parseInt(value.totAccRec)],
					['Cash Management', parseInt(value.totCSHMGM)],
					['Fixed Assets', parseInt(value.totFIXASS)],
					
					['General Ledger', parseInt(value.totGENLED)],
					['HR & Payroll', parseInt(value.totHRPAY)],
					['Inventory', parseInt(value.totINV)],
					['OTL', parseInt(value.totOTL)],
					['Oracle Purchasing', parseInt(value.totORPUR)],
					['Order Management', parseInt(value.totORMAN)],
					['Project Accouting', parseInt(value.totPA)],
				]);
				
				var options = {
	    			width: 450,
	    			height: 250,
	    			colors: ['#037D50', '#E60000', '#5E2750', '#4A4D4E','#FA5858', '#FE9A2E', '#F7FE2E', '#9AFE2E', '#B43104', '#2EFEF7', '#2E9AFE', '#2E2EFE', '#9A2EFE', '#FE2EF7', '#949FB1']
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
function drawTallymanCharts() {	
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
					['In Progress', parseInt(value.in_progress)],
					['No Result', parseInt(value.no_result)],	    			
				]);
	    		
				dataTable.addRows([					
					['Tallyman - Siebel', parseInt(value.totTallSieb)],
					['Siebel - Tallyman', parseInt(value.totSiebTall)],
				]);
				
				
				var options = {
	    			width : 450,
	    			height : 250,
	    			chartArea : { left: 80 },
	    			colors : ['#037D50', '#E60000', '#5E2750', '#4A4D4E','#FA5858', '#2E9AFE']
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
