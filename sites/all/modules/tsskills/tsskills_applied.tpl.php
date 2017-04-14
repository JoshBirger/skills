<div id="applied-jobs-wrap" class="col-lg-12">

<?php if(isset($jobcount)){ ?>
	<div class="job-search-listing-head">
				<h2><span class="txt-found">Found </span><span class="txt-count"><?php echo $jobcount; ?></span><span class="txt-job"> Jobs</span></h2>
			</div>
<?php } ?>

<div class="job-search-listing-list">
<?php
if($data){ 
$job_i = 0;
foreach( $data as $job ){ 
	$job_i++;
?>
<div class="job-search-job <?php print ($job_i%2?'even':'odd'); ?> job" data-dist="">
<div class="col-lg-7 ">
<h3><?php print l($job->title,drupal_lookup_path('alias',"node/".$job->nid) ) ; ?></h3>
<label class="clear_name">Secret</label>
<label class="posted_date">Posted: <?php print gmdate("Y-m-d", $job->created ); ?></label></div>
<div class="col-lg-3 "><?php print $job->location['city'].', '.$job->location['province_name'].' '.$job->location['country_name']; ?></div>
<div class="col-lg-2 action"></div><div class="clearfix clear"></div></div>


<?php } 
} else { ?>
<h4>You have not applied to any jobs</h4>
<?php } ?>

</div>

</div>
