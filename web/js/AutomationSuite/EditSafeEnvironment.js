function UpdateEnvDetails(env){
	
	var host = $("#host").val();
	var port = $("#port").val();	
	var htmltext;
	
	var Data = {host : host, port : port, env : env, action : 'updateEnv'};	
	
	$.ajax({
		type : 'POST',
		dataType: 'JSON',
		data : Data, 
		success: function(data) {	
			$.each(data, function(key, value){
				console.log(value);
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
				if(value != 1)
					htmltext = "<div class='small'>Some problem occured with update action !!!</div>";
				else {
					htmltext = "<div class='small'>Environment Details Updated !!!</div>";					
				}				
			
				$( "#dialog" ).html(htmltext);
				$( "#dialog" ).dialog( "open" );	
			});
        	
        },
        error: function(jqXHR, textStatus, errorThrown) {
        	  console.log(jqXHR.responseText);
        	  console.log(textStatus, errorThrown);
        }
	});
}
function UpdateServiceDetails(checkListArr, env){	
	var hosts = new Array;
	var ports = new Array;
	var servicenames = new Array;
	
	
	for(var i=0; i<checkListArr.length; i++){		
		hosts[i] = $("#servicehost_"+checkListArr[i]).val();	
		ports[i] = $("#serviceport_"+checkListArr[i]).val();
		servicenames[i] = $("#servicename_"+checkListArr[i]).val();
	}
	
	var Data = {env : env, count : checkListArr.length, hostArr : hosts, portArr : ports, serviceArr : servicenames, action : 'updateServices'};
		
	$.ajax({	
		type : 'POST',
		dataType: 'JSON',
		data : Data, 
		success: function(data) {	
			$.each(data, function(key, value){
				console.log(value);
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
				if(value != 1)
					htmltext = "<div class='small'>Some problem occured with update action !!!</div>";
				else {
					htmltext = "<div class='small'>Service Details Updated !!!</div>";					
				}				
				
				$( "#dialog" ).html(htmltext);
				$( "#dialog" ).dialog( "open" );
			});
        	
        },
        error: function(jqXHR, textStatus, errorThrown) {
        	  console.log(textStatus, errorThrown);
        }
	});
}

function checkBoxEvents(){
	var env = $("#env").val();
	
	$('#selectall').click(function() {	  			   	
        if(this.checked) { 
            $('.checkbox').each(function() { 	            	
                this.checked = true;  		                  
                $("#updateservice").attr("disabled", false);
                            
            });
        }else{
            $('.checkbox').each(function() { 
                this.checked = false;                 
                $("#updateservice").attr("disabled", true);      
            });        
        }
    });
    
    $('.checkbox').click(function() {		    	 	            	
        if(this.checked == true){	                  
        	$("#updateservice").attr("disabled", false);                	
        }
        else {
        	$("#updateservice").attr("disabled", true);
        }
	});
	
    if(env){
		$("#updateservice").click(function(){		    		    	
			var data = { 'checklist_array' : []};    			    	
			$('.checkbox').each(function() { 
				if(this.checked == true){		    			
				 	data['checklist_array'].push($(this).val());						  
				}
			});		    	
			
			UpdateServiceDetails(data['checklist_array'], env);		    	
		});
    }
}