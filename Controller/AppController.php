<?php
/**
 * Application level Controller
 *
 * This file is application-wide controller file. You can put all
 * application-wide controller-related methods here.
 *
 * PHP 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright 2005-2012, Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright 2005-2012, Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.Controller
 * @since         CakePHP(tm) v 0.2.9
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

App::uses('Controller', 'Controller');
App::uses('Partner', 'Model');
App::uses('Tag', 'Model');
App::uses('Release', 'Model');

/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @package       app.Controller
 * @link http://book.cakephp.org/2.0/en/controllers.html#the-app-controller
 */
class AppController extends Controller {
	public $helpers = array(
		'Js' => array('Jquery'),
		'Html',
		'Text',
		'Session',
		'Form'
	);
	public $components = array(
		'DebugKit.Toolbar',
		'DataCenter.Flash',
		'DataCenter.TagManager',
		'Session',
		'DataCenter.AutoLogin' => array(
			'username' => 'email',
			'expires' => '+1 year'
		),
        'Auth' => array(
            'loginRedirect' => array(
            	'controller' => 'releases',
            	'action' => 'index'
			),
            'logoutRedirect' => array(
            	'controller' => 'releases',
            	'action' => 'index'
			),
			'authorize' => array('Controller'),
			'authenticate' => array(
	            'Form' => array(
	                'fields' => array('username' => 'email')
	            )
	        )
        )
	);

	public function beforeFilter() {
		$this->AutoLogin->settings = array(
			// Model settings
			'model' => 'User',
			'username' => 'email',
			'password' => 'password',

			// Controller settings
			'plugin' => '',
			'controller' => 'users',
			'loginAction' => 'login',
			'logoutAction' => 'logout',

			// Cookie settings
			'cookieName' => 'rememberMe',
			'expires' => '+1 year',

			// Process logic
			'active' => true,
			'redirect' => true,
			'requirePrompt' => true
		);
		$this->Auth->allow();
	}

	public function beforeRender() {
		$Partner = new Partner();

		$Tag = new Tag();
		$tags = $Tag->find(
			'all',
			array(
				'fields' => array(
					'id',
					'name',
					'slug'
				),
				'order' => 'name',
				'contain' => array(
					'Release' => array(
						'fields' => array('id')
					)
				)
			)
		);
		$tags_simple = array();
		foreach ($tags as $result) {
			if (! empty($result['Release'])) {
				$tags_simple[] = $result['Tag'];
			}
		}

		$Release = new Release();
		$Release->displayField = 'released';
		$releases = $Release->find(
			'list',
			array(
				'order' => 'released DESC'
			)
		);
		$years = array();
		foreach ($releases as $date) {
			$year = substr($date, 0, 4);
			if (! in_array($year, $years)) {
				$years[] = $year;
			}
		}

		// Get a list of all partners and remove any not associated with releases
		$partners = $Partner->find(
			'all',
			array(
				'order' => 'name ASC',
				'fields' => array(
					'id',
					'name',
					'short_name',
					'slug'
				),
				'contain' => array(
					'Release' => array(
						'fields' => array('id')
					)
				)
			)
		);
		foreach ($partners as $k => $partner) {
			if (empty($partner['Release'])) {
				unset($partners[$k]);
			}
		}

		$this->set(array(
			'sidebar_vars' => array(
				'partners' => $partners,
				'tags' => $tags_simple,
				'years' => $years
			)
		));
	}

	public function isAuthorized($user = null) {
		return (bool)$this->Auth->user('id');
    }
}
