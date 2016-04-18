$("#tdmtrackeradmin_update").click( function() {	
	$( "#dialog" ).dialog({
	       autoOpen: false,		
	       height: 200,
		   width: 400,
	       buttons: {
	          Ok : function(){

	  			var referenceno = $("#refereneno").val();
	  			var formData = {comments : $("#comments").val(), status : $("#status").val(), referenceno : referenceno};	
	  			
	  		    $.ajax({	
	  		    	
	  		        type: 'post',
	  		        dataType: 'json', 
	  		        data: formData,
	  		        success: function(data) {
	  		        	$( "#dialog" ).dialog({
	  					       autoOpen: false,		
	  					       height: 200,
	  						   width: 400,
	  					       buttons: {
	  					          Ok : function(){
	  					        	  $(this).dialog("close");
	  					        	  history.back();
	  					          }
	  					       } 						       
	  					 });	  					
	  					htmltext = "<div class='small'>TDM Request details updated !!!</div>";
	  					
	  					$( "#dialog" ).html(htmltext);
	  					$( "#dialog" ).dialog( "open" );	  		        	
	  		        	
	  		        },
	  		        error: function(jqXHR, textStatus, errorThrown) {
	  		        	  console.log(textStatus, errorThrown);
	  		        }
	  		    });	  	
	          },	          
			  Cancel : function(){
				  $(this).dialog("close");
			  }
	       } 						       
	 });
	
	htmltext = "<div class='small'>Confirm to Update the details ?</div>";
	
	$( "#dialog" ).html(htmltext);
	$( "#dialog" ).dialog( "open" );	
	
});
