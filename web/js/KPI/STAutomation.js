$(function() {
	
	$('.stAutomatedTestCases').editable();
	$('.stTotalTestCases').editable();
	$(document).on('click', '.editable-submit', function() {
		var selectedColumn = $(this).closest('td').children('span').attr('id');
		var split = selectedColumn.split("_");
		//alert(selectedColumn);
		var action = split[0];
		
		var rowId= split[1];
		//alert(rowId);
		var projectID = "ProjectIDHidden_" + rowId;
		var projectIDValue=$("#"+projectID).val();
		//GET Value
		var newValue=$('.input-large').val();
		//alert(newValue);
		if (action == 'stAutomatedTestCases') {
			//alert(projectIDValue+newValue+rowId);
			AjaxUpdatePlanActionDetails(newValue,projectIDValue,action,rowId);
		} else if (action == 'stTotalTestCases') {
			AjaxUpdatePlanActionDetails(newValue,projectIDValue,action,rowId);
		}

	});

	function AjaxUpdatePlanActionDetails(newValue,projectID,action,rowId) {
		var Data = { newValue : newValue, projectID : projectID, action : action };
		var stAutomationid=".stAutomation_" + rowId;
		$.ajax({
			type : 'POST',
			dataType : 'JSON',
			data : Data,
			success : function(data) {
				
				var split = data.split("-");
				var data2 =split[0]; 
				var data3=split[1];		

				window.location.reload();
				
				$(stAutomationid).html(data3);
				
			},
			error : function(jqXHR, textStatus, errorThrown) {
				console.log(textStatus, errorThrown);
			}
		});

	}
});