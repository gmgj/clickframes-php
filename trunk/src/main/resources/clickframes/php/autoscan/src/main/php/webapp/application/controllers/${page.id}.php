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

#if ($action.type == "CREATE")
#foreach($entity in $form.entities)
        $newId = $this->${entity.name}_model->create${entity.name}(${dollarSign}${entity.id});
        ${dollarSign}${entity.id}->set${entity.primaryKey.name}($newId);
        $this->_${form.id}_${entity.id} = ${dollarSign}${entity.id};
#end
#elseif ($action.type == "CREATE_OR_UPDATE")
#foreach($entity in $form.entities)
        if (strlen(${dollarSign}this->input->post('${entity.id}_${entity.primaryKey.id}')) > 0) {
            // Set ID from hidden input
            ${dollarSign}${entity.id}->set${entity.primaryKey.name}(${dollarSign}this->input->post('${entity.id}_${entity.primaryKey.id}'));
            $this->${entity.name}_model->update${entity.name}(${dollarSign}${entity.id});
        } else {
            $newId = $this->${entity.name}_model->create${entity.name}(${dollarSign}${entity.id});
            ${dollarSign}${entity.id}->set${entity.primaryKey.name}($newId);
        }
        
        $this->_${form.id}_${entity.id} = ${dollarSign}${entity.id};
#end
#elseif ($action.type == "UPDATE")
#foreach($entity in $form.entities)
        // Set ID from hidden input
        ${dollarSign}${entity.id}->set${entity.primaryKey.name}(${dollarSign}this->input->post('${entity.id}_${entity.primaryKey.id}'));
        $this->${entity.name}_model->update${entity.name}(${dollarSign}${entity.id});
        $this->_${form.id}_${entity.id} = ${dollarSign}${entity.id};
#end
#elseif ($action.type == "DELETE")
#foreach($entity in $form.entities)
        // Get ID from hidden input
        $this->${entity.name}_model->delete${entity.name}(${dollarSign}this->input->post('${entity.primaryKey.id}'));
#end
#end

        // Compute the proper outcome
#foreach ($key in $page.allOutcomes.keySet())
#set($outcome = $page.allOutcomes.get($key))
        // ${dollarSign}outcome = self::OUTCOME_${key};
#end
        ${dollarSign}outcome = self::OUTCOME_${action.defaultOutcome.key};
        return ${dollarSign}outcome;
	}
#end
#end
#end

#foreach ($action in $page.actions)
	function _process${action.name}($params = array()) {

        // Compute the proper outcome
#foreach ($outcome in $action.outcomes)
        // ${dollarSign}outcome = self::OUTCOME_${outcome.key};
#end
        ${dollarSign}outcome = self::OUTCOME_${action.defaultOutcome.key};
        return ${dollarSign}outcome;
	}
#end

#foreach ($email in $page.emails)
	function _send${email.name}() {
		
		// TODO: Remove once implemented
		log_message('warn', '${page.name}->_send${email.name} called, but not yet implemented.');
		return;
		
		// TODO: Set sender address
		$from = '';
		
		// TODO: Set recipient(s)
		$to = ''; // may be an individual e-mail address, comma-separated values, or array of address
		
#foreach ($param in $email.parameters)
#set($found = false)
#foreach ($form in $page.forms)
#foreach ($entity in $form.entities)
#if ($param.entityProperty.entity.id == $entity.id)
#set($found = true)
		${dollarSign}${param.id} = $this->_${form.id}_${entity.id}->get${param.entityProperty.name}();
#end
#end
#if (!$found)
		// TODO: Define param value
		${dollarSign}${param.id} = null;
#end
#end
#end

#foreach ($output in $email.outputs)
#set($found = false)
#foreach ($form in $page.forms)
#foreach ($entity in $form.entities)
#if ($output.entity.id == $entity.id)
#set($found = true)
		${dollarSign}${output.id} = $this->_${form.id}_${entity.id};
#end
#end
#if (!$found)
		// TODO: Define output value
		${dollarSign}${output.id} = null;
#end
#end
#end
		
		$result = $this->${appspec.id}Email->send${email.name}($from, $to#foreach($param in $email.parameters), ${dollarSign}${param.id}#end#foreach($output in $email.outputs), ${dollarSign}${output.id}#end);
	
		if ($result !== TRUE) {
			show_error("Unable to send email.\n\n" + $result);
		}
	}
#end
   
}

/* clickframes::::clickframes */
?>