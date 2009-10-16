#set($dollarSign="$")
<?php

class ${appspec.name} extends Controller {

    function ${appspec.name}() {
    	include("${appspec.id}/${appspec.id}_constructor.php");
    }

    function index() {
        $this->${appspec.defaultPage.id}();
    }

#foreach ($page in $appspec.pages)
    /**
     *	Displays the '${page.title}' page and processes its input.
#foreach( $param in $page.parameters )
     *	@param string ${dollarSign}${param.id} $!{param.description}
#end
     */
#if ($page.parameters.size() > 0)
    function ${page.id}(#foreach( $param in $page.parameters )#if ($velocityCount != 1),#end ${dollarSign}${param.id} = null#end ) {
#else
    function ${page.id}() {
#end
#if ($page.actions.size() > 0 || $page.forms.size() > 0)
    	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
#foreach ($form in $page.forms)
    		// Form '${form.id}'
    		if ($this->input->post('clickframesFormId') == '${page.id}-${form.id}') {
       			include("${appspec.id}/${page.id}_form_${form.id}.php");
       		}

#end

#foreach ($action in $page.actions)
			// Action '${action.title}'
			if ($this->input->post('clickframesFormId') == '${page.id}-action-${action.id}') {
				include("${appspec.id}/${page.id}_action_${action.id}.php");
			}
#end
    	}
#end

    	include("${appspec.id}/${page.id}.php");
    }

#end
}

?>