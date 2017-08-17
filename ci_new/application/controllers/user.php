<?php
class User extends CI_Controller
{
    public function __construct() {
        parent::__construct();
        $this->load->helper(['users', 'admin', 'js', 'form']);
        $this->load->library(['session', 'layout', 'visitor', 'seo', 'quote', 'jscript']);

		$this->_data['logged'] = $this->visitor->checkLogin();
        $this->_data['user_info'] = $this->session->userdata('userInfo');
        $this->_data['seo'] = $this->seo->getSEO();
		$this->_data['quote'] = $this->quote->getFooterQuote();
		$this->_data['js'] = $this->jscript->loadRequired();
		$this->_data['search_option_value'] = 'user';
    }

	public function login() {
		if ($this->_data['logged']) {
			header('Location: ' . base_url());
			exit;
		}
		else {
			$this->load->library(array('form_validation', 'url'));
			if (!$this->form_validation->run('login')) {
				$this->_data['uri'] = $this->url->manipURIPassback();
				$this->layout->render('user/login.php', $this->_data, 'two_column.php');
			}
			else {
				$this->load->model('UserModel', '', true);
				
				$login_credentials = array(
					'email' => $this->input->post('email'),
					'password' => $this->input->post('password')
				);
				if ($user = $this->UserModel->login($login_credentials)) {
					$userInfo = array(
						'id' => $user->id,
						'username' => $user->username,
						'firstname' => $user->firstname,
						'lastname' => $user->lastname,
						'email' => $user->email,
						'birthdate' => $user->birthdate,
						'city' => $user->city,
						'state' => $user->state,
						'avatar' => $user->avatar,
						'avatarImage' => $user->avatarImage,
						'usertype_id' => $user->usertype_id,
						'usertype' => $user->usertype,
						'lastlogin' => $user->lastlogin,
						'joindate' => $user->joindate,
						'formatLastLogin' => $user->lastlogin,
						'uploadImage' => $user->uploadImage,
						'stateID' => $user->stateID
					);
					$this->session->set_userdata(array('userInfo' => $userInfo));

					$this->UserModel->updateLastLogin($user->id);

					$uri = $this->url->manipURIPassback();
					$args = $this->url->swap_out_uri(array('_', '.', '-'), '/', $uri);
					$url = 'user/profile/' . $user->id;
					if (!empty($args)) {
						$url = $args;
					}

					header('Location: ' . base_url() . $url);
					exit;
				}
				else {
					$this->_data['error'] = true;
                    $this->_data['uri'] = $uri = $this->url->manipURIPassback();

					$this->layout->render('user/login.php', $this->_data, 'two_column.php');
				}
			}
		}
	}

	/**
	 * reset the password for a user who is not logged in
	 */
	public function reset() {
		if (!$this->_data['logged']) {
			$segment =$this->uri->segment(3);
			if ($segment) {
				$this->_data['segment'] = base64_decode(urldecode($segment));
				if ('success' == $this->_data['segment'] || 'danger' == $this->_data['segment']) {
					$this->layout->render('user/reset_form/result.php', $this->_data, 'two_column.php');
				}
				else {
					header('Location: ' . base_url());
					exit;
				}
			}
			else {
				$this->load->model('CaptchaModel', '', true);
				$this->load->helper('captcha_pi');
				$this->load->library('form_validation');		
				
				if (!$this->form_validation->run('resetPassword')) {
					$this->layout->render('user/reset.php', $this->_data, 'two_column.php');
				}
				else {
					$this->load->library('mail');
					$this->load->model('UserModel', '', true);
					
					$email = filter_var($this->input->post('email'), FILTER_SANITIZE_EMAIL);
					$ui = $this->UserModel->getInfoIfEmailExists($email);
					if (!empty($ui) && is_array($ui)) {
						$this->mail->sendFormMail($ui + array('action' => 'resetPassword'));
						header('Location: ' . base_url() . 'user/reset/' . urlencode(base64_encode('success')));
						exit;
					}
					else {
						header('Location: ' . base_url() . 'user/reset/' . urlencode(base64_encode('danger')));
						exit;
					}
				}
			}
		}
	}

	public function reset_validate() {
		if (!$this->_data['logged']) {
			$boolean = false;
			$parts = explode('_', $this->uri->segment(3));
			if (is_array($parts) && count($parts) == 2) {
				$activationCode = filter_var($parts[0], FILTER_SANITIZE_STRING);
				$userID = filter_var($parts[1], FILTER_SANITIZE_NUMBER_INT);
				$this->load->model('UserModel', '', true);
				$check = $this->UserModel->validatePasswordCode(array('activationCode' => $activationCode, 'userID' => $userID));
				if ($check) {
					$check['newPassword'] = substr(sha1(date('Y-m-d H:i:s')), 0, 12);
					$check['userID'] = $userID;
					
					if (1 == $this->UserModel->setPassword($check)) {
						$this->load->library('mail');
						$this->mail->sendFormMail($check + array('action' => 'changedPassword'));

						$boolean = true;

						$this->layout->render('user/reset_validate/success.php', null, 'two_column.php');
					}
				}
			}
			
			if (!$boolean) {
				$this->layout->render('user/reset_validate/danger.php', $this->_data, 'two_column.php');
			}
		}
		else {
			header('Location: ' . base_url());
			exit;
		}
	}

    /**
     * Log the user out, if they are logged in.  Send them back to the main page of the site.
     *
     * @return void
     */
	public function logout() {
		if ($this->_data['logged']) {
			$this->session->sess_destroy();
			header('Location: ' . base_url());
			exit;
		}
        else {
			header('Location: ' . base_url());
			exit;
		}
	}

	public function createAccount() {
		if ($this->_data['logged']) {
			$this->layout->render('user/create_account.php', $this->_data, 'two_column.php');
		}
        else {
			$this->load->model('StateModel', '', true);
			$this->load->model('UserModel', '', true);
            $this->load->model('CaptchaModel', '', true);
			$this->load->library('form_validation');
			$this->load->helper('captcha_pi');

			if (!$this->form_validation->run('createAccount')) {
				$this->_data['select_data'] = $this->StateModel->getAllForDropDown();
				$this->_data['select_class'] = 'state';

				$this->layout->render('user/create_account.php', $this->_data, 'two_column.php');
			}
            else {
				$stateAbbr = $this->StateModel->getStateByID($this->input->post('state'));
				$userID = $this->UserModel->createAccount($this->input->post() + array(
					'usertype' => 2,
					'ip' => $_SERVER['REMOTE_ADDR'],
					'stateAbbr' => $stateAbbr['stateAbbr']
				));
				
				$activationCode = $this->UserModel->getActivationCode($userID);				
				$array = array(
					'activationCode' => $activationCode,
					'membername' => $this->input->post('username'),
					'email' => $this->input->post('email'),
					'action' => 'activationEmail'
				);
				$this->load->library('mail');
				$this->mail->sendFormMail($array);

				header('Location: ' . base_url() . 'user/formSuccess/createAccount');
			}
		}
	}

	public function activateAccount() {//$this->output->enable_profiler(TRUE);
		$activation_code = explode('_', $this->uri->segment(3));

		if (!empty($activation_code) && count($activation_code) == 2) {
			$this->load->model('UserModel', '', true);
			$this->_data['int'] = $this->UserModel->activateAccount($activation_code[0], $activation_code[1]);
		}
		else {
			header('Location: ' . base_url());
			exit;
		}

		$this->layout->render('user/activate_account.php', $this->_data, 'two_column.php');
	}

	public function formSuccess()
	{
		$type = filter_var($this->uri->segment(3), FILTER_SANITIZE_STRING);
		switch ($type) {
			case 'createAccount':
				$this->layout->render('user/form_success.php', $this->_data, 'two_column.php');
				break;
			default:
				header('Location: ' . base_url());
				exit;
		}
	}

	public function emailExists($email) {		
		$this->load->model('UserModel', '', true);
		$boolean = $this->UserModel->emailCheck($email);
		if (!$boolean) {
			$this->form_validation->set_message('emailExists', 'The %s address you have chosen already has an account.');
		}
		return $boolean;
	}
	
	public function emailCheckMatch($email) {
		$this->load->model('UserModel', '', true);
		$boolean = $this->UserModel->emailCheckMatch($email);
		if (!$boolean) {
			$this->form_validation->set_message('emailCheckMatch', 'The %s address you have chosen does not exist in our records.');
		}
		return $boolean;
	}
	
	public function usernameExists($username) {
		$this->load->model('UserModel', '', true);
		$boolean = $this->UserModel->usernameCheckCreateAccount($username);
		if (!$boolean) {
			$this->form_validation->set_message('usernameExists', 'The %s you have chosen already exists, please choose another');
		}
		return $boolean;
	}
	
	public function alphaNumericSpace($str) {
		return (!preg_match("/^([a-z0-9\s])+$/i", $str)) ? false : true;
	}
	
	public function validateCaptcha($str) {
		
	}

    public function profile() {
        if (!$this->_data['logged']) {
        	header('Location:' . base_url() . 'user/login');
            exit;
        }
        
        $id = filter_var($this->uri->segment(3), FILTER_SANITIZE_NUMBER_INT);
		if (!$id) {
            header('Location:' . base_url());
            exit;
        }

        $this->load->model('UserModel', '', true);
        $this->load->model('SwapModel', '', true);

        $this->_data['js'][] = 'dudelist.js';

        if (!$this->UserModel->idCheck($id)) {
            $this->_data['exist'] = true;
			$this->layout->render('user/profile.php', $this->_data, 'two_column.php');
        }
        else {
            $this->_data['exist'] = false;
            $this->_data['id'] = $id;
            
            $this->_data['user_profile'] = $this->UserModel->getUserProfile($id);
            $this->_data['user_profile']['last_activity'] = determineTimeSinceLastActive($this->_data['user_profile']['secondsLastLogin']);

            $this->load->model('RatingModel', '', true);
            $this->_data['beer_ratings'] = $this->RatingModel->getBeerRatingByUserIDStatistics($id);
            $this->_data['establishment_ratings'] = $this->RatingModel->getEstablishmentRatingByUserIDStatistics($id);
            $this->_data['total_establishment_ratings'] = $this->RatingModel->getEstablishmentRatingByUserIDStatisticsCount($id);
        }

        //$this->_data['seo'] = array_slice($array, 0, 3);
		//$this->_data['avatar'] = $this->load->view('user/avatar.php', $this->_data, true);
        //echo '<pre>' . print_r($this->_data, true); exit;
        $this->layout->render('user/profile.php', $this->_data, 'two_column.php');
    }

	public function updateProfile()
	{
		if ($this->_data['logged']) {
			$this->_data['id'] = $this->_data['user_info']['id'];

			$this->load->model('StateModel', '', true);
			$this->load->model('UserModel', '', true);

			$this->load->library('form_validation');			
			if (!$this->form_validation->run('updateProfile')) {
				if (!$this->input->post()) {
					$this->_data['user_profile'] = $this->UserModel->getUserProfile($this->_data['id']);
				}
				$this->_data['select_data'] = $this->StateModel->getAllForDropDown();
				$this->_data['select_class'] = 'state';
				
				$this->_data['js'][] = 'dudelist.js';

				$this->_data['seo'] = $this->seo->getDynamicSEO((object)$this->_data['user_info'], 'update profile');

				$this->layout->render('user/updateProfile.php', $this->_data, 'two_column.php');
			} else { 
				$stateInfo = $this->StateModel->getStateByID($this->input->post('state'));
				$config = array(
					'username' => $this->_data['user_info']['username'],
					'firstname' => $this->input->post('firstname'),
					'lastname' => $this->input->post('lastname'),
					'email' => $this->_data['user_info']['email'],
					'birthdate' => $this->_data['user_info']['birthdate'],
					'city' => $this->input->post('city'),
					'state' => $stateInfo['stateAbbr'],
					'notes' => $this->input->post('notes')
				);
				$this->UserModel->updateProfileByID($this->_data['user_info']['id'], $config);
				
				$this->session->set_userdata('firstname', $this->input->post('firstname'));
				$this->session->set_userdata('lastname', $this->input->post('lastname'));
				$this->session->set_userdata('city', $this->input->post('city'));
				$this->session->set_userdata('state', $stateInfo['stateAbbr']);
				$this->session->set_userdata('stateID', $this->input->post('state'));
				
				header('Location: ' . base_url() . 'user/profile/' . $this->_data['user_info']['id']);
				exit;
			}	
		}
		/*else
		{
			$this->_data['leftCol'] = '
				<h2 class="brown">Update Profile</h2>
				<p class="marginTop_8">You do not have access to this portion of the site without being 
				<a href="' . base_url() . 'user/login">logged in</a>.  If are not a member, 
				<a href="' . base_url() . 'user/createAccount">create</a> a free membership.</p>';
		}*/
		else {
			header('Location: ' . base_url());
			exit;
		}
	}
	
	public function buddylist() {
		// set the page seo information
		$this->_data['seo'] = getSEO();
		
		if(checkLogin() === true) {
			// user is logged in
			// load the user model
			$this->load->model('UserModel', '', true);
			// load the user library
			$this->load->library('userslib');
			// need the logged in user id
			$userInfo = $this->session->userdata('userInfo');
			// get the action to take
			$action = $this->uri->segment(3);
			// check which action to take
			switch($action) {
				case 'buddy':
				default:
					$this->_data['buddylist'] = $this->userslib->showBuddyList($userInfo);
					break;
				case 'showMessage':
					// get the message id
					$messageID = $this->uri->segment(4);
					// make sure the id was there
					if($messageID == false) {
						// no message id
						$this->_data['buddylist'] = '<p>No message found matching requested information.</p>';
					} else {
						// message id
						$this->_data['buddylist'] = $this->userslib->showMessageByID($messageID, $userInfo);
					}
					break;
				case 'block':
					break;
			}
			
			$arr_load = array(
				'pages' => array('header' => true, 'buddy' => true, 'footer' => true)
				, 'data' => $this->_data
			);				
			// load all parts for the view
			$this->doLoad($arr_load);
		} else {
			// set the output for the screen
			header('Location:' . base_url() . 'user/login');
			exit;
		}
	}
	
	public function updatePass()
	{
		if (!$this->_data['logged']) {
			header('Location: ' . base_url());
			exit;
		}

		$userID = $this->_data['id'] = filter_var($this->uri->segment(3), FILTER_SANITIZE_NUMBER_INT);		
		if ($userID && $userID == $this->_data['user_info']['id']) {
			$this->load->library('form_validation');		
			if (!$this->form_validation->run('updatePassword')) {
				$this->_data['seo'] = $this->seo->getDynamicSEO((object)$this->_data['user_info'], 'update profile password');
				//$this->layout->render('user/updatePass.php', $this->_data, 'two_column.php');
			} else {
				$this->load->model('UserModel', '', true);
				$this->UserModel->setPassword(array('userID' => $userID, 'newPassword' => $this->input->post('password1')));
				
				$this->_data['success'] = true;
			}
		} else {
			$this->_data['warning'] = true;
		}
		$this->_data['js'][] = 'dudelist.js';

		$this->layout->render('user/updatePass.php', $this->_data, 'two_column.php');	
	}
    
    public function beer() { 
    	if (!$this->_data['logged']) {
    		header('Location: ' . base_url() . 'user/login');
    		exit;
    	}

		$this->_data['user_name'] = filter_var($this->uri->segment(3), FILTER_SANITIZE_STRING);
		if (!$this->_data['user_name']) {
            if (isset($this->_data['user_info']['id'])) {
                header('Location: ' . base_url() . 'user/beer/' . $this->_data['user_info']['username']);
                exit;
            } else {
                header('Location: ' . base_url() . 'user/login') ;
                exit;
            }
        }

        $offset = filter_var($this->uri->segment(4), FILTER_SANITIZE_NUMBER_INT);
		if (empty($offset) || !ctype_digit($offset)) {
			$offset = 0;
		} 
        
        $this->load->model('UserModel', '', true);
        $this->_data['user_id'] = $this->UserModel->getIDByUsername($this->_data['user_name']);

        $this->load->model('RatingModel', '', true);
        $this->_data['total'] = $this->RatingModel->getRatingsByUserIDForUserProfileCount($this->_data['user_id']);
        $this->_data['records'] = $this->RatingModel->getRatingsByUserIDForUserProfile($this->_data['user_id'], $offset); 

        $this->load->library('pagination');
        //$this->_data['seo'] = $array_output['seo'];
        
        $this->layout->render('user/beer.php', $this->_data, 'two_column.php');
    }
    
    public function style() {
    	if (!$this->_data['logged']) {
    		header('Location: ' . base_url() . 'user/login');
    		exit;
    	}

		$this->_data['user_name'] = filter_var($this->uri->segment(3), FILTER_SANITIZE_STRING);
        if (!$this->_data['user_name']) {
            if (isset($this->_data['user_info']['id'])) {
                header('Location: ' . base_url() . 'user/style/' . $this->_data['user_info']['username']);
                exit;
            } else {
                header('Location: ' . base_url() . 'user/login') ;
                exit;
            }
        }
        
        $this->load->model('UserModel', '', true);
        $this->_data['user_id'] = $this->UserModel->getIDByUsername($this->_data['user_name']);
        
        $this->load->model('RatingModel', '', true);
        $this->_data['total'] = $this->RatingModel->getStylesRatedByUserIDForUserProfileCount($this->_data['user_id']);
        $this->_data['records'] = $this->RatingModel->getStylesRatedByUserIDForUserProfile($this->_data['user_id']);

		array_push($this->_data['js'], 'bootstrap-slider.min.js', 'user_style.js');

        $this->layout->render('user/style.php', $this->_data, 'two_column.php');
    }

    public function styleBeerList() {
    	$user_id = filter_var($this->input->post('user_id'), FILTER_SANITIZE_NUMBER_INT);
    	$style_id = filter_var($this->input->post('style_id'), FILTER_SANITIZE_NUMBER_INT);

    	$result = 'success';
    	if (!$user_id || !$style_id) {
    		$result = 'danger';
    		$message = 'There is something missing.';
    	}
    	else {
    		$this->load->model('RatingModel', '', true);
    		$this->_data['records'] = $this->RatingModel->getBeerByStylesRatedByUserIDForUserProfile($user_id, $style_id);

    		$this->load->library('image');
    		foreach ($this->_data['records'] as $record) {
	    		$config = [
				    'picture' => $record->picture,
				    'id' => $record->id,
				    'alt' => $record->beerName . ' - ' . $record->name,
				    'width' => 30,
				    'height' => 70
				];
				$record->image = $this->image->checkForAnImage($config, $this->_data['logged']);
			}

    		$message = $this->load->view('user/style_beer_grid.php', $this->_data, true);
    	}

    	echo json_encode(array('result' => $result, 'message' => $message));
    }

    public function styleBeerComment() {
    	$rating_id = filter_var($this->input->post('id'), FILTER_SANITIZE_NUMBER_INT);
    	
    	$result = 'success';
    	$title = '';
    	if (!$rating_id) {
    		$result = 'danger';
    		$message = 'There is something missing.';
    	}
    	else {
    		$this->load->model('RatingModel', '', true);
    		$record = $this->RatingModel->getRatingByID($rating_id);

    		$message = nl2br($record->comments);
    		$title = 'Comments for ' . $record->beerName;
    	}

    	echo json_encode(array('result' => $result, 'message' => $message, 'title' => $title));	
    }

    public function establishment() {
    	if (!$this->_data['logged']) {
    		header('Location: ' . base_url() . 'user/login');
    		exit;
    	}

		$user_id = filter_var($this->uri->segment(3), FILTER_SANITIZE_NUMBER_INT);
        if (!$user_id) {
            if (isset($this->_data['user_info']['id'])) {
                header('Location: ' . base_url() . 'user/establishment/' . $this->_data['user_info']['id']);
                exit;
            } else {
                header('Location: ' . base_url() . 'user/login') ;
                exit;
            }
        }
        
        $this->load->model('UserModel', '', true);
        $this->_data['user'] = $this->UserModel->getUserProfile($user_id, 'obj');
		if (empty($this->_data['user'])) {
        	header('Location: ' . base_url()) ;
            exit;
        }
        
        $this->load->model('RatingModel', '', true);
        $this->_data['total'] = $this->RatingModel->getEstablishmentRatingByUserIDStatisticsCount($this->_data['user']->id);
        $this->_data['records'] = $this->RatingModel->getEstablishmentRatingByUserIDStatistics($this->_data['user']->id, 'obj');
        //echo '<pre>' . print_r($this->_data['total'], true); exit;
        array_push($this->_data['js'], 'bootstrap-slider.min.js', 'user_establishment.js');
        $this->layout->render('user/establishment.php', $this->_data, 'two_column.php');
    }

    public function establishmentList() {
    	$user_id = filter_var($this->input->post('user_id'), FILTER_SANITIZE_NUMBER_INT);
    	$category_id = filter_var($this->input->post('category_id'), FILTER_SANITIZE_NUMBER_INT);

    	$result = 'success';
    	if (!$user_id || !$category_id) {
    		$result = 'danger';
    		$message = 'There is something missing.';
    	}
    	else {
    		$this->load->model('RatingModel', '', true);
    		$this->_data['records'] = $this->RatingModel->getEstablishmentRatingsByUserIDAndCategoryID($user_id, $category_id);
    		$message = $this->load->view('user/establishment_grid.php', $this->_data, true);
    	}

    	echo json_encode(array('result' => $result, 'message' => $message));
    }

    public function establishmentComment() {
    	$rating_id = filter_var($this->input->post('id'), FILTER_SANITIZE_NUMBER_INT);
    	
    	$result = 'success';
    	$title = '';
    	if (!$rating_id) {
    		$result = 'danger';
    		$message = 'There is something missing.';
    	}
    	else {
    		$this->load->model('EstablishmentModel', '', true);
    		$record = $this->EstablishmentModel->getEstblishmentRatingsByRatingsID($rating_id);
    		$message = nl2br($record['comments']);
    		$title = 'Comments for ' . $record['name'];
    	}

    	echo json_encode(array('result' => $result, 'message' => $message, 'title' => $title));	
    }
}
?>
