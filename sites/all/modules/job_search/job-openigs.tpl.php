<link rel="stylesheet" type="text/css" href="" />

<div class='job-search-wrap'>
	<?php
		$search_active = ($data && count($data) > 0)?'':'active';
		$browse_active = ($data && count($data) > 0)?'active':'';
		
		//print_r($filter);
	?>
	<div class="toggle-search"><button id="search-toggle">Refine Search</button><button id="close-button">Close</button></div>
	<div class="job-search-left col-lg-3 col-md-4 col-sm-5">
		<ul class="nav nav-tabs" role="tablist">
			<li role="presentation" class="<?php echo $search_active;?>" ><a href="#job_search_search_tab" aria-controls="search" role="tab" data-toggle="tab">Search</a></li>
			<li role="presentation" class="<?php echo $browse_active;?>" ><a href="#job_search_browse_tab" aria-controls="browse" role="tab" data-toggle="tab">Browse</a></li>
		</ul>
		<div class="tab-content">
			<div role="tabpanel" class="tab-pane <?php echo $search_active;?>" id="job_search_search_tab">
				
				<form class="job_search_form">
					<button type="reset" class="btn btn-danger clear-filter">Clear</button>
					<button type="submit" class="btn btn-success search-filter">Search</button>
					<input type="hidden" name="page" value="<?php echo $page; ?>" />
					<input type="hidden" name="city" value="<?php echo $filter['city']; ?>" />
					<input type="hidden" name="faceted" value="<?php echo $filter['faceted']; ?>" />
					<div class="form-group">
						<label class="filter-label" for="js_keywords">Keywords</label>
						<div class="filter-content">
							<?php
								$post_keywords = isset($filter['keywords'])?$filter['keywords']:'';
							?>
							<input type="text" class="form-control" name="keywords" value="<?php echo $post_keywords; ?>" id="js_keywords" placeholder="Keywords">
						</div>
					</div>
					<div class="form-group">
						<label class="filter-label">Security Clearance</label>
						<div class="filter-content list clearance">
							<?php
								$post_clearance = isset($filter['clearance'])?$filter['clearance']:array();
								foreach($clearance as $clear){
									$sel = in_array( $clear->tid,$post_clearance) ? ' checked="checked" ':''; 
									echo '<label ><input name="clearance[]" '.$sel.' type="checkbox" value="'.$clear->tid.'" /> '.$clear->name.'</label>';
								}
							?>
							<!--input type="hidden" name="clear_vid" value="<?php echo $clear->vid; ?>" /-->
						</div>
						<div class="less_more"></div>
					</div>
				
					<div class="form-group" style="display:none;">
						<label class="filter-label">Job Categories</label>
						<div class="filter-content list categories">
							<?php
								$post_categories = isset($filter['categories'])?$filter['categories']:array();
								foreach($job_categories as $jcat){
								 	$sel = in_array($jcat->vid .'_'.$jcat->tid,$post_categories) ? ' checked="checked" ':''; 
									echo '<label ><input '.$sel.' name="categories[]" type="checkbox" value="'.$jcat->tid.'" /> '.$jcat->name.'</label>';
								}
							?>
							<!--input type="hidden" name="cat_vid" value="<?php echo $jcat->vid; ?>" /-->
						</div>
						<div class="less_more"></div>
					</div>
					
					
					<div class="form-group">
						<label class="filter-label" for="js_zipcode">Zip Code</label>
						<div class="filter-content ">
							<?php 
								$post_zipcode = isset($filter['zipcode'])? $filter['zipcode']:'';
							?>
							<input type="text" class="form-control" name="zipcode" id="js_zipcode" value="<?php echo $post_zipcode;?>" placeholder="Zip Code">
						</div>
					</div>
					
					<div class="form-group">
						<label class="filter-label" for="zip_radius">Zip Code radius in miles </label>
						<div class="filter-content ">
							<?php 
								$zip_radius = isset($filter['zip_radius'])? $filter['zip_radius']:'';
							?>
							<select name="zip_radius" id="zip_radius" class="form-control">
								  <option value="all">Any Distance</option>
								  <option <?php if($zip_radius == '5'){ echo ' selected="selected" ';}?> value="5">5</option>
								  <option <?php if($zip_radius == '10'){ echo ' selected="selected" ';}?> value="10">10</option>
								  <option <?php if($zip_radius == '15'){ echo ' selected="selected" ';}?> value="15">15</option>
								  <option <?php if($zip_radius == '20'){ echo ' selected="selected" ';}?> value="20" >20</option>
								  <option <?php if($zip_radius == '50'){ echo ' selected="selected" ';}?> value="50">50</option>
								  <option <?php if($zip_radius == '100'){ echo ' selected="selected" ';}?> value="100">100</option>
								  <option <?php if($zip_radius == '250'){ echo ' selected="selected" ';}?> value="250">250</option>
							</select>
						</div>
					</div>
					
					<div class="form-group">
						<label class="filter-label">Countries</label>
						<div class="filter-content list countries">
							<?php
								$post_countries = isset($filter['countries'])?$filter['countries']:array();
								$sel = in_array(strtolower('us'), $post_countries) ? ' checked="checked" ':'';
                                                                echo '<label ><input '.$sel.' name="countries[]" type="checkbox" value="us" />United States</label>';
								foreach($countries as $code => $country){
									if($code=='us') continue;
									$sel = in_array(strtolower($code), $post_countries) ? ' checked="checked" ':''; 
									echo '<label ><input '.$sel.' name="countries[]" type="checkbox" value="'.strtolower($code).'" /> '.$country.'</label>';
								}
							?>
						</div>
						<div class="less_more"></div>
					</div>
					<div class="form-group">
						<label class="filter-label">States</label>
						<div class="filter-content list states">
							<?php
								$post_states = isset($filter['states'])?$filter['states']:array();
								foreach($states as $country_states){
									foreach($country_states as $code => $state){
										$sel = in_array($code,$post_states) ? ' checked="checked" ':''; 
										echo '<label ><input '.$sel.' name="states[]" type="checkbox" value="'.$code.'" /> '.$state.'</label>';
									}
									echo '<hr />';
								}
							?>
						</div>
						<div class="less_more"></div>
					</div>
					
					
					
					
					
					<button type="reset" class="btn btn-danger clear-filter">Clear</button>
					<button type="submit" class="btn btn-success search-filter">Search</button>
				</form>
				
			</div>
			<div role="tabpanel" class="tab-pane <?php echo $browse_active;?>" id="job_search_browse_tab">
				<div class="job_search_browse">
					<div class="form-group job-location">
						<label class="filter-label" >Security Clearance</label>
						<div class="filter-content list">
							<ul>
							<?php
								foreach($clearance_job_count as $clearance){
									echo '<li><a href="javascript:goto_clearance(\''.$clearance->tid.'\')">'. $clearance->name. '('.$clearance->node_count.')</a>';
								}
							?>
							</ul>
						</div>
						<!--div class="less_more"><a href="javascript:void(0);">Show More...</a></div-->
					</div>
					
					<div class="form-group job-location">
						<label class="filter-label" >Job Location</label>
						<div class="filter-content list">
							<ul>
							<?php
								foreach($locations_job_count as $location){
									echo '<li><a href="javascript:goto_location(\''.trim($location->city).'\',\''.$location->province.'\',\''.$location->country.'\');" >'. $location->city. ', '. $location->province.'('.$location->node_count.')</a>';
								}
							?>
							</ul>
						</div>
						<!--div class="less_more"><a href="javascript:void(0);">Show More...</a></div-->
					</div>
					<div class="form-group job-location">
						<label class="filter-label" >Job Categories</label>
						<div class="filter-content list">
							<ul>
							<?php
								foreach($categories_job_count as $category){
									echo '<li><a href="javascript:goto_categories(\''.$category->tid.'\')">'. $category->name. '('.$category->node_count.')</a>';
								}
							?>
							</ul>
						</div>
						<!--div class="less_more"><a href="javascript:void(0);">Show More...</a></div-->
					</div>
					
				</div>
				
			</div>
		 
		</div>
	</div>
	<div class="job-search-listing col-lg-9 col-md-8 col-sm-7">
			<?php //print_r($data);?>
			<div class="job-search-listing-head">
				<h2><span class="txt-found">Found</span><span class="txt-count"> <?php echo $total_jobs; ?></span><span class="txt-job"> Jobs</span></h2>
			</div>
			<div class="job-search-listing-list">
			<?php 
				$location_module_path = DRUPAL_ROOT . '/sites/all/modules/location/supported/';
				$indx = 0;
				foreach($data as $job){
					
					$addr = $job->city ? $job->city . ', ' : '';
					if($job->country){
						include_once ( $location_module_path . 'location.'.$job->country.'.inc');
						$method = 'location_province_list_'.$job->country;
						$states_list = $method();
						$addr .= isset($states_list[$job->province])? $states_list[$job->province] : '';
						//$addr .= $job->country ? $job->country . ',' : '';
					}
					
					$distance = isset($job->distance)?$job->distance:'';
					$addr = rtrim($addr, ',');
					$row_cls = ($indx % 2 == 0)?'even':'odd';
					
					echo '<div class="job-search-job '.$row_cls.' '.$job->type.'" data-dist="'.$distance.'">';
					echo '<div class="col-lg-7 ">';
					echo '<h3><a href="'.url("node/".$job->nid).'">'.$job->title.'</a></h3>';
					echo '<label class="clear_name">' . $job->clear_name . '</label>';
					echo '<label class="posted_date">Posted: ' . date ("M d, Y", $job->created) . '</label>';
					echo '</div>';
					echo '<div class="col-lg-3 ">';
					echo ucwords($addr);
					echo '</div>';
					
					echo '<div class="col-lg-2 action">';
					//echo '<a href="#"><i class="icon ion-ios7-checkmark-outline"></i> <span>Apply</span></a>'; //
					//echo '<a href="#"><i class="icon ion-ios7-download-outline"></i> <span>Save</span></a>'; //
					echo '</div>';
					echo '<div class="clearfix clear"></div>';
					echo '</div>';
					
					$indx++;
				}
			
				$nop = ceil( $total_jobs / $no_jobs_in_page);
				$nop_to_show = 10;
				$previous = ($page > 1)?($page - 1 ):'';
				$next = ($page < $nop )?($page + 1 ):'';
			if($nop > 1 ) {
			?>
				<div class="job_search_pagination">
					<ul class="custom-pager custom-pager-<?php //print $position; ?>">
					  <?php if($page > 1){ ?> 
							<li class="first"><a href="javascript:load_page('1');">First</a> </li>
					  <?php 
						}
						if($previous){ ?> 
							<li class="previous"><a href="javascript:load_page('<?php echo $previous ;?>');">Previous</a></li>
					  <?php
						}
						if($nop_to_show >= $nop){
							for($i = 1; $i <= $nop; $i++){
								$cur = ($i == $page)?'current':'';
								echo '<li class="key '.$cur.'"><a href="javascript:load_page(\''.$i.'\')">'.$i.'</a></li>';
							}
						} else {
							$cur = ($page == 1)?'current':'';
							echo '<li class="key '.$cur.'"><a href="javascript:load_page("1");">1</a></li>';
							$i = max(2, $page - 5);
							if ($i > 2){ echo " <li class='dots'> . . . </li> "; }
							for (; $i < min($page + 6, $nop); $i++) {
								$cur = ($i == $page)?'current':'';
								echo '<li class="key '.$cur.'"><a href="javascript:load_page(\''.$i.'\')">'.$i.'</a></li>';
							}
							if ($i != $nop){  echo "<li class='dots'> . . . </li>"; }
							$cur = ($page == $nop)?'current':'';
							echo '<li class="key '.$cur.'"> <a href="javascript:load_page(\''.$nop.'\');">'.$nop.'</a></li>';
						}
						
					  ?> 
					  <?php if($next){ ?> 
								<li class="next"><a href="javascript:load_page('<?php echo $next;?>');">Next</a></li>
					  <?php }
							if($nop > $page) {
					   ?>
							<li class="last"><a href="javascript:load_page('<?php echo $nop;?>');">Last</a></li>
					  <?php } ?>
					</ul>
				</div>
			<?php } ?>
			</div>
	</div>
	<div class="clearfix clear"></div>
</div>
<script type="text/javascript">
	
</script>
