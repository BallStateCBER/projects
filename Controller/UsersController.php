<?php
App::uses('AppController', 'Controller');
/**
 * Users Controller
 *
 * @property User $User
 */
class UsersController extends AppController {

	public function beforeFilter() {
	    parent::beforeFilter();
	    $this->Auth->deny(
	    	'add',
	    	'change_password',
	    	'edit',
	    	'delete',
	    	'index',
	    	'view'
		);
	}

/**
 * index method
 *
 * @return void
 */
	public function index() {
		$this->User->recursive = 0;
		$this->set(array(
			'title_for_layout' => 'Users',
			'users' => $this->paginate()
		));
	}

/**
 * view method
 *
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		$this->User->id = $id;
		if (!$this->User->exists()) {
			throw new NotFoundException(__('Invalid user'));
		}
		$this->set('user', $this->User->read(null, $id));
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
			$this->User->create();
			if ($this->User->save($this->request->data)) {
				$this->Flash->success('The user has been saved');
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Flash->error('The user could not be saved. Please, try again.');
			}
		}
		$this->set('title_for_layout', 'Add User');
	}

/**
 * edit method
 *
 * @param string $id
 * @return void
 */
	public function edit($id = null) {
		$this->User->id = $id;
		if (!$this->User->exists()) {
			throw new NotFoundException(__('Invalid user'));
		}
		if ($this->request->is('post') || $this->request->is('put')) {
			if ($this->User->save($this->request->data)) {
				$this->Flash->set(__('The user has been saved'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Flash->set(__('The user could not be saved. Please, try again.'));
			}
		} else {
			$this->request->data = $this->User->read(null, $id);
		}
	}

/**
 * delete method
 *
 * @param string $id
 * @return void
 */
	public function delete($id = null) {
		if (!$this->request->is('post')) {
			throw new MethodNotAllowedException();
		}
		$this->User->id = $id;
		if (!$this->User->exists()) {
			throw new NotFoundException(__('Invalid user'));
		}
		if ($this->User->delete()) {
			$this->Flash->set(__('User deleted'));
			$this->redirect(array('action' => 'index'));
		}
		$this->Flash->set(__('User was not deleted'));
		$this->redirect(array('action' => 'index'));
	}

	public function login() {
	    if ($this->request->is('post')) {
	        if ($this->Auth->login()) {
	            $this->redirect($this->Auth->redirect());
	        } else {
	            $this->Flash->error('Invalid username or password.');
	        }
	    }
	    $this->set('title_for_layout', 'Log In');
	}

	public function logout() {
		$this->redirect($this->Auth->logout());
	}

	public function change_password() {
		if ($this->request->is('post')) {
			$this->request->data['User']['password'] = $this->request->data['User']['new_password'];
			$this->User->id = $this->Auth->user('id');
			if ($this->User->save($this->request->data)) {
				$this->Flash->success('Password changed');
				$this->redirect(array('controller' => 'releases', 'action' => 'index'));
			} else {
				$this->Flash->error('There was an error changing your password.');
			}
			$this->request->data = array();
		}
		$this->set('title_for_layout', 'Change Password');
	}
}
