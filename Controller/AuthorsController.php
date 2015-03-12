<?php
App::uses('AppController', 'Controller');
/**
 * Authors Controller
 *
 * @property Author $Author
 * @property PaginatorComponent $Paginator
 */
class AuthorsController extends AppController {
	public $components = array('Paginator');

	public function beforeFilter() {
		parent::beforeFilter();
		$this->Auth->deny('index', 'add', 'delete', 'edit');
	}

	public function index() {
		$this->Author->recursive = 0;
		$this->set(array(
			'authors' => $this->Paginator->paginate(),
			'title_for_layout' => 'Authors'
		));
	}

	public function view($id = null) {
		if (! $this->Author->exists($id)) {
			throw new NotFoundException(__('Invalid author'));
		}
		$author = $this->Author->find(
			'first',
			array(
				'conditions' => array(
					'Author.' . $this->Author->primaryKey => $id
				)
			)
		);
		$author_name = $author['Author']['name'];
		$this->set(array(
			'author' => $author,
			'title_for_layout' => $author_name,
			'author_name' => $author_name
		));
	}

	public function add() {
		if ($this->request->is('post')) {
			$this->Author->create();
			if ($this->Author->save($this->request->data)) {
				$this->Flash->success('The author has been saved.');
				$this->redirect(array('action' => 'index'));
			}
		}
		$this->set('title_for_layout', 'Add a New Author');
	}

	public function edit($id = null) {
		if (! $this->Author->exists($id)) {
			throw new NotFoundException(__('Invalid author'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->Author->save($this->request->data)) {
				$this->Flash->success('The author has been saved.');
				$this->redirect(array('action' => 'index'));
			}
		} else {
			$options = array('conditions' => array('Author.' . $this->Author->primaryKey => $id));
			$this->request->data = $this->Author->find('first', $options);
		}
		$this->set(array(
			'releases' => $this->Author->Release->find('list'),
			'title_for_layout' => 'Update Author'
		));
	}

	public function delete($id = null) {
		$this->Author->id = $id;
		if (! $this->Author->exists()) {
			throw new NotFoundException(__('Invalid author'));
		}
		$this->request->onlyAllow('post', 'delete');
		if ($this->Author->delete()) {
			$this->Flash->success('The author has been deleted.');
		} else {
			$this->Flash->error('The author could not be deleted. Please try again.');
		}
		$this->redirect($this->request->referer());
	}
}
