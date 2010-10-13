#set($dollarSign="$")
<?php

include_once('Abstract${entity.name}_model.php');
include_once('dto/${entity.name}DTO.class.php');
#if ($entity.fileProperties.size() > 0)
include_once('dto/BinaryDTO.class.php');
#end

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

#if ($entity.loginEntity)
	/**
	 *	Checks user's credentials against database. If valid, creates
	 *	an authenticated session.
	 *	@param ${entity.name}DTO User to authenticate.
	 *	@return boolean True if login successful.
	 */
	function login(${dollarSign}${entity.id}) {
		
		log_message('info', 'Attempting to log in user `' . ${dollarSign}${entity.id}->getLoginUsername() . '`...');
		
        // Retrieve ${entity.name} from data source
        $query = $this->db->get_where('${entity.id}',
			array(
				'${appspec.loginUsernameEntityProperty.id}' => ${dollarSign}${entity.id}->getLoginUsername(),
				'${appspec.loginPasswordEntityProperty.id}' => ${dollarSign}${entity.id}->getLoginPassword()
			));

        if ($query->num_rows() > 0) {
			
			log_message('info', 'Successfully authenticated user `' . ${dollarSign}${entity.id}->getLoginUsername() . '`...');
			
			$row = $query->row();
            $loggedIn = $this->mapRowTo${entity.name}($row);
			
            // create session
			$this->session->set_userdata('userid', $loggedIn->get${entity.primaryKey.name}());
			$this->session->set_userdata('username', $loggedIn->getLoginUsername());
			$this->session->set_userdata('last_activity', time());
			// load additional data into session here if desired
			return true;
        }
		
		log_message('info', 'Authentication failed for user `' . ${dollarSign}${entity.id}->getLoginUsername() . '`...');
        
        // Item not found
        return false;
	}
	
	/**
	 *	Destroys user's session.
	 *	@return void
	 */
	function logout() {
		log_message('info', 'Logging out, destroying session...');
		$this->session->sess_destroy();
	}
#end

#foreach ($outputList in $entity.referringOutputListsUnique)
    /**
     *  ${outputList.description}
     *  @return array An array of ${entity.name}DTO objects
     */
    function get${outputList.name}($params = array()) {
        // TODO: Implement get${outputList.name}
		return $this->list${outputList.entity.name}();
    }
#end
    
    /**
     *  Persist a new ${entity.name} in the database.
     *  @param ${entity.name}DTO ${dollarSign}${entity.id} ${entity.title}
     *  @return ${entity.primaryKey.type} Created object's new unique identifier
     */
    function create${entity.name}(${dollarSign}${entity.id}) {
        // Create ${entity.name} in data source
        
#foreach ($property in $entity.properties)
#if ($property.persistent && !$property.multiple)
        if (!is_null(${dollarSign}${entity.id}->get${property.name}())) {
#if ($property.type == 'FILE')
			$binary = ${dollarSign}${entity.id}->get${property.name}();
			$this->db->set('${property.id}_path', $binary->getPath());
			$this->db->set('${property.id}_filename', $binary->getFilename());
			$this->db->set('${property.id}_mimetype', $binary->getMimeType());
			$this->db->set('${property.id}_is_image', $binary->isImage());
#else
            $this->db->set('${property.id}', ${dollarSign}${entity.id}->get${property.name}());
#end
        }
#end
#end
        $this->db->insert('${entity.id}');
        
        return $this->db->insert_id();
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
        $query = $this->db->get('${entity.id}');
        
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
		$this->db->where('${entity.primaryKey.id}', ${dollarSign}${entity.id}->getPrimaryKey());
		$this->db->update('${entity.id}', ${dollarSign}${entity.id}->toArray());
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
#if ($property.foreignEntity)
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
    
	/**
	 *	Returns all ${property.name} values for the specified ${entity.name} object.
	 *	@param ${entity.primaryKey.type} ${dollarSign}${entity.primaryKey.id} Unique identifier of ${entity.name} object
	 *	@return array An array of ${property.foreignEntity.name}DTO objects, or null if none
	 */
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
     *  Add new ${property.name} value to ${entity.name}
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
     *  Removes ${property.name} value from ${entity.name} object.
     *  @param ${entity.primaryKey.type} ${dollarSign}${entity.id}${entity.primaryKey.name} Unique identifier of ${entity.name} object
     *  @param ${property.foreignEntity.primaryKey.type} ${dollarSign}${property.foreignEntity.id} Unique identifier of ${property.foreignEntity.name} object to map
     *  @return boolean True if remove successful.
     */
    function removeFrom${property.name}($id) {
        $this->db->delete('${entity.id}_${property.id}', array('id' => $id));
        return true;
    }
    
	/**
	 *	Returns all ${property.name} values for the specified ${entity.name} object.
	 *	@param ${entity.primaryKey.type} ${dollarSign}${entity.primaryKey.id} Unique identifier of ${entity.name} object
	 *	@return array An array of ${property.type}, or null if none
	 */
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
#if ($property.type == 'FILE')
		if (!is_null($row->${property.id}_path)) {
			$binary = new BinaryDTO();
			$binary->setPath($row->${property.id}_path);
			$binary->setFilename($row->${property.id}_filename);
			$binary->setMimeType($row->${property.id}_mimetype);
			$binary->setImage($row->${property.id}_is_image);
			${dollarSign}${entity.id}->set${property.name}($binary);
		}
#else
#if ($property.loginPassword)
		${dollarSign}${entity.id}->set${property.name}Direct($row->${property.id});
#else
        ${dollarSign}${entity.id}->set${property.name}($row->${property.id});
#end
		
#end
#end
#end
        return ${dollarSign}${entity.id};
    }
    
}


/* clickframes::::clickframes */
?>