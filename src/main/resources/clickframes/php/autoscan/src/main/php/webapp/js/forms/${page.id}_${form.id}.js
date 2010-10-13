// Form: ${form.name}

$(document).ready(function(){

	$("#${page.id}-${form.id}").validate();

#foreach ($input in $form.inputs)
#if ($input.validations.size() > 0)
	// Input: ${input.title}
	var rules = {};
	var messages = {};

#foreach ($validation in $input.validations)
#if ($validation.type == "required")
	rules['required']        = true;
#if ($validation.description != '')
	messages['required']     = "${validation.description}";
#end
#end
#if ($validation.type == "email")
	rules['email']           = true;
#if ($validation.description != '')
	messages['email']        = "${validation.description}";
#end
#end
#if ($validation.type == "length" && $validation.hasArg("min"))
	rules['minlength']       = ${validation.getArgAsString("min")};
#if ($validation.description != '')
	messages['minlength']    = "${validation.description}";
#end
#end
#if ($validation.type == "length" && $validation.hasArg("max"))
	rules['maxlength']       = ${validation.getArgAsString("max")};
#if ($validation.description != '')
	messages['maxlength']    = "${validation.description}";
#end
#end
#if ($validation.type == "matchesInput")
	rules['equalTo']       = '#${validation.otherInputId}';
#if ($validation.description != '')
	messages['equalTo']    = "${validation.description}";
#end
#end
#end

	rules['messages'] = messages;
	$("#${input.id}").rules("add", rules);


#end
#end

});

/* clickframes::::clickframes */