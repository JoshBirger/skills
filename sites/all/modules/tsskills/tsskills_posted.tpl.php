<div id="applied-jobs-wrap" class="col-lg-12">

<?php if(isset($jobcount)){ ?>
	<div class="job-search-listing-head">
				<h2><span class="txt-found">Found </span><span class="txt-count"><?php echo $jobcount; ?></span><span class="txt-job"> Jobs</span></h2>
			</div>
<?php } ?>

<div class="job-search-listing-list">
<?php
if($jobs){ 
$job_i = 0;
foreach( $jobs as $job ){ 
	$job_i++;
?>
<div class="job-search-job <?php print ($job_i%2?'even':'odd'); ?> job" data-dist="">
<div class="col-lg-7 " style="border-bottom:1px solid #ccc;">
<h3><?php print l($job->title,drupal_lookup_path('alias',"node/".$job->nid) ) ; ?></h3>
<label class="clear_name"><?php if($is_admin){ print l($job->employer['title'], drupal_lookup_path('alias',"node/".$job->employer['nid']) ); } ?></label>
<label class="posted_date">Posted: <?php print gmdate("Y-m-d", $job->created ); ?></label>
</div>

<div class="col-lg-2 "><?php 
	print (isset($job->location['city'])?$job->location['city']:'').', '
	.(isset($job->location['province_name'])?$job->location['province_name']:'').' '
	.(isset($job->location['country_name'])?$job->location['country_name']:''); ?>
</div>
<div class="col-lg-3 action"><?php print $job->application_count; /*l('Contact Us','page/contact-tsskills');*/ ?> Applications</div>
<div class="clearfix clear"></div>
</div>
<div class="col-lg-12" id="applicants_<?php print $job_i; ?>">
<?php 
  if($job->application_count > 0) {
	$app_i=0; 
	foreach($job->applications as $app){ 
		$app_i++; $emp = $app['employee']; $au = $app['user']; $node_emp = node_view($emp);
		$emp_name =$app['employee']->field_employee_fname[LANGUAGE_NONE][0]['value'].' '.$app['employee']->field_employee_lname[LANGUAGE_NONE][0]['value'];
?>
<div class="panel panel-default" <?php /*if($app_i%2){ print "style='background:#eee;'"; }*/ ?>>
	<div class="panel-heading">
		<a data-toggle="collapse" data-parent="#applicants<?php print $job_i; ?>" href="#<?php print $job_i.'_'.$app_i; ?>" class="collapsed">
			<?php print $emp_name; ?>
		</a>
	</div>

	<div id="<?php print $job_i.'_'.$app_i; ?>" class="panel-collapse collapse">

	<div class="col-lg-12"><?php print l(t('view employee profile'), drupal_lookup_path('alias',"node/".$app['employee']->nid) ); ?></div>
	<div class="col-lg-4"><?php print drupal_render($node_emp['field_employee_telephone']); ?></div>
        <div class="col-lg-4"><?php print drupal_render($node_emp['field_employee_zip']); ?></div>
        <div class="col-lg-4"><?php print drupal_render($node_emp['field_clearance']); ?></div>

	<div class="col-lg-8"><b>Cover Letter</b><div class="cover-letter"><?php print $app['cover'];  ?></div></div>

	

	<div class="col-lg-4"><?php print drupal_render($node_emp['field_employee_resume']); ?></div>
	<div class="col-lg-9"><?php print drupal_render($node_emp['field_employee_text_resume']);  ?></div>
	
	<div class="col-lg-9"><?php print drupal_render($node_emp['field_employee_skills']);  ?></div>
	<div class="col-lg-9"><?php print html_entity_decode(drupal_render($node_emp['field_employee_message']));  ?></div>

	</div>

</div>

<?php } /* end job applications */ ?>
<div class="clearfix"></div>
<?php } /* end jobs foreach */ ?>

</div>

<?php } 
} else { ?>
<h4>You have not posted any jobs</h4>
<?php } ?>

</div>

</div>
