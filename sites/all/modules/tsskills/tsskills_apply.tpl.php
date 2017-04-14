<?div /** Display form to apply for job **/ 
	global $base_url;
?>
<div id="tsskills_profile_wrap" class="col-lg-9 col-md-8 col-sm-7 col-xs-7">

<div class="job">
	<div class="col-lg-7"><h3>
		<?php  print l($job->title,drupal_lookup_path('alias',"node/".$job->nid) ) ; ?>
		</h3>
	</div>
	<div class="col-lg-3">
		<?php print $job->location['city'].($job->location['province_name']?', '.$job->location['province_name']:'').($job->location['country_name']?' '.$job->location['country_name']:''); ?>
	</div>
</div>

<div class="employee col-lg-7">
<?php 
	$emp_view = node_view($employee, 'full');
	unset($emp_view['links']); unset($emp_view['field_employee_resume']);
	//print_r(array_keys($emp_view));
	print drupal_render($emp_view);
?>
</div>
<div class="apply-form col-lg-7">
	<?php   
		$apply_form = drupal_get_form('job_application_form',$job->nid);
		//$apply_form = drupal_get_form('tsskills_apply_form'); 
		//$apply_form['job_id'] = array('#type'=>'hidden','#value'=>$job->nid,'#default_value'=>$job->nid);
		//$apply_form['jobid'] = array('#type'=>'markup','#markup'=>'<input type="hidden" name="jobid" value="'.$job->nid.'">');
		print drupal_render($apply_form);
	?>

</div>

</div>

