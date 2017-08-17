<?php
if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

class Mail
{
	private $_ci;	

	public function __construct() {
		$this->_ci =& get_instance();
	}

	private function _sendEmail($config) {	
		mail($config['to'], $config['subject'], $config['message'], $config['header']);
	}

	public function sendFormMail($config) { 
		$headers = 'MIME-Version: 1.0' . "\r\n";
		$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
		$headers .= 'From: webmaster@twobeerdudes.com' . "\r\n";

		$array = [];
		switch ($config['action']) {
			case 'beer':
				$array['to'] = 'scot@twobeerdudes.com';
				$array['subject'] = $config['subject'];
				$array['message'] = 'The following beer review was added:<br><br>' .
					'ratingID: ' . $config['id'] . '<br>' .
					'username: ' . $config['username'] . '<br>' .
					'userID: ' . $config['userID'] . '<br>' .
					'rating: ' . $config['rating'] . '<br>' .
					'comments: ' . nl2br($config['comments']) . '<br><br>';
				$array['header'] = $headers;
				break;
			case 'shortbeer':
				$array['to'] = 'scot@twobeerdudes.com';
				$array['subject'] = $config['subject'];
				$array['message'] = 'The following beer review was added:<br /><br />' .
					'ratingID: ' . $config['id'] . '<br />' .
					'username: ' . $config['username'] . '<br />' .
					'userID: ' . $config['userID'] . '<br />' .
					'aroma: ' . $config['aroma'] . '<br />' .
					'taste: ' . $config['taste'] . '<br />' .
					'look: ' . $config['look'] . '<br />' .
					'drinkability: ' . nl2br($config['drinkability']) . '<br /><br />';
				$array['header'] = $headers;
				break;
			case 'establishmentReview':
				$array['to'] = 'scot@twobeerdudes.com';
				$array['subject'] = $config['subject'];
				$array['message'] = 'The following establishment review was added:<br /><br />' .
					'establishmentID: ' . $config['establishmentID'] . '<br />' .
					'username: ' . $config['username'] . '<br />' .
					'userID: ' . $config['userID'] . '<br />' .
					'rating: ' . $config['rating'] . '<br />' .
					'comments: ' . nl2br($config['comments']) . '<br /><br />';
				$array['header'] = $headers;
				break;
			case 'newBeer':
				$array['to'] = 'scot@twobeerdudes.com';
				$array['subject'] = $config['subject'];
				$array['message'] = 'The following new beer was added:<br /><br />' .
					'User ID: ' . $config['userID'] . '<br />' .
					'Beer ID: ' . $config['beerID'] . '<br />' .
					'Establishment ID: ' . $config['data']['establishmentID'] . '<br />' .
					'Style ID: ' . $config['data']['styleID'] . '<br />' .
					'Beer Name: ' . $config['data']['beerName'] . '<br />' .
					'ABV: ' . $config['data']['alcoholContent'] . '<br />' .
					'IBU: ' . $config['data']['ibu'] . '<br />' .
					'Malts: ' . $config['data']['malts'] . '<br />' .
					'Hops: ' . $config['data']['hops'] . '<br />' .
					'Yeast: ' . $config['data']['yeast'] . '<br />' .
					'Gravity: ' . $config['data']['gravity'] . '<br />' .				
					'Food: ' . $config['data']['food'] . '<br />' .
					'Glassware: ' . $config['data']['glassware'] . '<br />' .
					'Seasonal: ' . $config['data']['seasonal'] . '<br />' .
					'Seasonal Period: ' . $config['data']['seasonalPeriod'] . '<br /><br />';
				$array['header'] = $headers;
				break;
			case 'newEstablishment':
				$array['to'] = 'scot@twobeerdudes.com';
				$array['subject'] = $config['subject'];
				$array['message'] = 'The following new establishment was added:<br /><br />' .
					'User ID: ' . $config['userID'] . '<br />' .
					'Establishment ID: ' . $config['establishmentID'] . '<br />' .
					'Establishment Category: ' . $config['data']['categoryID'] . '<br />' .
					'Establishment Name: ' . $config['data']['name'] . '<br />' .
					'Address: ' . $config['data']['address'] . '<br />' .
					'City: ' . $config['data']['city'] . '<br />' .
					'State ID: ' . $config['data']['state'] . '<br />' .
					'Zip: ' . $config['data']['zip'] . '<br />' .
					'Phone: ' . $config['data']['phone'] . '<br />' .
					'URL: ' . $config['data']['url'] . '<br /><br />' .				
				$array['header'] = $headers;
				break;
			case 'resetPassword':
				$array['to'] = $config['email'];
				$array['subject'] = 'Two Beer Dudes Reset Password for ' . $config['username'];
				$array['message'] = '<html><body><p>Hey ' . $config['username'] . ',</p>
					<p>We have received a request to reset the password for your account via ' . $config['email'] . '.  If you DO NOT want to reset your password, simply discard this email and no other action is required.</p>
					<p>Otherwise, please follow the link below to reset your password:</p>
					<p><a href="' . base_url() . 'user/reset_validate/' . $config['validationKey'] . '_' . $config['id'] . '">' . base_url() . 'user/reset_validate/' . $config['validationKey'] . '_' . $config['id'] . '</a></p>
					<p>Regards,<br />Two Beer Dudes<br /><a href="' . base_url() . '">' . base_url() . '</a></p>';
				$array['header'] = 'MIME-Version: 1.0' . "\r\n" .
					'Content-type: text/html; charset=iso-8859-1' . "\r\n" .
					'From: webmaster@twobeerdudes.com' . "\r\n" .
					'Reply-To: webmaster@twobeerdudes.com' . "\r\n" .
					'X-Mailer: PHP/' . phpversion();
				//echo '<pre>' . print_r($array['message'], true); exit;
				break;
			case 'changedPassword':
				$array['to'] = $config['email'];
				$array['subject'] = 'Two Beer Dudes New Password for ' . $config['username'];
				$array['message'] = '<html><body><p>Hey ' . $config['username'] . ',</p>
					<p>Your password has been reset.  Your current login information:</p>
					<p>login: ' . $config['email'] . '<br />password: ' . $config['newPassword'] . '</p>
					<p>Follow the link below to login:</p>
					<p><a href="' . base_url() . 'user/login">' . base_url() . 'user/login</a></p>
					<p>Regards,<br />Two Beer Dudes<br /><a href="' . base_url() . '">' . base_url() . '</a></p>';
				$array['header'] = 'MIME-Version: 1.0' . "\r\n" .
					'Content-type: text/html; charset=iso-8859-1' . "\r\n" .
					'From: webmaster@twobeerdudes.com' . "\r\n" .
					'Reply-To: webmaster@twobeerdudes.com' . "\r\n" .
					'X-Mailer: PHP/' . phpversion();
					//echo '<pre>' . print_r($array['message'], true); exit;
				break;
			case 'contactUs':
				$array['to'] = $config['to'];
				$array['subject'] = 'Two Beer Dudes Contact Us Form';
				$array['message'] = '<html><body><p>Hey,</p>
					<p>The following person contacted you via the site:</p>
					<p>name: ' . $config['name'] . '<br />email: ' . $config['email'] . '</p>
					<p>comments:</p>
					<p>' . nl2br($config['comments']) . '</p>
				';
				$array['header'] = 'MIME-Version: 1.0' . "\r\n" .
					'Content-type: text/html; charset=iso-8859-1' . "\r\n" .
					'From: webmaster@twobeerdudes.com' . "\r\n" .
					'Reply-To: webmaster@twobeerdudes.com' . "\r\n" .
					'X-Mailer: PHP/' . phpversion();
				break;
			case 'updateInfo':
				$array['to'] = $config['to'];
				$array['subject'] = 'Two Beer Dudes Update Information Request';
				$array['message'] = '<html><body><p>Hey,</p>
					<p>The following inforamation was submitted for updating:</p>
					<p>submitted by: ' . $config['userInfo']['username'] . ' (' . $config['userInfo']['id'] . ')</p>
					<p>item information: ' . $config['itemInfo']->beerName . '</p>
					<p>change type: ' . $config['change']->changetype . '</p>
					<p>comments:</p>
					<p>' . nl2br($config['comments']) . '</p>
				';
				$array['header'] = 'MIME-Version: 1.0' . "\r\n" .
					'Content-type: text/html; charset=iso-8859-1' . "\r\n" .
					'From: webmaster@twobeerdudes.com' . "\r\n" .
					'Reply-To: webmaster@twobeerdudes.com' . "\r\n" .
					'X-Mailer: PHP/' . phpversion();
				break;
			case 'activationEmail':
				$array['to'] = $config['email'];
				$array['subject'] = 'Two Beer Dudes membership activation';
				$array['message'] = '<html><body><p>Hey ' . $config['membername'] . ',</p>
					<p>Your request for membership is appreciated and almost complete.  We hope that you enjoy the site.  Please feel free to offer your suggestions as we want to continue to improve on the site</p>
					<p>Please follow the link below to activate your account:</p>
					<p><a href="' . base_url() . 'user/activateAccount/' . $config['activationCode'] . '">' . base_url() . 'user/activateAccount/' . $config['activationCode'] . '</a></p>
					<p>Cheers,<br>Two Beer Dudes</p>';
				$array['header'] = 'MIME-Version: 1.0' . "\r\n" .
					'Content-type: text/html; charset=iso-8859-1' . "\r\n" .
					'From: webmaster@twobeerdudes.com' . "\r\n" .
					'Reply-To: webmaster@twobeerdudes.com' . "\r\n" .
					'X-Mailer: PHP/' . phpversion();
				break;
		}
		//echo '<pre>' . print_r($array, true); exit;
		// send the email
		$this->_sendEmail($array);
	}
}