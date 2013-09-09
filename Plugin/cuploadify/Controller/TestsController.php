<?php
class TestsController extends AppController {
	var $uses = null;
	var $name = 'Tests';
	var $components = array('Cuploadify.cuploadify');
	var $helpers = array('Js');

    function upload() {
		$this->cuploadify->upload();
		$this->set('session_id', $this->Session->id());
    }
}