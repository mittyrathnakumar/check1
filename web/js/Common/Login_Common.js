/**
 *	COMMON FUNCTION FOR A LOGIN CHECK 
 */

function checkUserLogin() {
	var email = $("#Email").val();
	var password = $("#Password").val();
	var referrer = $("#referrer").val();
	
	var htmltext;
	
	var data = { email : email, password : password, referrer : referrer };	
	$.ajax({		
		type: "POST",
	    dataType: 'JSON',
	    data : data,
	    success: function(data) {
	    	$.each(data, function(key, value){
	    		
	    		console.log(value);
				if(key == 'status' && value != 1) {					
					$( "#dialog" ).dialog({
						   modal : true,
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

					/* Path is defined in index.html where login page is included */
					var Path = $("#AfterLoginPath").attr("post-url");
					
					if(Path != ''){
						//var Path = 'http://localhost/KPIDash/web/app_dev.php/'+value;
						window.location.href = Path;
					} 
					
					if(key == 'home_page' && value != ''){
						var hostname = $(location).attr('hostname'); 
						var pathname = $(location).attr('pathname');						
						
						var foldername = pathname.substring(0, pathname.length-5); /* This removes login from the end */ 
						var Path = 'http://' + hostname + foldername + value /* Combines all values along with new Home Page */				
						window.location.href = Path;
					}
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

