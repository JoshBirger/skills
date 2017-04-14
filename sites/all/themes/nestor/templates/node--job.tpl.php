<?php

/**
 * @file
 * Default theme implementation to display a node.
 *
 * Available variables:
 * - $title: the (sanitized) title of the node.
 * - $content: An array of node items. Use render($content) to print them all,
 *   or print a subset such as render($content['field_example']). Use
 *   hide($content['field_example']) to temporarily suppress the printing of a
 *   given element.
 * - $user_picture: The node author's picture from user-picture.tpl.php.
 * - $date: Formatted creation date. Preprocess functions can reformat it by
 *   calling format_date() with the desired parameters on the $created variable.
 * - $name: Themed username of node author output from theme_username().
 * - $node_url: Direct URL of the current node.
 * - $display_submitted: Whether submission information should be displayed.
 * - $submitted: Submission information created from $name and $date during
 *   template_preprocess_node().
 * - $classes: String of classes that can be used to style contextually through
 *   CSS. It can be manipulated through the variable $classes_array from
 *   preprocess functions. The default values can be one or more of the
 *   following:
 *   - node: The current template type; for example, "theming hook".
 *   - node-[type]: The current node type. For example, if the node is a
 *     "Blog entry" it would result in "node-blog". Note that the machine
 *     name will often be in a short form of the human readable label.
 *   - node-teaser: Nodes in teaser form.
 *   - node-preview: Nodes in preview mode.
 *   The following are controlled through the node publishing options.
 *   - node-promoted: Nodes promoted to the front page.
 *   - node-sticky: Nodes ordered above other non-sticky nodes in teaser
 *     listings.
 *   - node-unpublished: Unpublished nodes visible only to administrators.
 * - $title_prefix (array): An array containing additional output populated by
 *   modules, intended to be displayed in front of the main title tag that
 *   appears in the template.
 * - $title_suffix (array): An array containing additional output populated by
 *   modules, intended to be displayed after the main title tag that appears in
 *   the template.
 *
 * Other variables:
 * - $node: Full node object. Contains data that may not be safe.
 * - $type: Node type; for example, story, page, blog, etc.
 * - $comment_count: Number of comments attached to the node.
 * - $uid: User ID of the node author.
 * - $created: Time the node was published formatted in Unix timestamp.
 * - $classes_array: Array of html class attribute values. It is flattened
 *   into a string within the variable $classes.
 * - $zebra: Outputs either "even" or "odd". Useful for zebra striping in
 *   teaser listings.
 * - $id: Position of the node. Increments each time it's output.
 *
 * Node status variables:
 * - $view_mode: View mode; for example, "full", "teaser".
 * - $teaser: Flag for the teaser state (shortcut for $view_mode == 'teaser').
 * - $page: Flag for the full page state.
 * - $promote: Flag for front page promotion state.
 * - $sticky: Flags for sticky post setting.
 * - $status: Flag for published status.
 * - $comment: State of comment settings for the node.
 * - $readmore: Flags true if the teaser content of the node cannot hold the
 *   main body content.
 * - $is_front: Flags true when presented in the front page.
 * - $logged_in: Flags true when the current user is a logged-in member.
 * - $is_admin: Flags true when the current user is an administrator.
 *
 * Field variables: for each field instance attached to the node a corresponding
 * variable is defined; for example, $node->body becomes $body. When needing to
 * access a field's raw values, developers/themers are strongly encouraged to
 * use these variables. Otherwise they will have to explicitly specify the
 * desired field language; for example, $node->body['en'], thus overriding any
 * language negotiation rule that was previously applied.
 *
 * @see template_preprocess()
 * @see template_preprocess_node()
 * @see template_process()
 *
 * @ingroup themeable
 */
?>
<?php //print_r($node); ?>
<div id="node-<?php print $node->nid; ?>" class="job-post <?php print $classes; ?>"<?php print $attributes; ?>>
	<div class="job-post-left col-xs-12 col-md-3">
		<div class="info-row company-info">
			<div class="company-logo"></div>
			<div class="company-name">
				<?php 
					$company = get_company_info($node);
				?>
				<label class="in-content-heading"><strong>Company Name:</strong></label>
				<label class="in-content"> 
					<!--a href="<?php //echo url('node/'.$company['nid']); ?>"-->
						<?php echo $company['name'];?>
					<!--/a--> 
				</label>
			</div>
		</div>
		<?php if(isset($node->field_clearance['und'][0]['taxonomy_term'])){ ?>
			<div class="info-row">
			<label class="in-content-heading"><strong>Security Clearance:</strong></label>
			<label class="in-content">
				<?php echo $node->field_clearance['und'][0]['taxonomy_term']->name;?>
			</label>
		</div>
		<?php } ?>
		<div class="info-row">
			<label class="in-content-heading"><strong>Location:</strong></label>
			<label class="in-content">
				<?php echo $node->location['city']. ', ' . $node->location['province_name'];?>
			</label>
		</div>
		<div class="info-row">
			<label class="in-content-heading"><strong>Country:</strong></label>
			<label class="in-content">
				<?php echo $node->location['country_name'];?>
			</label>
		</div>
		
		<div class="info-row">
			<label class="in-content-heading"><strong>Job Number:</strong></label>
			<label class="in-content">
				<?php echo $node->field_job_number['und'][0]['value'];?>
			</label>
		</div>
		

		<?php if(isset($node->field_position_type['und'][0]['taxonomy_term'])){ ?>
		<div class="info-row">
			<label class="in-content-heading"><strong>Job Position Type:</strong></label>
			<label class="in-content">
				<?php echo $node->field_position_type['und'][0]['taxonomy_term']->name;?>
			</label>
		</div>
		<?php } ?>
		<?php if(isset($node->field_job_category['und'][0]['taxonomy_term'])){ ?>
		<div class="info-row">
			<label class="in-content-heading"><strong>Job Category:</strong></label>
			<label class="in-content">
				<?php echo $node->field_job_category['und'][0]['taxonomy_term']->name;?>
			</label>
		</div>
		<?php } ?>
		<div class="info-row">
			<?php 
				$address =  $node->location['city']. ', ' . $node->location['province_name'] . ', '. $node->location['country_name'];
				$latLong =  $node->location['latitude'] .','.$node->location['longitude'];
			?>
			<div style="width:100%; height:300px;" id="job_map_canvas">
			<iframe style="width:100%;height:300px;border:0;" src="https://www.google.com/maps/embed/v1/place?key=AIzaSyDCob92Ti5YP7UUwqjp1_F8CdToLK9gusw
			  &q=<?php echo $address;?>&zoom=8
			  &attribution_source=Google+Maps+Embed+API
			  &attribution_ios_deep_link_id=comgooglemaps://?daddr=<?php echo $latLong;?>"></iframe>
			
			</div>
		</div>
		
		
	</div>
	<div class="job-post-right col-xs-12 col-md-9">
		<div class="head-wrap"><h2><?php echo $node->title;?></h2></div>
		<div class="cotent-wrap">
			<div class="job-body"><?php if(isset($node->body['und'])) { echo $node->body['und'][0]['value']; }?> </div>
			
			<?php if(isset($node->field_job_requirements['und'][0]['value']) && $node->field_job_requirements['und'][0]['value']){ ?>
				<div class="info-row job-requirements">
					<label class="in-content-heading"><strong>Job Requirements</strong></label>
					<div class="job-requirements-content">
						<?php echo $node->field_job_requirements['und'][0]['value'];?>
					</div>
				</div>
			<?php } ?>
			
			<?php 	if(isset($user) && in_array('employee',$user->roles) ){ ?>
				<div class="info-row job-apply">
					<a class="btn btn-info fill" href="/apply/<?php print $node->nid; ?>">Apply</a>
				</div>
			<?php } ?>
		</div>
	</div>
	<div class="clearfix"></div>
	<script  type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDCob92Ti5YP7UUwqjp1_F8CdToLK9gusw"> </script>
	<script type="text/javascript">
		var geocoder;
			var map;
			var address = "<?php echo $address; ?>";

			function initialize() {
			  geocoder = new google.maps.Geocoder();
			  var latlng = new google.maps.LatLng(<?php echo $node->location['latitude'];?>, <?php echo $node->location['longitude'];?>);
			  var myOptions = {
				zoom: 8,
				center: latlng,
				mapTypeControl: false,
				mapTypeControlOptions: {
				  style: google.maps.MapTypeControlStyle.DROPDOWN_MENU
				},
				navigationControl: true,
				mapTypeId: google.maps.MapTypeId.ROADMAP
			  };
			  map = new google.maps.Map(document.getElementById("job_map_canvas"), myOptions);
			  if (geocoder) {
				geocoder.geocode({
				  'address': address
				}, function(results, status) {
				  if (status == google.maps.GeocoderStatus.OK) {
					if (status != google.maps.GeocoderStatus.ZERO_RESULTS) {
					  map.setCenter(results[0].geometry.location);

					  var infowindow = new google.maps.InfoWindow({
						content: '<b>' + address + '</b>',
						//size: new google.maps.Size(150, 50)
						//pixelOffset: new google.maps.Size(50,0)
					  });

					  var marker = new google.maps.Marker({
						position: results[0].geometry.location,
						map: map,
						title: address
					  });
					  infowindow.open(map, marker);
					  //google.maps.event.addListener(marker, 'click', function() {
						//infowindow.open(map, marker);
					 // });

					} else {
					  alert("No results found");
					}
				  } else {
					alert("Geocode was not successful for the following reason: " + status);
				  }
				});
			  }
			}
			//google.maps.event.addDomListener(window, 'load', initialize);
		
	</script>
</div>
