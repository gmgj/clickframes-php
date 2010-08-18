// Form: ${form.name}

$(document).ready(function(){

	$("#${page.id}-${form.id}").validate();

#foreach ($input in $form.inputs)
#if ($input.validations.size() > 0)
	// Input: ${input.title}
	var rules = {};
	var messages = {};

#foreach ($validation in $input.validations)
#if ($validation.type.toUpperCase() == "REQUIRED")
	rules['required']        = true;
#if ($validation.description != '')
	messages['required']     = "${validation.description}";
#end
#end
#if ($validation.type.toUppercase() == "EMAIL")
	rules['email']           = true;
#if ($validation.description != '')
	messages['email']        = "${validation.description}";
#end
#end
#if ($validation.type.toUppercase() == "LENGTH" && $validation.hasArg("min"))
	rules['minlength']       = ${validation.argAsString("min")};
#if ($validation.description != '')
	messages['minlength']    = "${validation.description}";
#end
#end
#if ($validation.type.toUppercase() == "LENGTH" && $validation.hasArg("max"))
	rules['maxlength']       = ${validation.argAsString("max")};
#if ($validation.description != '')
	messages['maxlength']    = "${validation.description}";
#end
#end
#end

	rules['messages'] = messages;
	$("#${input.id}").rules("add", rules);


#end
#end

});

/* clickframes::::clickframes */