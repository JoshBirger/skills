<?php
	/** Create the tabs for employee registration, login, and request new password **/
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
          
<?php /**  Create New Employee Form **/ ?>
<?php 
	//module_load_include('inc', 'node', 'node.pages');   
	//$form = node_add('employee');
	$form = drupal_get_form('tsskills_employee_register_form'); //drupal_get_form('user_register_form');
	//print drupal_render($form);
	$d_messages = drupal_get_messages();
	watchdog('tsskills','fetched all drupal messages: '.print_r($d_messages,true) );
	foreach($_POST as $pkey=>$pval){
		if(strpos($pkey,'field_')===false) continue;
		if(isset($form[$pkey])){
			if(isset($pval[LANGUAGE_NONE]) && isset($pval[LANGUAGE_NONE][0]) && isset($pval[LANGUAGE_NONE][0]['value']) ){
				//watchdog("tsskills","Set form default $pkey  -> ".print_r($pval[LANGUAGE_NONE][0]['value'],true).' --- '.print_r($form[$pkey][LANGUAGE_NONE][0]['value'],true) );
				$form[$pkey][LANGUAGE_NONE][0]['value']['#default_value'] = $pval[LANGUAGE_NONE][0]['value'];
				$form[$pkey][LANGUAGE_NONE][0]['value']['#defaults_loaded'] = 0; 
				//watchdog("tsskills","Set form default $pkey  -> ".print_r($form[$pkey][LANGUAGE_NONE][0]['value'],true) );
			} 
		}
	}
?>
<?php print '<form id="'.$form['#id'].'" accept-charset="UTF-8" method="'.$form['#method'].'" action="'.$form['#action'].'">'; ?>
<?php print drupal_render($form['form_id']);?>
<?php print drupal_render($form['form_build_id']);?>
<?php print drupal_render($form['form_token']);?>

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
	<p><?php print drupal_render($form['field_clearance']); ?></p>
</div>
<div class="panel-item col-sm-6">
	<h6>Login Credentials</h6>
	<p>Your private information to access your account should be unique and not used on other websites</p>
	<p><?php print drupal_render($form['user_email']); ?></p>
	<p>A Password will be emailed to you after registration</p>
</div>
<div class="panel-item col-sm-6">
	<h6>Contact Information</h6>
	<p><i>Key information for employers to communicate with your </i></p>
	<p><?php print drupal_render($form['field_employee_fname']); ?></p>
	<p><?php print drupal_render($form['field_employee_lname']); ?></p>
	<p><?php print drupal_render($form['field_employee_telephone']); ?></p>
	<p><?php print drupal_render($form['field_employee_cellphone']); ?></p>
	<p><?php print drupal_render($form['field_employee_street_add']); ?></p>
	<p><?php print drupal_render($form['field_employee_zip']); ?></p>
	<h6>Message for Recruiters</h6>
	<p><?php print drupal_render($form['field_employee_message']); ?></p>
</div>
<div class="panel-item col-sm-6">
	<h6>Job Profile</h6>
	<p><?php print drupal_render($form['field_availability']); ?></p>
	<p><?php print drupal_render($form['field_position_type']); ?></p>
	<p><?php print drupal_render($form['field_employee_skills']); ?></p>
	<p><?php print drupal_render($form['field_employee_text_resume']); ?></p>
	<p><?php print drupal_render($form['field_employee_resume']); ?></p>
</div>
<div class="panel-item col-sm-12">
	
</div>

<div class="panel-item col-sm-12">
	<h6>CAPTCHA Challenge</h6>
	
	<p><?php
//		unset($form['my_captcha']['#element_validate']); unset($form['my_captcha']['#process']);
		$captcha_render =  drupal_render($form['my_captcha']); 
		print $captcha_render;
	?></p>

	<p><?php print drupal_render($form['submit_button']); ?></p>

<script type="text/javascript">
<?php
//drupal render won't respect submitted form or setting the defaults manually above so will set the field values in javascript
foreach($_POST as $pkey=>$pval){
                if(strpos($pkey,'field_')===false) continue;
                if(isset($form[$pkey])){
                        if(isset($pval[LANGUAGE_NONE]) && isset($pval[LANGUAGE_NONE][0]) && isset($pval[LANGUAGE_NONE][0]['value']) ){
				$selector = 'input[name="'.$pkey.'[und][0][value]';
				?>
				var <?php print $pkey; ?> =  document.querySelector('<?php print $selector; ?>');
				if( <?php print $pkey; ?>){
					<?php print $pkey; ?>.value = '<?php print $pval[LANGUAGE_NONE][0]['value']; ?>';
				}
				<?php
                        }
                }
        }


?>
</script>
</div>
</div>
</div>

<?php print '</form>'; ?>


        </div> <!-- /tab-pane create new account -->
        <div class="tab-pane col-sm-6 fade" id="profile1">
	  <p><?php
		$uform = drupal_get_form('user_login_block');
                unset($uform['links']);
                print drupal_render($uform);
	  ?></p>
        </div> <!-- /tab-pane -->
        <div class="tab-pane col-sm-6 fade" id="messages1">
		<p><?php
			$rform = drupal_get_form('tsskills_password_request_form');
			                        print drupal_render($rform);
                ?></p>
                <script type="text/javascript">
                        setTimeout(function(){
                                document.querySelector('div.captcha img').height=60;
                                document.querySelector('div.captcha img').width=180;
                        },150);
                        document.querySelector('#myTab1 > li:nth-child(3) > a').onclick = function(){
                                document.querySelector('#tsskills-password-request-form > div > div.captcha > img').height=60;
                                document.querySelector('#tsskills-password-request-form > div > div.captcha > img').width=180;
                        }; //tab js appears to be forcing the captcha to 0 height
                </script>

        </div> <!-- /tab-pane -->
      </div> <!-- /tab-content -->
      <!-- /Tabs -->

    </div> <!-- /col-md-12 -->
</div>
