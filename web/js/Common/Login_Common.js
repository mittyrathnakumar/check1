/**
 *	COMMON FUNCTION FOR A LOGIN CHECK 
 */

function checkUserLogin() {
	var email = $("#Email").val();
	var password = $("#Password").val();	
	var htmltext;
	
	var data = {email : email, password : password};	
	$.ajax({		
		type: "POST",
	    dataType: 'JSON',
	    data : data,
	    success: function(data) {
	    	$.each(data, function(key, value){	    				    	
		    	 
				if(value != 1) {					
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
					
					htmltext = "<div class='small'>"+value+"</div>";
					
					$( "#dialog" ).html(htmltext);
					$( "#dialog" ).dialog( "open" );
				}
				else {	
					//alert(document.URL);
					//alert(window.location.pathname);
					window.location.href = 'http://localhost/ART/web/app_dev.php/About';
				}
								
	    	});
	    
	    	
	   },
	    error: function(xhr, status, errorThrown) {
	    	console.log(xhr.responseText);
	        console.log("Error: " + errorThrown);
	        console.log("Status: " + status);
	        console.dir(xhr);
	    }
	});
}

