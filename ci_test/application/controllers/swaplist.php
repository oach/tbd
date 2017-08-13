<?php
class Swaplist extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->helper(['users', 'admin', 'js', 'form']);
        $this->load->library(['session', 'layout', 'visitor', 'seo', 'quote', 'jscript']);
        $this->load->model('UserModel', '', true);

		$this->_data['logged'] = $this->visitor->checkLogin();
        if (!$this->_data['logged'])
        {
            $this->visitor->force_login();
        }
        
        $this->_data['user_info'] = $this->session->userdata('userInfo');
        $this->_data['seo'] = $this->seo->getSEO();
        $this->_data['quote'] = $this->quote->getFooterQuote();
        $this->_data['js'] = $this->jscript->loadRequired();
    }

    private function _check_module_active($return = false)
    {
        if (!SWAPLIST_ACTIVE)
        {
            if ($return)
            {
                return false;
            }
            else
            {
                header('Location: ' . base_url() . 'swaplist/not_active');
                exit;
            }
        }
        return true;
    }

    public function not_active()
    {
        $this->layout->render('swaplist/not_active.php', $this->_data, 'one_column.php');
    }

    public function show()
    {
        $this->_check_module_active();

        $this->_data['id'] = filter_var($this->uri->segment(4), FILTER_SANITIZE_NUMBER_INT);
        $this->_data['swap_type'] = filter_var($this->uri->segment(3), FILTER_SANITIZE_STRING);
        if (!$this->_data['id'] || !$this->_data['swap_type'])
        {
            header('Location: ' . base_url());
            exit;
        }

        $this->load->model('SwapModel', '', true);
        $this->load->model('BeerModel', '', true);
        
        if ($this->_data['id'] != $this->_data['user_info']['id'])
        {
            $this->load->model('UserModel', '', true);
            $this->_data['user_name'] = $this->UserModel->getUsernameByID($this->_data['id']);
        }

        $this->_data['swaps'] = $this->SwapModel->{'getSwap' . ucfirst($this->_data['swap_type']) . 'ByUserID'}($this->_data['id']);
        $this->layout->render('swaplist/in.php', $this->_data, 'two_column.php');
    }

    public function delete()
    {
        if ($this->_check_module_active(true) && $this->input->is_ajax_request())
        {
            $beer_id = filter_var($this->input->post('id'), FILTER_SANITIZE_NUMBER_INT);
            $swap_type = filter_var($this->input->post('type'), FILTER_SANITIZE_STRING);

            $this->load->model('SwapModel', '', true);
            $this->load->model('BeerModel', '', true);

            $this->SwapModel->{'removeSwap' . ucfirst(substr($swap_type, 0, -1))}($this->_data['user_info']['id'], $beer_id);

            $this->_data['id'] = $this->_data['user_info']['id'];
            $this->_data['swap_type'] = $swap_type;
            $this->_data['swaps'] = $this->SwapModel->{'getSwap' . ucfirst($swap_type) . 'ByUserID'}($this->_data['id']);
            
            $html = $this->load->view('swaplist/data.php', $this->_data, true);
            echo json_encode(array('result' => 'success', 'message' => $html, 'count' => count($this->_data['swaps'])));
        }
        else
        {
            echo json_encode(array('result' => 'error', 'message' => 'The process could not be completed.'));
        }
    }

    public function add() {
        if ($this->_check_module_active(true) && $this->input->is_ajax_request()) {
            $beer_id = filter_var($this->input->post('id'), FILTER_SANITIZE_NUMBER_INT);
            $swap_type = filter_var($this->input->post('type'), FILTER_SANITIZE_STRING);

            $this->load->model('SwapModel', '', true);
            $this->load->model('BeerModel', '', true);

            $this->SwapModel->{'insertSwap' . ucfirst(substr($swap_type, 0, -1))}($this->_data['user_info']['id'], $beer_id);

            $this->_data['id'] = $this->_data['user_info']['id'];
            $this->_data['swap_type'] = $swap_type;
            $this->_data['beer_info'] = $this->BeerModel->getBeerByID($beer_id);
            $this->_data['swaps'] = $this->SwapModel->getInsAndOutsByBeerID($beer_id);
            
            $html = $this->load->view('swaplist/beer.php', $this->_data, true);
            echo json_encode(array('result' => 'success', 'message' => $html));
        }
        else {
            echo json_encode(array('result' => 'error', 'message' => 'The process could not be completed.'));
        }
    }
}