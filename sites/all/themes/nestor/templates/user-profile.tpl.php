<?php
if($user->uid == 1){

	$url_parts = explode('/',$_SERVER['REQUEST_URI']);
	$uname = $url_parts[sizeof($url_parts)-1];
	$utitle = drupal_get_title();
	if($uname == 'user' || $uname == 'employee' || $uname == 'employer' || $uname=='admin' || $utitle == $user->name ){
		tsskills_user_edit($user->uid,false);
		return;
	}
	$uname = $utitle;
	$u = user_load_by_name($uname);
	if(!$u){
		print "Could not load user: $uname "; return;
	}
	$uview = user_view($u);
	$n = null; $emp_label = '?'; 
	if(isset($u->roles[4])){
		$emp_label = 'Employee';
		$n = _tsskills_employee_by_uid($u->uid);
	} else if(isset($u->roles[5])) {
		$emp_label = 'Employer';
		$n = _tsskills_employer_by_uid($u->uid);
	}
	//print drupal_render($uview);
?>
<p>
<a class="btn btn-default" href="/user/<?php print $u->uid; ?>/edit">Edit User</a>
<a class="btn btn-default" href="/node/<?php print ($n?$n->nid:null); ?>/edit">Edit <?php print $emp_label; ?></a>
</p>
<p>
<?php if($n){
	$nview = node_view($n);
	print drupal_render($nview);
	}
?>
<?php
} //end if admin
?>
