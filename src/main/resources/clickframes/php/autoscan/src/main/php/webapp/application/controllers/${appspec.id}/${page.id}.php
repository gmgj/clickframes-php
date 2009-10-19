<?php

/*
**	${page.title}
**	${page.description}
**
#foreach ($fact in $page.facts)**	- ${fact.description}
#end
*/

#if ($page.loginRequired)
if (!$this->clickframes->sessionAlive()) {
	$this->session->set_flashdata('referer', uri_string());
	redirect('/${appspec.id.toLowerCase()}/${appspec.security.defaultLoginPage.id}');
}
#end

#if ($page.loginPage)
$this->session->keep_flashdata('referer');
#end

$data['applicationTitle'] = $this->lang->line('${appspec.id}_title');;
$data['pageTitle'] = $this->lang->line('${appspec.id}_${page.id}_title');
$data['pageId'] = '${page.id}';

$data['message'] = $this->session->flashdata('message');

## navigation
$data['navigation'] = '';
#foreach ($linkSet in $appspec.globalLinkSets)
$data['navigation'] .= $this->load->view('navigation/${linkSet.id.toLowerCase()}', '', true);
#end
#foreach ($linkSet in $page.linkSets)
$data['navigation'] .= $this->load->view('navigation/${linkSet.id.toLowerCase()}', '', true);
#end

## entity lists
#foreach ($entityList in $page.entityLists)
$data['${entityList.id}'] = $this->${appspec.name}_${entityList.entity.name}_model->list${entityList.entity.name}();
#end

## header view
$this->load->view('header', $data);
## this view
$this->load->view('${page.id.toLowerCase()}', $data);
## footer view
$this->load->view('footer', $data);

?>