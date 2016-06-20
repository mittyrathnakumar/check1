$(document).ready(function(){
	
	var date = new Date();
    var maxDate = "-" + date.getDate() + "D";
    var minDate = "-1M " + "-" + (date.getDate() - 1) + "D";
    
	    $(".monthPicker").datepicker({ 
        dateFormat: 'M-y',
        minDate: '-1M',
        maxDate: 0
    });
   
  
   
});