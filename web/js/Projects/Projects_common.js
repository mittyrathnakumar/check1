function ProjectFormGeneralFunctions(){	
	$(document).ready(function(){
		$("#frmAddEditProject").validate();
	}); 
	 
	$.validator.setDefaults({
		submitHandler: function() {
			AddEditProject();
		}
	});	 
	
	var roleID = $("#RoleID").val();	
	
	/* Show datepicker for Managers, Delivery Managers, PMO & Admin only */	
	
	//if(roleID == 2 || roleID == 3 || roleID == 4 || roleID == 1){
	    $("#EstimatedProdLiveDate").datepicker({
			dateFormat: "dd-M-y",
			numberOfMonths: 2,
			onSelect: function (selected) {
	            var dt = new Date(selected);
	            dt.setDate(dt.getDate());
	            $("#ActualProdLiveDate").datepicker("option", "minDate", dt);
	        }
		});
	    
	    $("#ActualProdLiveDate").datepicker({
			dateFormat: "dd-M-y",
			numberOfMonths: 2		
		});
	//}
	
	/* === */
}
