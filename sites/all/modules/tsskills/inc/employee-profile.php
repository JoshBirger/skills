<?php
/** display page for employee profile menu options **/



function tsskills_employee_menu_old(&$account,$user){
	$imgbase = file_create_url('public://');
	unset($account->content['user-picture']);
	$account->content['employeemenu'] = array('#markup'=>'

	<div class="left prepand">
          <div class="round"><img src="'.$imgbase.'tsskills/tsskills-round10.gif" alt="Round"></div>
          <div class="jobseekers-left">
            <div class="jobseekers-left-body">
              <div class="">
		Welcome to TSSkills, if you are looking for a permanent position or contract, TSSkills has a mix of employment opportunities.
		</div>
            </div>
            <div class="jobseekers-left-body prepand6 col-lg-12">
              <div class="signup-create col-lg-4">
                <div class="round"><img src="'.$imgbase.'tsskills/tsskills-round11.gif" alt="Round"></div>
                <div class="signup-create-body">
                  <div><img src="'.$imgbase.'tsskills/tsskills-image3.png" alt="Image"></div>
                  <h2 class="prepand3"><a href="/jobs">Find a Job</a></h2>
                  
                </div>
                <div class="round"><img src="'.$imgbase.'tsskills/tsskills-round12.gif" alt="Round"></div>
              </div>
              <div class="signup-create padding6 col-lg-4">
                <div class="round"><img src="'.$imgbase.'tsskills/tsskills-round11.gif" alt="Round"></div>
                <div class="signup-create-body">
                  <div><img src="'.$imgbase.'tsskills/tsskills-image4.png" alt="Image"></div>
                  <h2 class="prepand3"><a href="/user/applied-jobs">Jobs I\'ve Applied to</a></h2>
                 
                </div>
                <div class="round"><img src="'.$imgbase.'tsskills/tsskills-round12.gif" alt="Round"></div>
              </div>
              <div class="signup-create padding6 col-lg-4">
                <div class="round"><img src="'.$imgbase.'tsskills/tsskills-round11.gif" alt="Round"></div>
                <div class="signup-create-body">
                  <div><img src="'.$imgbase.'tsskills/tsskills-image5.png" alt="Image"></div>
                  <h2 class="prepand3"><a href="/user/'.$user->uid.'/edit/employee">Manage your resume, <br>
                    update certs/degrees <br>
                    etc.</a></h2>
                </div>
                <div class="round"><img src="'.$imgbase.'/tsskills/tsskills-round12.gif" alt="Round"></div>
              </div>
            </div>
            
          </div>
          <div class="round"><img src="'.$imgbase.'tsskills/tsskills-round6.gif" alt="Round"></div>
        </div>
	
	<div style="clear:left;"></div>

	');


}

function tsskills_employee_menu(&$account,$user){
			$employee = '';
			$query = new EntityFieldQuery();
			$entities = $query->entityCondition('entity_type', 'node')
				->propertyCondition('type', 'employee')
				->propertyCondition('uid', $user->uid)
				->propertyCondition('title', $user->name)
				->propertyCondition('status', 1)
				->range(0,1)
				->execute();

			if (!empty($entities['node'])) {
				$employee = node_load(array_shift(array_keys($entities['node'])));
			}
		$zip = ($employee)?$employee->field_employee_zip['und'][0]['value']:'';

        $opts = array(
                array('Find a job','tsskills/find_a_job.png','/jobs/?zipcode='.$zip.'&zip_radius=250'),
                array('Jobs I\'ve Applied to','tsskills/josb_ive_applied_to.png','/user/applied-jobs'),
                array('Manage resume','tsskills/manage_resume.png','/user/'.$user->uid.'/edit/employee')
        );
	$intro = '
		<p>
		Welcome to TSSkills, if you are looking for a permanent position or contract, TSSkills has a mix of employment opportunities.
		</p>
	';
        tsskills_display_menu($account, $user, $intro, $opts);
}


function tsskills_employer_menu(&$account,$user){
	$opts = array(
		array('Create a job','tsskills/create_a_job.png','/user/create-job'),
		array('View your job postings','tsskills/view_your_job_postings.png','/user/'.$user->uid.'/posted-jobs'),
		array('Manage company profile','tsskills/manage_company_profile.png','/user/'.$user->uid.'/edit/employer')
	);
	$intro = '
	<p>
                Welcome to TSSkills, we offer a completely free option for you to advertise your positions to our pool of qualified cleared talent. You may place an unlimited number of free postings for fully funded positions, and the resumes are sent directly to your applicant tracking system. 
This provides a cost-effective way to advertise all of your openings to qualified prospects.
                </p>
                                <div class="col-lg-12 col-xs-12 text-center">
				<h6>Highlights of the Zero Cost Option:</h6>
					<div class="bs-callout bs-callout-info">100% FREE to Post your Positions 
										</div>
                                        <div class="bs-callout bs-callout-info">Unlimited Postings</div>
                                        <div class="bs-callout bs-callout-info">Resumes Sent Directly to Your ATS System</div>
                                        <div class="bs-callout bs-callout-info">All Applicants Qualified and Vetted</div>
                                        
                                </div>
	';
	$outro = '
	
	<div class="col-lg-12 col-xs-12 text-center" style="margin-top: 20px;">
                                <h6>Learn More</h6>
				<p>If you need additional recruiting support TSSkills offers customized solutions to meet your cleared recruiting needs.  Below is a snapshot of our offerings.</p>
                                        <div class="bs-callout bs-callout-info">
					Proposal/Bid Support - TSSkills can build your next winning team and manage the Letter of Intent Process- saving you time to focus on live positions.
                                                                                </div>
                                        <div class="bs-callout bs-callout-info">Contract Resources</div>
                                        <div class="bs-callout bs-callout-info">6 month contract to perm employees</div>
                                        <div class="bs-callout bs-callout-info">Contingent Search</div>
					<p>To learn more, contact TSSkills today at <a href="mailto:admin@tsskills.com">admin@tsskills.com</a></p>
                                </div>


	';

	tsskills_display_menu($account, $user, $intro, $opts, $outro);
}

function tsskills_display_menu(&$account,$user,$intro,$opts,$outro=null){
        $markup = '
		<div class="col-lg-12">
			'.$intro.'
                </div>
		<div class="dashboard-options text-center">
		';
		foreach($opts as $opt){

			$img_style = 'box_170x100';
			$uri = 'public://'.$opt[1];
			$img = image_style_url($img_style,$uri);
		
		
			$markup .= '
			<div class="col-xs-12 col-md-4 text-center">
				<a href="'.drupal_get_path_alias($opt[2]).'">
				<div class="col-lg-8 dashboard-option col-lg-offset-2">
				<div class="col-lg-12 text-center"><img src="'.$img.'" alt="'.$opt[0].'"></div>
				<p>'.$opt[0].'</p>
				</div>
				</a>
			</div>
			';
		}

	if($outro){
		$markup .= '</div>'.$outro . '<div class="clearfix"></div>';
	} else {
	$markup .= '
		</div>
		<div class="clearfix"></div>
	';
	}
	$account->content['employeemenu'] = array('#markup'=>$markup);
}

function tsskills_employer_menu1(&$account,$user){
        $imgbase = file_create_url('public://');
        $account->content['employeemenu'] = array('#markup'=>'

        <div class="left prepand">
          <div class="round"><img src="'.$imgbase.'tsskills/tsskills-round10.gif" alt="Round"></div>
          <div class="jobseekers-left col-lg-12">
            <div class="jobseekers-left-body col-lg-12">
              <div class="font8">Welcome to TSSkills a leading provider of recruitment and talent management solutions within the "TS" and higher communities.  Let TSSkills be your partner in attracting the top  talent in the industry- we offer specialized recruitment support in the following areas:<br>
				<ul>
					<li>Contract resources</li>
					<li>6 month contract to perm employees</li>
					<li>Permanent employees</li>
					<li>Proposal/Bid Support - TSSkills can build your next winning team and manage the Letter of Intent Process- saving you time to focus on live positions.</li>
				</ul>
			   </div>
            </div>
            <div class="jobseekers-left-body prepand6 col-lg-12">
              <div class="signup-create">
                <div class="round"><img src="'.$imgbase.'tsskills/tsskills-round11.gif" alt="Round"></div>
                <div class="signup-create-body col-lg-4">
                  <div><img src="'.$imgbase.'tsskills/tsskills-image3.png" alt="Image"></div>
                  <h2 class="prepand3"><a href="/node/add/job">Create a job</a></h2>
                  
                </div>
                <div class="round"><img src="'.$imgbase.'tsskills/tsskills-round12.gif" alt="Round"></div>
              </div>
              <div class="signup-create padding6 col-lg-4">
                <div class="round"><img src="'.$imgbase.'tsskills/tsskills-round11.gif" alt="Round"></div>
                <div class="signup-create-body">
                  <div><img src="'.$imgbase.'tsskills/tsskills-image4.png" alt="Image"></div>
                  <h2 class="prepand3"><a href="/user/'.$user->uid.'/posted-jobs">View your job postings</a></h2>
                  
                </div>
                <div class="round"><img src="'.$imgbase.'tsskills/tsskills-round12.gif" alt="Round"></div>
              </div>
              <div class="signup-create padding6 col-lg-4">
                <div class="round"><img src="'.$imgbase.'tsskills/tsskills-round11.gif" alt="Round"></div>
                <div class="signup-create-body">
                  <div><img src="'.$imgbase.'tsskills/tsskills-image5.png" alt="Image"></div>
                  <h2 class="prepand3"><a href="/user/'.$user->uid.'/edit/employer">Manage company profile </a></h2>
                  
                </div>
                <div class="round"><img src="'.$imgbase.'tsskills/tsskills-round12.gif" alt="Round"></div>
              </div>
            </div>
            <div class="jobseekers-left-body prepand6">
              <div class="find-job prepand">
                <div class="round"><img alt="Round" src="'.$imgbase.'tsskills/tsskills-round13.gif"></div>
                <div align="right" class="find-job-body"><a href="/page/employer-faq"><img alt="Find Job" src="'.$imgbase.'tsskills/employer-faq.png"></a></div>
                <div class="round"><img alt="Round" src="'.$imgbase.'tsskills/tsskills-round14.gif"></div>
              </div>
              
            </div>
          </div>
          <div class="round"><img src="'.$imgbase.'tsskills/tsskills-round6.gif" alt="Round"></div>
        </div>

        <div style="clear:left;"></div>

        ');


}

function tsskills_admin_menu(&$account,$user){
        $imgbase = file_create_url('public://');
	$account->picture = null;
        $account->content['employeemenu'] = array('#markup'=>'
		<div class="col-md-12">
		<div class="col-md-12 text-center dashboard-admin-options">
		<div class="col-md-4"><a class="btn btn-default" href="/user/admin/posted-jobs">Applied Jobs</a></div>
		<div class="col-md-4"><a class="btn btn-default" href="/tsadmin/publish-jobs">Jobs list</a></div>
		<div class="col-md-4"><a class="btn btn-default" href="/tsadmin/employee-search">Employee Search</a></div>
		</div>
		</div>
		<div class="clearfix"></div>

	');
}

?>









