<?php

// Application-level messages
$lang['${appspec.id}_title'] = <<<EOD
${appspec.title}
EOD;

// Page-level messages
#foreach ($page in $appspec.pages)
$lang['${appspec.id}_${page.id}_title'] = <<<EOD
${page.title}
EOD;

#foreach ($form in $page.forms)
#foreach ($action in $form.actions)
#foreach ($outcome in $action.outcomes)
#if ($outcome.message)
$lang['${appspec.id}_${page.id}_${form.id}_${action.id}_${outcome.id}_message'] = <<<EOD
${outcome.message}
EOD;
#end
#end
#end
#end

#foreach ($action in $page.actions)
#foreach ($outcome in $action.outcomes)
#if ($outcome.message)
$lang['${appspec.id}_${page.id}_${action.id}_${outcome.id}_message'] = <<<EOD
${outcome.message}
EOD;
#end
#end
#end

#end

// Email Messages
#foreach ($email in $appspec.emails)
$lang['email_${email.id}_subject'] = <<<EOD
${email.emailSubject}
EOD;
$lang['email_${email.id}_message'] = <<<EOD
${email.emailText}
EOD;
#end

?>