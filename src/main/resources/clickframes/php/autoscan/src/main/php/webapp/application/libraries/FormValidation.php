<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Form Validation Class
 *
 * @package		CodeIgniter
 * @subpackage	Libraries
 * @category	Validation
 * @author		ExpressionEngine Dev Team
 * @author		Jonathan Abbett
 */
class FormValidation {
	
	var $CI;
	var $_field_data			= array();	
	var $_config_rules			= array();
	var $_error_array			= array();
	var $_error_messages		= array();	
	var $_error_prefix			= '<p>';
	var $_error_suffix			= '</p>';
	var $error_string			= '';
	var $_safe_form_data 		= FALSE;


	/**
	 * Constructor
	 *
	 */	
	function FormValidation($rules = array())
	{	
		$this->CI =& get_instance();
		
		// Validation rules can be stored in a config file.
		$this->_config_rules = $rules;
		
		// Automatically load the form helper
		$this->CI->load->helper('form');

		// Set the character encoding in MB.
		if (function_exists('mb_internal_encoding'))
		{
			mb_internal_encoding($this->CI->config->item('charset'));
		}
	
		log_message('debug', "Form Validation Class Initialized");
	}
	
	// --------------------------------------------------------------------
	
	function set_field($field, $label = '', $default = '') {

		// No fields? Nothing to do...
		if (!is_string($field) || $field == '') {
			return;
		}
		
		$label = ($label == '') ? $field : $label;
		
		$this->_field_data[$field] = array(
			'field'				=> $field, 
			'label'				=> $label, 
			'postdata'			=> $default,
			'error'				=> '',
			'rules'				=> array()
//			'is_array'			=> $is_array,
//			'keys'				=> $indexes,
			);
	}
	
	function set_rule($field, $rule, $message = '') {
		// No field? No rule? Nothing to do...
		if (!is_string($field) || !is_string($rule) || $field == '' || $rule == '') {
			return;
		}

		$this->_field_data[$field]['rules'][] = array(
			'rule'				=> $rule,
			'message'			=> ($message == '' ? $this->get_message_for_rule($field, $rule) : $message)
			);
	}
	
	function get_message_for_rule($field, $rule) {
		// TODO: improve this!
		return $field . ' is invalid (' . $rule . ')';
	}
	
	
/*

		// Is the field name an array?  We test for the existence of a bracket "[" in
		// the field name to determine this.  If it is an array, we break it apart
		// into its components so that we can fetch the corresponding POST data later		
		if (strpos($field, '[') !== FALSE AND preg_match_all('/\[(.*?)\]/', $field, $matches))
		{	
			// Note: Due to a bug in current() that affects some versions
			// of PHP we can not pass function call directly into it
			$x = explode('[', $field);
			$indexes[] = current($x);

			for ($i = 0; $i < count($matches['0']); $i++)
			{
				if ($matches['1'][$i] != '')
				{
					$indexes[] = $matches['1'][$i];
				}
			}
			
			$is_array = TRUE;
		}
		else
		{
			$indexes 	= array();
			$is_array	= FALSE;		
		}

*/
	
	/**
	 * Set The Error Delimiter
	 *
	 * Permits a prefix/suffix to be added to each error message
	 *
	 * @access	public
	 * @param	string
	 * @param	string
	 * @return	void
	 */	
	function set_error_delimiters($prefix = '<p>', $suffix = '</p>') {
		$this->_error_prefix = $prefix;
		$this->_error_suffix = $suffix;
	}


	/**
	 * Get Error Message
	 *
	 * Gets the error message associated with a particular field
	 *
	 * @access	public
	 * @param	string	the field name
	 * @return	void
	 */	
	function error($field = '', $prefix = '', $suffix = '') {	
		if ( ! isset($this->_field_data[$field]['error']) OR $this->_field_data[$field]['error'] == '')
		{
			return '';
		}
		
		if ($prefix == '')
		{
			$prefix = $this->_error_prefix;
		}

		if ($suffix == '')
		{
			$suffix = $this->_error_suffix;
		}

		return $prefix.$this->_field_data[$field]['error'].$suffix;
	}

	// --------------------------------------------------------------------
	
	/**
	 * Error String
	 *
	 * Returns the error messages as a string, wrapped in the error delimiters
	 *
	 * @access	public
	 * @param	string
	 * @param	string
	 * @return	str
	 */	
	function error_string($prefix = '', $suffix = '')
	{
		// No errors, validation passes!
		if (count($this->_error_array) === 0)
		{
			return '';
		}
		
		if ($prefix == '')
		{
			$prefix = $this->_error_prefix;
		}

		if ($suffix == '')
		{
			$suffix = $this->_error_suffix;
		}
		
		// Generate the error string
		$str = '';
		foreach ($this->_error_array as $val)
		{
			if ($val != '')
			{
				$str .= $prefix.$val.$suffix."\n";
			}
		}
		
		return $str;
	}

	// --------------------------------------------------------------------
	
	/**
	 * Run the Validator
	 *
	 * This function does all the work.
	 *
	 * @access	public
	 * @return	bool
	 */		
	function run($group = '') {
		
		log_message('debug', 'FIELD DATA:'."\n".print_r($this->_field_data, true));
		
		// Do we even have any data to process?  Mm?
		if (count($_POST) == 0)
		{
			return FALSE;
		}

/*		
		// Does the _field_data array containing the validation rules exist?
		// If not, we look to see if they were assigned via a config file
		if (count($this->_field_data) == 0) {

			// No validation rules?  We're done...
			if (count($this->_config_rules) == 0)
			{
				return FALSE;
			}
			
			// Is there a validation rule for the particular URI being accessed?
			$uri = ($group == '') ? trim($this->CI->uri->ruri_string(), '/') : $group;
			
			if ($uri != '' AND isset($this->_config_rules[$uri]))
			{
				$this->set_rules($this->_config_rules[$uri]);
			}
			else
			{
				$this->set_rules($this->_config_rules);
			}
	
			// Were we able to set the rules correctly?
			if (count($this->_field_data) == 0)
			{
				log_message('debug', "Unable to find validation rules");
				return FALSE;
			}
		}
*/

		if (count($this->_field_data) == 0) {
			log_message('debug', "Unable to find validation rules");
			return FALSE;
		}
		
		/*
		// Load the language file containing error messages
		$this->CI->lang->load('form_validation');
		*/
							
		// Cycle through the rules for each field, match the 
		// corresponding $_POST item and test for errors
		foreach ($this->_field_data as $field => $row) {		
			// Fetch the data from the corresponding $_POST array and cache it in the _field_data array.
			if (isset($_POST[$field]) && $_POST[$field] != "") {
				$this->_field_data[$field]['postdata'] = $_POST[$field];
			}
			$this->_execute($row, $row['rules'], $this->_field_data[$field]['postdata']);
		}

		// Did we end up with any errors?
		$total_errors = count($this->_error_array);

		if ($total_errors > 0) {
			$this->_safe_form_data = TRUE;
		}

		// Now we need to re-set the POST data with the new, processed data
		$this->_reset_post_array();
		
		// No errors, validation passes!
		if ($total_errors == 0) {
			return TRUE;
		}

		// Validation fails
		return FALSE;
	}


	/**
	 * Traverse a multidimensional $_POST array index until the data is found
	 *
	 * @access	private
	 * @param	array
	 * @param	array
	 * @param	integer
	 * @return	mixed
	 */		
	function _reduce_array($array, $keys, $i = 0)
	{
		if (is_array($array))
		{
			if (isset($keys[$i]))
			{
				if (isset($array[$keys[$i]]))
				{
					$array = $this->_reduce_array($array[$keys[$i]], $keys, ($i+1));
				}
				else
				{
					return NULL;
				}
			}
			else
			{
				return $array;
			}
		}
	
		return $array;
	}

	// --------------------------------------------------------------------
	
	/**
	 * Re-populate the _POST array with our finalized and processed data
	 *
	 * @access	private
	 * @return	null
	 */		
	function _reset_post_array()
	{
		foreach ($this->_field_data as $field => $row)
		{
			if ( ! is_null($row['postdata']))
			{
/*
				if ($row['is_array'] == FALSE)
				{
*/
					if (isset($_POST[$row['field']]))
					{
						$_POST[$row['field']] = $this->prep_for_form($row['postdata']);
					}
/*
				}
				else
				{

					// start with a reference
					$post_ref =& $_POST;
					
					// before we assign values, make a reference to the right POST key
					if (count($row['keys']) == 1)
					{
						$post_ref =& $post_ref[current($row['keys'])];
					}
					else
					{
						foreach ($row['keys'] as $val)
						{
							$post_ref =& $post_ref[$val];
						}
					}

					if (is_array($row['postdata']))
					{
						$array = array();
						foreach ($row['postdata'] as $k => $v)
						{
							$array[$k] = $this->prep_for_form($v);
						}

						$post_ref = $array;
					}
					else
					{
						$post_ref = $this->prep_for_form($row['postdata']);
					}
				}
*/
			}
		}
	}

	// --------------------------------------------------------------------
	
	function _has_rule($rule, $rules) {
		return !is_null($this->_get_first_rule($rule, $rules));
	}
	
	function _get_first_rule($rule, $rules) {
		foreach ($rules as $r) {
			if ($r['rule'] == $rule) {
				return $r;
			}
		}
		return null;
	}
	
	/**
	 * Executes the Validation routines
	 *
	 * @access	private
	 * @param	array
	 * @param	array
	 * @param	mixed
	 * @param	integer
	 * @return	mixed
	 */	
	function _execute($row, $rules, $postdata = NULL, $cycles = 0) {
		
		/*
		// If the $_POST data is an array we will run a recursive call
		if (is_array($postdata))
		{ 
			foreach ($postdata as $key => $val)
			{
				$this->_execute($row, $rules, $val, $cycles);
				$cycles++;
			}
			
			return;
		}
		*/
		

		// If the field is blank, but NOT required, no further tests are necessary
		$callback = FALSE;
		
		if (!$this->_has_rule('required', $rules) && is_null($postdata)) {
			return;
			
			// TODO: figure this out
			/*
			// Before we bail out, does the rule contain a callback?
			if (preg_match("/(callback_\w+)/", implode(' ', $rules), $match))
			{
				$callback = TRUE;
				$rules = (array('1' => $match[1]));
			}
			else
			{
				return;
			}
			*/
		}

		// --------------------------------------------------------------------
		
		// Isset Test. Typically this rule will only apply to checkboxes.
		if (is_null($postdata) AND $callback == FALSE) {
			
			if ($this->_has_rule('isset', $rules)) {
				$rule = $this->_get_first_rule('isset', $rules);
			} else {
				$rule = $this->_get_first_rule('required', $rules);
			}
			
			$this->_field_data[$row['field']]['error'] = $rule['message'];
			
			if (!isset($this->_error_array[$row['field']])) {
				$this->_error_array[$row['field']] = $rule['message'];
			}
			
			return;
		}

		// Cycle through each rule and run it
		foreach ($rules as $ruleArray) {
/*
			$_in_array = FALSE;
			
			// We set the $postdata variable with the current data in our master array so that
			// each cycle of the loop is dealing with the processed data from the last cycle
			if ($row['is_array'] == TRUE AND is_array($this->_field_data[$row['field']]['postdata']))
			{
				// We shouldn't need this safety, but just in case there isn't an array index
				// associated with this cycle we'll bail out
				if ( ! isset($this->_field_data[$row['field']]['postdata'][$cycles]))
				{
					continue;
				}
			
				$postdata = $this->_field_data[$row['field']]['postdata'][$cycles];
				$_in_array = TRUE;
			}
			else
			{
*/
			$postdata = $this->_field_data[$row['field']]['postdata'];
//			}

			// --------------------------------------------------------------------
	
			$currentRule = $ruleArray['rule'];
	
			// Is the rule a callback?			
			$callback = FALSE;
			if (substr($currentRule, 0, 9) == 'callback_') {
				$currentRule = substr($rule, 9);
				$callback = TRUE;
			}
			
			// Strip the parameter (if exists) from the rule
			// Rules can contain a parameter: max_length[5]
			$param = FALSE;
			if (preg_match("/(.*?)\[(.*?)\]/", $currentRule, $match)) {
				$currentRule	= $match[1];
				$param			= $match[2];
			}
			
			// Call the function that corresponds to the rule
			if ($callback === TRUE) {
				if (!method_exists($this->CI, $currentRule)) { 		
					continue;
				}
				
				// Run the function and grab the result
				$result = $this->CI->$currentRule($postdata, $param);

				// Re-assign the result to the master data array
				/*
				if ($_in_array == TRUE)
				{
					$this->_field_data[$row['field']]['postdata'][$cycles] = (is_bool($result)) ? $postdata : $result;
				}
				else
				{
				*/
				$this->_field_data[$row['field']]['postdata'] = (is_bool($result)) ? $postdata : $result;
//				}
			
				// If the field isn't required and we just processed a callback we'll move on...
				if (!$this->_has_rule('required', $rules) && $result !== FALSE) {
					continue;
				}
			} else {				
				if (!method_exists($this, $currentRule)) {
					
					// If our own wrapper function doesn't exist we see if a native PHP function does. 
					// Users can use any native PHP function call that has one param.
					if (function_exists($currentRule)) {
						
						$result = $currentRule($postdata);
											
						/*
						if ($_in_array == TRUE)
						{
							$this->_field_data[$row['field']]['postdata'][$cycles] = (is_bool($result)) ? $postdata : $result;
						}
						else
						{*/
						$this->_field_data[$row['field']]['postdata'] = (is_bool($result)) ? $postdata : $result;
//						}
					}
										
					continue;
				}

				$result = $this->$currentRule($postdata, $param);
/*
				if ($_in_array == TRUE)
				{
					$this->_field_data[$row['field']]['postdata'][$cycles] = (is_bool($result)) ? $postdata : $result;
				}
				else
				{
*/
				$this->_field_data[$row['field']]['postdata'] = (is_bool($result)) ? $postdata : $result;
//				}
			}
							
			// Did the rule test negatively?  If so, grab the error.
			if ($result === FALSE) {
			
				$line = $rule['message'];
/*				
			
			

*/

				// Save the error message
				$this->_field_data[$row['field']]['error'] = $rule['message'];

				if (!isset($this->_error_array[$row['field']])) {
					$this->_error_array[$row['field']] = $rule['message'];
				}
				
				return;
			}
		}
	}

	
	/**
	 * Get the value from a form
	 *
	 * Permits you to repopulate a form field with the value it was submitted
	 * with, or, if that value doesn't exist, with the default
	 *
	 * @access	public
	 * @param	string	the field name
	 * @param	string
	 * @return	void
	 */	
	function set_value($field = '', $default = '') {
		if ( ! isset($this->_field_data[$field])) {
			return $default;
		}
		return $this->_field_data[$field]['postdata'];
	}
	

	/**
	 * Set Select
	 *
	 * Enables pull-down lists to be set to the value the user
	 * selected in the event of an error
	 *
	 * @access	public
	 * @param	string
	 * @param	string
	 * @return	string
	 */	
	function set_select($field = '', $value = '', $default = FALSE) {		
		if ( ! isset($this->_field_data[$field]) OR ! isset($this->_field_data[$field]['postdata'])) {
			if ($default === TRUE AND count($this->_field_data) === 0) {
				return ' selected="selected"';
			}
			return '';
		}
	
		$field = $this->_field_data[$field]['postdata'];
		
		if (is_array($field)) {
			if ( ! in_array($value, $field)) {
				return '';
			}
		} else {
			if (($field == '' OR $value == '') OR ($field != $value)) {
				return '';
			}
		}
			
		return ' selected="selected"';
	}
	

	/**
	 * Set Radio
	 *
	 * Enables radio buttons to be set to the value the user
	 * selected in the event of an error
	 *
	 * @access	public
	 * @param	string
	 * @param	string
	 * @return	string
	 */	
	function set_radio($field = '', $value = '', $default = FALSE) {
		if ( ! isset($this->_field_data[$field]) OR ! isset($this->_field_data[$field]['postdata'])) {
			if ($default === TRUE AND count($this->_field_data) === 0) {
				return ' checked="checked"';
			}
			return '';
		}
	
		$field = $this->_field_data[$field]['postdata'];
		
		if (is_array($field)) {
			if ( ! in_array($value, $field)) {
				return '';
			}
		} else {
			if (($field == '' OR $value == '') OR ($field != $value)) {
				return '';
			}
		}
			
		return ' checked="checked"';
	}
	
	/**
	 * Set Checkbox
	 *
	 * Enables checkboxes to be set to the value the user
	 * selected in the event of an error
	 *
	 * @access	public
	 * @param	string
	 * @param	string
	 * @return	string
	 */	
	function set_checkbox($field = '', $value = '', $default = FALSE) {
		if ( ! isset($this->_field_data[$field]) OR ! isset($this->_field_data[$field]['postdata'])) {
			if ($default === TRUE AND count($this->_field_data) === 0) {
				return ' checked="checked"';
			}
			return '';
		}
	
		$field = $this->_field_data[$field]['postdata'];
		
		if (is_array($field)) {
			if ( ! in_array($value, $field)) {
				return '';
			}
		} else {
			if (($field == '' OR $value == '') OR ($field != $value)) {
				return '';
			}
		}
			
		return ' checked="checked"';
	}
	

	/**
	 * Required
	 *
	 * @access	public
	 * @param	string
	 * @return	bool
	 */
	function required($str) {
		if (!is_array($str)) {
			return (trim($str) == '') ? FALSE : TRUE;
		} else {
			return (!empty($str));
		}
	}
	

	/**
	 * Match one field to another
	 *
	 * @access	public
	 * @param	string
	 * @param	field
	 * @return	bool
	 */
	function matches($str, $field) {
		if ( ! isset($_POST[$field])) {
			return FALSE;				
		}
		
		$field = $_POST[$field];

		return ($str !== $field) ? FALSE : TRUE;
	}
	

	/**
	 * Minimum Length
	 *
	 * @access	public
	 * @param	string
	 * @param	value
	 * @return	bool
	 */	
	function min_length($str, $val) {
		if (preg_match("/[^0-9]/", $val)) {
			return FALSE;
		}

		if (function_exists('mb_strlen')) {
			return (mb_strlen($str) < $val) ? FALSE : TRUE;		
		}
	
		return (strlen($str) < $val) ? FALSE : TRUE;
	}
	

	/**
	 * Max Length
	 *
	 * @access	public
	 * @param	string
	 * @param	value
	 * @return	bool
	 */	
	function max_length($str, $val) {
		if (preg_match("/[^0-9]/", $val)) {
			return FALSE;
		}

		if (function_exists('mb_strlen')) {
			return (mb_strlen($str) > $val) ? FALSE : TRUE;		
		}
	
		return (strlen($str) > $val) ? FALSE : TRUE;
	}
	

	/**
	 * Exact Length
	 *
	 * @access	public
	 * @param	string
	 * @param	value
	 * @return	bool
	 */	
	function exact_length($str, $val) {
		if (preg_match("/[^0-9]/", $val)) {
			return FALSE;
		}

		if (function_exists('mb_strlen')) {
			return (mb_strlen($str) != $val) ? FALSE : TRUE;		
		}
	
		return (strlen($str) != $val) ? FALSE : TRUE;
	}
	

	/**
	 * Valid Email
	 *
	 * @access	public
	 * @param	string
	 * @return	bool
	 */	
	function valid_email($str) {
		return ( ! preg_match("/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix", $str)) ? FALSE : TRUE;
	}


	/**
	 * Valid Emails
	 *
	 * @access	public
	 * @param	string
	 * @return	bool
	 */	
	function valid_emails($str) {
		if (strpos($str, ',') === FALSE) {
			return $this->valid_email(trim($str));
		}
		
		foreach(explode(',', $str) as $email) {
			if (trim($email) != '' && $this->valid_email(trim($email)) === FALSE) {
				return FALSE;
			}
		}
		
		return TRUE;
	}


	/**
	 * Validate IP Address
	 *
	 * @access	public
	 * @param	string
	 * @return	string
	 */
	function valid_ip($ip) {
		return $this->CI->input->valid_ip($ip);
	}


	/**
	 * Alpha
	 *
	 * @access	public
	 * @param	string
	 * @return	bool
	 */		
	function alpha($str) {
		return ( ! preg_match("/^([a-z])+$/i", $str)) ? FALSE : TRUE;
	}
	

	/**
	 * Alpha-numeric
	 *
	 * @access	public
	 * @param	string
	 * @return	bool
	 */	
	function alpha_numeric($str) {
		return ( ! preg_match("/^([a-z0-9])+$/i", $str)) ? FALSE : TRUE;
	}
	

	/**
	 * Alpha-numeric with underscores and dashes
	 *
	 * @access	public
	 * @param	string
	 * @return	bool
	 */	
	function alpha_dash($str) {
		return ( ! preg_match("/^([-a-z0-9_-])+$/i", $str)) ? FALSE : TRUE;
	}
	

	/**
	 * Numeric
	 *
	 * @access	public
	 * @param	string
	 * @return	bool
	 */	
	function numeric($str) {
		return (bool)preg_match( '/^[\-+]?[0-9]*\.?[0-9]+$/', $str);

	}


    /**
     * Is Numeric
     *
     * @access    public
     * @param    string
     * @return    bool
     */
    function is_numeric($str) {
        return ( ! is_numeric($str)) ? FALSE : TRUE;
    } 

	/**
	 * Integer
	 *
	 * @access	public
	 * @param	string
	 * @return	bool
	 */	
	function integer($str) {
		return (bool)preg_match( '/^[\-+]?[0-9]+$/', $str);
	}
	

    /**
     * Is a Natural number  (0,1,2,3, etc.)
     *
     * @access	public
     * @param	string
     * @return	bool
     */
    function is_natural($str) {   
   		return (bool)preg_match( '/^[0-9]+$/', $str);
    }


    /**
     * Is a Natural number, but not a zero  (1,2,3, etc.)
     *
     * @access	public
     * @param	string
     * @return	bool
     */
	function is_natural_no_zero($str) {
    	if ( ! preg_match( '/^[0-9]+$/', $str)) {
    		return FALSE;
    	}
    	
    	if ($str == 0) {
    		return FALSE;
    	}
    
   		return TRUE;
    }
	

	/**
	 * Valid Base64
	 *
	 * Tests a string for characters outside of the Base64 alphabet
	 * as defined by RFC 2045 http://www.faqs.org/rfcs/rfc2045
	 *
	 * @access	public
	 * @param	string
	 * @return	bool
	 */
	function valid_base64($str) {
		return (bool) ! preg_match('/[^a-zA-Z0-9\/\+=]/', $str);
	}
	

	/**
	 * Prep data for form
	 *
	 * This function allows HTML to be safely shown in a form.
	 * Special characters are converted.
	 *
	 * @access	public
	 * @param	string
	 * @return	string
	 */
	function prep_for_form($data = '') {
		if (is_array($data)) {
			foreach ($data as $key => $val) {
				$data[$key] = $this->prep_for_form($val);
			}
			
			return $data;
		}
		
		if ($this->_safe_form_data == FALSE OR $data === '') {
			return $data;
		}

		return str_replace(array("'", '"', '<', '>'), array("&#39;", "&quot;", '&lt;', '&gt;'), stripslashes($data));
	}
	

	/**
	 * Prep URL
	 *
	 * @access	public
	 * @param	string
	 * @return	string
	 */	
	function prep_url($str = '') {
		if ($str == 'http://' OR $str == '') {
			return '';
		}
		
		if (substr($str, 0, 7) != 'http://' && substr($str, 0, 8) != 'https://') {
			$str = 'http://'.$str;
		}
		
		return $str;
	}
	

	/**
	 * Strip Image Tags
	 *
	 * @access	public
	 * @param	string
	 * @return	string
	 */	
	function strip_image_tags($str) {
		return $this->CI->input->strip_image_tags($str);
	}
	

	/**
	 * XSS Clean
	 *
	 * @access	public
	 * @param	string
	 * @return	string
	 */	
	function xss_clean($str) {
		return $this->CI->input->xss_clean($str);
	}
	

	/**
	 * Convert PHP tags to entities
	 *
	 * @access	public
	 * @param	string
	 * @return	string
	 */	
	function encode_php_tags($str) {
		return str_replace(array('<?php', '<?PHP', '<?', '?>'),  array('&lt;?php', '&lt;?PHP', '&lt;?', '?&gt;'), $str);
	}

}

/* clickframes::::clickframes */