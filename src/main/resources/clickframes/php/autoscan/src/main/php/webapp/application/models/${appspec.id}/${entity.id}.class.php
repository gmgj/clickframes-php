#set($dollarSign="$")
<?php

class ${entity.name} {
    
#foreach ($property in $entity.properties)
    private ${dollarSign}${property.id};
#end
    
    function ${entity.name}(#foreach( $property in $entity.properties )#if ($velocityCount != 1),#end ${dollarSign}${property.id} = null#end ) {
        
#foreach ($property in $entity.properties)
        $this->${property.id} = ${dollarSign}${property.id};
#end

    }
    
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

?>