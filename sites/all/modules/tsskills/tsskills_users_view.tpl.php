<?div /** Display tabs and edit forms for user profile and employee/employer profile **/ ?>
<div id="tsskills_profile_wrap" class="col-lg-9 col-md-8 col-sm-7 col-xs-7">
	<p><?php 
		$n = $employee ? $employee : $employer;
	?>
	<a class="btn btn-default" href="/user/edit">Edit User</a>
	<a class="btn btn-default" href="/node/edit">Edit Employee</a>
	</p>
	<?php 
		$nview = node_view($n);
		print drupal_render($nview); 
	?>


</div>
