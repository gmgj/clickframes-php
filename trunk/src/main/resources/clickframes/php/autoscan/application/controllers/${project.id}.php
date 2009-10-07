<?php

class ${appspec.name} extends Controller {

    function ${appspec.name}() {
        parent::Controller();
        $this->load->helper('form');
        $this->load->helper('url');
        $this->load->library('session');
        $this->load->library('form_validation');
        $this->form_validation->set_error_delimiters('','');
        $this->lang->load('${appspec.name.toLowerCase()}', 'english');
    }

    function index() {
        $this->${appspec.defaultPage.id}();
    }

    #foreach ($page in $appspec.pages)
    function ${page.id}() {
        $data['applicationTitle'] = $this->lang->line('${appspec.id}_title');;
        $data['pageTitle'] = $this->lang->line('${appspec.id}_${page.id}_title');
        $data['pageId'] = '${page.id}';

        ## if form
        #if ($page.forms.size() > 0)
            #set( $form = $page.forms.get(0) )
            ## form - define validations
            #foreach($input in $form.inputs)
            $this->form_validation->set_rules('${input.id}', '${input.title}', 'trim#parse("clickframes/php/validations.vm")');
            #end

            ## form - execute validation
            #if ($form.inputs.size() > 0)
            if ($this->form_validation->run() == TRUE) {
            #end
            #foreach ($action in $form.actions)
                if ($this->input->post('action:${action.id}')) {
                    ## form - redirect to first successful outcome
                    #if ($action.defaultOutcome.isInternal())
                        #if ($action.defaultOutcome.message)
                        $this->session->set_flashdata('message', array('class' => 'success', 'text' => $this->lang->line('${appspec.id}_${page.id}_${form.id}_${action.id}_${action.defaultOutcome.id}_message')));
                        #end
                        redirect('/${appspec.id.toLowerCase()}/${action.defaultOutcome.pageRef}');
                    #else
                        redirect('${action.defaultOutcome.href}');
                    #end

                    ## generate additional outcomes
                }
            #end
            #if ($form.inputs.size() > 0)
            }
            #end
        #end

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
    }
    #end
}
?>