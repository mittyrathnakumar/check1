function CreateUser(){
	
	var emailid = $("#emailid").val();
	var password = $("#password").val();
	var firstname = $("#firstname").val();
	var lastname = $("#lastname").val();
	var userrole = $("#userrole").val();
	var htmltext;
	
	var Data = {emailid : emailid, password : password, firstname: firstname, lastname: lastname, userrole : userrole};
	
	$.ajax({		
		type : 'POST',
		dataType: 'JSON',
		data : Data, 
		success: function(data) {			
			 $( "#dialog" ).dialog({
			       autoOpen: false,		
			       height: 300,
				   width: 400,
			       buttons: {
			          Ok : function(){
			        	  $(this).dialog("close");
			          }
			       } 						       
			 });
			if(data == 0)
				htmltext = "<div class='small'>User with given Email Id already exists !!!</div>";
			else {
				htmltext = "<div class='small'>New User Added !!!</div>";
				window.location.reload();
			}
			
			$( "#dialog" ).html(htmltext);
			$( "#dialog" ).dialog( "open" );	
        	
        	
        },
        error: function(jqXHR, textStatus, errorThrown) {
        	  console.log(textStatus, errorThrown);
        }
	});
}
