<?php
/**
 * Controller de exemplo para REST Server
 * 
 * Ver tambem:
 *    
 *    Config/routes.php para exemplo de configuração das rotas de REST Server
 * 
 */
App::uses('OAuthAppController', 'OAuth.Controller');

class ServerController extends OAuthAppController {

    public $components = array(
        /**
         * substituir userModel pelo model que vai identificar o usuário no app. Ex: Empresa, Orgao, Singular...
         */
        'OAuth.OAuth' => array('authenticate' => array('userModel' => 'Orgao')), 
        'Session', 
        'RequestHandler'
    );

    /**
     * Resources de Exemplo
     */
    public $resources = array(
        '1' => array('id' => 1, 'name' => 'batata'),
        '2' => array('id' => 2, 'name' => 'cenouras'),
        '3' => array('id' => 3, 'name' => 'ervilhas')
    );

	public function beforeFilter() {
        /**
         * Não utilizar o Auth porque o OAuth será utilizado no lugar
         */
        $this->Components->unload('Auth');        
        /**
         * Coloca o usuário associado ao Client que solicitou o recursos junto com os recursos
         */
        $user = $this->OAuth->user();
        $this->resources = array_merge($this->resources, array('User' => $user));
	}

    /**
     * Retorna todos resources
     * 
     * url /api/server
     * method GET 
     * return resources;
     */
    public function index() {
        $resources = $this->resources;
        $this->set(array('resources' => $resources, '_serialize' => 'resources'));
    }

    /**
     * Retorna resource por id
     * 
     * url /api/server/[id]
     * method GET 
     * return resource;
     */
    public function view($id = null) {
        $resource = $this->resources[$id];
        $this->set(array('resource' => $resource, '_serialize' => 'resource'));
    }

    /**
     * Grava novo resource
     * 
     * url /api/server
     * method POST
     * data [id, name]
     * return resources;
     */
    public function add() {
        if (!empty($this->request->data)) {
            $this->resources[$this->request->data['id']] = $this->request->data;
        }       
        $resources = $this->resources;
        $this->set(array('resources' => $resources, '_serialize' => 'resources'));        
    }

    /**
     * Atualiza resource por id
     * 
     * url /api/server/[id]
     * method POST
     * data [id, name]
     * return resources;
     */
    public function edit($id = null) {
        if (!empty($this->request->data)) {
            $this->resources[$id] = $this->request->data;
        }       
        $resources = $this->resources;
        $this->set(array('resources' => $resources, '_serialize' => 'resources'));        
    }

    /**
     * Delete resource por id
     * 
     * url /api/server/[id]
     * method DELETE
     * return resources;
     */
    public function delete($id = null) {
        unset($this->resources[$id]);
        $resources = $this->resources;
        $this->set(array('resources' => $resources, '_serialize' => 'resources'));        
    }

}
