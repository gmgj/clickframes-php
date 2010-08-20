#set($dollarSign="$")
<?php

include('${appspec.id}/${appspec.name}Controller.php');

/**
 * This class contains a default implementation of all methods required to
 * retrieve data from the model and process page/form actions. Please avoid
 * reimplementing the methods in this class; override them in the child class.
 * 
 * This page displays the details of an issue.
 *
 * Automatically generated by Clickframes.
 */
class Download extends ${appspec.name}Controller {

	/**
	 *	Constructor
	 */
	function Download() {
		parent::IssuetrackerController();
		$this->load->helper('download');
	}

#foreach ($entity in $appspec.entities)
#foreach ($property in $entity.fileProperties)
	function ${entity.id}${property.name}(${dollarSign}${entity.primaryKey.id}) {
		
		// Check that id was provided
		if (is_null(${dollarSign}${entity.primaryKey.id})) {
			// replace with HTTP error code
			show_error('Required ID not provided.', 400);
		}
		
		// Read entity from database
		${dollarSign}${entity.id} = $this->${entity.name}_model->read${entity.name}(${dollarSign}${entity.primaryKey.id});
		if (is_null(${dollarSign}${entity.id})) {
			show_error('${entity.name} not found for id `'.${dollarSign}${entity.primaryKey.id}.'`.', 404);
		}
		
		// Send file
		$binary = ${dollarSign}${entity.id}->get${property.name}();
		if (is_null($binary)) {
			show_error('BinaryDTO is null in ${entity.name} for id `'.${dollarSign}${entity.primaryKey.id}.'`.', 500);
		}
	
		force_download($binary->getFilename(), file_get_contents($binary->getPath()));
	}
#end##foreach property
#end##foreach entity

}

/* clickframes::::clickframes */
?>