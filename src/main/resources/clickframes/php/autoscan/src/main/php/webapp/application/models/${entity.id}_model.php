#set($dollarSign="$")
<?php

include_once('Abstract${entity.name}_model.php');
include_once('dto/${entity.name}DTO.class.php');

class ${entity.name}_model extends Abstract${entity.name}_model {
    
    function ${entity.name}_model() {
        parent::Abstract${entity.name}_model();
    }
    
###foreach ($output in $entity.referringOutputsUnique)
##    /**
##     *  ${output.description}
##     *  @return ${entity.name}DTO
##     */
##    function get${output.name}() {
##        // TODO: Implement get${output.name}
##        return null;
##    }
###end

#foreach ($outputList in $entity.referringOutputListsUnique)
    /**
     *  ${outputList.description}
     *  @return array An array of ${entity.name}DTO objects
     */
    function get${outputList.name}($params = array()) {
        // TODO: Implement get${outputList.name}
        return array();
    }
#end
    
    /**
     *  Persist a new ${entity.name} in the database.
     *  @param ${entity.name}DTO ${dollarSign}${entity.id} ${entity.title}
     *  @return ${entity.name}DTO Object updated with database-generated fields, if applicable.
     */
    function create${entity.name}(${dollarSign}${entity.id}) {
        // Create ${entity.name} in data source
        
#foreach ($property in $entity.properties)
#if ($property.persistent && !$property.multiple)
        if (!is_null(${dollarSign}${entity.id}->get${property.name}())) {
            $this->db->set('${property.id}', ${dollarSign}${entity.id}->get${property.name}());
        }
#end
#end
        $this->db->insert('${entity.id}');
        
        // Update identifier field in ${dollarSign}${entity.id}
        ${dollarSign}${entity.id}->set${entity.primaryKey.name}($this->db->insert_id());
        
        return ${dollarSign}${entity.id};
    }

    /**
     *  Retrieve an existing ${entity.name} from the database using its identifier.
     *  @param mixed ${dollarSign}id ${entity.name}'s unique identifier
     *  @return ${entity.name}DTO
     */
    function read${entity.name}(${dollarSign}${entity.primaryKey.id}) {

        // Retrieve ${entity.name} from data source
        $query = $this->db->get_where('${entity.id}', array('${entity.primaryKey.id}' => ${dollarSign}${entity.primaryKey.id}));

        if ($query->num_rows() > 0) {
            $row = $query->row();
            return $this->mapRowTo${entity.name}($row);
        }
        
        // Item not found
        return null;
    }
    
    /**
     *  Retrieve an array of ${entity.name} objects.
     *  @param integer $page Which batch of results to display
     *  @param integer $perPage Number of ${entity.name}DTO objects to return in batch, 0 for all
     *  @return array An array of ${entity.name}DTO objects
     */
    function list${entity.name}($page = 1, $perPage = 0) {
        
        if ($perPage != 0) {
            $this->db->limit($perPage, ($page - 1) * $perPage);
        }
        
        // Retrieve ${entity.name}s from data source
        $this->db->get('${entity.id}');
        
        // Create and populate ${entity.name} instances
        if ($query->num_rows() > 0) {
            $list = array();
            foreach ($query->result() as $row) {
                $list[] = $this->mapRowTo${entity.name}($row);
            }
            return $list;
        }
        
        // No items found
        return array();
    }
    
    /**
     *  Update an existing ${entity.name} in the database. 
     *  @param ${entity.name}DTO ${dollarSign}${entity.id} The updated object.
     *  @return boolean True if update successful.
     */
    function update${entity.name}(${dollarSign}${entity.id}) {
        // TODO: Update ${entity.name} in data source
        return true;
    }
    
    /**
     *  Delete an existing ${entity.name} from the database.
     *  @param mixed ${dollarSign}id Unique identifier of ${entity.name} to delete
     *  @return boolean True if delete successful.
     */
    function delete${entity.name}(${dollarSign}${entity.primaryKey.id}) {
        // Remove ${entity.name} from data source
        $this->db->delete('${entity.id}', array('${entity.primaryKey.id}' => ${dollarSign}${entity.primaryKey.id}));
        return true;
    }
    
#foreach ($property in $entity.properties)
#if ($property.multiple)
#if ($property.foreignEntityId != '')
    /**
     *  Map a ${property.foreignEntity.name} to a ${entity.name}
     *  @param ${entity.primaryKey.type} ${dollarSign}${entity.id}${entity.primaryKey.name} Unique identifier of ${entity.name} object
     *  @param ${property.foreignEntity.primaryKey.type} ${dollarSign}${property.foreignEntity.id}${property.foreignEntity.primaryKey.name} Unique identifier of ${property.foreignEntity.name} object to map
     *  @return boolean True if add successful.
     */
    function addTo${property.name}(${dollarSign}${entity.id}${entity.primaryKey.name}, ${dollarSign}${property.foreignEntity.id}${property.foreignEntity.primaryKey.name}) {
        $this->db->set('${entity.id}_${entity.primaryKey.id}', ${dollarSign}${entity.id}${entity.primaryKey.name});
        $this->db->set('${property.foreignEntity.id}_${property.foreignEntity.primaryKey.id}', ${dollarSign}${property.foreignEntity.id}${property.foreignEntity.primaryKey.name});
        $this->db->insert('${entity.id}_${property.id}');
        return true;
    }
    
    /**
     *  Unmaps a ${property.foreignEntity.name} from a ${entity.name}.
     *  Note that you may wish to delete the ${property.foreignEntity.name} afterwards.
     *  @param ${entity.primaryKey.type} ${dollarSign}${entity.id}${entity.primaryKey.name} Unique identifier of ${entity.name} object
     *  @param ${property.foreignEntity.primaryKey.type} ${dollarSign}${property.foreignEntity.id} Unique identifier of ${property.foreignEntity.name} object to map
     *  @return boolean True if remove successful.
     */
    function removeFrom${property.name}(${dollarSign}${entity.id}${entity.primaryKey.name}, ${dollarSign}${property.foreignEntity.id}${property.foreignEntity.primaryKey.name}) {
        $this->db->delete('${entity.id}_${property.id}',
            array('${entity.id}_${entity.primaryKey.id}' => ${dollarSign}${entity.id}${entity.primaryKey.name}, '${property.foreignEntity.id}_${property.foreignEntity.primaryKey.id}' => ${dollarSign}${property.foreignEntity.id}${property.foreignEntity.primaryKey.name}));
        return true;
    }
    
    function get${entity.name}${property.name}(${dollarSign}${entity.primaryKey.id}) {
        $this->db->select('${property.foreignEntity.id}.*');
        $this->db->from('${entity.id}_${property.id}');
        $this->db->join('${property.foreignEntity.id}', '${entity.id}_${property.id}.${property.foreignEntity.id}_${property.foreignEntity.primaryKey.id} = ${property.foreignEntity.id}.${property.foreignEntity.primaryKey.id}');
        
        $query = $this->db->get();
        
        if ($query->num_rows() > 0) {
            
            // Load model of foreign entity
            $CI =& get_instance();
            $CI->load->model('${property.foreignEntity.id}_model', '${property.foreignEntity.id}Model', true);
            
            // Map each row
            $list = array();
            foreach ($query->result() as $row) {
                $list[] = $CI->${property.foreignEntity.id}Model->mapRowTo${property.foreignEntity.name}($row);
            }
            return $list;
        }
        
        // No items found
        return null;
    }
    
#else
    /**
     *  Add new ${property} value to ${entity.name}
     *  @param ${entity.primaryKey.type} ${dollarSign}${entity.id}${entity.primaryKey.name} Unique identifier of ${entity.name} object
     *  @param ${property.type} ${dollarSign}${property.id} Value to add.
     *  @return int New row ID if insert is successful.
     */
    function addTo${property.name}(${dollarSign}${entity.id}${entity.primaryKey.name}, ${dollarSign}${property.id}) {
        $this->db->set('${entity.id}_${entity.primaryKey.id}', ${dollarSign}${entity.id}${entity.primaryKey.name});
        $this->db->set('${property.id}', ${dollarSign}${property.id});
        $this->db->insert('${entity.id}_${property.id}');
        return $this->db->insert_id();
    }
    
    /**
     *  Unmaps a ${property.foreignEntity.name} from a ${entity.name}.
     *  Note that you may wish to delete the ${property.foreignEntity.name} afterwards.
     *  @param ${entity.primaryKey.type} ${dollarSign}${entity.id}${entity.primaryKey.name} Unique identifier of ${entity.name} object
     *  @param ${property.foreignEntity.primaryKey.type} ${dollarSign}${property.foreignEntity.id} Unique identifier of ${property.foreignEntity.name} object to map
     *  @return boolean True if remove successful.
     */
    function removeFrom${property.name}ById($id) {
        $this->db->delete('${entity.id}_${property.id}', array('id' => $id));
        return true;
    }
    
    function get${entity.name}${property.name}(${dollarSign}${entity.primaryKey.id}) {
        $query = $this->db->get_where('${entity.id}_${property.id}', array('${entity.id}_${entity.primaryKey.id}' => ${dollarSign}${entity.primaryKey.id}));
        
        if ($query->num_rows() > 0) {
            $list = array();
            foreach ($query->result() as $row) {
                $list[] = array('id' => $row->id, 'value' => $row->${property.id});
            }
            return $list;
        }
        
        // No items found
        return null;
    }
#end
#end
#end
    
    function mapRowTo${entity.name}($row) {
        ${dollarSign}${entity.id} = new ${entity.name}DTO();
        // Populate ${dollarSign}${entity.id} fields
#foreach ($property in $entity.properties)
#if ($property.persistent && !$property.multiple)
        ${dollarSign}${entity.id}->set${property.name}($row->${property.id});
#end
#end
        return ${dollarSign}${entity.id};
    }
    
}


/* clickframes::::clickframes */
?>