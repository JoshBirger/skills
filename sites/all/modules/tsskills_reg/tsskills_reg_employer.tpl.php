<?php
	/** Create the tabs for employer registration, login, and request new password **/
	drupal_add_css(drupal_get_path('theme','nestor').'/css/employee.css',array('group'=>CSS_THEME,'type'=>'file'));
?>

<div class="row">
    <div class="col-md-12">
		<!-- Tabs! -->
		<ul class="nav nav-tabs" id="myTab1">
			<li class="active"><a href="#home1" data-toggle="tab">Create New Account</a></li>
			<li><a href="#profile1" data-toggle="tab">Login</a></li>
			<li><a href="#messages1" data-toggle="tab">Request New Password</a></li>
		</ul>
		<div class="tab-content">
			<div class="tab-pane fade in active col-sm-6" id="home1">
				<div class="panels-1 margin-bottom-30">
					<div class="row">
						<div class="panel-item col-sm-12">
							<p>The form below is for new employer registration. For assistance with posting jobs or for more information about TSSkills please email <a href="mailto:admin@tsskills.com">admin@tsskills.com</a>
							</p>
							<p>Your private information to access your account should be unique and not used on other websites</p>
						</div>
						<div class="panel-item ">
							<div class="panel-item col-sm-6">
								<p><?php print drupal_render($form['employer_email']); ?></p>
								<p>A Password will be emailed to you after registration</p>
								<p><?php print drupal_render($form['field_employer_fname']); ?></p>
								<p><?php print drupal_render($form['field_employer_lname']); ?></p>
								<p><?php print drupal_render($form['field_employer_company']); ?></p>
								<p><?php print drupal_render($form['field_employer_phone']); ?></p>
							</div>
							<div class="panel-item col-sm-6">
								<p><?php print drupal_render($form['field_employer_cprofile']); ?></p>
								
								<p><?php print drupal_render($form['field_employer_address']); ?></p>
								<p><?php print drupal_render($form['field_employer_zip']); ?></p>
								<p><?php print drupal_render($form['field_employer_message']); ?></p>
							</div>
							<div style="clear:both;"></div>
							<div class="panel-item col-sm-12">
								<h6>CAPTCHA Challenge</h6>
								<p><?php  print drupal_render($form['captcha']); ?></p>
							</div>
						</div>
						<?php print drupal_render_children($form); ?>
						
					</div>
				</div>
			</div> <!-- /tab-pane create new account -->
			<div class="tab-pane fade" id="profile1">
				  <p><?php
					//$uform = drupal_get_form('tsskills_employee_login_form');
					//$uform = drupal_get_form('user_login_block');
					//unset($uform['links']);
					//print drupal_render($uform);
				  ?></p>
			</div> <!-- /tab-pane -->
			<div class="tab-pane fade" id="messages1">
					<p><?php
						//$rform = drupal_get_form('tsskills_password_request_form');
						//print drupal_render($rform);
					?></p>
					<script type="text/javascript">
						/*
						setTimeout(function(){
							document.querySelector('div.captcha img').height=60;
							document.querySelector('div.captcha img').width=180;
						},150);
						document.querySelector('#myTab1 > li:nth-child(3) > a').onclick = function(){ 
							document.querySelector('div.captcha img').height=60;
											document.querySelector('div.captcha img').width=180;
						}; //tab js appears to be forcing the captcha to 0 height
						*/
					</script>
			</div> <!-- /tab-pane -->
		</div> <!-- /tab-content -->
				  <!-- /Tabs -->
    </div> <!-- /col-md-12 -->
</div>
