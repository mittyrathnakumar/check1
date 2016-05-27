$(function() {
	
	$(".estimatedProdLiveDate").editable({
		format : 'dd-mm-yyyy',
		viewformat : 'dd-MM-yy',
		datepicker : {
			weekStart : 1
		}
	});
	
	$(".deliveryDate_").editable({
		format : 'dd-mm-yyyy',
		viewformat : 'dd-MM-yy',
		datepicker : {
			weekStart : 1
		}
	});
	
	/*$("a").bind("click", function() {
		var id = $(this).attr("id");
		var split = id.split("_");
		var column = split[0];
		var counter = split[1];
		$("#estimatedProdLiveDate_" + counter).editable({
			format : 'dd-mm-yyyy',
			viewformat : 'dd-MM-yy',
			datepicker : {
				weekStart : 1
			}
		});
		$("#deliveryDate_" + counter).editable({
			format : 'dd-mm-yyyy',
			viewformat : 'dd-MM-yy',
			datepicker : {
				weekStart : 1
			}
		});

	});*/

	$(document).on('click', '.editable-submit', function() {
		var selectedDate = $(this).closest('td').children('span').attr('id');
		var split = selectedDate.split("_");
		var action = split[0];
		var rowId= split[1];
		
		var projectID = "ProjectIDHidden_" + rowId;
		var projectIDValue=$("#"+projectID).val();
		
		//GET SELECTED DATE
		var date1 = $('.active.day').text();
		var yeartemp = $('.datepicker-switch').text();
		var monthTemp = yeartemp.split(" ");
		var month = monthTemp[0].toUpperCase();
		var year = monthTemp[1].substr(0, 4);
		var newDate = date1 + "-" + month + "-" + year;
	
		if (action != '') {
			AjaxUpdatePlanActionDetails(newDate,projectIDValue,action,rowId);
		} 
		/*else if (action == 'deliveryDate') {
			AjaxUpdatePlanActionDetails(newDate,projectIDValue,action,rowId);
		}*/

	});

	function AjaxUpdatePlanActionDetails(newDate,projectID,action,rowId) {
		var Data = { newDate : newDate, projectID : projectID, action : action };
		var diffinDateid=".DifferenceInDate_" + rowId;
		$.ajax({
			type : 'POST',
			dataType : 'JSON',
			data : Data,
			success : function(data) {
				var split = data.split("-");
				var data2 =split[0]; 
				var data3=split[1];
				$( "#dialog" ).dialog({
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
				
				htmltext = "<div class='small'>"+data2+"</div>";
				
				$( "#dialog" ).html(htmltext);
				$( "#dialog" ).dialog( "open" );
				
				$(diffinDateid).html(data3);
				
			},
			error : function(jqXHR, textStatus, errorThrown) {
				console.log(textStatus, errorThrown);
			}
		});

	}
});