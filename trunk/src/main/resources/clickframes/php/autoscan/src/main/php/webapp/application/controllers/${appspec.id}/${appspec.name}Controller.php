#set($dollarSign="$")
<?php

class ${appspec.name}Controller extends Controller {

    function ${appspec.name}Controller() {
        parent::Controller();
        $this->load->helper('form');
        $this->load->helper('url');
        $this->load->library('session');
        $this->load->library('form_validation');
        $this->form_validation->set_error_delimiters('','');
        $this->lang->load('${appspec.name.toLowerCase()}', 'english');
#foreach ($entity in $appspec.entities)
        $this->load->model('${entity.name}_model', '', TRUE);
#end
    }

#if ($appspec.securityEnabled)    
    function _checkSecurity() {
		if (!$this->sessionAlive()) {
			$this->session->set_flashdata('referer', uri_string());
			redirect('/${appspec.getLoginPage.id}');
		}
    }
    
	function sessionAlive() {
		return ($this->session->userdata('username') !== FALSE
				&& $this->session->userdata('last_activity') > (time() - 900));
	}
#end

    function _getDisplayData() {
        $data = array();
        $data['pageId'] = $this->router->class;
		$data['applicationTitle'] = $this->lang->line('${appspec.id}_title');
		$data['message'] = $this->session->flashdata('message');
        return $data;
    }

	function sendMessage($from, $to, $subject, ${dollarSign}template, $vars) {

		$this->load->library('email');

		$this->email->clear(TRUE);

		$this->email->from($from);
		$this->email->to($to);
		$this->email->subject($subject);
		$this->email->message(str_replace(array_keys($vars), array_values($vars), ${dollarSign}template));

		if (!$this->email->send()) {
			return $this->email->print_debugger();
		}

		return TRUE;
	}


}

/* clickframes::::clickframes */
?>