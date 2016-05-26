/**
 * COMMON FUNCTIONS FOR DATATABLE
 */

function loadDataTable_Export(filename){
	
	var m_names = new Array("Jan", "Feb", "Mar","Apr", "May", "Jun", "Jul", "Aug", "Sep","Oct", "Nov", "Dec");
	var d = new Date();
	var curr_date = d.getDate();
	var curr_month = d.getMonth();
	var curr_year = d.getFullYear();
	
	var dateToday = curr_date + "-" + m_names[curr_month] + "-" + curr_year;
		
	$(document).ready(function(){
		$("#dataTable").DataTable({
			dom: 'Bfrtip',
		    "pageLength": 25,	        	
	        	 
	        buttons: [
	            {
	                extend: 'excelHtml5',
	                title: filename+'_'+dateToday
	            },
	            /*{
	                extend: 'pdfHtml5',
	                title: filename+'_'+dateToday,
	                pageSize: 'LEGAL'
	            },*/
	            
	            	'print'
        	]
    	});
	});
	
}

