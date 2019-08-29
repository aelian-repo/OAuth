<?php
App::uses('OAuthAppController', 'OAuth.Controller');

class ClientController extends OAuthAppController {

    public $components = array('RequestHandler');

    public $autoRender = false;
    public $id = null;
    public $accessToken = '';

	public function beforeFilter() {
        $this->Components->unload('Auth');
        if (empty($this->accessToken)) {
            $this->getAccessToken();
        }
    }
    
    private function getAccessToken() {
        $credencial = array(
            'response_type' => 'code',
            'client_id' => 'NWQ2Njg4MWIxNjRmNWY5', 
            'client_secret' => '62be871736ed29ebbf1ef055b058df23400bf432'
        );
        $authorization = $this->authorizationRequest('authorize', $credencial);
        pr($authorization);
        
        $authorization = array(
            'grant_type' => 'authorization_code',
            'code' => $authorization['code'],
            'client_id' => $credencial['client_id'], 
            'client_secret' => $credencial['client_secret']
        );
        $token = $this->authorizationRequest('token', $authorization);
        pr($token);

        $this->accessToken = $token['access_token'];
    }

    public function index() {
        $response = $this->request('GET');
        pr('INDEX');
        pr($response);
    }

    public function view($id = null) {
        $this->id = $id;
        $response = $this->request('GET');
        pr('VIEW');
        pr($response);
    }

    public function add() {
        $this->id = null;
        $resource = array('id' => '4', 'name' => 'milho');
        $response = $this->request('POST', $resource);
        pr('ADD');
        pr($response);
    }

    public function edit($id = null) {
        $this->id = $id;
        $resource = array('id' => $id, 'name' => 'beterraba');
        $response = $this->request('POST', $resource);
        pr('EDIT');
        pr($response);
    }

    public function delete($id = null) {
        $this->id = $id;
        $response = $this->request('DELETE');
        pr('DELETE');
        pr($response);
    }
    
    private function authorizationRequest($method, $credencial) {
        $url = Router::url('/api/v1/oauth/' . $method, true) . '?' . http_build_query($credencial);
        pr($url);
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'GET');                                              
        curl_setopt($curl, CURLOPT_VERBOSE, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_TIMEOUT, 30);
        curl_setopt($curl, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);        
        $response = curl_exec($curl);
        if (curl_errno($curl)) {
            $response = array('erros' => curl_error($curl));            
        } else {
            $response = json_decode($response, true);
        }
        curl_close($curl);

        return $response;                
    }       

    private function request($method, $resource = array()) {
        $resource = json_encode($resource);                                                                   
        $url = Router::url('/api/v1/server/', true) . $this->id . '?access_token=' . $this->accessToken;
        pr($url);
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method);                                              
        curl_setopt($curl, CURLOPT_POSTFIELDS, $resource);
        curl_setopt($curl, CURLOPT_VERBOSE, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_TIMEOUT, 30);
        curl_setopt($curl, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);        
        curl_setopt($curl, CURLOPT_HTTPHEADER, array(                                                           
           'Content-Type: application/json',   
           'Content-Length: ' . strlen($resource),                                                        
//           'authorization: Bearer ' . $this->accessToken,                                                      
        ));                                                                                           
        $response = curl_exec($curl);
        if (curl_errno($curl)) {
            $response = array('erros' => curl_error($curl));            
        } else {
            $response = json_decode($response, true);
        }
        curl_close($curl);

        return $response;                
    }       
    
}
