<?php
/** Read in the logs for employees with missing resumes and try to attach the correct file **/
print "Disabled\n"; exit();

$_SERVER['HTTP_HOST'] = 'example.org';
$_SERVER['REMOTE_ADDR'] = '127.0.0.1';

$servername = "localhost";
$username = "root";
$password = "gunslinger";
$dbname="import_20170516";
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

$RESUME_DIR = '/home/ubuntu/migrate/2017_resume/';
$RESUME_LOG = '/home/ubuntu/skills/migrate_resumes.txt';

print "D7 Bootstrapped\n";


$f_resumes = fopen($RESUME_LOG, 'r');
$rA = array();
while($fline = fgets($f_resumes) ){
	$obj = unserialize($fline);
	array_push($rA, $obj);
}
fclose($f_resumes);

print "Read file array: ".count($rA)."\n";

foreach($rA as $oldfile){
	print "Trying to attach fid ".$oldfile['fid']." -- ".$oldfile['filename']."\n";
	$result = $D6CONN->query('select content_type_employee.*, node.uid, node.title from  content_type_employee left join node on content_type_employee.nid = node.nid where field_employee_resume_fid =  '.$oldfile['fid']);
	if(!$result || $result->num_rows < 1){
		print "Could not find employee with this file\n"; continue;
	}
	$oldemp = $result->fetch_assoc();
	if(!$oldemp['title'] || strlen($oldemp['title']) < 2){
		print "Didn't find valid title for this employee\n"; continue;
	}
	//print_r($oldemp); print "\n";
	$nresult = $D7CONN->query('select nid from node where type = "employee" and title = "'.$oldemp['title'].'" limit 1');
	if(!$nresult || $nresult->num_rows < 1){
		print "Error: Could not find new employee record with title '".$oldemp['title']."' \n"; continue;
	}
	$nrow = $nresult->fetch_assoc();
	$node = node_load(intval($nrow['nid']));
	print "Loaded new employee record -- ".$node->title." [".$node->nid."]\n";

	$full_file = $RESUME_DIR . $oldfile['filename'];
	if(!file_exists($full_file)){
		print "Error: could not find file at $full_file \n"; continue;
	}
	//print "file: $full_file \n"; continue;
	store_file($node, $full_file, 'field_employee_resume');
	print "Saved resume\n";
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
}

