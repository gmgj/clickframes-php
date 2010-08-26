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
$lang['${appspec.id}_${outcome.key}_message'] = <<<EOD
${outcome.message}
EOD;
#end##if
#end##outcomes
#end##actions
#end##forms

#foreach ($action in $page.actions)
#foreach ($outcome in $action.outcomes)
#if ($outcome.message)
$lang['${appspec.id}_${outcome.key}_message'] = <<<EOD
${outcome.message}
EOD;
#end##if
#end##outcomes
#end##actions

#foreach ($outputList in $page.outputLists)
#foreach ($action in $outputList.actions)
#foreach ($outcome in $action.outcomes)
$lang['${appspec.id}_${outcome.key}_message'] = <<<EOD
${outcome.message}
EOD;
#end##outcomes
#end##actions
#end##outputLists

#foreach ($content in $page.contents)
#if ($content.verbatim)
$lang['${appspec.id}_${page.id}_${content.id}'] = <<<EOD
${content.text}
EOD;
#end
#end

#end##pages

// Email Messages
#foreach ($email in $appspec.emails)
$lang['email_${email.id}_subject'] = <<<EOD
${email.emailSubject}
EOD;
$lang['email_${email.id}_message'] = <<<EOD
${email.emailText}
EOD;
#end

/* clickframes::::clickframes */
?>