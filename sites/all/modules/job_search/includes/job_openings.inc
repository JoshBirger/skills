<?php

function job_search_jobs_callback(){
	$job_search_data = array();
	
	$query = db_select('node', 'n');
	//$query->leftJoin('location_instance', 'lin', 'n.nid = lin.nid');
	//$query->leftJoin('location', 'l', 'lin.lid = l.lid');
	//$query->leftJoin('field_data_field_job_description', 'desc', 'n.nid = desc.entity_id');
	//$query->leftJoin('field_data_field_job_requirements', 'req', 'n.nid = req.entity_id');
	//$query->leftJoin('field_data_field_job_category', 'cat', 'n.nid = cat.entity_id');
	//$query->leftJoin('field_data_field_clearance', 'clear', 'n.nid = clear.entity_id');
	//$query->leftJoin('field_data_field_position_type', 'pos_type', 'n.nid = pos_type.entity_id');
	
	
	//conditions 
	$query->condition('n.status', 1);
	
	//fields
	$query->fields('n',array('title'));
	//$query->fields('l',array('city','province', 'country', 'street','postal_code'));
	
	$query->range(0, 100);
	
	$result = $query->execute()->fetchAll();
	
	 print_r($result);
	
	return " custom job opening page";
	
	//return theme('jobs',array('data' => $job_search_data));
}

function categories_jobs_count( $categories = array() ){
	$where = '';
	$sql = 'select ttd.name, ttd.nid, ttd.tid from taxonomy_index as ti JOIN node as n on (n.nid=ti.nid) left join taxonomy_term_data as ttd on (ttd.tid=ti.tid) where n.type='job' group by tid limit 0, 50';
	
}
function locations_jobs_count($locations = array()){
	$where  = '';
	$sql = 'select l.city, count(li.nid) as node_count from location_instance as li left join  location as l  on (li.lid=l.lid) '.$where.'  group by l.city limit 0, 20';
}



