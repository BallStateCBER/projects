<?php
App::uses('AppModel', 'Model');
/**
 * Release Model
 *
 * @property Tag $Tag
 */
class Release extends AppModel {
	public $actsAs = array(
		'Sluggable.Sluggable' => array(
			'label' => 'title',
			'slug' => 'slug',
			'separator' => '-',
			'overwrite' => false   
		),
		'Search.Searchable'
	);
	
	public $hasMany = array(
		'Graphic' => array(
			'className' => 'Graphic',
			'foreignKey' => 'release_id',
			'order' => array('Graphic.weight ASC', 'Graphic.created DESC'),
			'dependent' => true
		)
	);
	
	public $belongsTo = array('Partner');
	
	public $hasAndBelongsToMany = array(
		'Tag' => array(
			'className' => 'Tag',
			'joinTable' => 'releases_tags',
			'foreignKey' => 'release_id',
			'associationForeignKey' => 'tag_id',
			'unique' => 'keepExisting',
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'finderQuery' => '',
			'deleteQuery' => '',
			'insertQuery' => ''
		)
	);
	
	public $validate = array(
		'title' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				'message' => 'Required',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			)
		),
		'released' => array(
			'date' => array(
				'rule' => array('date'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			)
		),
		'partner_id' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				'message' => 'Please choose or add a client / partner / sponsor for this release.',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			)
		)
	);
	
	// Used by the Search plugin
	public $filterArgs = array(
		array('name' => 'q', 'type' => 'query', 'method' => 'filterQuery')
		//array('name' => 'q', 'type' => 'like')
		//'connectorAnd' => '+', 'connectorOr' => ','
	);
	
	public function filterQuery($data = array()) {
		if (empty($data['q'])) {
			return array();
		}
		$query = '%'.$data['q'].'%';
		return array(
			'OR' => array(
				'Release.title LIKE' => $query,
				'Release.description LIKE' => $query
			)
		);
	}
}
