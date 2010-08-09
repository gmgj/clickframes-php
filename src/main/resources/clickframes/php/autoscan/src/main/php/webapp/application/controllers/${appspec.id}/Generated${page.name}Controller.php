#set ($controller = ${context.get($page).controller})
#set($dollarSign="$")
<?php

include('${appspec.name}Controller.php');

/**
 * This class contains a default implementation of all methods required to
 * retrieve data from the model and process page/form actions. Please avoid
 * reimplementing the methods in this class; override them in the child class.
 * 
#if ($page.description) 
 * ${page.description}
#end
 *
 * Automatically generated by Clickframes.
 */
class Generated${page.name}Controller extends ${appspec.name}Controller {

#foreach ($key in $page.allOutcomes.keySet())
#set($outcome = $page.allOutcomes.get($key))
	const OUTCOME_${key} = ${velocityCount};
#end

#foreach($param in $page.parameters)
	private $_${param.id};
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
        $this->_${param.id} = ${dollarSign}${param.id};
#if ($param.required)
        if (is_null(${dollarSign}${param.id})) {
            show_error('Required parameter `${param.id}` not provided.');
        }
#end
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
				case self::OUTCOME_${key} :
					$this->_${outcome.id}Outcome();
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
		return self::OUTCOME_${action.defaultOutcome.key};
	}
#end
#end

#foreach ($action in $page.actions)
	function _process${action.name}($params = array()) {
		// return the default successful outcome
		return self::OUTCOME_${action.defaultOutcome.key};
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
	
	function _display($data) {
		// Customize by overriding this function in the child class
		$this->load->view('header', $data);
		$this->load->view('${page.id.toLowerCase()}', $data);
		$this->load->view('footer', $data);
	}
    
	function _getDisplayData($params = array()) {
		// Customize by overriding this function in the child class
		$data = parent::_getDisplayData();
        
        $data['pageTitle'] = $this->lang->line('${appspec.id}_${page.id}_title');
		
#foreach ($linkset in $appspec.globalLinkSets)
		$data['navigations'][] = $this->load->view('navigation/${linkset.id}', '', true);
#end

### OUTPUTS (MAPPED TO PARAMS)
#foreach($pageParameter in $page.parameters) 
#if (${pageParameter.entityProperty} && ${pageParameter.entityProperty.primaryKey})
		$data['outputs']['${pageParameter.entityProperty.entity.id}'] = $this->_load${pageParameter.entityProperty.entity.name}();
#end
#end

### OUTPUT LISTS
#foreach($outputList in $page.outputLists)
		$data['outputLists']['${outputList.id}'] = $this->_load${outputList.name}($params);
#end

		return $data;
	}
    
#foreach ($pageEntity in ${controller.pageEntities})
	function _load${pageEntity.name}() {
        // Customize by overriding this function in the child class
#if (${pageEntity.type} == 'PARAM_PRIMARY_KEY')
        if (!is_null($this->_${pageEntity.pageParameter.id})) {
            return $this->${pageEntity.name}_model->read${pageEntity.name}($this->_${pageEntity.pageParameter.id});
        }
#end
        return null;
	}
#end

#foreach($outputList in $page.outputLists)
	function _load${outputList.name}($params = array()) {
		// Customize by overriding this function in the child class
		return $this->${outputList.entity.name}_model->get${outputList.name}($params);
	}
#end

}

/* clickframes::::clickframes */
?>