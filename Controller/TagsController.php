<?php
App::uses('AppController', 'Controller');
/**
 * Tags Controller
 *
 * @property Tag $Tag
 */
class TagsController extends AppController {
	
	public function beforeFilter() {
		parent::beforeFilter();
		$this->Auth->deny(array('edit', 'delete'));
	}
	
/**
 * view method
 *
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		$this->Tag->id = $id;
		if (!$this->Tag->exists()) {
			throw new NotFoundException(__('Invalid tag'));
		}
		$this->set('tag', $this->Tag->read(null, $id));
	}

/**
 * add method
 *
 * @return void
 */
	
/**
 * edit method
 *
 * @param string $id
 * @return void
 */
	public function edit($id = null) {
		if ($id) {
			$this->Tag->id = $id;
			if (!$this->Tag->exists()) {
				throw new NotFoundException(__('Invalid tag'));
			}
			if ($this->request->is('post') || $this->request->is('put')) {
				if ($this->Tag->save($this->request->data)) {
					$this->Flash->success('Tag updated');
					$id = null;
				} else {
					$this->Flash->error('The tag could not be saved. Please, try again.');
				}
			} else {
				$this->request->data = $this->Tag->read(null, $id);
			}
		}
		$this->set(array(
			'tag_id' => $id,
			'tags' => $this->Tag->find('list', array('order' => 'name ASC')),
			'title_for_layout' => 'Edit Tag'
		));
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
		$this->Tag->id = $id;
		if (!$this->Tag->exists()) {
			throw new NotFoundException(__('Invalid tag'));
		}
		if ($this->Tag->delete()) {
			$this->Flash->success('Tag deleted');
			$this->redirect(array('action' => 'edit'));
		}
		$this->Flash->error('There was an error deleting that tag');
		$this->redirect(array('action' => 'edit'));
	}
}
