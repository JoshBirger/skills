<?php 

/* migrate from a D6 database
imports:
-users
-employee profiles
-employer profile
-jobs
-any related tags
*/

// set HTTP_HOST or drupal will refuse to bootstrap
$_SERVER['HTTP_HOST'] = 'example.org';
$_SERVER['REMOTE_ADDR'] = '127.0.0.1';

$servername = "localhost";
$username = "root";
$password = "gunslinger";
$dbname="nestor_import";
// Create connection
$D6CONN = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($D6CONN->connect_error) {
    die("Connection 6 failed: " . $D6CONN->connect_error);
} 
$D7CONN = new mysqli($servername, $username, $password, 'nestor');
// Check connection
if ($D7CONN->connect_error) {
    die("Connection 7 failed: " . $D7CONN->connect_error);
} 
echo "Connected successfully to D6 and D7 DB";

//root of Drupal 7 site
$DRUPAL7_ROOT="/home/ubuntu/skills";
define('DRUPAL_ROOT',$DRUPAL7_ROOT);
chdir($DRUPAL7_ROOT);
require_once "./includes/bootstrap.inc";
drupal_bootstrap(DRUPAL_BOOTSTRAP_FULL);
require_once "./includes/password.inc";

print "D7 Bootstrapped\n";


//first get all D6 users
$roles = array();
$roles_d7 = array('employee'=>4,'employer'=>5,'Recruiter'=>6);
$result = $D6CONN->query('SELECT * FROM role');
if(!$result || $result->num_rows < 1){
	die("Couldnt load role table");
}
while($row = $result->fetch_assoc()){
	$roles[ $row['name'] ] = $row['rid'];
	$roles[ $row['rid'] ] = $row['name'];
}

$usersArray = array(); 
$uids = array(); 
//load array from mysql + role
// select user.* from users as user;
$result = $D6CONN->query('select users_roles.rid as role_id, users.* from users left join users_roles on users.uid = users_roles.uid where users.uid >5100');
print "Found ".$result->num_rows." users\n";
if(!$result || $result->num_rows < 1){ die("Could not load users"); }
$imported = array('users'=>0,'jobs'=>0,'employee'=>0,'employer'=>0);

/*
$f_resumes = fopen('migrate_resumes.txt','w');
if($f_resumes){
	print "Opened migrate_resumes.txt for logging files that need to be copied over\n";
} else {
	die("Cannot open migrate_resumes.txt for logging\n");
}

while($row = $result->fetch_assoc() ){
	if(!$row['role_id'] || ($row['role_id']!=3 && $row['role_id']!=4 && $row['role_id']!= 6 ) ) continue;
	//.print_r($row,true)."\n"; break;
	$account = existsUser($row['uid']);
	$newUser = $account ? false : true;
	$uid = $account ? null : $row['uid'];
	if(!$account){
		print "Processing user [".$row['uid']."] ".$row['name']."\n"; 
		$row['role_name'] = isset($roles[ $row['role_id'] ]) ? $roles[$row['role_id']] : null;
		//if($row['role_id']!=$roles['employer']){ continue; }
		addUser($row, $roles_d7);
		$account = user_load(array('uid' => $uid));
		if(!$account || empty($account) || !$account->uid){
			print "ERROR: Unable to create user ".$uid."\n";
			break;
		}
		$imported['users']++;
		
		if($row['role_id']==$roles['employee']){
			$added = addUserEmployee($account, $D6CONN, $f_resumes, $D7CONN);
			if($added){ $imported['employee']++; }
			else { 
				print "Couldn't create employee for this user\n";
				break; 
			}
		} else if($row['role_id']==$roles['employer']){
			$added = addUserEmployer($account, $D6CONN);
			if($added){ $imported['employer']++; }
			else { 
				print "Couldn't create employer for this user\n";
				break; 
			}
		} else {

		} 
		
	} else if($row['name'] != $account->name) {
		//print "Found user with this uid already exists - ".$account->name."\n";
		continue;
	} else {
		//print "This user already exists, using existing record\n";
		$uid = $row['uid'];
		continue;
	}

	//break;
}

fclose($f_resumes);
print "Imported ".print_r($imported,true)."\n";


//jobs
//first get last created from d7
$result = $D7CONN->query('select title, uid, created, type from node where type = "job" order by created desc limit 1');
if(!$result || $result->num_rows < 1){
	die("ERROR: no jobs found\n");
}
$row7 = $result->fetch_assoc();
print "Searching for jobs newer than ".$row7['created']."\n";

$result = $D6CONN->query('select node_revisions.body, content_type_job.*, node.uid, node.title, users.name, node.created from content_type_job left join node on content_type_job.nid = node.nid left join users on node.uid = users.uid left join node_revisions on node.nid = node_revisions.nid and node.vid = node_revisions.vid where node.nid IS NOT NULL and node.created > '.$row7['created']);
if(!$result || $result->num_rows < 1){
	die("ERROR: no jobs found\n");
}
print "Found ".$result->num_rows." newer jobs\n";
$equery = new EntityFieldQuery();
while($row = $result->fetch_assoc()){
	$added = addJob($row, $D6CONN,$equery,$D7CONN);
	if($added){
		$imported['jobs']++;
	}
	
}


print "Imported ".print_r($imported,true)."\n";
*/

//clean up duplicate locations from new db
print "Loading jobs to look for duplicate \n";
$qLoc = 'select node.nid, node.created, node.type, location_instance.genid, location.* from location_instance left join node on location_instance.nid = node.nid left join location on location_instance.lid = location.lid where node.type="job" and node.nid > 12000 order by node.nid';
$result = $D7CONN->query($qLoc);
	//$result = $conn7->query('select * from location_instance left join location on location_instance.lid = location.lid  where nid = '.$node->nid);
	if(!$result || $result->num_rows < 1){
		print("ERROR: no location entries found for this node\n");
	} else {
		$good_row = null; 
		$good_info = array();
		$curNid = null;
		$bad_row=null;
		while($row = $result->fetch_assoc() ){
			print "Nid: ".$row['nid']." - last: ".$curNid."\n";
			if($curNid != $row['nid'] ){
				if($good_row != null){
					print "Update location instance with : ".print_r($good_info,true)."\n";
					$qUp = "UPDATE location SET postal_code=".$good_info['postal_code'].", latitude=".$good_info['latitude'].", longitude=".$good_info['longitude']." WHERE lid = ".$good_row." LIMIT 1";
					$qDel1 = "DELETE FROM location where  lid = ".$bad_row." LIMIT 1";
					$qDel2 = "DELETE FROM location_instance where nid =".$good_info['nid']." AND lid != ".$good_row." LIMIT 1";
					print "Queries: $qUp \n $qDel1 \n $qDel2 \n";
					$D7CONN->query($qUp);$D7CONN->query($qDel1);$D7CONN->query($qDel2);

				}
				print "Clear row\n";
				$good_row=null; $good_info=array(); $curNid = $row['nid']; $bad_row=null;
			} else {
				print "Match\n";
			}

			if($row['genid']) { $good_row = $row['lid']; 
			} else { $bad_row = $row['lid']; } 
			foreach($row as $k=>$v){
				if(!$v || $v == 0) continue;
				if(isset($good_info[$k]) ) continue;
				$good_info[$k] = $v;
			}
		}
		if($curNid != $row['nid'] ){
				if($good_row != null){
					print "Update location instance with : ".print_r($good_info,true)."\n";
					$qUp = "UPDATE location SET postal_code=".$good_info['postal_code'].", latitude=".$good_info['latitude'].", longitude=".$good_info['longitude']." WHERE lid = ".$good_row." LIMIT 1";
					$qDel1 = "DELETE FROM location where  lid = ".$bad_row." LIMIT 1";
					$qDel2 = "DELETE FROM location_instance where nid =".$good_info['nid']." AND lid != ".$good_row." LIMIT 1";
					print "Queries: $qUp \n $qDel1 \n $qDel2 \n";
					$D7CONN->query($qUp);$D7CONN->query($qDel1);$D7CONN->query($qDel2);
					
				}
				print "Clear row\n";
				$good_row=null; $good_info=array(); $curNid = $row['nid']; $bad_row=null;
			} else {
				print "Match\n";
			}
	}



/* ----------------------------------- */

function addJob($row, $conn,$equery, $conn7){
	//$existing = node_load(intval($row['nid']));
	$curUser = user_load_by_name($row['name']);
	if(!$curUser){
		print "Could not load D7 user [".$row['name']."]\n";
		return false;
	}
	$uid = $curUser->uid;
	$existing = $equery->entityCondition('entity_type','node')
		->entityCondition('bundle','job')
		->propertyCondition('uid',$uid)
		->propertyCondition('title',$row['title'])
		->execute();
	if(!empty($existing['node'])){
		$existing = node_load(array_shift(array_keys($existing['node'])));
	} else {
		$existing = false;
	}
	if($existing){
		print "Job: ".$row['nid']." - ".$row['title']." already exists as ".$existing->title."\n";
		//return false;
	}
	$node = new stdClass();
	$node->title = $row['title'];
	$node->type = "job";
	
	node_object_prepare($node); // Sets some defaults. Invokes hook_prepare() and hook_node_prepare().
	if($existing) { //when importing dont keep the same nid as there are new pages
		$node->nid = $existing->nid; 
	} else {
		$node->is_new = true;
	}
	$node->language = LANGUAGE_NONE; // Or e.g. 'en' if locale is enabled
	$node->uid = $curUser->uid;
	$node->status = 1; //(1 or 0): published or not
	$node->promote = 0; //(1 or 0): promoted to front page
	$node->comment = 0;
	$node->vid = 0;
	$node->created = $row['created'];
	$node->body[LANGUAGE_NONE][0]['value'] = utf8_encode(str_replace('<p>','',$row['body']));
	$node->field_job_requirements[LANGUAGE_NONE][0]['value'] = utf8_encode(str_replace('<p>','',$row['field_job_requirements_value']));
	$node->field_job_requirements[LANGUAGE_NONE][0]['format'] = $row['field_job_requirements_format'];
	$node->field_job_number[LANGUAGE_NONE][0]['value'] = $row['field_job_number_value'];
	$node->field_job_email[LANGUAGE_NONE][0]['value'] = $row['field_job_email_value'];
	
	//terms
	$vTof = array('Clearance'=>'field_clearance','Availability'=>'field_availability','Position Type'=>'field_position_type','Job Category'=>'field_job_category','Funding Status'=>'field_funding_status');
	$all_terms = getOldTerms($row, $conn);
	foreach($all_terms as $vname => $vlist){
		//print "Vocab ".$vname." has ".count($vlist)." terms to add\n";
		if(!isset($vTof[$vname]) ){
			print "Vocab does not have matching field\n"; continue;
		}
		$vocab_field = $vTof[$vname];
		$terms = array();
		foreach($vlist as $tnode){
			$tid = _get_tid_from_term_name( trim($tnode['name']), $vname );
			if(!$tid) {
				print "Did not get tid for ".$tnode['name']." - ".$vname."\n";
				continue;
			}
			array_push($terms, array('tid'=>$tid));
		}
		$node->{$vocab_field}[$node->language] = $terms;
		//print "Set $vocab_field ".count($terms)." term ids (".print_r($terms,true).")\n".print_r($node->{$vocab_field},true)."\n";
	}


	//print "Adding job:\n".print_r($node,true)."\n";

	//$node = node_submit($node); // Prepare node for saving
	try {
		if(!$node->uid)  $node->uid = $curUser->uid;
		node_save($node);
		if(!$node->uid) $node->uid = intval($curUser->uid);
		node_save($node);
		print "Job added (old# ".$row['nid'].") [".$node->title."] - #".$node->nid." to user ".$curUser->uid." - ".$node->uid."\n";
		//$node = node_load($node->nid);
		//$node->uid = $curUser->uid;
		//node_save($node);
		//print "Reloaded nid to set author - ".$node->uid."\n";
		if(!$node->uid && $node->nid){
			print "Setting job author directly in the database\n";
			$conn7->query('update node set uid = '.$curUser->uid.' where nid = '.$node->nid.' limit 1');
		}

	} catch(Exception $e){
		print "ERROR Saving job: ".$e->getMessage()."\n";
		throw $e;
	}
	
	//location
	setNodeLocation($row, $node, $conn);



	return true;
}

function addUserEmployee($account, $conn, $f_resumes, $conn_new){
	$query = 'select nid, type, vid, title from node where type = "employee" and uid = "'.$account->uid.'" LIMIT 1';
	print "query: $query\n";
	$result = $conn->query($query);
	if(!$result || $result->num_rows < 1){
		print "Did not find employee record for user ".$account->uid." -- ".$account->name."\n";
		return false;
	}
	$oldnode = $result->fetch_assoc();
	$query = 'select * from content_type_employee where nid = '.$oldnode['nid'].' and vid = '.$oldnode['vid'].' limit 1';
	$result = $conn->query($query);
	if(!$result || $result->num_rows < 1){
		print "Did not find employee content type for this node, clearing nid to create new one\n";
		return false;
	}
	$row = $result->fetch_assoc();
	$existing = node_load(intval($oldnode['nid']));
	if($existing){
		print "There already exists an employee record with this nid\n";
		//return false;
	}

	print "Creating new employee record [".$oldnode['nid']."].. \n";
	$node = new stdClass();
	$node->title = $oldnode['title'];
	$node->type = "employee";
	
	node_object_prepare($node); // Sets some defaults. Invokes hook_prepare() and hook_node_prepare().
	if(!$existing){ 
		//$node->nid = intval($oldnode['nid']); 
	}
	$node->language = LANGUAGE_NONE; // Or e.g. 'en' if locale is enabled
	$node->uid = $account->uid; 
	$node->status = 1; //(1 or 0): published or not
	$node->promote = 0; //(1 or 0): promoted to front page
	$node->comment = 0;
	$node->is_new = true; 
	$node->vid = 0;
	/** go through all the specific fields and try to convert them **/
	$node->field_employee_fname[LANGUAGE_NONE][0]['value'] = $row['field_employee_fname_value'];
	$node->field_employee_lname[LANGUAGE_NONE][0]['value'] = $row['field_employee_lname_value'];
	$node->field_employee_telephone[LANGUAGE_NONE][0]['value'] = $row['field_employee_telephone_value'];
	$node->field_employee_telephone_ext[LANGUAGE_NONE][0]['value'] = $row['field_employee_telephone_ext_value'];
	$node->field_employee_cellphone[LANGUAGE_NONE][0]['value'] = $row['field_employee_cellphone_value'];
	$node->field_employee_skills[LANGUAGE_NONE][0]['value'] = utf8_encode($row['field_employee_skills_value']);
	$node->field_employee_skills[LANGUAGE_NONE][0]['format'] = $row['field_employee_skills_format'];
	$node->field_employee_message[LANGUAGE_NONE][0]['value'] = utf8_encode($row['field_employee_message_value']);
	$node->field_employee_message[LANGUAGE_NONE][0]['format'] = $row['field_employee_message_format'];
	$node->field_employee_street_add[LANGUAGE_NONE][0]['value'] = $row['field_employee_street_add_value'];
	$node->field_employee_zip[LANGUAGE_NONE][0]['value'] = $row['field_employee_zip_value'];
	$node->field_employee_text_resume[LANGUAGE_NONE][0]['value'] = utf8_encode( $row['field_employee_text_resume_value'] );
	$node->field_employee_message[LANGUAGE_NONE][0]['format'] = $row['field_employee_text_resume_format'];
	$node->field_notes[LANGUAGE_NONE][0]['value'] = utf8_encode($row['field_notes_value']);
	$node->field_notes[LANGUAGE_NONE][0]['format'] = $row['field_notes_format'];


	//terms
	$vTof = array('Clearance'=>'field_clearance','Availability'=>'field_availability','Position Type'=>'field_position_type','Job Category'=>'field_job_category','Funding Status'=>'field_funding_status');
	$all_terms = getOldTerms($oldnode, $conn);
	foreach($all_terms as $vname => $vlist){
		//print "Vocab ".$vname." has ".count($vlist)." terms to add\n";
		if(!isset($vTof[$vname]) ){
			print "Vocab does not have matching field\n"; continue;
		}
		$vocab_field = $vTof[$vname];
		$terms = array();
		foreach($vlist as $tnode){
			$tid = _get_tid_from_term_name( trim($tnode['name']), $vname );
			if(!$tid) {
				print "Did not get tid for ".$tnode['name']." - ".$vname."\n";
				continue;
			}
			array_push($terms, array('tid'=>$tid));
		}
		$node->{$vocab_field}[$node->language] = $terms;
		//print "Set $vocab_field ".count($terms)." term ids (".print_r($terms,true).")\n".print_r($node->{$vocab_field},true)."\n";
	}

	try {
		node_save($node);
		print "User employee [".$oldnode['nid']."] ".$node->title." saved as [".$node->nid."]\n";

	} catch(Exception $e){
		print "ERROR Saving employee: ".$e->getMessage()."\n";
		throw $e;
	}

	//location
	setNodeLocation($oldnode, $node, $conn);

	//resume
	if($row['field_employee_resume_fid']){
		$fid = $row['field_employee_resume_fid'];
		$result = $conn->query('select * from files where fid = '.$row['field_employee_resume_fid']);
		if($result && $result->num_rows > 0){
			$frow = $result->fetch_assoc();
			$frow['employee_nid'] = $node->nid;
			print "Found file that needs to be uploaded: ".$frow['filepath']."\n";
			fwrite($f_resumes, serialize($frow)."\n");
			
			$filename = basename($frow['filepath']);
			if( store_file($node, '/home/ubuntu/migrate/private/private/resumes/'.$filename, 'field_employee_resume') ){
				print "Saved resume\n";
			} else {
				print "Error saving resume\n";
			}
			/*
			$queryI = 'INSERT INTO file_managed (fid, uid, filename, uri, filemime, filesize, status, timestamp) VALUES ('.$fid.','.$account->uid.',"'.
				$frow['filename'].'","public://'.
				$filename.'","'.$frow['filemime'].'",'.$frow['filesize'].',1,'.$frow['timestamp'].')';
			if(!$conn_new->query($queryI)){
				print "ERROR: Could not insert file_managed : ".$conn_new->error."\n";
				return false;
			}

			$queryI = 'INSERT INTO file_usage (fid, module, type, count) VALUES ('.$fid.',"file","node",1)';
			$conn_new->query($queryI);

			$node->field_employee_resume[LANGUAGE_NONE][0] = array(
					'display' => 1,
					'fid' => $fid,
					'filename' => $frow['filename'],
					'uri' => 'public://'.$filename,
					'filemime' => $frow['filemime'],
					'filesize' => $frow['filesize'],
					'status' => 1,
					'timestamp' => $frow['timestamp']
				);
			node_save($node);
			print "Attached file database entries\n";
			*/
		}
	}

	return true;
}

function addUserEmployer($account, $conn){
	$query = 'select nid, type, vid, title from node where type = "employer" and uid = "'.$account->uid.'" LIMIT 1';
	print "query: $query\n";
	$result = $conn->query($query);
	if(!$result || $result->num_rows < 1){
		print "Did not find employer record for user ".$account->uid." -- ".$account->name."\n";
		return false;
	}
	$oldnode = $result->fetch_assoc();
	$query = 'select * from content_type_employer where nid = '.$oldnode['nid'].' and vid = '.$oldnode['vid'].' limit 1';
	$result = $conn->query($query);
	if(!$result || $result->num_rows < 1){
		print "Did not find employer content type for this node\n";
		return false;
	}
	$row = $result->fetch_assoc();
	$existing = node_load(intval($oldnode['nid']));
	if($existing){
		print "There already exists an employer record with this nid\n";
		//return false;
	}

	print "Creating new employer record [".$oldnode['nid']."].. \n";
	$node = new stdClass();
	$node->title = $oldnode['title'];
	$node->type = "employer";
	if(!$existing){ /*$node->nid = $oldnode['nid'];*/ }
	node_object_prepare($node); // Sets some defaults. Invokes hook_prepare() and hook_node_prepare().
	$node->language = LANGUAGE_NONE; // Or e.g. 'en' if locale is enabled
	$node->uid = $account->uid; 
	$node->status = 1; //(1 or 0): published or not
	$node->promote = 0; //(1 or 0): promoted to front page
	$node->comment = 0;

	/** go through all the specific fields and try to convert them **/
	$node->field_employer_fname[LANGUAGE_NONE][0]['value'] = $row['field_employer_fname_value'];
	$node->field_employer_lname[LANGUAGE_NONE][0]['value'] = $row['field_employer_lname_value'];
	$node->field_employer_phone[LANGUAGE_NONE][0]['value'] = $row['field_employer_phone_value'];
	$node->field_employer_ext[LANGUAGE_NONE][0]['value'] = $row['field_employer_ext_value'];
	
	$node->field_employer_message[LANGUAGE_NONE][0]['value'] = utf8_encode(str_replace('<p>','',$row['field_employer_message_value']));
	$node->field_employer_message[LANGUAGE_NONE][0]['format'] = $row['field_employer_message_format'];
	$node->field_employer_cprofile[LANGUAGE_NONE][0]['value'] = utf8_encode(str_replace('<p>','',$row['field_employer_cprofile_value']));
	$node->field_employer_cprofile[LANGUAGE_NONE][0]['format'] = $row['field_employer_cprofile_format'];

	$node->field_employer_address[LANGUAGE_NONE][0]['value'] = $row['field_employer_address_value'];
	$node->field_employer_zip[LANGUAGE_NONE][0]['value'] = $row['field_employer_zip_value'];
	$node->field_employer_city[LANGUAGE_NONE][0]['value'] = $row['field_employer_city_value'];

	try {
		node_save($node);
		print "User employer [".$oldnode['nid']."] ".$node->title." saved as [".$node->nid."]\n";
	} catch(Exception $e){
		print "ERROR Saving employer: ".$e->getMessage()."\n";
		throw $e;
	}

	//location
	setNodeLocation($oldnode, $node, $conn);

	return true;
}


function getOldTerms($node, $conn){
	$result = $conn->query('select * from vocabulary');
	if(!$result || $result->num_rows < 1){
		print "Couldnt load vocab\n";
		return array();
	}
	$vids = array();
	while($row = $result->fetch_assoc()){
		$vids[ $row['vid'] ]= $row['name'];
	}
	$query = 'select term_node.tid, term_data.name, term_data.vid from term_node left join term_data on term_node.tid = term_data.tid where term_node.nid = '.$node['nid'];
	$result = $conn->query($query);
	if(!$result || $result->num_rows < 1){
		print "no terms found: $query\n";
		return array();
	}
	print "Found ".$result->num_rows." terms\n";
	$terms = array();
	while($row = $result->fetch_assoc()){
		if( isset($vids[ $row['vid'] ]) ){
			$vname = $vids[ $row['vid'] ];
			if(!isset($terms[ $vname ]) ){
				$terms[ $vname ] = array();
			}
			array_push($terms[ $vname ], $row);
		}
	}
	return $terms;
}

function setNodeLocation($row,$node,$conn){
	$result = $conn->query('select lid from location_instance where nid = '.$row['nid'].' and vid = '.$row['vid']);
	if(!$result || $result->num_rows < 1){
		print "Couldnt find location instance for nid ".$row['nid']."\n";
		return false;
	}
	$lid = $result->fetch_assoc();
	$lid = $lid['lid'];
	$result = $conn->query('select * from location where lid = '.$lid);
	if(!$result || $result->num_rows < 1){
		print "Couldnt find location instance for nid ".$row['nid']." and lid ".$lid."\n";
		return false;
	}
	$locations = array();
	$locations[0] = $result->fetch_assoc();
	$criteria = array(
		'nid' => $node->nid,
		'vid' => $node->vid,
		'genid' => $node->type
		);
	print "Saving location ".$lid." to nid ".$node->nid."\n";
	try {
		location_save_locations($locations, $criteria);
	} catch(Exception $e){
		print "Error saving location for ".$node->nid." : ".$e->getMessage()."\n";
	}
}

function existsUser($uid){
	$account = user_load(array('uid' => $uid));
	if ( empty($account) || $account->uid != $uid ) {
	  return null;
	}
	else {
	  return $account;
	}
}

function addUser($row, $role_ids){
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
	user_save($account);
	return $account->uid;
}


function _get_tid_from_term_name($term_name,$vocab_name=null,$skip_add=false) {
	if($vocab_name) { $vocab_name = str_replace(' ','_',strtolower($vocab_name)); }
  	$vocabulary = $vocab_name ? $vocab_name : null;
  
  //print "$vocabulary -- $term_name \n";
  $arr_terms = taxonomy_get_term_by_name($term_name, $vocabulary);
  if (!empty($arr_terms)) {
    $arr_terms = array_values($arr_terms);
    $tid = $arr_terms[0]->tid;
  } else {
    if($skip_add){
    	print "Not found, not adding $vocabulary -- $term_name\n";
	return null;
	}
	print "Adding new term $vocabulary -- $term_name \n";
    $vobj = taxonomy_vocabulary_machine_name_load($vocabulary);
    $term = new stdClass();
    $term->name = $term_name;
    $term->vid = $vobj->vid;
    taxonomy_term_save($term);
    $tid = $term->tid;
  }
  return $tid;
}

/**
 * @param node the node to which you want to add the file
 * @param file the path to the file: e.g.: /tmp/image.jpg
 * @param fieldname the name of the field to store the file
 * @return TRUE if success, FALSE if not
 */
function store_file($node, $filepath, $fieldname) {
    if (!isset($filepath) || !file_exists($filepath) ) {
        print('ERROR: File does not exists');
        return FALSE;
    }


    
    $file = (object) array(
    	'uid' => $node->uid,
    	'uri' => $filepath,
    	'filemime' => file_get_mimetype($filepath),
    	'status' => 1
    	);
    $file = file_copy($file, 'public://');
    $node->field_employee_resume[$node->language][0] = (array)$file;
    $node->field_employee_resume[$node->language][0]['display'] = 1;
    node_save($node);
    return true;
 
 /*
    $details = stat($file);
    $filesize = $details['size'];
    $dest = 'public://'; //file_directory_path();
    if(!file_copy($file,$dest)) {
        drupal_set_message("Failed to move file: $file");
        return FALSE;
    } else {
        $name = basename($file);
    }
 
    // build the file object
    $file_obj = array();
    $file_obj['filename'] = $name;
    $file_obj['filepath'] = $file;
    $file_obj['filemime'] =  file_get_mimetype($name);
    $file_obj['filesize'] = $filesize;
    $file_obj['status'] = FILE_STATUS_TEMPORARY;
    $file_obj['timestamp'] = time();
    $file_obj['list'] = 1;
    $file_obj['new'] = TRUE;
    $file_obj['data']['alt'] = 'foo';
    $file_obj['data']['title'] = 'bar';
    $file_obj['uid'] = '1'; // 1 = admin, but you can do here whatever you want ...
 
    // save file to files table, fid will be set
    drupal_write_record('files', $file_obj);
 
    // change file status to permanent
    file_set_status($file_obj, 1);
 
    // attach the file object to your node
    $node->$fieldname[LANGUAGE_NONE][0] = $file_obj; // property can be different 
    return TRUE;
	*/
}