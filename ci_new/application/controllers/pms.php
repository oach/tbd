<?php
class Pms extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->helper(['users', 'admin', 'js', 'form']);
        $this->load->library(['session', 'layout', 'visitor', 'seo', 'quote', 'jscript']);
        $this->load->model('UserModel', '', true);

		$this->_data['logged'] = $this->visitor->checkLogin();
        if (!$this->_data['logged']) {
            $this->visitor->force_login();
        }
        
        $this->_data['user_info'] = $this->session->userdata('userInfo');
        $this->_data['seo'] = $this->seo->getSEO();
        $this->_data['quote'] = $this->quote->getFooterQuote();
        $this->_data['js'] = $this->jscript->loadRequired();
    }

    private function _check_module_active($return = false)
    {
        if (!PMS_ACTIVE) {
            if ($return) {
                return false;
            } else {
                header('Location: ' . base_url() . 'pms/not_active');
                exit;
            }
        }
        return true;
    }

    public function not_active()
    {
        $this->layout->render('pms/not_active.php', $this->_data, 'one_column.php');
    }

    public function view()
    {
        $this->_check_module_active();
        
        $this->_data['id'] = $this->_data['user_info']['id'];
        $this->_data['seo'] = $this->seo->getDynamicSEO((object) $this->_data['user_info'], 'private messages');
        $this->_data['pms'] = $this->UserModel->getPMSByUserID($this->_data['user_info']['id']);

        array_push($this->_data['js'], 'spinner.js', 'malted_mail.js');
        //echo '<pre>' . print_r($this->_data, true); exit;
        $this->layout->render('pms/inbox.php', $this->_data, 'two_column.php');
    }

    public function showMessage()
    {
        $this->_check_module_active();

        $this->_data['id'] = $this->_data['user_info']['id'];
        $this->_data['messageID'] = filter_var($this->uri->segment(3), FILTER_SANITIZE_NUMBER_INT);
        $this->_data['msg'] = $this->UserModel->getPMByMessageID($this->_data['messageID'], $this->_data['id']);
        $this->layout->render('pms/show_message.php', $this->_data, 'two_column.php');  
    }

    public function create()
    {
        $this->_check_module_active();

        $this->_data['id'] = $this->_data['user_info']['id'];

        $this->load->library('form_validation');
        if (!$this->form_validation->run('sendMaltedMail')) {
            $this->_data['formType'] = 'create';

            $sendToID = filter_var($this->uri->segment(3), FILTER_SANITIZE_NUMBER_INT);
            if ($sendToID) {
                $this->_data['nameToSendTo'] = $this->UserModel->getUsernameByID($sendToID);
            }

            array_push($this->_data['js'], 'malted_mail.js');

            $this->layout->render('pms/create/form.php', $this->_data, 'two_column.php');
        } else {
            $sentToID = $this->UserModel->getIDByUsername($this->input->post('to'));
            $array = array(
                'fromID' => $this->_data['user_info']['id'],
                'toID' => $sentToID,
                'subject' => filter_var($this->input->post('subject'), FILTER_SANITIZE_STRING),
                'message' => filter_var($this->input->post('message'), FILTER_SANITIZE_STRING)
            );                      
            $this->UserModel->insertPM($array);

            header('Location: ' . base_url() . 'pms/success/create/' . $sentToID);
            exit;
            
            $this->_data['type'] = 'create';
            $this->_data['to'] = filter_var($this->input->post('to'), FILTER_SANITIZE_STRING);
            $this->layout->render('pms/form_success.php', $this->_data, 'two_column.php');
        }        
    }

    public function reply()
    {
        $this->_check_module_active();

        $this->_data['id'] = $this->_data['user_info']['id'];

        $this->load->library('form_validation');
        if (!$this->form_validation->run('sendMaltedMail')) {
            $messageID = filter_var($this->uri->segment(3), FILTER_SANITIZE_NUMBER_INT);
            if (!$messageID) {
                header('Location: ' . base_url() . 'user/pms');
                exit;
            } else {
                $this->_data['formType'] = 'reply';
                $this->_data['message_info'] = $this->UserModel->getMessageInfoByMessageID($messageID);
                $this->layout->render('pms/reply/form.php', $this->_data, 'two_column.php');
            }                           
        } else { 
            $sentToID = $this->UserModel->getIDByUsername($this->input->post('to'));
            $array = array(
                'fromID' => $this->_data['user_info']['id'],
                'toID' => $sentToID,
                'subject' => filter_var($this->input->post('subject'), FILTER_SANITIZE_STRING),
                'message' => filter_var($this->input->post('message'), FILTER_SANITIZE_STRING)
            );                      
            $this->UserModel->insertPM($array);

            header('Location: ' . base_url() . 'pms/success/reply/' . $sentToID);
            exit;
        }
    }

    public function forward()
    {
        $this->_check_module_active();

        $this->_data['id'] = $this->_data['user_info']['id'];

        $this->load->library('form_validation');
        if (!$this->form_validation->run('sendMaltedMail')) {
            $messageID = filter_var($this->uri->segment(3), FILTER_SANITIZE_NUMBER_INT);
            if (!$messageID) {
                header('Location: ' . base_url() . 'user/pms');
                exit;
            } else {
                $this->_data['formType'] = 'forward';
                $this->_data['message_info'] = $this->UserModel->getMessageInfoByMessageID($messageID);
                $this->layout->render('pms/forward/form.php', $this->_data, 'two_column.php');
            }                           
        } else {
            $sentToID = $this->UserModel->getIDByUsername($this->input->post('to'));
            $array = array(
                'fromID' => $this->_data['user_info']['id'],
                'toID' => $sentToID,
                'subject' => filter_var($this->input->post('subject'), FILTER_SANITIZE_STRING),
                'message' => filter_var($this->input->post('message'), FILTER_SANITIZE_STRING)
            );                      
            $this->UserModel->insertPM($array);

            header('Location: ' . base_url() . 'pms/success/forward/' . $sentToID);
            exit;
        }
    }

    public function success()
    {
        $this->_check_module_active();

        $this->_data['id'] = $this->_data['user_info']['id'];

        if ($this->uri->segment(3) && $this->uri->segment(4)) {
            $this->_data['to'] = $this->UserModel->getUsernameByID(filter_var($this->uri->segment(4), FILTER_SANITIZE_NUMBER_INT));
            $this->_data['type'] = filter_var($this->uri->segment(3), FILTER_SANITIZE_STRING);
            //$this->_data['to'] = filter_var($this->input->post('to'), FILTER_SANITIZE_STRING);
            array_push($this->_data['js'], 'malted_mail_success.js');
            $this->layout->render('pms/form_success.php', $this->_data, 'two_column.php');
        } else {
            header('Location: ' . base_url());
            exit;
        }
    }

    public function delete()
    {
        if ($this->_check_module_active(true) && $this->input->is_ajax_request()) {
            $messageID = filter_var($this->input->post('id'), FILTER_SANITIZE_NUMBER_INT);
            $this->UserModel->removePM($messageID, $this->_data['user_info']['id']);
            
            $this->_data['id'] = $this->_data['user_info']['id'];
            $this->_data['pms'] = $this->UserModel->getPMSByUserID($this->_data['user_info']['id']);
            $pms = $this->UserModel->getPMSByUserID($this->_data['user_info']['id']);
            
            $html = $this->load->view('pms/inbox/pms.php', $this->_data, true);
            echo json_encode(array('result' => 'success', 'message' => $html, 'count' => count($this->_data['pms'])));
        } else {
            echo json_encode(array('result' => 'error', 'message' => 'The process could not be completed.'));
        }
    }

    public function userExists($to)
    {
        $boolean = $this->UserModel->usernameCheck($to);
        if ($boolean === false) {
            $this->form_validation->set_message('userExists', 'The %s recipient does not exist.');
        }
        return $boolean;
    }

    public function userBlocked($to)
    {
        $boolean = $this->UserModel->checkBlockUsername($to, $this->_data['user_info']['id']);
        if ($boolean === false) {
            $this->form_validation->set_message('userBlocked', 'The %s recipient has blocked Malt Mail from you.');
        }
        return $boolean;
    }
    
    public function mailLength($message)
    {
        $strlen = strlen($message);
        if ($strlen > PRIVATE_MESSAGE_MAX_LENGTH) {
            $this->form_validation->set_message('mailLength', 'The %s is too long (' . $strlen . ').  There is a ' . PRIVATE_MESSAGE_MAX_LENGTH . ' character limit.');
            return false;
        }
        return true;
    }
}