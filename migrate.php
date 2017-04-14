<?php

/** manual migration steps to import data from drupal 6 install **/

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


    print "D7 Bootstrapped\n";
//	exit;

    //print "File public path: ".drupal_realpath('public://');  exit;



	/** Import Employees **/
	/* imported via feeds
	$xml_url = 'http://52.24.70.204/www_tsskills/employee/rss.xml';
	$response_xml_data = loginGetXML($xml_url); //file_get_contents($xml_url);
	if(!$response_xml_data){
		print "Error fetching xml url\n"; exit;
	}
	libxml_use_internal_errors(true);
	$xml_data = simplexml_load_string($response_xml_data);
	if(!$xml_data){
		print "Error parsing xml data\n";
		foreach(libxml_get_errors() as $error) {
           		echo "\t", $error->message;
       		}
		print "\n"; exit;
	}
	//print "Loaded xml data\n".print_r($xml_data,true)."\n".substr($response_xml_data,0,100)."\n";
	exit;
	*/

	/** Go through employees xml and set resume file field **/
	//$xml_string = file_get_contents('/home/ubuntu/migrate/views_data_export_1_all.xml');
	
	
	libxml_use_internal_errors(true);
	$xml_data = simplexml_load_file('/home/ubuntu/migrate/Content_Export_Employer.xml');
        if(!$xml_data){
                print "Error parsing xml data\n";
                foreach(libxml_get_errors() as $error) {
                        echo "\t", $error->message;
                }
                print "\n"; exit;
        }



	/** go through jobs/employer and set author data to company username/profile **/
        for($i=1; $i<count($xml_data->node); $i++){
                $node = node_load( intval($xml_data->node[$i]->Nid) );
                if(!$node) continue;
                //($xml_data->node[$i]->$allterms)
		if($node->uid) continue;
		//print (!$node->uid) ? "no uid":"has uid"; break;

		//print_r($xml_data->node[$i]->Uid); print "\n--\n"; 
		if($xml_data->node[$i]->Uid && count($xml_data->node[$i]->Uid) > 0 && user_load(intval($xml_data->node[$i]->Uid[0])) ){
			$node->uid = intval($xml_data->node[$i]->Uid[0]);
			node_save($node);
			print "Node #".$node->nid." : Updated uid to ".$node->uid." \n";
		} 
		//print_r($node->uid); break;
	}
	exit;

	/** Add terms from all vocabs **/
	/*
	$php_data = file_get_contents('/home/ubuntu/migrate/migrate_vocab.txt');
	$term_rows = unserialize($php_data);
	if(!$term_rows){ print "Couldn't unserialize term data"; exit; }
	foreach($term_rows as $row){
		$term_name = $row['name']; 
		$vocab_name = str_replace(' ','_',strtolower($row['vocab_name']));
		//$arr_terms = taxonomy_get_term_by_name($row['name'], $vocab_name);
		//print "get term by name: $term_name -- ".$vocab_name." \n".print_r($arr_terms,true)."\n";
		//exit;

		$tid = _get_tid_from_term_name($term_name, $vocab_name);
		print "Saved ".$row['name']." -- ".$vocab_name." -- $tid\n";
		//break;
	}
	exit;
	*/


	/** Add location data to jobs **/

	/*	
        $php_data = file_get_contents('/home/ubuntu/migrate/migrate_location.txt');
        $l_rows = unserialize($php_data);
        if(!$l_rows){ print "Couldn't unserialize term data"; exit; }
	$row_count=0;
        foreach($l_rows as $row){
		if(!$row['nid'] || $row['nid']==0) continue;
		//print "location : ".print_r($row,true)."\n";
		$node = node_load(intval($row['nid']));
		if(!$node) continue;
		//print "Node: ".$node->title."\n";

		$locations = array();
		$locations[0] = $row;
		unset($locations[0]['lid']); unset($locations[0]['is_primary']); unset($locations[0]['nid']);
		foreach($locations[0] as $lkey=>$lrow){
			if(!is_string($lrow)) continue;
			$locations[0][$lkey] = utf8_encode($lrow);
		}
		$criteria = array(
			'nid' => $node->nid,
			'vid' => $node->vid,
			'genid' => 'job'
		);
		if($locations[0]['country']=='kr'){
			//print mb_detect_encoding($locations[0]['city'])."   ".$node->nid."\n";
			//print_r($locations); 
		} else { 
			//continue; 
		}
		try {
			location_save_locations($locations, $criteria);
		} catch(Exception $e){
			print "Error saving location for ".$node->nid." : ".$e->getMessage()."\n";
		}
		//break;
		$row_count++;
		//if($row_count>2) break;
        }
        exit;
	*/

	/** Go through all jobs and remove p tags from requirements**/
        /*
        $result = db_query("SELECT nid, type FROM {node} WHERE type = 'job'");
        foreach($result as $row)
        {
		$node = node_load($row->nid);
		if(!$node || empty($node->field_job_requirements[LANGUAGE_NONE]) ) continue;
		$new_req = str_replace('<p>','',$node->field_job_requirements[LANGUAGE_NONE][0]['value']);
		$new_req = str_replace('</p>','',$new_req);
		//print "Fixing tags:\n".$node->field_job_requirements[LANGUAGE_NONE][0]['value']."\n------\n".$new_req."\n";
		$node->field_job_requirements[LANGUAGE_NONE][0]['value'] = $new_req;
		node_save($node);
	}
	exit;
	*/

	/** Go through all job and employee content and set the terms for each vocab  **/
	/*
	$result = db_query("SELECT nid, type FROM {node} WHERE type = 'job' OR type = 'employee'");
	$job_vocab = array(
		'clearance','funding_status','job_category','position_type'
	);
	$emp_vocab = array('clearance','availability','position_type');
	foreach($result as $row)
	{	
		$node = node_load($row->nid);
		if(empty($node->field_taxonomy[LANGUAGE_NONE])) continue;
		$new_tags = array();
		//$avail = array(); $clearance=array(); $job_category=array();$funding_status=array();$position_type=array();
		print "Loaded node [".$row->type."] ".$node->title." ".$node->nid.": \n";//.print_r($node->field_taxonomy[LANGUAGE_NONE],true)."\n";
		foreach( $node->field_taxonomy[LANGUAGE_NONE] as $trow){
			$oldterm = taxonomy_term_load($trow['tid']);
			if(!$oldterm){ print "Couldn't load old term\n"; continue; }
			//print "Loaded term.. ".print_r($oldterm->name,true)."\n";
			$row_fields = ($row->type=='job'?$job_vocab:$emp_vocab); 
			foreach( $row_fields as $vkey){
				$matches = taxonomy_get_term_by_name($oldterm->name, $vkey);
				if(!$matches || empty($matches)) continue;
				//print "Found matches $vkey ".print_r($matches,true)."\n"; continue;
				$vfield2 = 'field_'.$vkey;
				$match_id = array_keys($matches); 
				//array_push( $node->$vfield2[LANGUAGE_NONE], array('tid'=>$match_id[0]) );
				if(!isset($new_tags[$vkey])) $new_tags[$vkey] = array();
				array_push( $new_tags[$vkey], array('tid'=> intval($match_id[0]) ) );
				//print $oldterm->name." $vfield2 ".print_r($node->$vfield2[LANGUAGE_NONE],true)."\n";
			}
		}
		//print_r($new_tags);
		foreach( $new_tags as $vkey => $varr){ 
			$vkey='field_'.$vkey; 
			if(empty($node->$vkey)){
				//$node->$vkey = array('und'=>array());
			}
			print "orig $vkey ".print_r($node->$vkey,true)."\n";
			$node->$vkey = array(LANGUAGE_NONE=>$varr);
		}
		node_save($node);
		print "node saved\n";
	}
	exit;
	*/

	/** Go through jobs xml and set terms -- don't use! have to set term to correct vocab **/
	/*
	$allterms = 'All-terms';
	for($i=1; $i<count($xml_data->node); $i++){
		$node = node_load( intval($xml_data->node[$i]->Nid) );
                if(!$node) continue;
                if($xml_data->node[$i]->$allterms){
                        $terms = array();
                        $termstrings = explode(',',$xml_data->node[$i]->$allterms);
                        foreach($termstrings as $term){
                                $tid = _get_tid_from_term_name( trim($term) );
                                if(!$tid) continue;
                                array_push($terms, array('tid'=>$tid));
                        }
                        //print "New term array: ".print_r($terms,true)."\n";
                        $node->field_taxonomy[$node->language] = $terms;
                        node_save($node);
                }
	}
	print "Jobs updated"; exit;
	*/

	/*
	print "Employee XML parsed ".count($xml_data->node)."\n";
	$upload = "Upload-Resume"; $allterms = "All-terms";
	for($i=1; $i<count($xml_data->node);$i++){ //$xml_data->node as $node){
		$node = node_load( intval($xml_data->node[$i]->Nid) );
		if(!$node) continue;
		if($xml_data->node[$i]->$allterms){
			$terms = array();
			$termstrings = explode(',',$xml_data->node[$i]->$allterms);
			foreach($termstrings as $term){
				$tid = _get_tid_from_term_name( trim($term) );
				if(!$tid) continue;
				array_push($terms, array('tid'=>$tid));
			}
			//print "New term array: ".print_r($terms,true)."\n";
			$node->field_taxonomy[$node->language] = $terms;
			node_save($node);
		}
		if(!$xml_data->node[$i]->$upload || !$xml_data->node[$i]->Nid ) continue;
		//$node = node_load( intval($xml_data->node[$i]->Nid) );
		if(!$node){ print "Couldn't load this employee (".$xml_data->node[$i]->Nid.")\n"; continue; }
		$resume_name = basename($xml_data->node[$i]->$upload);
		$file = file_save_data( file_get_contents('/home/ubuntu/skills/sites/default/files/resumes/'.$resume_name), 'public://'.$resume_name);
		$file->uid = intval($xml_data->node[$i]->Uid);
		$node->field_employee_resume[$node->language][0] = (array)$file;
		$node->field_employee_resume[$node->language][0]['display'] = 1;
		node_save($node);
		//print $xml_data->node[$i]->title." -- ".$xml_data->node[$i]->Upload-Resume."\n";
		//print $i." ".$xml_data->node[$i]->$upload."\n"; print_r($xml_data->node[$i]);
		
	}
	exit;
	*/


	function _get_tid_from_term_name($term_name,$vocab_name=null,$skip_add=false) {
	if($vocab_name) { $vocab_name = str_replace(' ','_',strtolower($row['vocab_name'])); }
  	$vocabulary = $vocab_name ? $vocab_name : null;
  
  print "$vocabulary -- $term_name \n";
  $arr_terms = taxonomy_get_term_by_name($term_name, $vocabulary);
  if (!empty($arr_terms)) {
    $arr_terms = array_values($arr_terms);
    $tid = $arr_terms[0]->tid;
  }
   else {
    if($skip_add){
	return null;
	}
    $vobj = taxonomy_vocabulary_machine_name_load($vocabulary);
    $term = new stdClass();
    $term->name = $term_name;
    $term->vid = $vobj->vid;
    taxonomy_term_save($term);
    $tid = $term->tid;
  }
  return $tid;
}



	function loginGetXML($xml_url) {
		$crl = curl_init();
		curl_setopt($crl, CURLOPT_COOKIEFILE, "/tmp/cookie.txt");
		curl_setopt($crl, CURLOPT_COOKIEJAR, "/tmp/cookie.txt");
		curl_setopt($crl, CURLOPT_FOLLOWLOCATION, 1); // Follow 'Location' headers.
		curl_setopt($crl, CURLOPT_RETURNTRANSFER, 1); // Return query results as a string rather than printing them.
		$login_url = 'http://52.24.70.204/www_tsskills/user/login';
		curl_setopt($crl, CURLOPT_URL, $login_url);
curl_setopt($crl, CURLOPT_POST, 1);
$postdata = array(
  "name" => 'admin',
  "pass" => 'gunslinger',
  "form_id" => "user_login",
  "op" => "Log in",
);
curl_setopt($crl, CURLOPT_POSTFIELDS, $postdata);
$result = curl_exec($crl);
$headers = curl_getinfo($crl);
if ($headers['url'] == $login_url) {
  exit("Could not log in\n"); // Or already logged in - simply no way of knowing.
}
	echo "Downloading file $xml_url \n";
set_time_limit(0); // Useful when downloading big files, as it prevents timeout of the script.
curl_setopt($crl, CURLOPT_URL, $xml_url);
curl_setopt($crl, CURLOPT_POST, 0);
curl_setopt($crl, CURLOPT_TIMEOUT, 60); // 60 seconds.
	$xml_data = curl_exec($crl);
	return $xml_data;
	}







   /** import users from serialized D6 data **/
   /*
   $users_file = "/home/ubuntu/migrate/migrate_users.txt";
   $recoveredData = file_get_contents($users_file);
   $recoveredArray = unserialize($recoveredData);
   print "Loaded user array of ".count($recoveredArray)."\n";
   $role_ids = array('employee'=>4,'employer'=>5,'Recruiter'=>6);
   foreach($recoveredArray as $row) {
	if($row['name']=='admin' || $row['name']=='administrator' || !$row['name']) continue;
        if($user = user_load_by_name($row['name']) ) {
		continue;
		//print "Found user ".$row['name']." -- ".$user->name." [".$user->uid."]\n"; exit;
		//user_delete($user->uid);
	}
	//continue;

	$hashed_pass =  'U'.user_hash_password($row['pass'],11);

	$account = new stdClass;
	$account->is_new = TRUE;
        $account->name = $row['name'];
        $account->pass = $hashed_pass;
        $account->mail = $row['mail'];
        $account->init = $row['mail'];
	$account->signature = $row['signature'];
        $account->status = TRUE;
	$account->uid = $row['uid'];
        $account->roles = array(DRUPAL_AUTHENTICATED_RID => TRUE);
	if(array_key_exists($row['role_name'], $role_ids) ){
		$account->roles[ $role_ids[ $row['role_name'] ] ] = TRUE;
	}
        $account->timezone = $row['timezone'] ? $row['timezone'] : variable_get('date_default_timezone', '');
	$account->language = $row['language'];
	$account->data = unserialize($row['data']);

	//print "Import user ".$row['name']."\n".print_r($row,true)."\n".print_r($account,true)."\n";
	user_save($account);

	//break;

   }
   */
