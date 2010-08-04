#set($dollarSign="$")
<?php

abstract class Abstract${entity.name}DTO {

#foreach ($property in $entity.properties)
#if ($property.type.toUpperCase() == "BOOLEAN")
    abstract function is${property.name}();
#else
    abstract function get${property.name}();
#end
#if ($property.persistent)
    abstract function set${property.name}(${dollarSign}${property.id});
#end
    
#end
}

/* clickframes::::clickframes */
?>