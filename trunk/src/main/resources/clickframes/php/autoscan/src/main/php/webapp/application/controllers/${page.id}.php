#set($dollarSign="$")
<?php

include('${appspec.id}/Generated${page.name}Controller.php');

class ${page.name} extends Generated${page.name}Controller {
    
    function ${page.name}() {
        parent::Generated${page.name}Controller();
    }
    
#foreach ($form in $page.forms)
	function _validate${form.name}() {
        return parent::_validate${form.name}();
	}
	
#foreach ($action in $form.actions)
	function _process${form.name}${action.name}($params = array()) {
#if ($form.inputs.size() > 0)
## Create entity objects for all referenced entities and populate them from the form
#foreach ($entity in $form.entities)
    	${dollarSign}${entity.id} = new ${entity.name}DTO();
#end
#foreach ($input in $form.inputs)
#if ($input.entityProperty)	
        ${dollarSign}${input.entityProperty.entity.id}->set${input.entityProperty.name}(${dollarSign}this->input->post('${input.id}'));
#end
#end

        // Compute the proper outcome
#foreach ($key in $page.allOutcomes.keySet())
#set($outcome = $page.allOutcomes.get($key))
        // ${dollarSign}outcome = self::OUTCOME_${key}_${outcome.uppercaseId};
#end
        ${dollarSign}outcome = self::OUTCOME_${action.defaultOutcome.uppercaseId};
        return ${dollarSign}outcome;
	}
#end
#end
#end

#foreach ($action in $page.actions)
	function _process${action.name}($params = array()) {

        // Compute the proper outcome
#foreach ($outcome in $action.outcomes)
        // ${dollarSign}outcome = self::OUTCOME_${outcome.uppercaseId};
#end
        ${dollarSign}outcome = self::OUTCOME_${action.defaultOutcome.uppercaseId};
        return ${dollarSign}outcome;
	}
#end
   
}

?>