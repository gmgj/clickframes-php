#set($dollarSign="$")
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

abstract class Abstract${appspec.name}Email {

#foreach ($email in $appspec.emails)
	abstract function send${email.name}($from, $to#foreach($param in $email.parameters ), ${dollarSign}${param.id} = null#end#foreach($output in $email.outputs), ${dollarSign}${output.id} = null#end);
#end

	protected function sendMessage($from, $to, $subject, $vars, ${dollarSign}template) {
		$CI =& get_instance();
		
		$CI->load->library('email');

		$CI->email->clear(TRUE);

		$CI->email->from($from);
		$CI->email->to($to);
		$CI->email->subject($subject);
		$CI->email->message(str_replace(array_keys($vars), array_values($vars), ${dollarSign}template));

		if (!$CI->email->send()) {
			return $CI->email->print_debugger();
		}

		return TRUE;
	}
}

/* clickframes::::clickframes */
?>