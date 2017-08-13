<?php
class Dude extends CI_Controller
{
    public function __construct() {
        parent::__construct();
        $this->load->helper(array('url', 'users', 'admin', 'js', 'form'));
        $this->load->library(array('session', 'layout', 'visitor'));
        $this->load->model('UserModel', '', true);
        
        $this->_data['logged'] = $this->visitor->checkLogin();
        $this->_data['user_info'] = $this->session->userdata('userInfo');
    }

    public function getList() {
    	if ($this->_data['logged']) {
        	$this->_data['dudes'] = $this->UserModel->selectDudeList($this->_data['user_info']['id']);
        }
        $this->load->view('user/profile/dude_list.php', $this->_data);
    }

    public function addDude() {
		if ($this->_data['logged']) {
			$id = filter_var($this->input->post('id'), FILTER_SANITIZE_NUMBER_INT);
			if ($id) {
				if ($id != $this->_data['userInfo']['id']) {
					$user = $this->UserModel->getUsernameByID($id);
					if (!empty($user)) {
						$this->UserModel->addToDudeList($this->_data['userInfo']['id'], $id);						
					}				
				}
			}
		}
		$this->getList();
	}

    public function removeDude() {
    	if ($this->_data['logged']) {
	    	$id = filter_var($this->input->post('id'), FILTER_SANITIZE_NUMBER_INT);
			if ($id) {
				$this->UserModel->removeFromDudeList($this->_data['userInfo']['id'], $id);
			}

			$this->_data['dudes'] = $this->UserModel->selectDudeList($id);
        }
		$this->getList();
    }
}