<?php
App::uses('AppController', 'Controller');
/**
 * Releases Controller
 *
 * @property Release $Release
 */
class ReleasesController extends AppController {
	public $helpers = array('Graphic', 'Paginator');
	public $paginate = array(
        'limit' => 5,
        'order' => array(
        	'Release.released' => 'desc',
        	'Release.created' => 'desc'
		),
		'contain' => array('Tag', 'Partner', 'Graphic')
    );
    public $components = array('Search.Prg');
    public $report_filetypes = array('pdf', 'doc', 'docx', 'xls', 'xlsx');
	public $presetVars = true; // Used by the Search plugin

	public function beforeFilter() {
		parent::beforeFilter();
		$this->Auth->deny('add', 'delete', 'edit', 'list_reports');
	}

	// Calls a page that refreshes the Data Center's homepage's cache of the latest release
	private function __updateDataCenterHome() {
		// Development server
		if (stripos($_SERVER['SERVER_NAME'], 'localhost') !== false) {
			$url = 'http://dchome.localhost/refresh_latest_release';
		// Production server
		} else {
			$url = 'http://cberdata.org/refresh_latest_release';
		}
		$results = trim(file_get_contents($url));
		return (boolean) $results;
	}

	private function __processNewAuthors() {
		if (! isset($this->request->data['new_authors']) || empty($this->request->data['new_authors'])) {
			return;
		}
		foreach ($this->request->data['new_authors'] as $author) {
			$this->Release->Author->create();
			$this->Release->Author->save(array(
				'name' => $author
			));
			$this->request->data['Author']['Author'][] = $this->Release->Author->getInsertID();
		}
	}

/**
 * index method
 *
 * @return void
 */
	public function index() {
		$this->Release->recursive = 0;
		$this->set(array(
			'releases' => $this->paginate(),
			'title_for_layout' => ''
		));
	}

/**
 * view method
 *
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		if (isset($this->params['named']['id'])) {
			$id = $this->params['named']['id'];
		}
		$this->Release->id = $id;
		if (!$this->Release->exists()) {
			throw new NotFoundException(__('Invalid release'));
		}
		$this->set('release', $this->Release->read(null, $id));
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		App::Uses('Partner', 'Model');
		$Partner = new Partner();
		$partners = $Partner->find('list', array('order' => 'name ASC'));
		$this->set(compact('partners'));

		if (! empty($this->request->data)) {

			// Process 'custom tags' field and eliminate duplicates
			$this->TagManager->processTagInput($this->request->data);

			$this->__processNewAuthors();

			// Process partner data
			if (! empty($this->request->data['Release']['new_partner'])) {
				$new_partner = trim($this->request->data['Release']['new_partner']);
				$partner_id = array_search($new_partner, $partners);
				if ($partner_id === false) {
					$Partner->create();
					$Partner->save(array('name' => $new_partner));
					$partner_id = $Partner->id;
					$partners[$partner_id] = $new_partner;
				}
				$this->request->data['Release']['new_partner'] = '';
				$this->request->data['Release']['partner_id'] = $partner_id;
			}

			// Fiddle with graphics data
			$images_were_uploaded = false;
			if (isset($this->request->data['Graphic'])) {
				foreach ($this->request->data['Graphic'] as $i => $g) {
					if (empty($g['image']['name'])) {
						// Ignore any graphics without uploaded images
						unset($this->request->data['Graphic'][$i]);
					} else {
						// Populate the 'filename' field so that it can be validated
						//$this->request->data['Graphic'][$i]['filename'] = $g['image']['name'];
						$images_were_uploaded = true;
					}
				}
			}

			$this->Release->create($this->request->data);
			if ($this->Release->validateAssociated($this->request->data)) {
				if ($this->Release->save($this->request->data)) {
					$this->loadModel('Graphic');
					if (isset($this->request->data['Graphic'])) {
						foreach ($this->request->data['Graphic'] as $g) {
							$this->Graphic->create($g);
							$this->Graphic->set('release_id', $this->Release->id);
							if (! $this->Graphic->save()) {
								$this->Flash->error('There was an error saving a release graphic ('.$g['image']['name'].'). Please try again.');
							}
						}
					}
					$this->Flash->success('Release added.');
					$this->__updateDataCenterHome();
					$this->redirect(array(
						'controller' => 'releases',
						'action' => 'view',
						'id' => $this->Release->id,
						'slug' => $this->Release->field('slug')
					));

				} else {
					$this->Flash->error('The release could not be saved. Please try again.');
				}
			} else {
				$this->Flash->error('Please correct the indicated errors.');
				if ($images_were_uploaded) {
					$this->Flash->error('You will need to re-upload the linked graphics for this release.');
				}
			}
		}

		// Sends $available_tags and $unlisted_tags to the view
		$this->TagManager->prepareEditor($this);

		// Removes the 'required' attribute for the #ReleasePartnerId field in the view,
		// which breaks the form if a new partner is entered
		unset($this->Release->validate['partner_id']);

		$this->set(array(
			'mode' => 'add',
			'title_for_layout' => 'Add a New Release',
			'report_filetypes' => $this->report_filetypes,
			'session_id' => $this->Session->id(),
			'authors' => $this->Release->Author->find('list', array(
				'order' => array(
					'Author.name' => 'ASC'
				)
			))
		));

		$this->render('/Releases/form');
	}

/**
 * edit method
 *
 * @param string $id
 * @return void
 */
	public function edit($id = null) {
		if ($id && ! $this->Release->exists($id)) {
			$this->Flash->error('The specified release (ID: '.$id.') doesn\'t exist.');
			$this->redirect('/');
		} elseif (! $id) {
			$this->Flash->error('No ID specified. Which release do you want to edit?');
			$this->redirect('/');
		}

		$this->Release->id = $id;

		App::Uses('Partner', 'Model');
		$Partner = new Partner();
		$partners = $Partner->find('list', array('order' => 'name ASC'));
		$this->set(compact('partners'));

		if (! empty($this->request->data)) {

			// Process 'custom tags' field and eliminate duplicates
			$this->TagManager->processTagInput($this->request->data);

			$this->__processNewAuthors();
			if (! isset($this->request->data['Author']['Author'])) {
				// This allows the last author to be removed
				$this->request->data['Author']['Author'] = array();
			}

			// Process partner data
			if (! empty($this->request->data['Release']['new_partner'])) {
				$new_partner = trim($this->request->data['Release']['new_partner']);
				$partner_id = array_search($new_partner, $partners);
				if ($partner_id === false) {
					$Partner->create();
					$Partner->save(array('name' => $new_partner));
					$partner_id = $Partner->id;
					$partners[$partner_id] = $new_partner;
				}
				$this->request->data['Release']['new_partner'] = '';
				$this->request->data['Release']['partner_id'] = $partner_id;
			}

			// Process graphics
			$this->loadModel('Graphic');
			$images_were_uploaded = false;
			foreach ($this->request->data['Graphic'] as $i => $g) {
				// Note if new images were uploaded and ignore any new graphics without uploaded images
				if (is_array($g['image'])) {
					if (empty($g['image']['name'])) {
						unset($this->request->data['Graphic'][$i]);
					} else {
						$images_were_uploaded = true;
					}

				// Handle removal of existing images
				} else {
					if (isset($g['remove']) && $g['remove']) {
						$this->Graphic->delete($g['id']);
						unset($this->request->data['Graphic'][$i]);
					}

				}
			}

			$this->Release->set($this->request->data);

			/* For some reason, validateAssociated() changes its first parameter, turning
			 * $data['Graphic'][0]... into $data['Graphic'][0]['Graphic']...
			 * That's why a copy of $this->request->data is used. */
			$data = $this->request->data;

			if ($this->Release->validateAssociated($data)) {
				if ($this->Release->saveAssociated($this->request->data)) {
					$this->Flash->success('Release updated.');
					$this->redirect(array(
						'controller' => 'releases',
						'action' => 'view',
						'id' => $this->Release->id,
						'slug' => $this->Release->field('slug')
					));
				} else {
					$this->Flash->error('The release could not be updated. Please try again.');
					$this->Flash->dump($this->Release->validationErrors);
				}
			} else {
				$this->Flash->error('Please correct the indicated errors.');
				if ($images_were_uploaded) {
					$this->Flash->error('You will need to re-upload new linked graphics for this release.');
				}
			}
		} else {
			$this->request->data = $this->Release->read();
		}

		// Sends $available_tags and $unlisted_tags to the view
		$this->TagManager->prepareEditor($this);

		$this->set(array(
			'mode' => 'edit',
			'release_id' => $id,
			'title_for_layout' => 'Edit Release',
			'report_filetypes' => $this->report_filetypes,
			'session_id' => $this->Session->id(),
			'authors' => $this->Release->Author->find('list', array(
				'order' => array(
					'Author.name' => 'ASC'
				)
			))
		));

		$this->render('/Releases/form');
	}

/**
 * delete method
 *
 * @param string $id
 * @return void
 */
	public function delete($id = null) {
		if (! $this->request->is('post')) {
			throw new MethodNotAllowedException();
		}
		$this->Release->id = $id;
		if (! $this->Release->exists()) {
			throw new NotFoundException(__('Invalid release'));
		}
		if ($this->Release->delete($id, true)) {
			$this->Flash->success('Release deleted');
		} else {
			$this->Flash->error('There was an error deleting this release');
		}
		$this->redirect(array('action' => 'index'));
	}

	public function year($year = null) {
		$this->set(array(
			'year' => $year,
			'releases' => $this->Release->find('all', array(
				'conditions' => array('released LIKE' => $year.'%'),
				'fields' => array('id', 'title', 'slug', 'released'),
				'contain' => false,
				'order' => 'released ASC'
			)),
			'title_for_layout' => $year.' Projects and Publications'
		));
	}

	public function upload_reports() {
	    $this->layout = 'DataCenter.blank';
        $this->render('DataCenter.Common/blank');

	    if (empty($_POST) || empty($_FILES)) {
	        echo 'Error: File was not successfully uploaded. This may be because the file exceeded a size limit.';
            return;
        }

		$targetFolder = 'reports'; // Relative to the root

		$verifyToken = md5(Configure::read('upload_token') . $_POST['timestamp']);

		if (!empty($_FILES) && $_POST['token'] == $verifyToken) {
			$tempFile = $_FILES['Filedata']['tmp_name'];
			$targetPath = WWW_ROOT.$targetFolder;
			$targetFile = rtrim($targetPath,'/') . '/' . $_FILES['Filedata']['name'];

			// Validate the file type
			$fileTypes = $this->report_filetypes; // File extensions
			$fileParts = pathinfo($_FILES['Filedata']['name']);

			if (in_array(strtolower($fileParts['extension']), $fileTypes)) {
				if (file_exists($targetFile) && ! (isset($_POST['overwrite']) && $_POST['overwrite'])) {
					echo "Error: {$_FILES['Filedata']['name']} has already been uploaded.";
				} else {
					if (move_uploaded_file($tempFile,$targetFile)) {
						echo "{$_FILES['Filedata']['name']} uploaded";
					} else {
						echo "Error uploading {$_FILES['Filedata']['name']}";
					}
				}
			} else {
				echo "Error: {$_FILES['Filedata']['name']} does not have one of these allowed extensions: ".implode(', ', $fileTypes);
			}
		}
	}

	/* $row_i is the iterator that identifies which row of the 'add/edit linked graphics'
	 * table is associated with this request. */
	public function list_reports($row_i) {
		App::uses('Folder', 'Utility');
		App::uses('File', 'Utility');
		$dir = new Folder(WWW_ROOT.'reports');
		$filenames = $dir->find();
		$files_newest = $files_alphabetic = array();
		foreach ($filenames as $i => $filename) {
			$file = new File(WWW_ROOT.'reports/'.$filename);
			$last_change = $file->lastChange();
			$file_info = array(
				'filename' => $filename,
				'timestamp' => $last_change,
				'date' => date('r', $last_change)
			);
			$files_newest["$last_change.$i"] = $file_info;
			$files_alphabetic[$filename] = $file_info;
		}
		krsort($files_newest);
		ksort($files_alphabetic);
		$this->layout = 'ajax';
		$this->set(array(
			'files_newest' => $files_newest,
			'files_alphabetic' => $files_alphabetic,
			'row_i' => $row_i
		));
	}

	// This provides a printout of serialized data used by the Data Center's homepage
	public function latest() {
		$release = $this->Release->find('first', array(
			'order' => 'released DESC',
			'fields' => array('id', 'title', 'released', 'slug'),
			'contain' => array(
				'Graphic' => array(
					'fields' => array('dir', 'image'),
					'order' => 'weight ASC',
					'limit' => 1
				)
			)
		));
		if (! empty($release)) {
			$release['Release']['url'] = Router::url(array(
				'controller' => 'releases',
				'action' => 'view',
				'id' => $release['Release']['id'],
				'slug' => $release['Release']['slug']
			), true);
			if (! empty($release['Graphic'])) {
				$this->loadModel('Graphic');
				$release['Graphic'][0]['thumbnail'] =
					Router::url('/', true).
					'img/releases/'.$release['Graphic'][0]['dir'].'/'.
					$this->Graphic->getThumbnailFilename($release['Graphic'][0]['image']);
			}
		}
		$this->set('release', $release);
		$this->layout = 'ajax';
	}

	public function search() {
		$this->Prg->commonProcess();
		$query = isset($this->passedArgs['q']) ? trim($this->passedArgs['q']) : false;

		if (! empty($query)) {

			// Get releases with the query in their titles or descriptions
			$this->paginate['conditions'] = $this->Release->parseCriteria($this->passedArgs);
			$this->paginate['fields'] = array('id', 'title', 'slug', 'released', 'description');
			$this->paginate['contain'] = false;
			$this->paginate['limit'] = 20;
			$releases = $this->paginate();

			// Get a list of matching tags
			$tags = $this->Release->Tag->find('all', array(
				'conditions' => array('name LIKE' => "%$query%"),
				'fields' => array('id', 'name', 'slug'),
				'contain' => false
			));

		} else {
			$releases = $tags = array();
		}

		$this->set(array(
			'title_for_layout' => "Search Results: $query",
			'releases' => $releases,
			'tags' => $tags,
			'query' => $query
		));
	}
}