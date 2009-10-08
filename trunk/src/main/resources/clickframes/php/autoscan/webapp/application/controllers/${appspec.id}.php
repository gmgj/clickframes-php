<?php

class ${appspec.name} extends Controller {

    function ${appspec.name}() {
    	include("${appspec.id}/${appspec.id}_constructor.php");
    }

    function index() {
        $this->${appspec.defaultPage.id}();
    }

    #foreach ($page in $appspec.pages)
    function ${page.id}() {
    	include("${appspec.id}/${page.id}.php");
    }

    #foreach ($form in $page.forms)
    function ${page.id}_${form.id}() {
    	include("${appspec.id}/${page.id}_${form.id}.php");
    }
    #end

    #end
}

?>