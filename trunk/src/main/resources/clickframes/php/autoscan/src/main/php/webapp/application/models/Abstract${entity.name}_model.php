#set($dollarSign="$")
<?php

abstract class Abstract${entity.name}_model extends Model {
    
    function Abstract${entity.name}_model() {
        parent::Model();
    }
    
    abstract function create${entity.name}(${dollarSign}${entity.id});
    abstract function read${entity.name}(${dollarSign}${entity.primaryKey.id});
    abstract function list${entity.name}($page = 1, $perPage = 0);
    abstract function update${entity.name}(${dollarSign}${entity.id});
    abstract function delete${entity.name}($id);
    
#foreach ($property in $entity.properties)
#if ($property.multiple)
#if ($property.foreignEntityId != '')
    abstract function addTo${property.name}(${dollarSign}${entity.id}${entity.primaryKey.name}, ${dollarSign}${property.foreignEntity.id}${property.foreignEntity.primaryKey.name});
    abstract function removeFrom${property.name}(${dollarSign}${entity.id}${entity.primaryKey.name}, ${dollarSign}${property.foreignEntity.id}${property.foreignEntity.primaryKey.name});
#else
    abstract function addTo${property.name}(${dollarSign}${entity.id}${entity.primaryKey.name}, ${dollarSign}${property.id});
    abstract function removeFrom${property.name}($id);
#end
    abstract function get${entity.name}${property.name}(${dollarSign}${entity.primaryKey.id});
#end
#end
    
##foreach ($output in $entity.referringOutputsUnique)
##    abstract function get${output.name}();
##end

#foreach ($outputList in $entity.referringOutputListsUnique)
    abstract function get${outputList.name}();
#end
    
}


/* clickframes::::clickframes */
?>