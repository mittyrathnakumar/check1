// THIS FUNCTION IS NOT IN USE, WILL REMOVE LATER WHILE FINAL TESTING

function submitTestRequestData(){
		if(confirm('Submit the request details ??')){			
			var formData = {name : $("#name").val(), employeeid : $("#employeeid").val(), email : $("#email").val(),
					mobile : $("#mobile").val(), project : $("#project").val(), release : $("#release").val(),
					environment : $("#environment").val(), dateneeded : $("#dateneeded").val(), 
					connections : $("#connections").val(), fileToUpload : $("#fileToUpload").val()};	
			
			
		    $.ajax({
		    	url: 'http://localhost/ART/web/app_dev.php/TestAssistingTools/TDMRequestFormSubmit',
		        type: 'post',
		        dataType: 'json', 
		        data: formData,
		        success: function(data) {
		        	alert('TDM Request details submitted !!!');		        	
		        	window.location.reload();
		        	
		        },
		        error: function(jqXHR, textStatus, errorThrown) {
		        	  console.log(textStatus, errorThrown);
		        }
		    });
		} else {
			return false;
		}
}

function formGeneralFunctions(){	
	$(document).ready(function(){
		$("#frmAddEditProject").validate();
	}); 
	 
	$.validator.setDefaults({
		submitHandler: function() {
			AddEditProject();
		}
	});	 
	
    $("#EstimatedProdLiveDate").datepicker({
		dateFormat: "dd-M-y",
		numberOfMonths: 3		
	});
    
    $("#ActualProdLiveDate").datepicker({
		dateFormat: "dd-M-y",
		numberOfMonths: 3		
	});
    
    $("#EngagementDate").datepicker({
		dateFormat: "dd-M-y",
		numberOfMonths: 3
	});
    
    $("#Gate1EstimationDeliveryDate").datepicker({
		dateFormat: "dd-M-y",
		numberOfMonths: 3
	});    
    
    $("#DeliveryDate").datepicker({
		dateFormat: "dd-M-y",
		numberOfMonths: 3
	});
    
    $("#SignoffDate").datepicker({
		dateFormat: "dd-M-y",
		numberOfMonths: 3
	});
       
    
}
