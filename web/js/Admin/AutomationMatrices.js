function calendarEvents(){
	$( "#from" ).datepicker({
			dateFormat: "dd/M/y" 			
	});
	
	$( "#to" ).datepicker({
			dateFormat: "dd/M/y" 			
	});	
	
	/*$('#from').on('input',function(e){
		if($("#from").val() != '' && $("#to").val() != '') {
			if($(this).data("lastval")!= $(this).val()){
			     $(this).data("lastval",$(this).val());
			     //change action
			     alert($(this).val());  
			 };
			 
			$("#showchart").attr("disabled", false);
		} else
			$("#showchart").attr("disabled", true);
		 
	});
	
	//$('#to').on('input',function(e){
	$('#to').on('input',function(e){
		if($("#from").val() != '' && $("#to").val() != '') {
			$("#showchart").attr("disabled", false);
		} else
			$("#showchart").attr("disabled", true);
		 
	});
	*/
	
	 $("#showchart").click(function(){
		 	drawChart();
	 }); 
}

function init() {	
	// Load the Visualization API and the corechart package.
    google.charts.load('current', {'packages':['corechart', 'bar']});    
    // Set a callback to run when the Google Visualization API is loaded.   
    
    google.charts.setOnLoadCallback(drawChart);    
    //google.charts.setOnLoadCallback(drawLineCharts);
}
	
function drawChart() {
	var Data = { from : $("#from").val(), to : $("#to").val()};
	$.ajax({
	    type: "POST",
	    data : Data,
	    dataType : "json",
	    success: function(data) {
	    	var flag = 0;
	    	var exeType;
            $.each(data, function(key, value) {
            	
            	if(key == 'env_barchartdata'){
            		//console.log(value);
            		var barchartdataArr = value.DATECOUNTS;
            	}
            	
            	if(value == 'Siebel'){
            		exeType = value;
            	}
            	else if(value == 'Oracle'){
            		exeType = value;            		
            	}
            	else if(value == 'Fusion'){
            		exeType = value;
            	}
            	
            	var monthArr = new Array();
            	var exeCountArr = new Array();
            	
            	monthArr = value.monthArr;
            	exeCountArr = value.exeCountArr;            	
            	
            	
            	if(exeType != 'Siebel' && exeType != 'Fusion' && flag != 1 && key == 'env_totals'){
            	    var dataTable = new google.visualization.DataTable();
		    		dataTable.addColumn('string', 'Result');
		    		dataTable.addColumn('number', 'Total');   		
		   		
		    		dataTable.addRows([
		    			['REGRESSION', parseInt(value.REGRESSION)],
		    			['SHAKEDOWN', parseInt(value.SHAKEDOWN)],	    			
		    		]);	    		  		
	    		
		    		var options = {
		    			width: 450,
			    		height: 300,
		    			colors: ['#007C92', '#5E2750'],
		    			pieHole: 0.2,
		    		};	   
		    		
		    		var chart = new google.visualization.PieChart(document.getElementById('env_totals'));
	    			chart.draw(dataTable, options);  
	    			
		    		flag = 1;
            	}
            	
            	else if(exeType != 'Oracle' && exeType != 'Fusion' && flag != 1 && key == 'env_totals'){                	
	            	var dataTable = new google.visualization.DataTable();
		    		dataTable.addColumn('string', 'Result');
		    		dataTable.addColumn('number', 'Total');   		
	
		    		
		    		dataTable.addRows([
		    			['REGRESSION', parseInt(value.REGRESSION)],
		    			['SHAKEDOWN', parseInt(value.SHAKEDOWN)],
		    			['TESTDATA(CREATION)', parseInt(value.TESTDATA)],
		    			['PRODUCTION(CVT)', parseInt(value.CVT)],
		    		]);
		    		
	    				    		
		    		var options = {
		    			width: 450,
			    		height: 300,
		    			colors: ['#5E2750', '#007C92', 'green', '#EB9700'],		    			
		    			pieHole: 0.2,
		    		};	    		
		    		
		    		var chart = new google.visualization.PieChart(document.getElementById('env_totals'));
		    		chart.draw(dataTable, options);  
		    		
		    		flag = 1;
	
                }
            	else if(exeType != 'Siebel' && exeType != 'Oracle' && flag != 1 && key == 'env_totals'){
            		
            		var dataTable = new google.visualization.DataTable();
		    		dataTable.addColumn('string', 'Result');
		    		dataTable.addColumn('number', 'Total');  		
	
		    		
		    		dataTable.addRows([
		    			['REGRESSION', parseInt(value.REGRESSION)],
		    			['ALERT TESTING', parseInt(value.ALERT)],		    			
		    		]);    	
	    		
	
		    		var options = {
		    			width: 450,
		    			height: 300,
		    			colors: ['#5E2750', '#007C92'],		    			
		    			pieHole: 0.4,
		    		};		
		    		var chart = new google.visualization.PieChart(document.getElementById('env_totals'));
	    			chart.draw(dataTable, options);  
	    			
		    		flag = 1;
            	}	              	
            	/*if(key == 'env_totals'){
	    			var chart = new google.visualization.PieChart(document.getElementById(key));
	    			chart.draw(dataTable, options);  
	    		}*/
            	
            	if($("#from").val() != '' && $("#to").val() != ''){
	            	if(key == 'env_barchartdata'){            		
	            		drawBarChart(barchartdataArr, exeType, 'env_barchartdata');
		    		}
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

function drawBarChart(barchartdataArr, exeType, key){	
	//console.log(barchartdataArr);

	var chartData = new Array();
	
	$.each(barchartdataArr, function (k, v){		
		chartData.push(v);		
	})	

	var dataTable = new google.visualization.DataTable();
	
	dataTable.addColumn('string', 'Period');
	
	if(exeType == 'Oracle'){
		dataTable.addColumn('number', 'SHAKEDOWN');
		dataTable.addColumn('number', 'REGRESSION');
	}	
	else if(exeType == 'Siebel'){
		dataTable.addColumn('number', 'SHAKEDOWN');
		dataTable.addColumn('number', 'REGRESSION');
		dataTable.addColumn('number', 'CVT');
		dataTable.addColumn('number', 'TEST DATA');		
	}
	else if(exeType == 'Fusion'){
		dataTable.addColumn('number', 'ALERT TESTING');
		dataTable.addColumn('number', 'REGRESSION');
	}
		
	var dateperiod;
	var executiontype;
	for(var i=0;i<chartData.length;i++){
			//console.log(chartData[i]);
			dateperiod = chartData[i][0].replace("%"," "); // EXTRACT MONTH FROM THE FIELD			
			
			if(exeType == 'Oracle'){
				dataTable.addRows([
					[dateperiod, parseInt(chartData[i]['SHAKEDOWN']), parseInt(chartData[i]['REGRESSION'])],				
				]);	
			}
			else if(exeType == 'Siebel'){
				dataTable.addRows([
					[dateperiod, parseInt(chartData[i]['SHAKEDOWN']), parseInt(chartData[i]['REGRESSION']), 
					 parseInt(chartData[i]['CVT']), parseInt(chartData[i]['TESTDATA'])],				
				]);		
			}
			else if(exeType == 'Fusion'){
					dataTable.addRows([
						[dateperiod, parseInt(chartData[i]['ALERTTESTING']), parseInt(chartData[i]['REGRESSION'])],				
					]);		
			}	
		
	}   		
	
	if(exeType == 'Oracle'){
		 var options = {
		   colors: ['#007C92','#5E2750'],
		   'title' : 'Test Automation Execution Statistics',
		   chart: {
			 height : 800,	     		
			 width : 800
		   },
		   bars: 'vertical' // Required for Material Bar Charts.
		 };
	}  else if(exeType == 'Siebel'){
		 var options = {
		   colors: ['#007C92','#5E2750', '#EB9700', 'green'],
		   chart: {
		     height : 800,	     		
		     width : 800
		   },
		   bars: 'vertical' // Required for Material Bar Charts.
		 };	
	}
	 else if(exeType == 'Fusion'){
		 var options = {
		   colors: ['#007C92', '#5E2750'],		   
		   bars: 'vertical' // Required for Material Bar Charts.
		 };	
	}
	
	
	var chart = new google.charts.Bar(document.getElementById(key));
	chart.draw(dataTable, options);	                                              
}
