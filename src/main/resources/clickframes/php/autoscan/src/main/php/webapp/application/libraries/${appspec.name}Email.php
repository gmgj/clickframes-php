#set($dollarSign="$")
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

include('Abstract${appspec.name}Email.php');

class ${appspec.name}Email extends Abstract${appspec.name}Email {
	
#foreach ($email in $appspec.emails)
	/*
	 * ${email.title}
	 * ${email.description}
	 *
#foreach ($fact in $email.facts)
	 * - ${fact.description}
#end
	 *
	 * @param string $from Sender's e-mail address
	 * @param mixed $to Individual e-mail address string, a comma-separated string of e-mail address, or an array of individual e-mail address strings
#foreach ($param in $email.parameters)
	 * @param string ${dollarSign}${param.id} $!{param.description}
#end
#foreach ($output in $email.outputs)
	 * @param ${output.entity.name}DTO ${output.description}
#end
	 *
	*/
	function send${email.name}($from, $to#foreach($param in $email.parameters), ${dollarSign}${param.id} = null#end#foreach($output in $email.outputs), ${dollarSign}${output.id} = null#end) {
		
		$vars = array();
#foreach($param in $email.parameters)
		$vars['#{${param.id}}'] = ${dollarSign}${param.id};
#end
#foreach($output in $email.outputs)
#foreach($property in $output.entity.properties)
		$vars['#{${output.id}.${property.id}}'] = ${dollarSign}${output.id}->get${property.name}();
#end
#end

		$this->sendMessage($from, $to, $this->lang->line('email_${email.id}_subject'), $vars, $this->lang->line('email_${email.id}_message'));

	}
#end
	
}

/* clickframes::::clickframes */
?>