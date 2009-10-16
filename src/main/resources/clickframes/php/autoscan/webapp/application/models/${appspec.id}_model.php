#set($dollarSign="$")
<?php

#foreach ($entity in $appspec.entities)
include_once('${appspec.id}/${entity.id}.class.php');
#end

class ${appspec.name}_model extends Model{
    
    function ${appspec.name}_model() {
        parent::Model();
    }
    
#foreach ($entity in $appspec.entities)
    /*
    **  ${entity.name}
    */
    function create${entity.name}(${dollarSign}${entity.id}) {
        // TODO: Create ${entity.name} in data source, assigning identifier if applicable
        // TODO: Update identifier field in ${dollarSign}${entity.id} and return, if applicable
        return ${dollarSign}${entity.id};
    }

    function read${entity.name}(${dollarSign}id) {
        ${dollarSign}${entity.id} = new ${entity.name}();

        // TODO: Retrieve ${entity.name} from data source

        // TODO: Populate ${dollarSign}${entity.id} fields
#foreach ($property in $entity.properties)
        ${dollarSign}${entity.id}->set${property.name}('');
#end

        return ${dollarSign}${entity.id};
    }
    
    function list${entity.name}() {
        // TODO: Retrieve all ${entity.name}s from data source
        // TODO: Create and populate ${entity.name} instances
        return array();
    }
    
    function search${entity.name}($searchTerms) {
        // TODO: Retrieve all matching ${entity.name}s from data source
        // TODO: Create and populate ${entity.name} instances
        return array();        
    }
    
    function update${entity.name}(${dollarSign}${entity.id}) {
        // TODO: Update ${entity.name} in data source
        return true;
    }
    
    function delete${entity.name}($id) {
        // TODO: Remove ${entity.name} from data source
        return true;
    }
    
    
#end
    
}

?>