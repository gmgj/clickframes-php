#set($dollarSign="$")
<?php

include('${appspec.name}Controller.php');

class Generated${page.name}Controller extends ${appspec.name}Controller {

#foreach ($key in $page.allOutcomes.keySet())
#set($outcome = $page.allOutcomes.get($key))
	const OUTCOME_${key}_${outcome.uppercaseId} = ${velocityCount};
#end

    /**
	 *	Constructor
	 */
	function Generated${page.name}Controller() {
    	parent::${appspec.name}Controller();
    }

    /**
     *	Displays the '${page.title}' page and processes its input.
#foreach( $param in $page.parameters )
     *	@param string ${dollarSign}${param.id} $!{param.description}
#end
     */
#if ($page.parameters.size() > 0)
    function index(#foreach( $param in $page.parameters )#if ($velocityCount != 1),#end ${dollarSign}${param.id} = null#end ) {
#else
    function index() {
#end
		$params = array();
#foreach( $param in $page.parameters )
		$params['${param.id}'] = ${dollarSign}${param.id};
#end

#if ($page.loginRequired)
		$this->_checkSecurity();
#end

#if ($page.actions.size() > 0 || $page.forms.size() > 0)
    	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
			${dollarSign}outcome = null;
			switch ($this->input->post('clickframesFormId')) {
#foreach ($form in $page.forms)
				case '${page.id}-${form.id}' :

					// Form '${form.id}'
					if ($this->_validate${form.name}()) {
#foreach ($action in $form.actions)

						// Action '${action.title}'
						if ($this->input->post('action:${action.id}')) {
							${dollarSign}outcome = $this->_process${form.name}${action.name}($params);
						}
#end						
					}
					break;
#end
#foreach ($action in $page.actions)
				case '${page.id}-action-${action.id}' :
					${dollarSign}outcome = $this->_process${action.name}($params);
					break;
#end
				default :
					show_error("Unexpected action ".$this->input->post('clickframesFormId')." on page '${page.id}'.");

			}
			
			switch (${dollarSign}outcome) {
#foreach ($key in $page.allOutcomes.keySet())
#set($outcome = $page.allOutcomes.get($key))
				case self::OUTCOME_${key}_${outcome.uppercaseId} :
					_${outcome.id}Outcome();
					break;
#end
				default:
					show_error("Unexpected outcome ".${dollarSign}outcome." on page '${page.id}'.");
			}
    	}
#end

#if ($page.loginPage)
		$this->session->keep_flashdata('referer');
#end
		$this->_display($this->_getDisplayData($params));

    }
	
#foreach ($form in $page.forms)
	function _validate${form.name}() {
#foreach($input in $form.inputs)
		$this->form_validation->set_rules('${input.id}', '${input.title}', 'trim#parse("clickframes/php/validations.vm")');
#end
		return $this->form_validation->run();
	}
	
#foreach ($action in $form.actions)
	function _process${form.name}${action.name}($params = array()) {
		// return the default successful outcome
		return self::OUTCOME_${action.defaultOutcome.uppercaseId};
	}
#end
#end

#foreach ($action in $page.actions)
	function _process${action.name}($params = array()) {
		// return the default successful outcome
		return self::OUTCOME_${action.parent.id}_${action.id}_${action.defaultOutcome.uppercaseId};
	}
#end

## OUTCOMES
#foreach ($key in $page.allOutcomes.keySet())
#set($outcome = $page.allOutcomes.get($key))
	function _${outcome.id}Outcome() {
#if ($outcome.negative)
		$messageClass = 'failure';
#else
		$messageClass = 'success';
#end
#if ($outcome.message)
		$this->session->set_flashdata('message', array('class' => $messageClass, 'text' => $this->lang->line('${appspec.id}_${page.id}_${key}_${outcome.id}_message')));
#end
#foreach ($email in $outcome.emails)
## REIMPLEMENT THIS!		
		include("email_${email.id}.php");
#end
#if ($outcome.loginSuccessfulOutcome)
		// Mark session as logged in
		$this->session->set_userdata('username', $this->input->post('${form.loginUsernameInput.id}'));
		// Redirect user to secure page he was trying to access, if applicable
		$referer = $this->session->flashdata('referer');
		if (!empty($referer)) {
			redirect($referer);
		}
#end
#if ($outcome.isInternal())
		redirect('/${outcome.pageRef}');
#else
		redirect('${outcome.href}');
#end
	}
#end
	
	function _getDisplayData($params = array()) {
		$data = array();
		
		$data['navigation'] = '';
#foreach ($linkset in $appspec.globalLinkSets)
		$data['navigation'] .= $this->load->view('navigation/${linkset.id}', '', true);
#end
		
		$data['applicationTitle']	= $this->lang->line('${appspec.id}_title');
		$data['pageTitle']			= $this->lang->line('${appspec.id}_${page.id}_title');
		$data['pageId']				= '${page.id}';
		$data['message']			= $this->session->flashdata('message');
		return $data;
	}
	
	function _display($data) {
		$this->load->view('header', $data);
		$this->load->view('${page.id.toLowerCase()}', $data);
		$this->load->view('footer', $data);
	}

}

/* clickframes::::clickframes */
?>