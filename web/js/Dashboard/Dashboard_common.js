function DashboardGeneralFunctions(){
	 $('.cause').editable();		 
	 $('.action').editable();	 
	 
	$(document).on('click','.editable-submit',function(){
		
		var counterTemp = $(this).closest('td').children('span').attr('id');
		var split = counterTemp.split("_");
		var counter = split[1];
		
		if(split[2])
			var kpi_type = split[2];
		else
			var kpi_type = '';
		
		var column = $(this).closest('td').children('span').attr('column');
				
		var value = $('.input-large').val();	
		
		var KPIID = $("#KPIHidden").val();
		
		var ProjectID = $("#ProjectIDHidden_"+counter).val();
		
		if(value != ""){			
			AjaxUpdateCauseActionDetails(column, value, KPIID, ProjectID, kpi_type);			
		}
	});	
	
	
	/* Open a page to show KPI List from Dashboard */
	
	$("#kpidata_inputs").on('click', function(){
		window.location.href =  $("#KPIListPath").attr("data-path");
	});
	
	/* */
	
	
	/* Loads the Cause Action Popup in a DIV */
	
	$(".viewcause").on('click', function(){	
		
		/*var KPID =  '';	
		var Month = '';
		var Cause = '';
		var Action = '';
		$("#cause_popup").val(Cause);
		$("#action_popup").val(Action);
		*/
		
		var tempValue = $(this).attr('id');
		var split = tempValue.split("_");
		var KPID =  split[0];	
		var Month = split[1];
		var Cause = split[2];
		var Action = split[3];
		
		alert(KPID+"==="+Month);
		
		
	
		/*
		$("#cause").val(Cause);
		$("#action").val(Action);
		*/
		
		$("#causeaction_dialog").css('display', '');
		
		
		
					
		var path = $("#causeaction_dialog").attr("data-path");	
		
		$("#causeaction_dialog").load(path).dialog({
			  modal : true,	
		      width : 700,
		      height : 600
		});	
		
		$("#kpiid").val(KPID);
		//$("#month").val(Month);
		
		
		$("#cause").val();	
		$("#action").val();	
		
		alert($("#kpiid").val());
		//alert($("#month").val());
		
	});
	
	/*  */
	
	
	/*$(document).ready(function(){
		$("#frmCauseActionSubmit").validate();
	});
	
	$.validator.setDefaults({
		submitHandler: function() {				
			submitKPICauseAction();
			//location.reload();
		}
	});
	*/
	
	
	
	$("#causeaction_submit").on('click', function(){		
			
		var cause = $("#cause").val();
		var action = $("#action").val();
		
		var kpiid = $("#kpiid").val();	
		var month = $("#month").val();
		
		alert(cause+"=="+action+"=="+kpiid+"==="+month)	
		
		var Data = { causeInsert : cause, actionInsert : action, kpiid : kpiid, month : month, action : 'updateMonthlyCauseAction' };		
		
		$.ajax({		
			type : 'POST',
			dataType: 'JSON',
			data : Data, 
			success: function(data) {				
					alert(data);			
					
					$( "#dialog" ).dialog({
						   modal: true,
					       autoOpen: false,		
					       height: 200,
						   width: 400,
					       buttons: {
					          Ok : function(){
					        	  $(this).dialog("close");
					        	  $("#causeaction_dialog").dialog("close");
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
	});
	
	
	/* Datepicker for Darshboard Search */
	
	$("#Month").datepicker({
		dateFormat: "M-y",
		changeMonth: true,
	    changeYear: true,
	    showButtonPanel: true,	   
	    onClose: function(dateText, inst) { 
	       $(this).datepicker('setDate', new Date(inst.selectedYear, inst.selectedMonth, 1));
	    }
		
	});
	
	 $(".monthPicker").focus(function () {
	      $(".ui-datepicker-calendar").hide();	        
	 });
	
	/* === */ 
	

}
function submitKPICauseAction(){
	var cause = $("#cause_popup").val();
	var action = $("#action_popup").val();
	
	var kpiid = $("#kpiid_popup").val();	
	var month = $("#month_popup").val();
	
	alert(cause+"=="+action+"=="+kpiid+"==="+month)	
}


/*function AjaxUpdatePlanActionDetails(value, KPIID, Month){
	
	var Data = { newVal : Month, KPIID : KPIID, Month : Month, action : 'updateCauseAction' };
	*/
function AjaxUpdateCauseActionDetails(column, newVal, KPIID, ProjectID, kpi_type){
	
	var Data = { column : column, newVal : newVal, KPIID : KPIID, ProjectID : ProjectID, kpi_type : kpi_type, action : 'updateCauseAction' };
	
	
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

