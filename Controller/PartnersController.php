<?php
App::uses('AppController', 'Controller');
/**
 * Partners Controller
 *
 * @property Partner $Partner
 */
class PartnersController extends AppController {	
/**
 * index method
 *
 * @return void
 */
	public function beforeFilter() {
		parent::beforeFilter();
		$this->Auth->deny('delete', 'edit');
	}
	
	public function index() {
		$this->Partner->recursive = 0;
		$foo = $this->paginate();
		$this->set(array(
			'title_for_layout' => 'Edit Clients, Partners, and Sponsors',
			'partners' => $this->Partner->find('all', array(
				'contain' => array(
					'Release' => array(
						'fields' => array('id')
					)
				),
				'order' => 'Partner.name ASC'
			))
		));
	}

/**
 * view method
 *
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		$this->Partner->id = $id;
		if (!$this->Partner->exists()) {
			throw new NotFoundException(__('Invalid partner (#'.$id.')'));
		}
		$this->set('partner', $this->Partner->read(null, $id));
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
			$this->Partner->create();
			if ($this->Partner->save($this->request->data)) {
				$this->Flash->success(__('Client/partner/sponsor added'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Flash->error(__('The client/partner/sponsor could not be saved. Please, try again.'));
			}
		}
	}

/**
 * edit method
 *
 * @param string $id
 * @return void
 */
	public function edit($id = null) {
		$this->Partner->id = $id;
		if (!$this->Partner->exists()) {
			throw new NotFoundException(__('Invalid partner'));
		}
		if ($this->request->is('post') || $this->request->is('put')) {
			if ($this->Partner->save($this->request->data)) {
				$this->Flash->success(__('Client/partner/sponsor saved'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Flash->error(__('Client/partner/sponsor could not be saved. Please, try again.'));
			}
		} else {
			$this->request->data = $this->Partner->read(null, $id);
		}
		$this->set('title_for_layout', 'Edit Client / Partner / Sponsor');
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
		$this->Partner->id = $id;
		if (!$this->Partner->exists()) {
			throw new NotFoundException(__('Invalid partner'));
		}
		if ($this->Partner->delete()) {
			$this->Flash->success(__('Client/partner/sponsor deleted.'));
			$this->redirect(array('action' => 'index'));
		}
		$this->Flash->set(__('There was an error deleting that client/partner/sponsor.'));
		$this->redirect(array('action' => 'index'));
	}
}
