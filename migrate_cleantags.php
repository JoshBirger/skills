<?php
exit;
/** Go through employer/employee records and clean <p> tags from text fields **/

// set HTTP_HOST or drupal will refuse to bootstrap
    $_SERVER['HTTP_HOST'] = 'example.org';
    $_SERVER['REMOTE_ADDR'] = '127.0.0.1';


    //root of Drupal 7 site
    $DRUPAL7_ROOT="/home/ubuntu/skills";
    define('DRUPAL_ROOT',$DRUPAL7_ROOT);
    chdir($DRUPAL7_ROOT);
    require_once "./includes/bootstrap.inc";
    drupal_bootstrap(DRUPAL_BOOTSTRAP_FULL);
    require_once "./includes/password.inc";


	$fields = array(
		'employer' => array(
			'field_employer_messager','field_employer_cprofile'
		),
		'employee' => array(
			'field_employee_skills','field_employee_message','field_employee_text_resume','field_notes','field_employee_street_add'
		)
	);

	$result = db_query("SELECT nid, title, type FROM node WHERE (type = 'employee' OR type = 'employer') AND nid > 1884");
        foreach($result as $row)
        {
                $node = node_load(intval($row->nid));
                if(!$node ) continue;
		//print $node->title."[".$row->nid."] body: ".print_r($node->body[LANGUAGE_NONE][0]['value'],true)."\n"; 
		$node_updated = false;
		if(strpos($node->body[LANGUAGE_NONE][0]['value'],'<p>')!==FALSE || strpos($node->body[LANGUAGE_NONE][0]['value'],'</p>')!==FALSE){
			$node_updated = true;
			$node->body[LANGUAGE_NONE][0]['value'] = str_replace('<p>','',$node->body[LANGUAGE_NONE][0]['value']);
			$node->body[LANGUAGE_NONE][0]['value'] = str_replace('</p>','',$node->body[LANGUAGE_NONE][0]['value']);
			//print "New body: ".$node->body[LANGUAGE_NONE][0]['value']."\n";
		}
		foreach($fields[$row->type] as $fname){
			$fnode = $node->$fname;
			if(strpos($fnode[LANGUAGE_NONE][0]['value'],'<p>')===FALSE && strpos($fnode[LANGUAGE_NONE][0]['value'],'</p>')===FALSE) continue;
			//print $fname.": ".print_r($fnode[LANGUAGE_NONE],true)."\n";
                	$fval = str_replace('<p>','',$fnode[LANGUAGE_NONE][0]['value']);
                	$fval = str_replace('</p>','',$fval);
                	$fnode[LANGUAGE_NONE][0]['value'] = $fval;
			if(isset($fnode[LANGUAGE_NONE][0]['safe_value'])){
				$fval = str_replace('&lt;p&gt;','',$fnode[LANGUAGE_NONE][0]['safe_value']);
                        	$fval = str_replace('&lt;/p&gt;','',$fval);
                        	$fnode[LANGUAGE_NONE][0]['safe_value'] = $fval;
			}
			$node->$fname = $fnode;
			//$fnode = $node->$fname;
			//print "New val: ".print_r($fnode,true)."\n";
			$node_updated = true;
		}
		if($node_updated){
			print "Updating node ".$node->nid." .. \n";
                	node_save($node);
			
		}
        }


