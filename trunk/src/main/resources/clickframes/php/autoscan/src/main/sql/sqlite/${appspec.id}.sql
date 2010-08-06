#macro (sqliteType $type)#if ($type == 'TEXT')VARCHAR(255)#elseif($type == 'INT')INTEGER#elseif($type == 'FILE')BLOB#else${type}#end#end

#foreach ($entity in $appspec.entities)
CREATE TABLE IF NOT EXISTS `$entity.id` (
#foreach ($property in $entity.simpleProperties)
#if ($property.foreignEntityId != '')
	`${property.id}` #sqliteType($property.foreignEntity.primaryKey.type)
#elseif ($property.persistent)	
	`${property.id}` #sqliteType($property.type)#if($property.primaryKey) PRIMARY KEY#end#if($property.primaryKey and $property.type == 'INT') AUTOINCREMENT#end
#end
#if ($velocityCount < $entity.simpleProperties.size()),
#end
#end

);

#foreach ($property in $entity.properties)
#if ($property.multiple)
CREATE TABLE IF NOT EXISTS `${entity.id}_${property.id}` (
#if ($property.foreignEntityId == '')
    `id` INTEGER PRIMARY KEY AUTOINCREMENT,
#end
	`${entity.id}_${entity.primaryKey.id}` #sqliteType($entity.primaryKey.type),
#if ($property.foreignEntityId != '')
	`${property.foreignEntity.id}_${property.foreignEntity.primaryKey.id}` #sqliteType($property.foreignEntity.primaryKey.type)
#else
	`${property.id}` #sqliteType($property.type)
#end

);

#end
#end

#end

/* clickframes::::clickframes */