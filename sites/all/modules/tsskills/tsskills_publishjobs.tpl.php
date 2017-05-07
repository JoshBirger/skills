<?php
global $pager_total_items;
?>

<div id="applied-jobs-wrap" class="col-lg-12">

<!-- search bar -->
<div class="col-lg-12 ">
<form accept-charset="UTF-8" action="" method="get" id="frm_searchemployees" name="frm_searchemployees">
	<input type="hidden" name="sortby" id="sortby" value="<?php print isset($_GET['sortby'])?$_GET['sortby']:'' ; ?>">
	<input type="hidden" name="sortasc" id="sortasc" value="<?php print isset($_GET['sortasc'])?$_GET['sortasc']:'ASC' ; ?>">
	<input type="hidden" name="changepublish" id="changepublish" value="">
	<input type="hidden" name="page" id="page" value="<?php print isset($_GET['page'])?$_GET['page']:'1' ; ?>">
	<div class="col-lg-5"><label for="keywords" style="margin-right:10px;">Title</label><input type="text" name="keywords" value="<?php print isset($_GET['keywords'])?$_GET['keywords']:''; ?>"></div>
	<div class="col-lg-2"><input type="submit" onClick="jQuery('#page').val(1);" value="Apply"></div>
</form>
</div>


<div class="col-lg-12">
<b>Found <?php print $total_jobs; ?> Jobs</b>
</div>

<div class="col-lg-12">

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
		<div class="col-lg-3 col-xs-3"><a onClick='sortBy("title");'>Title</a><?php print _tsskills_add_sort_arrow('title'); ?></div>
		<div class="col-lg-3 col-xs-3"><a onClick='sortBy("author");'>Author</a><?php print _tsskills_add_sort_arrow('author'); ?></div>
		<div class="col-lg-2 col-xs-2"><a onClick='sortBy("status");'>Published</a><?php print _tsskills_add_sort_arrow('status'); ?></div>
		<div class="col-lg-2 col-xs-2"><a onClick='sortBy("created");'>Created </a><?php print _tsskills_add_sort_arrow('created'); ?></div>
</div>
<div class="clearfix"></div>
	<?php $e_i = 0; 
		if(count($jobs) > 0){
			foreach($jobs as $e){ 
				$e_i++; 
	?>
				<div class="col-lg-12" style="<?php if($e_i%2){ print "background: #eeeeee;"; } ?>">
					<div class="col-lg-3"><h6><?php print l($e->title,drupal_get_path_alias('node/'.$e->nid)); ?></h6></div>
					<div class="col-lg-3"><?php print $e->author_fname? l($e->author_fname . ' ' . $e->author_lname,'node/'.$e->author_id):l('User '.$e->uid,'user/'.$e->uid); ?></div>
					<div class="col-lg-2"><?php print $e->status; ?></div>
					<div class="col-lg-2"><?php print date ("M d, Y", $e->created); ?></div>
					<div class="col-lg-2">
						<button class="btn <?php print $e->status?'btn-danger':'btn-success'; ?>" onClick='sendPublish(<?php print $e->nid; ?>);'><?php print ($e->status?"Unpublish":"Publish"); ?></button>
					</div>
					<hr />
					<div class="clearfix"></div>
				</div>
	<?php 
			}
			
			$nop = ceil( $total_jobs / $no_jobs_in_page);
			$nop_to_show = 10;
			$previous = ($page > 1)?($page - 1 ):'';
			$next = ($page < $nop )?($page + 1 ):'';
			

			if($nop > 1  ) {
			?>
				<div class="clearfix"></div>
				<div class="job_search_pagination">
					<ul class="custom-pager custom-pager-<?php //print $position; ?>">
					  <?php if($page > 1){ ?> 
							<li class="first"><a href="javascript:load_page('1');">« first</a> </li>
					  <?php 
						}
						if($previous){ ?> 
							<li class="previous"><a href="javascript:load_page('<?php echo $previous ;?>');">‹ previous</a></li>
					  <?php
						}
						if($nop_to_show >= $nop){
							for($i = 1; $i <= $nop; $i++){
								$cur = ($i == $page)?'current':'';
								echo '<li class="key '.$cur.'"><a href="javascript:load_page(\''.$i.'\')">'.$i.'</a></li>';
							}
						} else {
							$cur = ($page == 1)?'current':'';
							echo '<li class="key '.$cur.'"><a href="javascript:load_page("1");">1</a></li>';
							$i = max(2, $page - 5);
							if ($i > 2){ echo " <li class='dots'> . . . </li> "; }
							for (; $i < min($page + 6, $nop); $i++) {
								$cur = ($i == $page)?'current':'';
								echo '<li class="key '.$cur.'"><a href="javascript:load_page(\''.$i.'\')">'.$i.'</a></li>';
							}
							if ($i != $nop){  echo "<li class='dots'> . . . </li>"; }
							$cur = ($page == $nop)?'current':'';
							echo '<li class="key '.$cur.'"> <a href="javascript:load_page(\''.$nop.'\');">'.$nop.'</a></li>';
						}
						
					  ?> 
					  <?php if($next){ ?> 
								<li class="next"><a href="javascript:load_page('<?php echo $next;?>');">next ›</a></li>
					  <?php }
							if($nop > $page) {
					   ?>
							<li class="last"><a href="javascript:load_page('<?php echo $nop;?>');">last »</a></li>
					  <?php } ?>
					</ul>
				</div>
			<?php }

			
		} else {
			echo '<div class="col-lg-12" >No Matching Records Found</div>';
		}
	?>
</div>

<style>
	.clear{clear:both;}
	.job_search_pagination{margin-top:20px;}
	.job_search_pagination ul, .job_search_pagination li{ list-style:none; }
	.job_search_pagination ul li { float:left; }
	.job_search_pagination ul li a{color:#777; padding:0px 10px; display:block; background:transparent; border:0px solid #dddddd;}
	.job_search_pagination ul li.current a, .job_search_pagination ul li a:hover{background:transparent; border:0px solid #4Cbe50; color:#0038c7;}
	.job_search_pagination .dots{ margin:0 15px; } 
	.job_search_pagination .next a, .job_search_pagination .previous a{
		background:transparent;
	}
	.job_search_pagination .first a, .job_search_pagination .last a{
		background:transparent;
	}
</style>



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
	function load_page(pageno){
		document.getElementById('page').value = pageno;
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
