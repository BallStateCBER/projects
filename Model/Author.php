<?php
App::uses('AppModel', 'Model');
/**
 * Author Model
 *
 * @property Release $Release
 */
class Author extends AppModel {

/**
 * Display field
 *
 * @var string
 */
	public $displayField = 'name';

/**
 * Validation rules
 *
 * @var array
 */
	public $validate = array(
		'name' => array(
			'notEmpty' => array(
				'rule' => array('notEmpty'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
	);

	//The Associations below have been created with all possible keys, those that are not needed can be removed

/**
 * hasAndBelongsToMany associations
 *
 * @var array
 */
	public $hasAndBelongsToMany = array(
		'Release' => array(
			'className' => 'Release',
			'joinTable' => 'authors_releases',
			'foreignKey' => 'author_id',
			'associationForeignKey' => 'release_id',
			'unique' => true
		)
	);

}
