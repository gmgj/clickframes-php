#set( $dollarSign = "$" )
<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Clickframes {

	function sendMessage($from, $to, $subject, ${dollarSign}template, $vars) {

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

## FIXME: make timeout length configurable in techspec (or appspec?)
	// confirms if session is alive
	// default timeout of 900 seconds
	function sessionAlive() {

		$CI =& get_instance();
		$CI->load->library('session');

		return ($CI->session->userdata('username') !== FALSE
				&& $CI->session->userdata('last_activity') > (time() - 900));

	}

}

?>