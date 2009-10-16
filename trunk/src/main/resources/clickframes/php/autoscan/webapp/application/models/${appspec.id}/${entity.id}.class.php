#set($dollarSign="$")
<?php

class ${entity.name} {
    
#foreach ($property in $entity.properties)
    private ${dollarSign}${property.id};
#end
    
    function ${entity.name}(
#foreach ($property in $entity.properties)
            ${dollarSign}${property.id},
#end
            $clickframes
        ) {
        
#foreach ($property in $entity.properties)
        $this->${property.id} = ${dollarSign}${property.id};
#end

    }
    
#foreach ($property in $entity.properties)
    function get${property.name}() {
        return $this->${property.id};
    }
    
    function set${property.name}(${dollarSign}${property.id}) {
        $this->${property.id} = ${dollarSign}${property.id};
    }
    
#end
    
}

?>