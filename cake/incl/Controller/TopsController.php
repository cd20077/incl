<?php
App::uses('AppController', 'Controller');
/**
 * Tops Controller
 */
class TopsController extends AppController {


/**
 *  Layout
 */
	public $layout = 'top';


/**
 * Components
 */
	public $components = array('Paginator');

/**
 * index method
 */
	public function index($id = null) {

		$this->layout="top";
		//print_r($id);
		$getid = $id;
		$this->set(compact('getid'));
	}

}
