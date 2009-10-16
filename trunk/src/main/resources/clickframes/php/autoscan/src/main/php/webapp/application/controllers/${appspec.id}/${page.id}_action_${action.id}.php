<?php

/*
**	ACTION ${action.id}
**	${action.title}
*/

// TODO: Compute the proper outcome
$outcome = '${action.defaultOutcome.id}';

switch ($outcome) {
#foreach ($outcome in $action.outcomes)
	/*
		Outcome: ${outcome.title}
#foreach ($fact in $outcome.facts)
		- ${fact.description}
#end
	*/
	case '${outcome.id}':
#if ($outcome.negative)
		$messageClass = 'failure';
#else
		$messageClass = 'success';
#end
#if ($outcome.message)
		$this->session->set_flashdata('message', array('class' => $messageClass, 'text' => $this->lang->line('${appspec.id}_${page.id}_${action.id}_${outcome.id}_message')));
#end
#foreach ($email in $outcome.emails)
		include("email_${email.id}.php");
#end
#if ($action.defaultOutcome.isInternal())
		redirect('/${appspec.id.toLowerCase()}/${action.defaultOutcome.pageRef}');
#else
		redirect('${action.defaultOutcome.href}');
#end
		break;
#end
	default:
		show_error("Unexpected outcome '$outcome' to action '${action.id}' on page '${page.id}'.");
}

?>