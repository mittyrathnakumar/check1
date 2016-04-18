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
	var dateToday = new Date();
	
	/* $(document).ready(function(){
		$("#TDMRequestTestData").validate();
	 });*/
	
	
	 
	/* $.validator.setDefaults({
		submitHandler: function() {
			
			alert('in===');
			submitTestRequestData();
			//$("#TDMRequestTestData").submit();
		}
	});*/
	 
	 $(function() {	    
	    $( "#dateneeded" ).datepicker({
			dateFormat: "dd-M-yy",
			minDate: dateToday
		});
	 });  
}
