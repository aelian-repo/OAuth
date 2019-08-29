<?php
App::uses('OAuthAppController', 'OAuth.Controller');

class ServerController extends OAuthAppController {

    public $components = array(
        'OAuth.OAuth' => array('authenticate' => array('userModel' => 'Singular')), 
        'Session', 
        'RequestHandler'
    );

    public $resources = array(
        '1' => array('id' => 1, 'name' => 'batata'),
        '2' => array('id' => 2, 'name' => 'cenouras'),
        '3' => array('id' => 3, 'name' => 'ervilhas')
    );

	public function beforeFilter() {
        $this->Components->unload('Auth');
	}

    public function index() {
        $resources = $this->resources;
        $this->set(array('resources' => $resources, '_serialize' => 'resources'));
    }

    public function view($id = null) {
        $resource = $this->resources[$id];
        $this->set(array('resource' => $resource, '_serialize' => 'resource'));
    }

    public function add() {
        if (!empty($this->request->data)) {
            $this->resources[$this->request->data['id']] = $this->request->data;
        }       
        $resources = $this->resources;
        $this->set(array('resources' => $resources, '_serialize' => 'resources'));        
    }

    public function edit($id = null) {
        if (!empty($this->request->data)) {
            $this->resources[$id] = $this->request->data;
        }       
        $resources = $this->resources;
        $this->set(array('resources' => $resources, '_serialize' => 'resources'));        
    }

    public function delete($id = null) {
        unset($this->resources[$id]);
        $resources = $this->resources;
        $this->set(array('resources' => $resources, '_serialize' => 'resources'));        
    }

}
