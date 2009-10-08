<?php

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

	## header view
	$this->load->view('header', $data);
	## this view
	$this->load->view('${page.id.toLowerCase()}', $data);
	## footer view
	$this->load->view('footer', $data);

?>