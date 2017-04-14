<?php
global $pager_total_items;
?>

<div id="applied-jobs-wrap" class="col-lg-12">

<!-- search bar -->
<div class="col-lg-12">
<form accept-charset="UTF-8" method="get" id="frm_searchemployees" name="frm_searchemployees">
	<input type="hidden" name="sortby" id="sortby" value="<?php print isset($_GET['sortby'])?$_GET['sortby']:'' ; ?>">
	<input type="hidden" name="sortasc" id="sortasc" value="<?php print isset($_GET['sortasc'])?$_GET['sortasc']:'ASC' ; ?>">
	<input type="hidden" name="changepublish" id="changepublish" value="">
	<div class="col-lg-5"><label for="keywords" style="margin-right:10px;">Title</label><input type="text" name="keywords" value="<?php print isset($_GET['keywords'])?$_GET['keywords']:''; ?>"></div>
	<div class="col-lg-2"><input type="submit" value="Apply"></div>
</form>
</div>


<div class="col-lg-12">
<b>Found <?php print ($jobs?$pager_total_items[0]:"0"); ?> Jobs</b>
</div>

<div class="col-lg-12">

<div class="col-lg-2 col-xs-2"><b>Sort By:</b></div>
<div class="col-lg-12">
		<div class="col-lg-3 col-xs-3"><a onClick='sortBy("title");'>Title</a></div>
		<div class="col-lg-3 col-xs-3"><a onClick='sortBy("author");'>Author</a></div>
		<div class="col-lg-2 col-xs-2"><a onClick='sortBy("status");'>Published</a></div>
		<div class="col-lg-2 col-xs-2"><a onClick='sortBy("created");'>Created</div>
</div>
<div class="clearfix"></div>
	<?php $e_i=0; foreach($jobs as $e){  $e_i++; ?>
	<div class="col-lg-12" style="<?php if($e_i%2){ print "background: #eeeeee;"; } ?>">
		<?php
			//$author = field_view_field('node',$e,'author');
		?>
		<div class="col-lg-3"><h6><?php print l($e->title,drupal_get_path_alias('node/'.$e->nid)); ?></h6></div>
		
		<div class="col-lg-3"><?php print $e->employer? l($e->employer->title,'node/'.$e->employer->nid):l('User '.$e->uid,'user/'.$e->uid); ?></div>
		<div class="col-lg-2"><?php print $e->status; ?></div>
		<div class="col-lg-2"><?php print date ("M d, Y", $e->created); ?></div>
		<div class="col-lg-2">
			<button class="btn <?php print $e->status?'btn-danger':'btn-success'; ?>" onClick='sendPublish(<?php print $e->nid; ?>);'><?php print ($e->status?"Unpublish":"Publish"); ?></button></div>

		<!--
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
	function sendPublish(nid){
		document.getElementById('changepublish').value = nid;
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
