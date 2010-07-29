<?php

/*
**	E-mail '${email.id}'
**	${email.description}
#foreach ($fact in $email.facts)**	- ${fact.description}
#end
*/

// E-mail address of sender
$emailFrom = '';

// E-mail address of recipient
$emailTo = '';

// Specify all substitution variables for populating the e-mail text
$emailVars = array(
		'a' => 'b',
		'c' => 'd'
	);

$result = $this->clickframes->sendMessage(
		$emailFrom,
		$emailTo,
		$this->lang->line('email_${email.id}_subject'),
		$this->lang->line('email_${email.id}_message'),
		$emailVars
	);

if ($result !== TRUE) {
	show_error("Unable to send email.\n\n" + $result);
}

/* clickframes::::clickframes */
?>