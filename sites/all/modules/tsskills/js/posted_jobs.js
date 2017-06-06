
jQuery(document).ready(function(){
	var oTable_posted_jobs;
	oTable_posted_jobs = jQuery('#dt_posted_jobs').DataTable( {
		"processing": true,
		"serverSide": true,
		"ajax": "posted-jobs-ajax",
		"columns": [
					{ "data": "title", 'width': '45%'},
					{ "data": "location",'width': '12%'},
					//{ "data": "code"},
					{ "data": "posted", 'width': '12%'},
					{ "data": "expires", 'width': '12%'},
					{ "data": "total_apps", 'width': '10%',"orderable": true,'searchable': false},
					//{ "data": "unread"},
					//{ "data": "active"},
					//{ "data": "archived"},
					{ "data": "action", 'width': '8%',"orderable": false,'searchable': false},
				],
		"order": [[0, 'asc']],
		"fnServerParams": function ( aoData ) {
			//if(jQuery("#filter_status").val()){
			//	aoData.custom_search = {'status':jQuery("#filter_status").val()};
			//}
		},
		"initComplete": function(settings, json) {

		}
	});
	var oTable_job_applications;
	oTable_job_applications = jQuery('#dt_job_applications').DataTable( {
		 "dom": '<"top"p>rt<"bottom"><"clear">',
		"processing": true,
		"serverSide": true,
		"ajax": "applications-ajax",
		 "info":     false,
		'bFilter': false,
		"columns": [
					{ "data": "user_info", 'width': '50%'},
					{ "data": "job_info", 'width': '25%'},
					{ "data": "social_info", 'width': '25%'},
					//{ "data": "action", 'width': '10%'},
				],
		"order": [[0, 'asc']],
		"fnServerParams": function ( aoData ) {
			//if(jQuery("#filter_status").val()){
			//	aoData.custom_search = {'status':jQuery("#filter_status").val()};
			//}
		},
		"initComplete": function(settings, json) {

		}
	});
	
	
	
				
});
