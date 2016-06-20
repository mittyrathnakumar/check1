function ITSMDefectsFunctions(){
	 $('.p1p2').editable();		 
	 $('.p3p4').editable();	 
	 
	$(document).on('click','.editable-submit',function(){
		
		var counterTemp = $(this).closest('td').children('span').attr('id');
		var split = counterTemp.split("_");
		var projectID = split[1];
		
		var column = $(this).closest('td').children('span').attr('column');		
		var value = $('.input-mini').val();
		
		if(column != ""){											
			UpdateITSMDefects(column, value, projectID);				
		}
	});
}

/* Update ITSM Defects */

function UpdateITSMDefects(column, value, ProjectID){
	
	var Data = { column : column, value : value, ProjectID : ProjectID, action : 'updateDefects' };
	
	$.ajax({		
		type : 'POST',
		dataType: 'JSON',
		data : Data, 
		success: function(data) {				
				/*$( "#dialog" ).dialog({
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
				*/
        },
        error: function(jqXHR, textStatus, errorThrown) {
        	  console.log(textStatus, errorThrown);
        }
	});
}


function IntakeProcessFunction(){
	$(document).ready(function(){
		$("#frmAddEditProcess").validate();
	}); 
	 
	/*$.validator.setDefaults({
		submitHandler: function() {
			AddEditProject();
		}
	});*/	 
	
    $("#RequestDate").datepicker({
		dateFormat: "dd-M-y",
		numberOfMonths: 2	,
		onSelect: function (selected) {
            var dt = new Date(selected);
            dt.setDate(dt.getDate());
            $("#SubmissionDate").datepicker("option", "minDate", dt);
        }
	});
    
    $("#SubmissionDate").datepicker({
		dateFormat: "dd-M-y",
		numberOfMonths: 2		
	    /*onSelect: function (selected) {
		   var dt = new Date(selected);
		   dt.setDate(dt.getDate() - 1);
		   $("#RequestDate").datepicker("option", "maxDate", dt);
		 }*/
	});
    
    $('a.clearRequestDate').on('click',function(){
    	$('#RequestDate').datepicker('setDate', null);
    });    
    
    $('a.clearSubmissionDate').on('click',function(){
    	$('#SubmissionDate').datepicker('setDate', null);
    });
    
  
    function log( message ) {
		$( "<div>" ).text( message ).prependTo( "#log" );
		$( "#log" ).scrollTop( 0 );
	}
    
    var autoPath =  $("#AutoPath").attr("data-ajaxurl");
    
	$( "#ProjectName" ).autocomplete({
		source: autoPath,
		minLength: 2,
		select: function( event, ui ) {
			log( ui.item ?
				"Selected: " + ui.item.value + " aka " + ui.item.id :
				"Nothing selected, input was " + this.value );
		}
	});
	
}
/* Function - Delete Intake Process */

function deleteIntakeProcess(ID){
	$( "#dialog" ).dialog({
		   modal : true,
	       autoOpen: false,		
	       height: 200,
		   width: 400,
	       buttons: {
	    	   	Ok : function(){
	    	   		$(this).dialog("close");
	    	   		
					var Data = { action : 'deleteProcess', ID : ID }					
					$.ajax({		
						type : 'POST',
						dataType: 'JSON',
						data : Data, 
						success: function(data) {
							$.each(data, function(key, value){	 
								
								$( "#dialog" ).dialog({
									   modal : true,
								       autoOpen: false,		
								       height: 200,
									   width: 400
								});								
								
								htmltext = "<div class='div-jquery-popup'>"+value+"</div>";								
								$( "#dialog" ).html(htmltext);
								$( "#dialog" ).dialog( "open" );
								
								location.reload();
								
							});
					    },
					    error: function(jqXHR, textStatus, errorThrown) {
					    	console.log(textStatus, errorThrown);
					    }
					});
	          },
	
			  Cancel :function(){
	        	  $(this).dialog("close");
	          }
	       } 						       
	 });
	
	htmltext = "<div class='div-jquery-popup'>Confirm, to delete this record ?</div>";
	
	$( "#dialog" ).html(htmltext);
	$( "#dialog" ).dialog( "open" );
	
	
}



function DocumentationFunction(){
	$(document).ready(function(){
		$("#frmAddEditRecord").validate();
	}); 
	
	 $("#DeliveryDate").datepicker({
			dateFormat: "dd-M-y",
			numberOfMonths: 2
			/*onSelect: function (selected) {
	            var dt = new Date(selected);
	            dt.setDate(dt.getDate());
	            $("#SubmissionDate").datepicker("option", "minDate", dt);
	        }*/
	 });
	 
	 $("#SignoffDate").datepicker({
			dateFormat: "dd-M-y",
			numberOfMonths: 2
			/*onSelect: function (selected) {
	            var dt = new Date(selected);
	            dt.setDate(dt.getDate());
	            $("#SubmissionDate").datepicker("option", "minDate", dt);
	        }*/
	 });
	 
	 $('a.clearDeliveryDate').on('click',function(){
	    	$('#DeliveryDate').datepicker('setDate', null);
	 });    
	    
	 $('a.clearSignoffDate').on('click',function(){
	    	$('#SignoffDate').datepicker('setDate', null);
	 });
}

function QualityEstimationFunction(){
	$(document).ready(function(){
		$("#frmAddEditRecord").validate();
	}); 
	
	 $("#EngagementDate").datepicker({
			dateFormat: "dd-M-y",
			numberOfMonths: 2,
			onSelect: function (selected) {
	            var dt = new Date(selected);
	            dt.setDate(dt.getDate());
	            $("#Gate1EstimationDeliverydate").datepicker("option", "minDate", dt);
	        }
	 });
	 
	 $("#Gate1EstimationDeliverydate").datepicker({
			dateFormat: "dd-M-y",
			numberOfMonths: 2
			/*onSelect: function (selected) {
	            var dt = new Date(selected);
	            dt.setDate(dt.getDate());
	            $("#SubmissionDate").datepicker("option", "minDate", dt);
	        }*/
	 });
	 
	 $('a.clearEngagementDate').on('click',function(){
	    	$('#EngagementDate').datepicker('setDate', null);
	 });    
	    
	 $('a.clearGate1EstimationDeliverydate').on('click',function(){
	    	$('#Gate1EstimationDeliverydate').datepicker('setDate', null);
	 });
	 

    function log( message ) {
		$( "<div>" ).text( message ).prependTo( "#log" );
		$( "#log" ).scrollTop( 0 );
	}
    
    var autoPath =  $("#AutoPath").attr("data-ajaxurl");
    
	$( "#ProjectName" ).autocomplete({
		source: autoPath,
		minLength: 2,
		select: function( event, ui ) {
			log( ui.item ?
				"Selected: " + ui.item.value + " aka " + ui.item.id :
				"Nothing selected, input was " + this.value );
		}
	});
		
}
