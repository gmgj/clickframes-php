<?php

// Application-level messages
$lang['${appspec.id}_title'] = "${appspec.title}";

// Page-level messages
#foreach ($page in $appspec.pages) 
$lang['${appspec.id}_${page.id}_title'] = "${page.title}";

#foreach ($form in $page.forms)
#foreach ($action in $form.actions)
#foreach ($outcome in $action.outcomes)
#if ($outcome.message)
$lang['${appspec.id}_${page.id}_${form.id}_${action.id}_${outcome.id}_message'] = "${outcome.message}";
#end
#end
#end
#end

#foreach ($action in $page.actions)
#foreach ($outcome in $action.outcomes)
#if ($outcome.message)
$lang['${appspec.id}_${page.id}_${action.id}_${outcome.id}_message'] = "${outcome.message}";
#end
#end
#end

#end

?>