/*! SET FOREIGN_KEY_CHECKS = 0; */
#foreach ($entity in $appspec.entities)
DROP TABLE IF EXISTS `${entity.id}`;
#foreach ($property in $entity.properties)
#if ($property.multiple)
DROP TABLE IF EXISTS `${entity.id}_${property.id}`;
#end
#end
#end
/*! SET FOREIGN_KEY_CHECKS = 1; */

-- clickframes::::clickframes