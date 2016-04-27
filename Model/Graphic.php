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
				'thumbnailName' => '{filename}.{size}', // e.g. origfilename.thumb.extension
				'fileNameFunction' => 'sanitizeFileName',
				'deleteFolderOnDelete' => true
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
				'rule' => array('numeric')
			)
		),
		'url' => array(
			'notempty' => array(
				'rule' => array('notBlank'),
				'message' => 'A URL for this linked graphic is required',
				'required' => true
			)
		),
		'title' => array(
			'notempty' => array(
				'rule' => array('notBlank'),
				'message' => 'Required',
				'required' => true
			)
		),
		'image' => array(
			'notempty' => array(
				'rule' => array('uploadedImage'),
				'message' => 'Image required',
				'required' => true,
				'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
			'valid_extension' => array(
				'rule' => array('isValidExtension', array('jpg', 'jpeg', 'gif', 'png')),
        		'message' => 'File has an invalid extension (not jpg, jpeg, gif, or png)',
        		'on' => 'create'
			)
		)
	);

	private $__folderToDelete = null;

	public function uploadedImage($check) {
		return ! empty($check['image']['name']);
	}

	public function beforeDelete($cascade = true) {
		$this->__folderToDelete = $this->field('dir');
	}

	public function getThumbnailFilename($full_filename) {
		$filename_split = explode('.', $full_filename);
		$thumbnail_filename = array_slice($filename_split, 0, count($filename_split) - 1);
		$thumbnail_filename[] = 'thumb';
		$thumbnail_filename[] = end($filename_split);
		return implode('.', $thumbnail_filename);
	}

	public function afterFind($results, $primary = false) {
		foreach ($results as &$result) {
			if (isset($result['Graphic']['image'])) {
				// Construct the thumbnail filename by inserting '.thumb' before the extension
				// e.g. origfilename.jpg => origfilename.thumb.jpg
				$result['Graphic']['thumbnail'] = $this->getThumbnailFilename($result['Graphic']['image']);
			}
		}
		return $results;
	}

	public function sanitizeFileName($file_name){
		$file_ext = pathinfo($file_name, PATHINFO_EXTENSION);
		$file_name = Inflector::slug(pathinfo($file_name, PATHINFO_FILENAME));
		if (! empty($file_ext)) {
			$file_name .= '.'.$file_ext;
		}
		return $file_name;
    }
}