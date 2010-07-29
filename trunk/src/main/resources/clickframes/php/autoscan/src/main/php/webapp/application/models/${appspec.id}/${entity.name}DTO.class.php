#set($dollarSign="$")
<?php

include_once('Abstract${entity.name}DTO.class.php');

class ${entity.name}DTO extends Abstract${entity.name}DTO {
    
#foreach ($property in $entity.properties)
    private ${dollarSign}${property.id};
#end
    
#foreach ($property in $entity.properties)
#if ($property.type.toUpperCase() == "BOOLEAN")
    function is${property.name}() {
#else
    function get${property.name}() {
#end
        return $this->${property.id};
    }
    
    function set${property.name}(${dollarSign}${property.id}) {
        $this->${property.id} = ${dollarSign}${property.id};
    }
    
#end
    
}

/* clickframes::::clickframes */
?>