function AjaxUpdateSystemDetails(column, value, hostname){	
	var Data = {column : column, value : value, hostname : hostname};	
	$.ajax({		
		type : 'POST',
		dataType: 'JSON',
		data : Data, 
		success: function(data) {
			$( "#dialog" ).dialog({
			       autoOpen: false,		
			       height: 200,
				   width: 400,
			       buttons: {
			          Ok : function(){
			        	  $(this).dialog("close");
			          }
			       } 						       
			 });
			
			htmltext = "<div class='small'>System details updated !!!</div>";
			
			$( "#dialog" ).html(htmltext);
			$( "#dialog" ).dialog( "open" );					
     	
        },
        error: function(jqXHR, textStatus, errorThrown) {
        	  console.log(textStatus, errorThrown);
        }
	});
}