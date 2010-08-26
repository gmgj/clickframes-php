#foreach ($entity in $appspec.entities)
#foreach ($property in $entity.properties)
#if (!$property.multiple and $property.foreignEntityId != '')
ALTER TABLE `${entity.id}` ADD FOREIGN KEY (`${property.id}`) REFERENCES `${property.foreignEntity.id}` (`${property.foreignEntity.primaryKey.id}`);
#elseif ($property.multiple)
ALTER TABLE `${entity.id}_${property.id}` ADD FOREIGN KEY (`${entity.id}_${entity.primaryKey.id}`) REFERENCES `${entity.id}` (`${entity.primaryKey.id}`);
#if ($property.foreignEntityId != '')
ALTER TABLE `${entity.id}_${property.id}` ADD FOREIGN KEY (`${property.foreignEntity.id}_${property.foreignEntity.primaryKey.id}`) REFERENCES `${property.foreignEntity.id}` (`${property.foreignEntity.primaryKey.id}`);
#end
#end
#end
#end

-- clickframes::::clickframes