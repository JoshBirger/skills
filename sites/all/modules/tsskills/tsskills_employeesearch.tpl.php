<?php
global $pager_total_items;
?>

<div id="applied-jobs-wrap" class="col-lg-12">

<!-- search bar -->
<div class="col-lg-12">
<form accept-charset="UTF-8" method="get" id="frm_searchemployees" name="frm_searchemployees">
	<input type="hidden" name="sortby" id="sortby" value="<?php print isset($_GET['sortby'])?$_GET['sortby']:'' ; ?>">
	<input type="hidden" name="sortasc" id="sortasc" value="<?php print isset($_GET['sortasc'])?$_GET['sortasc']:'ASC' ; ?>">
	<div class="col-lg-3"><label for="keywords">Keywords</label><input type="text" name="keywords"></div>
	<div class="col-lg-3"><label for="keywords">Expertise</label><input type="text" name="expertise"></div>

	<div class="col-lg-2"><input type="submit" value="Apply"></div>
</form>
</div>


<div class="col-lg-12">
<b>Found <?php print ($employees?$pager_total_items[0]:"0"); ?> Employees</b>
</div>

<div class="col-lg-12">

<!--Sort By: <select id="sl_sortby">
	<option value="field_employee_lname">Last Name</option>
	<option value="field_employee_street_add">Address</option>
</select>-->
<div class="col-lg-2 col-xs-2"><b>Sort By:</b></div>
<div class="col-lg-12">
		<div class="col-lg-2 col-xs-2"><a onClick='sortBy("field_employee_lname");'>Name</a></div>
		<div class="col-lg-2 col-xs-2"><a onClick='sortBy("field_employee_street_add");'>Address</a></div>
		<div class="col-lg-2 col-xs-2"><a onClick='sortBy("field_employee_zip");'>Zip</a></div>
		<div class="col-lg-2 col-xs-2"><a onClick='sortBy("field_employee_telephone");'>Telephone</a></div>
		<div class="col-lg-2 col-xs-2"><a onClick='sortBy("field_employee_cellphone");'>Cellphone</a></div>
		<div class="col-lg-2 col-xs-2"><a onClick='sortBy("created");'>Created</div>
</div>
<div class="clearfix"></div>
	<?php $e_i=0; foreach($employees as $e){  $e_i++; ?>
	<div class="col-lg-12" style="<?php if($e_i%2){ print "background: #eeeeee;"; } ?>">
		<?php
			$name = isset($e->field_employee_fname[LANGUAGE_NONE])?$e->field_employee_fname[LANGUAGE_NONE][0]['value']:'';
			$name .= '  ';
			$name .= isset($e->field_employee_lname[LANGUAGE_NONE])?$e->field_employee_lname[LANGUAGE_NONE][0]['value']:'';
			$zip = field_view_field('node', $e, 'field_employee_zip');
			$streetadd = field_view_field('node',$e,'field_employee_street_add');
			$telephone = field_view_field('node', $e,'field_employee_telephone');
			$cell = field_view_field('node',$e,'field_employee_cellphone');
		?>
		<div class="col-lg-6"><h6><?php print l($name,drupal_get_path_alias('node/'.$e->nid)); ?></h4></div>
		<div class="col-lg-3"><?php print drupal_render($telephone); ?></div>
		<div class="col-lg-3"><?php print drupal_render($cell); ?></div>
		
		<div class="col-lg-6"><?php print drupal_render($streetadd); ?></div>
		<div class="col-lg-3"><?php print drupal_render($zip); ?></div>
		<div class="col-lg-3"><?php print drupal_render($created); ?></div>

		<!--
		<td><?php print isset($e->field_employee_street_add[LANGUAGE_NONE])?$e->field_employee_street_add[LANGUAGE_NONE][0]['value']:''; ?></td>
		<td><?php print isset($e->field_employee_zip[LANGUAGE_NONE])?$e->field_employee_zip[LANGUAGE_NONE][0]['value']:''; ?></td>
		<td><?php print isset($e->field_employee_telephone[LANGUAGE_NONE])?$e->field_employee_telephone[LANGUAGE_NONE][0]['value']:''; ?></td>
		<td><?php print isset($e->field_employee_cellphone[LANGUAGE_NONE])?$e->field_employee_cellphone[LANGUAGE_NONE][0]['value']:''; ?><</td>
		<td><?php print date ("M d, Y", $e->created); ?></td>-->

	<hr>
	</div>
	<?php } ?>
</div>



<div class="col-lg-12">

<?php $page_ar = array('pager' => array(
    '#markup' => theme('pager'),
    '#weight' => 10
  ));
        print drupal_render($page_ar);
?>
</div>

<script type="text/javascript">
	function sortBy(field_name){
		if( document.getElementById('sortby').value == field_name ){
			document.getElementById('sortasc').value = (document.getElementById('sortasc').value=='ASC'?'DESC':'ASC');
		} else {
			document.getElementById('sortby').value = field_name;
		}
		document.forms.frm_searchemployees.submit();
	}
/*
addEventListener(document, 'DOMContentLoaded', function() {
    addEventListener(document.getElementById('sl_sortby'), 'change', function() {
        //document.getElementById('UserName').value = this.value;
	sortBy(this.value);
    });
});
*/	
</script>

</div>
