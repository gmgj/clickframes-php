#foreach ($entity in $appspec.entities)
DROP TABLE IF EXISTS `${entity.id}`;
#foreach ($property in $entity.properties)
#if ($property.multiple)
DROP TABLE IF EXISTS `${entity.id}_${property.id}`;
#end
#end
#end

-- clickframes::::clickframes