#macro (mysqlType $type)#if ($type == 'TEXT')VARCHAR(255)#elseif($type == 'FILE')BLOB#else${type}#end#end

#foreach ($entity in $appspec.entities)
CREATE TABLE IF NOT EXISTS `$entity.id` (
#foreach ($property in $entity.simpleProperties)
#if ($property.foreignEntityId != '')
	`${property.id}` #mysqlType($property.foreignEntity.primaryKey.type),
#elseif ($property.persistent)	
	`${property.id}` #mysqlType($property.type)#if ($property.primaryKey and $property.type == 'INT') AUTO_INCREMENT#end,
#end
#end
	PRIMARY KEY (`${entity.primaryKey.id}`) 
);

#foreach ($property in $entity.properties)
#if ($property.multiple)
CREATE TABLE IF NOT EXISTS `${entity.id}_${property.id}` (
#if ($property.foreignEntityId == '')
    `id` INT PRIMARY KEY AUTO_INCREMENT,
#end
	`${entity.id}_${entity.primaryKey.id}` #mysqlType($entity.primaryKey.type),
#if ($property.foreignEntityId != '')
	`${property.foreignEntity.id}_${property.foreignEntity.primaryKey.id}` #mysqlType($property.foreignEntity.primaryKey.type)
#else
	`${property.id}` #mysqlType($property.type)
#end

);

#end
#end

#end

-- clickframes::::clickframes