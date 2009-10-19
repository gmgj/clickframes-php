<?php

parent::Controller();
$this->load->helper('form');
$this->load->helper('url');
$this->load->library('session');
$this->load->library('form_validation');
$this->load->library('clickframes');
$this->form_validation->set_error_delimiters('','');
$this->lang->load('${appspec.name.toLowerCase()}', 'english');
#foreach ($entity in $appspec.entities)
$this->load->model('${appspec.name}_${entity.name}_model', '', TRUE);
#end

?>