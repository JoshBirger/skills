<div id="applied-jobs-wrap" class="col-lg-12">

<ul class="nav nav-tabs" id="profileTab">
	<li class="active">
		<a href="#edituser" data-toggle="tab">User</a>
	</li>
	<li>
		<a href="#editprofile" data-toggle="tab">
			<?php print ($employee?'Employee':'Employer'); ?>
		</a>
	</li>
</ul>

<div class="tab-content">
	<div class="tab-pane fade in active" id="edituser">
		<?php 
		$uform = drupal_get_form('user_profile_form', $euser);
		print  drupal_render($uform); ?>
	</div>
	<div class="tab-pane fade" id="editprofile">
		<?php
		module_load_include('inc', 'node', 'node.pages'); 
		$nform = ($employee? drupal_get_form('employee_node_form', $employee) : drupal_get_form('employer_node_form', $employer) );
		print drupal_render($nform);
		?>
	</div>
</div>


</div>
