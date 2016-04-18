function ToscaAutomationCommons(){
	$('#selectallcheckbox').click(function() {	  			   	
        if(this.checked) { 
            $('.selectcheckbox').each(function() { 	            	
                this.checked = true;  		                  
                $("#execute").attr("disabled", false);		                            
            });
        }else{
            $('.selectcheckbox').each(function() { 
                this.checked = false;                 
                $("#execute").attr("disabled", true);      
            });        
        }
    });
    
    $('.selectcheckbox').click(function() {		    	 	            	
        if(this.checked == true){	                  
        	$("#execute").attr("disabled", false);                	
        }
        else {
        	$("#execute").attr("disabled", true);
        }                     

    });
    
    
    $("#execute").click(function(){
    		    	
    	var data = { 'checklist_array' : []};    			    	
    	$('.selectcheckbox').each(function() { 
    		if(this.checked == true){		    			
			 	data['checklist_array'].push($(this).val());						  
			}
    	});   	
    	
    	ExecuteSuite(data['checklist_array'], $("#application").val(), $("#executionType").val());
    });
    
}
function FusionAutomationCommon(){
	
	$('#selectallcheckbox').click(function() {	  			   	
        if(this.checked) { 
            $('.selectcheckbox').each(function() { 	            	
                this.checked = true;  		                  
                $("#execute").attr("disabled", false);		                            
            });
        }else{
            $('.selectcheckbox').each(function() { 
                this.checked = false;                 
                $("#execute").attr("disabled", true);      
            });        
        }
    });
    
    $('.selectcheckbox').click(function() {		    	 	            	
        if(this.checked == true){	                  
        	$("#execute").attr("disabled", false);                	
        }
        else {
        	$("#execute").attr("disabled", true);
        }                     

    });
    
    
    $("#execute").click(function(){
    		    	
    	var data = { 'checklist_array' : []};    			    	
    	$('.selectcheckbox').each(function() { 
    		if(this.checked == true){		    			
			 	data['checklist_array'].push($(this).val());						  
			}
    	});
    	
     ExecuteFusionSuite(data['checklist_array']);
    });
}

function ExecuteSuite(checkListArr, application, exeType){	

	/* AJAX CALL TO CHECK IF ANY EXECUTIONS ARE THERE IN THE LIST  */
	
	var Data = {application : application, checkListArr : checkListArr, action : 'getExecutionTosca'};
	
	$.ajax({	
		type : 'POST',
		dataType: 'JSON',
		data : Data, 
		success: function(data) {
			 $.each(data, function(key, value) {				 
				 if(value == 'undone') // RUN FURTHER SCRIPT AS NO EXECUTION SUIT FOUND
					 getExecutionDone(application, checkListArr);
				  
				 else { // DIALOG TO SHOW USER THAT THE EXECUTION IS DONE
					 $( "#dialog" ).dialog({
					       autoOpen: false,
					       height: 400,
						   width: 500,
					       buttons: {
					          Ok : function(){
					        	  $(this).dialog("close");
					          }
					       } 						       
					 });
					htmltext = "<div class='small'>Saved details to TOSCA Execution Table and execution started! </br> Please check the results.</div>";
					$( "#dialog" ).html(htmltext);
					$( "#dialog" ).dialog( "open" );
				 }
			 });	    	
	    },
	    error: function(jqXHR, textStatus, errorThrown) {
	    	  console.log(textStatus, errorThrown);
	    }
	});
}
function getExecutionDone(application, checkListArr){	
	
	/* IF NO EXECUTION LIST FOUND */
	
	var htmltext;	
	if(application == 'FUSION ST EXECUTE'){
		$( "#dialog" ).dialog({
		       autoOpen: false,		      
		       buttons: {
		          Ok : function(){
		        	  $(this).dialog("close");
		          }
		       }       			       
		 });
		htmltext = "<div class='small'>The suite has been reset to null for comparison. <br><br>Please select the services again and click on Compare.</div>";
		$( "#dialog" ).html(htmltext);
		$( "#dialog" ).dialog( "open" );
		
	} else {
		
		if(checkListArr.length > 0){
			var suiteStr = '';
			for (i = 0; i < checkListArr.length; i++) {
				suiteStr += checkListArr[i] + "<br>";
			}
		}
		
		$( "#dialog" ).dialog({
		   autoOpen: true,
		   resizable: false,
		   height: 400,
		   width: 400,
		   modal: true,
		   title: 'Select action',	 
	       buttons: {
	          Yes : function(){		
	        	  if(application != 'FUSION ST EXECUTE'){	        		 
	        		  var Data = {application : application, checkListArr : checkListArr, action : 'getExecutionDoneTosca'};
	        			
		        	   $.ajax({
		        			type : 'POST',
		        			dataType: 'JSON',
		        			data : Data, 
		        			success: function(data) {
		        				
		        				$.each(data, function(key, value){
		        					if(value == 'done'){			        						
		        						 $( "#dialog" ).dialog({			        							 
	        								   resizable: false,
	        								   height: 400,
	        								   width: 500,
	        								   modal: true,
	        								   title: 'Select action',		      
		        						       buttons: {
		        						          Ok : function(){
		        						        	  $(this).dialog("close");
		        						          }
		        						       } 		        							       
		        						 });
		        						htmltext = "<div class='small'>Saved details to TOSCA Execution Table and execution started! </br> Please check the results.</div>";
		        						$( "#dialog" ).html(htmltext);		        								        						
		        						$( "#dialog" ).dialog( "open" );
		        					}		        						
		        				});			        				
		        	        },
		        	        error: function(jqXHR, textStatus, errorThrown) {
		        	        	  console.log(textStatus, errorThrown);
		        	        }
		        	   });
	        	  }
	          },
	          No : function(){
	        	  $(this).dialog("close");
	          }
	       }   
		});		
		
		htmltext = "<div class='small'>There is another automation suite triggered.<br><br>Do you want to overwrite that execution and trigger this new execution?</br></br>"+suiteStr+"</div>";
		
		$( "#dialog" ).html(htmltext);
		$( "#dialog" ).dialog( "open" );		
	}	
	
}
function ExecuteFusionSuite(checkListArr){	
	
	var updateqc = $('[name=updateqc]:checked').val();	  
	var env = $("#environment").val();
	var release = $("#release").val();	

	var Data = {env : env, checkListArr : checkListArr, updateqc : updateqc, release : release, action : 'getExecutionTosca', type : 'Fusion'};
	
	$.ajax({
		type : 'POST',
		dataType: 'JSON',
		data : Data, 
		success: function(data) {
			 $.each(data, function(key, value) {				 
				
			   $( "#dialog" ).dialog({
				       autoOpen: false,	
				       height: 400,
					   width: 500,
				       buttons: {
				          Ok : function(){
				        	  $(this).dialog("close");
				          }
				       } 						       
				 });
				htmltext = "<div class='small'>Saved details to SAFE DB and execution started! </br> Please check the results.</div>";
				$( "#dialog" ).html(htmltext);
				$( "#dialog" ).dialog( "open" );
			
			 });	    	
	    },
	    error: function(jqXHR, textStatus, errorThrown) {
	    	  console.log(textStatus, errorThrown);
	    }
	});
}


function fetchServices(id){
	if(id != 1)
		id = '';
	
	var envname = $("#environment"+id).val();		
	var Data = {envname : envname, action : 'FetchService'};
	
	$.ajax({
		type : 'POST',
		dataType: 'JSON',
		data : Data, 
		success: function(data) {
			var valueArr = new Array();
			$.each(data, function(key, value){
				$.each(value, function (k, v){		
					valueArr.push(v);		
				})				
				
				$('#servicename'+id).empty();
				$('#servicename'+id).append($('<option>').text('--Select Service--').attr('value', ''));				
				for(var i=0;i<valueArr.length;i++){
					$('#servicename'+id).append($('<option>').text(valueArr[i]).attr('value', valueArr[i]));
				}				
			});
		},
	    error: function(jqXHR, textStatus, errorThrown) {
	    	  console.log(textStatus, errorThrown);
	    }
	});
}
function fetchTestCases(id){	
	
	if(id != 1)
		id = '';
	
	var envname = $("#environment"+id).val();
	var servicename = $("#servicename"+id).val();	
	
	var Data = {envname : envname, servicename : servicename, action : 'FetchTC'};
	
	$.ajax({
		type : 'POST',
		dataType: 'JSON',
		data : Data, 
		success: function(data) {
			var valueArr = new Array();
			$.each(data, function(key, value){
				$.each(value, function (k, v){		
					valueArr.push(v);		
				})				
				
				$('#testcasename'+id).empty();
				$('#testcasename'+id).append($('<option>').text('--Select Test Case--').attr('value', ''));
				for(var i=0;i<valueArr.length;i++){
					$('#testcasename'+id).append($('<option>').text(valueArr[i]).attr('value', valueArr[i]));
				}
				
			});
			
		},
	    error: function(jqXHR, textStatus, errorThrown) {
	    	  console.log(textStatus, errorThrown);
	    }
	});
}

function fetchXML(){
	
	var envname = $("#environment1").val();
	var servicename = $("#servicename1").val();
	var testcasename = $("#testcasename1").val();
	
	var Data = { envname : envname, servicename : servicename, testcasename : testcasename, action : 'FetchXML'};
	
	$.ajax({
		type : 'POST',
		dataType: 'JSON',
		data : Data, 
		success: function(data) {
			var valueArr = new Array();
			$.each(data, function(key, value){
				$("#description1").val(value.DESCRIPTION);
				$("#requestxml1").val(value.REQUESTXML);
				$("#responsexml1").val(value.RESPONSEXML);
			});
			
		},
	    error: function(jqXHR, textStatus, errorThrown) {
	    	  console.log(textStatus, errorThrown);
	    }
	});	
}

function submitTC(id){
	if(id == 1){	
		var action = 'EditTC'
	}
	else {
		id = '';
		var action = 'InsertTC'
	}

	var envname = $("#environment"+id).val();
	var servicename = $("#servicename"+id).val();
	var testcasename = $("#testcasename"+id).val();
	var description = $("#description"+id).val();
	var requestxml = $("#requestxml"+id).val();
	var responsexml = $("#responsexml"+id).val();
	var htmltext;
	
	var Data = {envname : envname, servicename : servicename, testcasename : testcasename, description : description,
			requestxml : requestxml, responsexml : responsexml, id : id, action : action};
	
	$.ajax({	
		type : 'POST',
		dataType: 'JSON',
		data : Data, 
		success: function(data) {
			$.each(data, function(key, value){
					
					if(value == 1){						
						htmltext = "<div class='small'>Details Saved !!!.</div>";
						window.location.reload();
					} else {										 
						htmltext = "<div class='small'>Some problem occurred, try again  !!!.</div>";
					}
						
					$( "#dialog" ).dialog({
				       autoOpen: false,		      
				       buttons: {
				          Ok : function(){
				        	  $(this).dialog("close");
				          }
				       } 						       
					});
					
					$( "#dialog" ).html(htmltext);
					$( "#dialog" ).dialog( "open" );
					
					if(key == 'inserttestcase'){
						window.location.reload();
					}

			});			
		},
	    error: function(jqXHR, textStatus, errorThrown) {
	    	  console.log(textStatus, errorThrown);
	    }
	});
}


function fetchServiceDetails(){
	var input = $("#baseline_dropdown").val();	
	
	var Data = { input : input }
	
	$.ajax({
		url : url,
		type : 'POST',
		dataType: 'JSON',
		data : Data, 
		success: function(data) {
			$.each(data, function(key, value){
					
					if(value == 1){						
						htmltext = "<div class='small'>Details Saved !!!.</div>";
						window.location.reload();
					} else {										 
						htmltext = "<div class='small'>Some problem occurred, try again  !!!.</div>";
					}
						
					$( "#dialog" ).dialog({
				       autoOpen: false,		      
				       buttons: {
				          Ok : function(){
				        	  $(this).dialog("close");
				          }
				       } 						       
					});
					
					$( "#dialog" ).html(htmltext);
					$( "#dialog" ).dialog( "open" );
					
					if(key == 'inserttestcase'){
						window.location.reload();
					}

			});			
		},
	    error: function(jqXHR, textStatus, errorThrown) {
	    	  console.log(textStatus, errorThrown);
	    }
	});
	
}
function SafeComparisonCheckboxEvents(){	
	
	// DOCUMENT READY EVENTS
	if($("#baseline_dropdown").val() != ''){	
		$("#fetchservice").attr("disabled", false);		
	}
	else {
		$("#fetchservice").attr("disabled", true);		
	}
	
	$("#compare").attr("disabled", true);
	
	
	// CHECK BOX EVENTS	
	$("#baseline_dropdown").on('change', function(){  			
			$("#expected_dropdown").val($(this).val());
			
			if($(this).val() != ''){	  			
				$("#fetchservice").attr("disabled", false);
			} else {
				$("#fetchservice").attr("disabled", true);
			}
		});
		
	$("#expected_dropdown").on('change', function(){  			 
    	$("#baseline_dropdown").val($(this).val());
    		
    	if($(this).val() != ''){  			
  			$("#fetchservice").attr("disabled", false);
  		} else {
  			$("#fetchservice").attr("disabled", true);
  		}
	});
	
	$('#selectallcheckbox').click(function() {	  			   	
        if(this.checked) { 
            $('.selectcheckbox').each(function() { 	            	
                this.checked = true;  		                  
                $("#compare").attr("disabled", false);		                            
            });
        }else{
            $('.selectcheckbox').each(function() { 
                this.checked = false;                 
                $("#compare").attr("disabled", true);      
            });        
        }
	});
    
	 $('.selectcheckbox').click(function() {	            	
        if(this.checked == true){   

        	var id = $(this).val().split("_");        	
        	baseid = id[1];        	
   	 
        	if(id[0] == 'servicenamebaseline'){
        		$("#servicenameexpected_"+baseid).prop("checked", true);	
        	} 
        	else if(id[0] = 'servicenameexpected'){		        	
        		$("#servicenamebaseline_"+baseid).prop("checked", true);	
        	}
        		 
        	$("#compare").attr("disabled", false);	             	
        }
        else {
        	
        	var id = $(this).val().split("_");        	
        	baseid = id[1];        	
        	     
        	$("#servicenamebaseline_"+baseid).prop("checked", false);
        	$("#servicenameexpected_"+baseid).prop("checked", false);       	
        	
        	
        	$("#compare").attr("disabled", true);
        }                     
	
	 });
	 
	 
	 $("#compare").click(function(){	    	
	    	var data = { 'checklist_array' : []};    			    	
	    	$('.selectcheckbox').each(function() { 
	    		if(this.checked == true){	    			
	    			var id = $(this).val().split("_");	 
	    			if(id[0] == 'servicenamebaseline'){		    			
					 	data['checklist_array'].push($("#servicename_"+id[1]).val());	
	    			}
				}
	    	});	    	
	    		    	
	   CompareSafeResponses(data['checklist_array'], $("#release").val(), $("#iteration").val());
	 });
}

function CompareSafeResponses(checkListArr, release, iteration){
	
	var htmltext;
	var Data = {checkListArr : checkListArr, action : 'checkDB'};
	
	$.ajax({		
		type : 'POST',
		dataType: 'JSON',
		data : Data, 
		success: function(data) {
			 $.each(data, function(key, value) {				 
				console.log(value);
				
			   // IF DB VALUES FOUND
				 if(value == 1){ 
					 $( "#dialog" ).dialog({
					       autoOpen: false,		 
					       height: 400,
						   width: 500,
					       buttons: {
					          Yes : function(){
					        	  var Data = {checkListArr : checkListArr, release : release, iteration : iteration, action : 'updateDB'};
					        	  $.ajax({					        			
					        			type : 'POST',
					        			dataType: 'JSON',
					        			data : Data, 
					        			success: function(data) {
					        				
					        				$.each(data, function(key, value) {	
					        					if(value == 1)
					        						htmltext = "<div class='small'>The suite has been reset to null for comparison. <br><br>Please select the services again and click on Compare.</div>";
					        					else 
					        						htmltext = "<div class='small'>Some problem occurred !!!</div>"
					        				});
					        				
					        				$( "#dialog" ).dialog({
					  					       autoOpen: false,		      
					  					       buttons: {
					  					          Yes : function(){
					  					        	  $(this).dialog("close");
					  					          }
					  					       } 						       
					        				 });
						  					$( "#dialog" ).html(htmltext);
						  					$( "#dialog" ).dialog( "open" );  				
					        				
					        				
					        			},
					        		    error: function(jqXHR, textStatus, errorThrown) {
					        		    	  console.log(textStatus, errorThrown);
					        		    }
					        	  });
					          },
					          No : function(){
					        	  $(this).dialog("close");
					          }					          
					       } 					       
					 });
					 
					htmltext = "<div class='small'>There is another automation suite triggered.<br><br>Do you want to overwrite that execution and trigger this new execution?</div>";
					$( "#dialog" ).html(htmltext);
					$( "#dialog" ).dialog( "open" );
				 } 
				 
				 // IF NO DB VALUES FOUND, UPDATE RECORDS
				 else {	
					 
					 var Data = {checkListArr : checkListArr, release : release, iteration : iteration, action : 'updateDBRecords'};
		        	  $.ajax({		        			
		        			type : 'POST',
		        			dataType: 'JSON',
		        			data : Data, 
		        			success: function(data) {
		        				$.each(data, function(key, value){
			        				// DIALOG TO LET USER KNOW THAT THE FIELD HAS BEEN SET TO NULL
		        					
		        					var Basecount = value.count.Basecount;
		        					var Actcount = value.count.Actcount;
		        					
			        				$( "#dialog" ).dialog({
			 					       autoOpen: false,		      
			 					       buttons: {
			 					          Ok : function(){		 					        	 
			 					        	  $(this).dialog("close");
			 					          } 
			 					          
			 					       } 						       
			        				});
			        				
			        				htmltext = "<div class='small'>"+ Basecount +" testcases selected in Baseline.<br>"+ Actcount +" testcases selected in Expected.<br><br> Execution Started. Please check the SAFE-ST EXECUTION RESULTS page. !!!</div>";
			    					$( "#dialog" ).html(htmltext);
			    					$( "#dialog" ).dialog( "open" );
			    					
		        				});
		        				
		        			},
		        		    error: function(jqXHR, textStatus, errorThrown) {
		        		    	  console.log(textStatus, errorThrown);
		        		    }
		        	  });
				 }			
			 
			 });			 
	    },
	    error: function(jqXHR, textStatus, errorThrown) {
	    	  console.log(textStatus, errorThrown);
	    }
	});
	
}

function SafeTCDocOnReady(){	
	$("#addTestCase").hide();
	$("#editTestCase").hide();
	$("#frmAddTC").validate();
	$("#frmEditTC").validate();	
}

function SafeTCEvents(){
	$("#insert_a").click(function(){
 		$("#addTestCase").show();
 		$("#editTestCase").hide();
 	});
 	
 	$("#edit_a").click(function(){
 		$("#editTestCase").show();
 		$("#addTestCase").hide();
 	}); 	
 	
 	$('#environment').on('change', function() { 		
 		fetchServices(0);
 	});
 	
 	$('#environment1').on('change', function() { 		
 		fetchServices(1);
 	});
 	
 	 
	 $.validator.setDefaults({
		submitHandler: function() {			
			if ( $("#addTestCase").css('display') == 'none' ){ 			  
 			  submitTC(1);
			}
			
			if ( $("#editTestCase").css('display') == 'none' ){ 			  
 			  submitTC(0);
			}						
		}
	 });
 
 	
 	$('#servicename1').on('change', function() { 	
 		fetchTestCases(1);
 	});
 	
 	$('#testcasename1').on('change', function() { 	
 		fetchXML(1);
 	});
 	
}