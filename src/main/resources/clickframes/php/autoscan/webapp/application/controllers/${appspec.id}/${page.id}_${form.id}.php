<?php

/*
**	${form.id}
*/

## form - define validations
#foreach($input in $form.inputs)
	$this->form_validation->set_rules('${input.id}', '${input.title}', 'trim#parse("clickframes/php/validations.vm")');
#end

## form - execute validation
#if ($form.inputs.size() > 0)
	if ($this->form_validation->run() == FALSE) {
		// Reload the page
		$this->${page.id}();
	} else {
#end

#foreach ($action in $form.actions)
		// Action: ${action.title}
		if ($this->input->post('action:${action.id}')) {

			// FIXME: Compute the proper outcome
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
#if ($outcome.message)
					$this->session->set_flashdata('message', array('class' => 'success', 'text' => $this->lang->line('${appspec.id}_${page.id}_${form.id}_${action.id}_${outcome.id}_message')));
#end
#foreach ($email in $outcome.emails)
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
#if ($action.defaultOutcome.isInternal())
					redirect('/${appspec.id.toLowerCase()}/${action.defaultOutcome.pageRef}');
#else
					redirect('${action.defaultOutcome.href}');
#end
					break;
#end
				default:
					show_error("Unexpected outcome '$outcome' to action '${action.id}' in form '${form.id}' on page '${page.id}'.");
			}

		}
#end
		else {
			show_error("Unexpected action '${action.id}' in form '${form.id}' on page '${page.id}'.");
		}

#if ($form.inputs.size() > 0)
	}
#end

?>