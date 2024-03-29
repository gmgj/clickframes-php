#set($dollarSign="$")
<?php

include_once('Abstract${entity.name}DTO.class.php');

class ${entity.name}DTO extends Abstract${entity.name}DTO {
    
#foreach ($property in $entity.properties)
#if ($property.multiple)
    private ${dollarSign}${property.id} = array();
#else
    private ${dollarSign}${property.id};
#end
#end
    
#foreach ($property in $entity.properties)
#if ($property.type.toUpperCase() == "BOOLEAN")
    function is${property.name}() {
#else
    function get${property.name}() {
#end
        return $this->${property.id};
    }

#if ($property.persistent)
    function set${property.name}(${dollarSign}${property.id}) {
#if ($property.loginPassword)
        // Hash the password, don't store it in plaintext
		$this->${property.id} = sha1(${dollarSign}${property.id});
#else
        $this->${property.id} = ${dollarSign}${property.id};
#end
    }

#if ($property.loginPassword)
	// Sets the password field without the hash
	function set${property.name}Direct(${dollarSign}${property.id}) {
		$this->${property.id} = ${dollarSign}${property.id};
	}
#end

#end
#if ($property.multiple)
    function add${property.name}(${dollarSign}${property.id}) {
        $this->${property.id}[] = ${dollarSign}${property.id};
    }

#end

#end
    
}

/* clickframes::::clickframes */
?>