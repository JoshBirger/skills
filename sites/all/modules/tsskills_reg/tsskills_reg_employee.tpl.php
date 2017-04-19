<?php

$form_id = isset($_POST['form_id'])?trim($_POST['form_id']):'';

/** Create the tabs for employee registration, login, and request new password **/
drupal_add_css(drupal_get_path('theme','nestor').'/css/employee.css',array('group'=>CSS_THEME,'type'=>'file'));

?>
<div class="row">

    <div class="col-md-12">
      <!-- Tabs! -->
		<ul class="nav nav-tabs" id="myTab1">
			<li class="<?php if(!$form_id || $form_id == 'tsskills_employee_form'){ echo 'active'; } ?>"><a href="#home1" data-toggle="tab">Create New Account</a></li>
			<li class="<?php if($form_id == 'user_login'){ echo 'active'; } ?>" ><a href="#profile1" data-toggle="tab">Login</a></li>
			<li class="<?php if($form_id == 'user_pass'){ echo 'active'; } ?>" ><a href="#messages1" data-toggle="tab">Request New Password</a></li>
		</ul>

      <div class="tab-content">
			<div class="tab-pane fade in <?php if(!$form_id || $form_id == 'tsskills_employee_form'){ echo 'active in'; } ?> col-sm-6" id="home1">
					<?php print '<form id="'.$reg_form['#id'].'" accept-charset="UTF-8" method="'.$reg_form['#method'].'" action="'.$reg_form['#action'].'">'; ?>
						<div class="panels-1 margin-bottom-30">
							<div class="row">
								<div class="panel-item col-sm-12">
									<p>Note: Job seeker registration on TSSkills is restricted to U.S. citizens with active or 
									current security clearance issued by the Federal government - no exceptions. Valid agency 
											  clearances include Dept of Defense, Dept of Energy, Dept of State, Dept of Homeland Security, 
											  or any of the intelligence agencies.</p>
								</div>
								<div class="panel-item col-sm-6">
									<h6> Active Clearance</h6>
									<p><?php print drupal_render($reg_form['field_learance']); ?></p>
								</div>
								<div class="panel-item col-sm-6">
									<h6>Login Credentials</h6>
									<p>Your private information to access your account should be unique and not used on other websites</p>
									<p><?php print drupal_render($reg_form['employee_email']); ?></p>
									<p>A Password will be emailed to you after registration</p>
								</div>
								<div class="panel-item col-sm-6">
									<h6>Contact Information</h6>
									<p><i>Key information for employers to communicate with your </i></p>
									<p><?php print drupal_render($reg_form['field_employee_fname']); ?></p>
									<p><?php print drupal_render($reg_form['field_employee_lname']); ?></p>
									<p><?php print drupal_render($reg_form['field_employee_telephone']); ?></p>
									<p><?php print drupal_render($reg_form['field_employee_cellphone']); ?></p>
									<p><?php print drupal_render($reg_form['field_employee_street_add']); ?></p>
									<p><?php print drupal_render($reg_form['field_employee_zip']); ?></p>
									<h6>Message for Recruiters</h6>
									<p><?php print drupal_render($reg_form['field_employee_message']); ?></p>
								</div>
								<div class="panel-item col-sm-6">
									<h6>Job Profile</h6>
									<p><?php print drupal_render($reg_form['field_availability']); ?></p>
									<p><?php print drupal_render($reg_form['field_position_type']); ?></p>
									<p><?php print drupal_render($reg_form['field_employee_skills']); ?></p>
									<p><?php print drupal_render($reg_form['field_employee_text_resume']); ?></p>
									<p><?php print drupal_render($reg_form['field_employee_resume']); ?></p>
								</div>
								<div class="panel-item col-sm-12">
									<h6>CAPTCHA Challenge</h6>
									<p><?php  print drupal_render($reg_form['captcha']); ?></p>
								</div>
							</div>
							<?php print drupal_render_children($reg_form); ?>
						</div>
					</form>
			</div> <!-- /tab-pane create new account -->
			<div class="tab-pane <?php if($form_id == 'user_login'){ echo 'active in'; } ?> col-sm-6 fade" id="profile1">
				  <p><?php
							
						$elements = drupal_get_form("user_login"); 
						$login_form = drupal_render($elements);
						echo $login_form;
				  ?></p>
			</div> <!-- /tab-pane -->
			<div class="tab-pane <?php if($form_id == 'user_pass'){ echo 'active in'; } ?> col-sm-6 fade" id="messages1">
				<p><?php
	
						 module_load_include('inc', 'user', 'user.pages');
						$elements2 =  drupal_get_form('user_pass');
						$reset_form = drupal_render($elements2);
						echo $reset_form;
						
						
					?></p>
					<script type="text/javascript">
							/*setTimeout(function(){
									document.querySelector('div.captcha img').height=60;
									document.querySelector('div.captcha img').width=180;
							},150);
							document.querySelector('#myTab1 > li:nth-child(3) > a').onclick = function(){
									document.querySelector('#tsskills-password-request-reg_form > div > div.captcha > img').height=60;
									document.querySelector('#tsskills-password-request-reg_form > div > div.captcha > img').width=180;
							}; //tab js appears to be forcing the captcha to 0 height
							*/
					</script>

			</div> <!-- /tab-pane -->
		</div> <!-- /tab-content -->
      <!-- /Tabs -->

    </div> <!-- /col-md-12 -->
	
</div>

