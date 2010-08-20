<?php

class BinaryDTO {
    
	private $path;
	private $filename;
	private $mimeType;
	private $image = false;
	
	function BinaryDTO() {
		// default constructor
	}
	
	/**
	 *	Constructs a new BinaryDTO object.
	 *	@param array Array of data related to uploaded file. See http://codeigniter.com/user_guide/libraries/file_uploading.html
	 */
	public static function withData($uploadData) {
		$binary = new BinaryDTO();
		$binary->setPath($uploadData['full_path']);
		$binary->setFilename($uploadData['file_name']);
		$binary->setMimeType($uploadData['file_type']);
		$binary->setImage($uploadData['is_image']);
		return $binary;
	}
	
    function getPath() {
        return $this->path;
    }

    function setPath($path) {
        $this->path = $path;
    }

    function getFilename() {
        return $this->filename;
    }

    function setFilename($filename) {
        $this->filename = $filename;
    }

    function getMimeType() {
        return $this->mimeType;
    }

    function setMimeType($mimeType) {
		$this->mimeType = $mimeType;
    }

    function isImage() {
        return $this->image;
    }

    function setImage($image) {
		$this->image = $image;
    }
   
}

/* clickframes::::clickframes */
?>