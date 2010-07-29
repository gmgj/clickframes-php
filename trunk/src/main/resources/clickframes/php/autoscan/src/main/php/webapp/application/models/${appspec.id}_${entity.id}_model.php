#set($dollarSign="$")
<?php

include_once('${appspec.id}/${entity.name}DTO.class.php');

class ${appspec.name}_${entity.name}_model extends Model {
    
    function ${appspec.name}_${entity.name}_model() {
        parent::Model();
    }
    
    /**
     *  Persist a new ${entity.name} in the database.
     *  @param ${entity.name}DTO ${dollarSign}${entity.id} ${entity.title}
     *  @return ${entity.name}DTO Object updated with database-generated fields, if applicable.
     */
    function create${entity.name}(${dollarSign}${entity.id}) {
        // TODO: Create ${entity.name} in data source, assigning identifier if applicable
        // TODO: Update identifier field in ${dollarSign}${entity.id} and return, if applicable
        return ${dollarSign}${entity.id};
    }

    /**
     *  Retrieve an ${entity.name} from the database using its identifier.
     *  @param mixed ${dollarSign}id ${entity.name}'s unique identifier
     *  @return ${entity.name}DTO
     */
    function read${entity.name}(${dollarSign}id) {
        ${dollarSign}${entity.id} = new ${entity.name}DTO();

        // TODO: Retrieve ${entity.name} from data source

        // TODO: Populate ${dollarSign}${entity.id} fields
#foreach ($property in $entity.properties)
        ${dollarSign}${entity.id}->set${property.name}('');
#end

        return ${dollarSign}${entity.id};
    }
    
    /**
     *  Retrieve an array of ${entity.name} objects.
     *  @param integer $page Which batch of results to display
     *  @param integer $perPage Number of ${entity.name}DTO objects to return in batch, 0 for all
     *  @return array
     */
    function list${entity.name}($page = 1, $perPage = 0) {
        // TODO: Retrieve all ${entity.name}s from data source
        // TODO: Create and populate ${entity.name} instances
        return array();
    }
    
    /**
     *  Retrieve an array of ${entity.name}DTO objects which match the provided
     *  search terms.
     *  @param string $searchTerms Search terms to match
     *  @return array
     */
    function search${entity.name}($searchTerms) {
        // TODO: Retrieve all matching ${entity.name}s from data source
        // TODO: Create and populate ${entity.name} instances
        return array();        
    }
    
    /**
     *  Update an existing ${entity.name} in the database. 
     *  @param ${entity.name}DTO ${dollarSign}${entity.id} The updated object.
     *  @return boolean Returns true if update successful.
     */
    function update${entity.name}(${dollarSign}${entity.id}) {
        // TODO: Update ${entity.name} in data source
        return true;
    }
    
    /**
     *  Delete an ${entity.name} from the database.
     *  @param mixed ${dollarSign}id Unique identifier of ${entity.name} to delete
     *  @return boolean Returns true if delete successful.
     */
    function delete${entity.name}($id) {
        // TODO: Remove ${entity.name} from data source
        return true;
    }
    
}


/* clickframes::::clickframes */
?>