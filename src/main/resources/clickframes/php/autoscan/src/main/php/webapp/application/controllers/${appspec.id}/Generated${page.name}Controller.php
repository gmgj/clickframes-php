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
	/**
     *  URL parameter: ${param.name}
#if ($param.description)
     *  ${param.description}
#end
#if ($param.required)
     *  (Required)
#end
     */
    protected $_${param.id};

#end

#foreach ($form in $page.forms)
#foreach ($entity in $form.entities)
    /**
     *  ${entity.name} entity manipulated by ${form.name} form
     */
    protected $_${form.id}_${entity.id};

#end
#end

#foreach ($outputList in $page.outputLists)
	/**
	 *	Primary key of ${outputList.entity.name} selected for action in '${outputList.title}'
	 */
	protected $_${outputList.id}SelectedId;

	/**
	 *	Selected object of type ${outputList.entity.name} for action in '${outputList.title}'
	 */
	protected $_${outputList.id}Selected;

#end

	/**
	 *	Constructor
	 */
	function Generated${page.name}Controller() {
		parent::${appspec.name}Controller();
#if ($page.anyFileInputsOnPage)
		$this->load->library('upload');
#end
	}

	/**
	 *	Displays the '${page.title}' page and processes its input.
#foreach( $param in $page.parameters )
	 *	@param string ${dollarSign}${param.id} $!{param.description}
#end
	 */
#if ($page.parameters.size() > 0)
	function index(#foreach( $param in $page.parameters )#if ($velocityCount != 1),#end ${dollarSign}${param.id} = null#end) {
#else
	function index() {
#end
#if ($page.loginRequired)
		$this->_checkSecurity();
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

#foreach ($form in $page.forms)
#foreach ($entity in $form.entities)
		$this->_load${form.name}${entity.name}();
#end
		$this->_load${form.name}();
#end

#if ($page.actions.size() > 0 || $page.forms.size() > 0)
		if ($_SERVER['REQUEST_METHOD'] == 'POST') {
			${dollarSign}outcome = null;
			switch ($this->input->post('clickframesFormId')) {
#foreach ($form in $page.forms)
				case '${page.id}-${form.id}' :

					// Form '${form.id}'
#if ($form.inputs.size() > 0)
					if ($this->_validate${form.name}()) {
#else
					if (true) {
#end
#foreach ($action in $form.actions)

						// Action '${action.title}'
						if ($this->input->post('action:${action.id}')) {
							$this->_performOutcome($this->_process${form.name}${action.name}());
						}
#end						
					}
					break;
#end
#foreach ($action in $page.actions)
				case '${page.id}-action-${action.id}' :
					${dollarSign}outcome = $this->_process${action.name}();
					break;
#end
				default :
					show_error("Unexpected action ".$this->input->post('clickframesFormId')." on page '${page.id}'.");

			}
		}
#end

#if ($page.loginPage)
		$this->session->keep_flashdata('referer');
#end
		$this->_display($this->_getDisplayData($params));

	}
	
#foreach ($outputList in $page.outputLists)
#foreach ($action in $outputList.actions)
#if ($page.parameters.size() > 0)
	function ${action.id}(#foreach( $param in $page.parameters )${dollarSign}${param.id} = null,#end $${outputList.id}Selected) {
#else
	function ${action.id}($${outputList.id}Selected) {
#end
		$params = array();

		// check parameters
#foreach( $param in $page.parameters )
        $params['${param.id}'] = ${dollarSign}${param.id};
		$this->_${param.id} = ${dollarSign}${param.id};
#if ($param.required)
        if (is_null(${dollarSign}${param.id})) {
            show_error('Required parameter `${param.id}` not provided.');
        }
#end
#end
		$this->_${outputList.id}SelectedId = $${outputList.id}Selected;
		$this->_${outputList.id}Selected = $this->${outputList.entity.name}_model->read${outputList.entity.name}($this->_${outputList.id}SelectedId);
		
		// perform action
		$this->_performOutcome($this->_process${outputList.name}${action.name}());
	}

#end
#end

### Special case to create 
#if ($page.loginPage)
	function logout() {
		$this->${appspec.loginEntity.name}_model->logout();
		redirect('/${page.id}');
	}
#end

#foreach ($form in $page.forms)
#foreach ($entity in $form.entities)
    function _load${form.name}${entity.name}() { /* do nothing by default */ }
#end

	function _load${form.name}() { /* do nothing by default */ }
#end

	
	function _performOutcome(${dollarSign}outcome) {

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
	
#foreach ($form in $page.forms)
	function _validate${form.name}() {
#foreach($input in $form.inputs)
#foreach($validation in $input.validations)
		// ${validation.type}
#if ($validation.type == "required")
		$this->formvalidation->set_rule('${input.id}', 'required', '${validation.description}');
#end
#if ($validation.type == "email")
		$this->formvalidation->set_rule('${input.id}', 'valid_email', '${validation.description}');
#end
#if ($validation.type == "length" && $validation.hasArg("min"))
		$this->formvalidation->set_rule('${input.id}', 'min_length[${validation.getArgAsString("min")}]', '${validation.description}');
#end
#if ($validation.type == "length" && $validation.hasArg("max"))
		$this->formvalidation->set_rule('${input.id}', 'max_length[${validation.getArgAsString("max")}]', '${validation.description}');
#end
#if ($validation.type == "matchesInput")
		$this->formvalidation->set_rule('${input.id}', 'matches[${validation.otherInputId}]', '${validation.description}');
#end
#### cover other validation types here
#end
#end
		return $this->formvalidation->run();
	}
	
#foreach ($action in $form.actions)
	function _process${form.name}${action.name}() {
		// return the default successful outcome
		return self::OUTCOME_${action.defaultOutcome.key};
	}

#end
#end

#foreach ($action in $page.actions)
	function _process${action.name}() {
		// return the default successful outcome
		return self::OUTCOME_${action.defaultOutcome.key};
	}

#end

#foreach ($outputList in $page.outputLists)
#foreach ($action in $outputList.actions)
	function _process${outputList.name}${action.name}() {
		// return the default successful outcome
		return self::OUTCOME_${action.defaultOutcome.key};
	}

#end
#end

## OUTCOMES
#foreach ($form in $page.forms)
#foreach ($action in $form.actions)
#foreach ($outcome in $action.outcomes)
#parse("clickframes/php/outcome.vm")


#end
#end
#end

#foreach ($outputList in $page.outputLists)
#foreach ($action in $outputList.actions)
#foreach ($outcome in $action.outcomes)
#parse("clickframes/php/outcome.vm")


#end
#end
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
        
		$data['params'] = $params;
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

## TODO: what about outputs not mapped to params?

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
		log_message('debug', 'ENTITY PRIMARY KEY: ' . $this->_${pageEntity.pageParameter.id});
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