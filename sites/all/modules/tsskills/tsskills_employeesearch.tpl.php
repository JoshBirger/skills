<?php
global $pager_total_items;
?>

<div id="applied-jobs-wrap" class="col-lg-12">

<!-- search bar -->
<div class="col-lg-12">
<?php
	$form_filter = array();
	$form_filter['position_type'] = array(
  '#type' => 'select',
  '#title' => t('Position Type'),
  '#options' => taxonomy_allowed_values(field_info_field('field_position_type')),
);
	$form_filter['clearance'] = array(
  '#type' => 'select',
  '#title' => t('Clearance'),
  '#options' => taxonomy_allowed_values(field_info_field('field_clearance')),
);
  $form_filter['clearance']['#options']['']='All';
  if(isset($_GET['clearance']) && $_GET['clearance']){
	$form_filter['clearance']['#default_value'] = $_GET['clearance'];
  }

function _tsskills_term_options($vocab_name, $field_name){
	$vocab = taxonomy_vocabulary_machine_name_load($vocab_name);
	if(!$vocab){
		return '<option disabled="disabled" value="">No vocabulary found</option>';
	}
	//$results = db_query("SELECT tid, name FROM {taxonomy_term_data} WHERE vid = ".$vocab->vid)->fetchAll();
	$results = taxonomy_get_tree($vocab->vid);
	$options = '';
	$matched_field = false;
	foreach($results  as $key=>$value){
		$selected='';
		if(isset($_GET[$field_name]) && $_GET[$field_name]==$value->tid){
			$selected = 'selected="selected"';
			$matched_field = true;
		}
		$options .= '<option value="'.$value->tid.'" '.(isset($_GET[$field_name]) && $_GET[$field_name]==$value->tid ? 'selected="selected"':'').'>'.$value->name.'</option>';
	}

	$options = '<option value="" '.($matched_field==false?'selected="selected"':'').'>All</option>'.$options; 

	return $options;
	
}

?>
<form accept-charset="UTF-8" method="get" id="frm_searchemployees" name="frm_searchemployees">
	<input type="hidden" name="sortby" id="sortby" value="<?php print isset($_GET['sortby'])?$_GET['sortby']:'' ; ?>">
	<input type="hidden" name="sortasc" id="sortasc" value="<?php print isset($_GET['sortasc'])?$_GET['sortasc']:'ASC' ; ?>">
	<div class="col-lg-12">
	<div class="col-lg-3"><label for="keywords">Keywords</label><input value="<?php if(isset($_GET['keywords'])){ echo $_GET['keywords']; }?>" type="text" name="keywords"></div>
	<div class="col-lg-3"><label for="position_type">Position Type</label>
		<select name="position_type" class="form-control form-select"><?php print _tsskills_term_options('position_type','position_type'); ?></select></div>
	 <div class="col-lg-3"><label for="position_type">Clearance</label>
                <select name="clearance" class="form-control form-select"><?php print _tsskills_term_options('clearance','clearance'); ?></select></div> 
	<div class="col-lg-2"><label for="keywords">Expertise</label><input value="<?php if(isset($_GET['expertise'])){ echo $_GET['expertise']; }?>" type="text" name="expertise"></div> 
	</div>
	<div class="col-lg-12">
	<?php
	drupal_add_library('system', 'ui.datepicker');
drupal_add_js("(function ($) { $('.datepicker').datepicker(); })(jQuery);", array('type' => 'inline', 'scope' => 'footer', 'weight' => 5));
	?>

	<div class="col-lg-3"><label for="joined_after">Joined After</label><input class="datepicker" value="<?php if(isset($_GET['joined_after'])){ echo $_GET['joined_after']; }?>" type="text" name="joined_after"></div>
	<div class="col-lg-2"><br><input type="submit" value="Apply"></div>
	</div>
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
<?php 
function _tsskills_add_sort_arrow($field){
	if( !isset($_GET['sortby']) || $_GET['sortby'] != $field) return '';
	if( isset($_GET['sortasc']) && $_GET['sortasc'] == 'DESC' ){
		return '<span class="ion-arrow-down-b pull-right"></span>';
	}
	return '<span class="ion-arrow-up-b pull-right"></span>';
}
?>
<div class="col-lg-12 th">
		<div class="col-lg-2 col-xs-2"><a onClick='sortBy("field_employee_lname");'>Name</a><?php print _tsskills_add_sort_arrow('field_employee_lname'); ?> </div>
		<div class="col-lg-2 col-xs-2"><a onClick='sortBy("field_employee_street_add");'>Address</a><?php print _tsskills_add_sort_arrow('field_employee_street_add'); ?></div>
		<div class="col-lg-2 col-xs-2"><a onClick='sortBy("field_employee_zip");'>Zip</a><?php print _tsskills_add_sort_arrow('field_employee_zip'); ?></div>
		<div class="col-lg-2 col-xs-2"><a onClick='sortBy("field_employee_telephone");'>Telephone</a><?php print _tsskills_add_sort_arrow('field_employee_telephone'); ?></div>
		<div class="col-lg-2 col-xs-2"><a onClick='sortBy("field_employee_cellphone");'>Cellphone</a><?php print _tsskills_add_sort_arrow('field_employee_cellphone'); ?></div>
		<div class="col-lg-2 col-xs-2"><a onClick='sortBy("created");'>Created</a><?php print _tsskills_add_sort_arrow('created'); ?></div>
</div>
<div class="clearfix"></div>
	<?php $e_i=0; foreach($employees as $e){  $e_i++; ?>
	<div class="col-lg-12" style="<?php if($e_i%2){ print "background: #eeeeee;"; } ?>">
		<?php
			$name = isset($e->field_employee_fname[LANGUAGE_NONE])?$e->field_employee_fname[LANGUAGE_NONE][0]['value']:'';
			$name .= '  ';
			$name .= isset($e->field_employee_lname[LANGUAGE_NONE])?$e->field_employee_lname[LANGUAGE_NONE][0]['value']:'';
			//$zip = field_view_field('node', $e, 'field_employee_zip');
			//$streetadd = field_view_field('node',$e,'field_employee_street_add');
			//$telephone = field_view_field('node', $e,'field_employee_telephone');
			//$cell = field_view_field('node',$e,'field_employee_cellphone');
			
			$name = '<a href="/'.drupal_get_path_alias('node/'.$e->nid).'">'.$name.'</a>';

			$streetadd = isset($e->field_employee_street_add[LANGUAGE_NONE]) && trim($e->field_employee_street_add[LANGUAGE_NONE][0]['value'])?$e->field_employee_street_add[LANGUAGE_NONE][0]['value']:' - ';
			$zip = isset($e->field_employee_zip[LANGUAGE_NONE]) && trim($e->field_employee_zip[LANGUAGE_NONE][0]['value'])?$e->field_employee_zip[LANGUAGE_NONE][0]['value']:' - ';
			$telephone = isset($e->field_employee_telephone[LANGUAGE_NONE]) && trim($e->field_employee_telephone[LANGUAGE_NONE][0]['value'])?$e->field_employee_telephone[LANGUAGE_NONE][0]['value']:' - ';
			$cell = isset($e->field_employee_cellphone[LANGUAGE_NONE]) && trim($e->field_employee_cellphone[LANGUAGE_NONE][0]['value'])?$e->field_employee_cellphone[LANGUAGE_NONE][0]['value']:' - ';
			
		?>
		<?php /*
		<div class="col-lg-6"><h6><?php print l($name,drupal_get_path_alias('node/'.$e->nid)); ?></h4></div>
		<div class="col-lg-3"><?php print drupal_render($telephone); ?></div>
		<div class="col-lg-3"><?php print drupal_render($cell); ?></div>
		
		<div class="col-lg-6"><?php print drupal_render($streetadd); ?></div>
		<div class="col-lg-3"><?php print drupal_render($zip); ?></div>
		<div class="col-lg-3"><?php //print drupal_render($created); ?></div> */ ?>

		<div class="col-lg-2 col-xs-2"><?php print $name ; ?></div>
		<div class="col-lg-2 col-xs-2"><?php print $streetadd; ?></div>
		<div class="col-lg-2 col-xs-2"><?php print $zip; ?></div>
		<div class="col-lg-2 col-xs-2"><?php print $telephone; ?></div>
		<div class="col-lg-2 col-xs-2"><?php print $cell; ?></div>
		<div class="col-lg-2 col-xs-2"><?php print date ("M d, Y", $e->created); ?></div>

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
