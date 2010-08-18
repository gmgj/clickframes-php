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
#if ($property.multiple)
    abstract function add${property.name}(${dollarSign}${property.id});
#end
    
#end

	function toArray() {
		$data = array();
#foreach ($property in $entity.properties)
#if ($property.persistent && !$property.multiple)
#if ($property.type.toUpperCase() == "BOOLEAN")
		$data['${property.id}'] = $this->is${property.name}();
#else
		$data['${property.id}'] = $this->get${property.name}();
#end
#end
#end
		return $data;
	}
	
#if ($entity.primaryKey)
	function getPrimaryKey() {
		return $this->get${entity.primaryKey.name}();
	}
#end
}

/* clickframes::::clickframes */
?>