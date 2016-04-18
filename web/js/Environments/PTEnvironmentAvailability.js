/**
 * 
 */
google.load("visualization", "1", {
	packages: ["timeline"]
});

google.setOnLoadCallback(drawChart);

function drawChart() {
	var container = document.getElementById('timeline');
	var chart = new google.visualization.Timeline(container);
	var dataTable = new google.visualization.DataTable();
	
	dataTable.addColumn({ type: 'string', id: 'System' });
	dataTable.addColumn({ type: 'string', id: 'Project' });
	dataTable.addColumn({ type: 'date', id: 'Start' });
	dataTable.addColumn({ type: 'date', id: 'End' });
	
	dataTable.addRows([
		[ 'ASAP', 'ASAP refresh & Testing', new Date(2015, 8, 28,9,0,0), new Date(2015, 9, 9,18,0,0) ],	
		[ 'ASAP', 'ASAP E2E Baseline', new Date(2015, 8, 18,9,0,0), new Date(2015, 8, 27,18,0,0) ],
		[ 'ASAP', 'ASAP Load Balancer Fix', new Date(2015, 9, 12,9,0,0), new Date(2015, 9, 22,18,0,0) ],			
		[ 'ASAP', 'E2E Environment Integration - TechM', new Date(2015,10,1,9,0,0), new Date(2015,11,13,18,0,0) ],
		[ 'ASAP', 'Benchmark PT', new Date(2015,10,13,9,0,0), new Date(2015,10,20,18,0,0) ],
		[ 'ASAP', 'E2E PT Run 1', new Date(2015,11, 14,9,0,0), new Date(2015, 11, 23,18,0,0) ],
		[ 'ASAP', 'E2E PT Run 2', new Date(2015,11,24,9,0,0), new Date(2016,0,8,18,0,0) ],			
		[ 'ETL', 'ETL', new Date(2015, 9, 28,9,0,0), new Date(2015, 9, 28,18,0,0) ],
		[ 'FUSION MNP', 'UniSIM', new Date(2015, 7, 24,9,0,0), new Date(2015, 8, 11,18,0,0) ],
		[ 'FUSION MNP', 'E2E Environment Integration - TechM',  new Date(2015,10,1,9,0,0), new Date(2015,11,13,18,0,0) ],
		[ 'FUSION MNP', 'DB Refresh',  new Date(2015,10,25,9,0,0), new Date(2015,11,11,18,0,0) ],
		[ 'FUSION MNP', 'E2E PT Run 1',  new Date(2015,11, 14,9,0,0), new Date(2015, 11, 23,18,0,0) ],
		[ 'FUSION MNP', 'E2E PT Run 2',  new Date(2015,11,24,9,0,0), new Date(2016,0,8,18,0,0) ],	
		[ 'FUSION SS2', 'CSI',   new Date(2015, 9, 18,9,0,0), new Date(2015, 10, 5,18,0,0) ],
		[ 'FUSION SS2', 'E2E Environment Integration - TechM',  new Date(2015,10,1,9,0,0), new Date(2015,11,13,18,0,0) ],
		[ 'FUSION SS2', 'E2E PT Run 1',  new Date(2015,11, 14,9,0,0), new Date(2015, 11, 23,18,0,0) ],
		[ 'FUSION SS2', 'E2E PT Run 2',  new Date(2015,11,24,9,0,0), new Date(2016,0,8,18,0,0) ],
		[ 'FUSION SS2', 'NBA Online',  new Date(2016,0,9,9,0,0), new Date(2016,0,22,18,0,0) ],
		[ 'FUSION SS2', 'Release 16.1',  new Date(2016,0,23,9,0,0), new Date(2016,1,12,18,0,0) ],
		[ 'FUSION VF', 'UniSIM',   new Date(2015, 7, 24,9,0,0), new Date(2015, 8, 13,18,0,0) ],			
		[ 'FUSION VF', 'CSI',   new Date(2015, 9, 18,9,0,0), new Date(2015, 9, 30,18,0,0) ],
		[ 'FUSION VF', 'Tallyman',   new Date(2015,9, 2,9,0,0), new Date(2015, 9, 17,18,0,0) ],	
		[ 'FUSION VF', 'E2E Environment Integration - TechM',  new Date(2015,10,1,9,0,0), new Date(2015,11,13,18,0,0) ],
		[ 'FUSION VF', 'DB Refresh',  new Date(2015,10,20,9,0,0), new Date(2015,10,27,18,0,0) ],
		[ 'FUSION VF', 'E2E PT Run 1',  new Date(2015,11, 14,9,0,0), new Date(2015, 11, 23,18,0,0) ],
		[ 'FUSION VF', 'E2E PT Run 2',  new Date(2015,11,24,9,0,0), new Date(2016,0,8,18,0,0) ],			
		[ 'FUSION VF', 'NBA Online',  new Date(2016,0,9,9,0,0), new Date(2016,0,22,18,0,0) ],
		[ 'FUSION VF', 'Release 16.1',  new Date(2016,0,23,9,0,0), new Date(2016,1,12,18,0,0) ],
		[ 'OCSG', 'UniSIM',   new Date(2015, 7, 24,9,0,0), new Date(2015, 8, 11,18,0,0) ],
		[ 'PSM', 'UniSIM',   new Date(2015, 7, 24,9,0,0), new Date(2015, 8, 11,18,0,0) ],
		[ 'RTDM', 'SAS Upgrade',   new Date(2015, 9, 2,9,0,0), new Date(2015, 9, 22,18,0,0) ],
		[ 'RTDM', 'NBA Online',  new Date(2016,0,9,9,0,0), new Date(2016,0,22,18,0,0) ],
		[ 'SIEBEL', 'UniSIM',   new Date(2015, 7, 24,9,0,0), new Date(2015, 8, 20,18,0,0) ],
		[ 'SIEBEL', 'CSI',   new Date(2015, 9, 5,9,0,0), new Date(2015, 9, 30,18,0,0) ],
		[ 'SIEBEL', 'CEP VOLTE Delete',   new Date(2015, 9, 16,9,0,0), new Date(2015, 9, 19,18,0,0) ],
		[ 'SIEBEL', 'PKE (ORF Error)',   new Date(2015, 9, 6,9,0,0), new Date(2015, 9, 9,18,0,0) ],
		[ 'SIEBEL', 'E2E Environment Integration - TechM',  new Date(2015,10,1,9,0,0), new Date(2015,11,13,18,0,0) ],
		[ 'SIEBEL', 'DB Refresh',  new Date(2015,10,24,9,0,0), new Date(2015,11,4,18,0,0) ],
		[ 'SIEBEL', 'E2E PT Run 1',  new Date(2015,11, 14,9,0,0), new Date(2015, 11, 23,18,0,0) ],
		[ 'SIEBEL', 'E2E PT Run 2',  new Date(2015,11,24,9,0,0), new Date(2016,0,8,18,0,0) ],
		[ 'SIEBEL', 'Release 16.1',  new Date(2016,0,18,9,0,0), new Date(2016,1,14,18,0,0) ],
		[ 'SIEBEL', 'NZ & FJ Data Purge',  new Date(2016,2,1,9,0,0), new Date(2016,3,1,18,0,0) ],
		[ 'TALLYMAN', 'Tallyman Upgrade',   new Date(2015, 8, 14,9,0,0), new Date(2015, 8, 25,18,0,0) ],
		[ 'TALLYMAN', 'Tallyman Upgrade',   new Date(2015, 8, 29,9,0,0), new Date(2015, 9, 17,18,0,0) ],
		[ 'ORACLE EBIZ', 'Oracle Replatforming',   new Date(2015, 8, 25,9,0,0), new Date(2015, 9, 30,18,0,0) ],			
		[ 'BRM', 'E2E Environment Integration - TechM',  new Date(2015,10,1,9,0,0), new Date(2015,11,13,18,0,0) ],
		[ 'BRM', 'E2E PT Run 1',  new Date(2015,11, 14,9,0,0), new Date(2015, 11, 23,18,0,0) ],
		[ 'BRM', 'E2E PT Run 2',  new Date(2015,11,24,9,0,0), new Date(2016,0,8,18,0,0) ],
		[ 'MOCK SERVICES', 'SERVER PT',  new Date(2015,10,5,9,0,0), new Date(2015,11,2,18,0,0) ],
	]);
	
	var options = {				
		timeline: { 
			colorByRowLabel: true 
		}			
	};
	
	chart.draw(dataTable);
}
