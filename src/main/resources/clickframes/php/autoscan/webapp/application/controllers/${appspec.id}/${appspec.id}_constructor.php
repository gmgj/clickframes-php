<?php

parent::Controller();
$this->load->helper('form');
$this->load->helper('url');
$this->load->library('session');
$this->load->library('form_validation');
$this->load->library('clickframes');
$this->form_validation->set_error_delimiters('','');
$this->lang->load('${appspec.name.toLowerCase()}', 'english');

?>