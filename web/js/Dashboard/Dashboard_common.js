function DashboardGeneralFunctions(){
	 $('.cause').editable();		 
	 $('.action').editable();	 
	 
	$(document).on('click','.editable-submit',function(){
		
		var counterTemp = $(this).closest('td').children('span').attr('id');
		var split = counterTemp.split("_");
		var KPIID = split[1];
		var Month = split[2];
				
		var value = $('.input-large').val();				
		
		if(value != ""){											
			 AjaxUpdatePlanActionDetails(value, KPIID, Month);				
		}
	});
}
function AjaxUpdatePlanActionDetails(value, KPIID, Month){
	
	var Data = { newVal : Month, KPIID : KPIID, Month : Month, action : 'updateCauseAction' };
	
	$.ajax({		
		type : 'POST',
		dataType: 'JSON',
		data : Data, 
		success: function(data) {				
				$( "#dialog" ).dialog({
					   modal: true,
				       autoOpen: false,		
				       height: 200,
					   width: 400,
				       buttons: {
				          Ok : function(){
				        	  $(this).dialog("close");
				          }
				       } 						       
				 });
				
				htmltext = "<div class='small'>"+data+"</div>";
				
				$( "#dialog" ).html(htmltext);
				$( "#dialog" ).dialog( "open" );
        },
        error: function(jqXHR, textStatus, errorThrown) {
        	  console.log(textStatus, errorThrown);
        }
	});
}

/*
function OnTDBlurEditValues(){	
	
	$("td[contenteditable=true]").blur(function(){		
					
		var id = $(this).attr("id");
		
		var split = id.split("_");	
		var column = split[0];
		var counter = split[1];
		
		var KPIID = $("#KPIHidden").val();
		var newVal = $(this).text();		
				
		var ProjectID = $("#ProjectIDHidden_"+counter).val();		
		
		if(column != ""){											
			 AjaxUpdatePlanActionDetails(column, newVal, KPIID, ProjectID);				
		}
		
	});
}


function AjaxUpdatePlanActionDetails(column, value, KPIID, ProjectID){
	
	var Data = { column : column, newVal : newVal, KPIID : KPIID, ProjectID : ProjectID, action : 'updateCauseAction' };
	
	$.ajax({		
		type : 'POST',
		dataType: 'JSON',
		data : Data, 
		success: function(data) {				
				$( "#dialog" ).dialog({
					   modal: true,
				       autoOpen: false,		
				       height: 200,
					   width: 400,
				       buttons: {
				          Ok : function(){
				        	  $(this).dialog("close");
				          }
				       } 						       
				 });
				
				htmltext = "<div class='small'>"+data+"</div>";
				
				$( "#dialog" ).html(htmltext);
				$( "#dialog" ).dialog( "open" );
        },
        error: function(jqXHR, textStatus, errorThrown) {
        	  console.log(textStatus, errorThrown);
        }
	});
}
*/
function init() {	
	// Load the Visualization API and the corechart package.
	google.charts.load('current', {'packages':['corechart', 'bar']});   

    // Set a callback to run when the Google Visualization API is loaded.   
    
    google.charts.setOnLoadCallback(drawCharts);   
    
}
	
function drawCharts() {	
	$.ajax({
	    type: "POST",
	    dataType : "json",
	    success: function(data) {
            $.each(data, function(key, value) {
            	
            	//console.log(value);
            	var dataTable = new google.visualization.DataTable();
            	
            	dataTable.addColumn('string', 'Month');
            	dataTable.addColumn('number', 'Total Defects');   
            	dataTable.addColumn('number', 'Rejected/Withdrawn Defects');	

            	/*for(var i=0;i<3i++){
            		dataTable.addRows([
            			[dArr[i], parseInt(assignedArr[i]), parseInt(comArr[i])],		
            			
            		]);	  
            	}*/	
            	dataTable.addRows([
            		    			['Mar', 53, 10],
            		    			['Apr', 315, 15],	    				    
            		    			['May', 222, 20],            		    			
            		    		]);	  
            	
            	var options = {
            	   colors: ['#e08938','#345fbc'],
            	   chart: {
            	     title: '',   
            	     subtitle : 'KPI - Effectiveness of test process',
            	     width : '100%',
            	     height : '100%'
            	   },
            	   bars: 'vertical' 
            	};	
            	
            	var chart = new google.charts.Bar(document.getElementById('monthly_kpi_result'));
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

function tableToExcel(table, name){
	  var uri = 'data:application/vnd.ms-excel;base64,'
			, template = '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40"><head><!--[if gte mso 9]><xml><x:ExcelWorkbook><x:ExcelWorksheets><x:ExcelWorksheet><x:Name>{worksheet}</x:Name><x:WorksheetOptions><x:DisplayGridlines/></x:WorksheetOptions></x:ExcelWorksheet></x:ExcelWorksheets></x:ExcelWorkbook></xml><![endif]--></head><body><table>{table}</table></body></html>'
			, base64 = function(s) { return window.btoa(unescape(encodeURIComponent(s))) }
			, format = function(s, c) { return s.replace(/{(\w+)}/g, function(m, p) { return c[p]; }) }
		  return function(table, name) {
			if (!table.nodeType) table = document.getElementById(table)
			var ctx = {worksheet: name || 'Worksheet', table: table.innerHTML}
			window.location.href = uri + base64(format(template, ctx))
		  }
}

