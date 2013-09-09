<?php
App::uses('AppModel', 'Model');
/**
 * Graphic Model
 *
 * @property Release $Release
 */
class Graphic extends AppModel {
	public $actsAs = array(
		'Upload.Upload' => array(
			'image' => array(
				'path' => '{ROOT}webroot{DS}img{DS}releases{DS}',
				
				// These must also be included in checkExtensions() in /js/admin.js
				'extensions' => array('jpg', 'jpeg', 'gif', 'png'),
				'fields' => array('dir' => 'dir'),
				'thumbnails' => true,
				'thumbnailQuality' => 90,
				'thumbnailSizes' => array('thumb' => '195l'), // longest side will be 195px
				'thumbnailMethod' => 'php',
				'thumbnailName' => '{filename}.{size}' // e.g. origfilename.thumb.extension
			)
		)
	);
	public $belongsTo = array(
		'Release' => array(
			'className' => 'Release',
			'foreignKey' => 'release_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);
	public $validate = array(
		'release_id' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => true,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			)
		),
		'url' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				'message' => 'A URL for this linked graphic is required',
				//'allowEmpty' => false,
				'required' => true,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			)
		),
		'title' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				'message' => 'Required',
				//'allowEmpty' => false,
				'required' => true,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			)
		),
		/*
		'filename' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				'message' => 'Image required',
				//'allowEmpty' => false,
				'required' => true,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			)
		),
		*/
		'image' => array(
			'notempty' => array(
				'rule' => array('uploadedImage'),
				'message' => 'Image required',
				//'allowEmpty' => false,
				'required' => true,
				//'last' => false, // Stop validation after this rule
				'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
			'valid_extension' => array(
				'rule' => array('isValidExtension', array('jpg', 'jpeg', 'gif', 'png')),
        		'message' => 'File has an invalid extension'
			)
		)
	);
	
	private $__folderToDelete = null;
	
	public function uploadedImage($check) {
		return ! empty($check['image']['name']);
	}
	
	public function beforeDelete() {
		// Imports a re-definition of the deprecated mime_content_type() function
		// needed by UploadBehavior
		
		
		$this->__folderToDelete = $this->field('dir');
	}
	
	public function afterDelete() {
		// Delete empty folder
		App::uses('Folder', 'Utility');
		$dir = $this->__folderToDelete;
		$folder = new Folder(WWW_ROOT."img/releases/$dir");
		$contents = $folder->find();
		if (empty($contents)) {
			$folder->delete();	
		}
		
		/* Seems unnecessary if just deleting the folder works
		App::uses('File', 'Utility');
		$filename = $this->field('filename');
		$file = new File(WWW_ROOT."img/releases/$dir/$filename", false, 0777);
		$file->delete();
		
		Oh, UploadBehavior seems to do this for us.
		App::uses('Folder', 'Utility');
		$dir = $this->field('dir');
		$folder = new Folder(WWW_ROOT."img/releases/$dir");
		return $folder->delete();
		*/
	}
	
	public function getThumbnailFilename($full_filename) {
		$filename_split = explode('.', $full_filename);
		$thumbnail_filename = array_slice($filename_split, 0, count($filename_split) - 1);
		$thumbnail_filename[] = 'thumb';
		$thumbnail_filename[] = end($filename_split);
		return implode('.', $thumbnail_filename);
	}
	
	public function afterFind($results) {
		foreach ($results as &$result) {
			if (isset($result['Graphic']['image'])) {
				// Construct the thumbnail filename by inserting '.thumb' before the extension
				// e.g. origfilename.jpg => origfilename.thumb.jpg
				$result['Graphic']['thumbnail'] = $this->getThumbnailFilename($result['Graphic']['image']);	
			}
		}
		return $results;
	}
}
