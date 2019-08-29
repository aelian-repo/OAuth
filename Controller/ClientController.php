<?php
/**
 * Controller de exemplo para REST Client
 * 
 * Ver tambem:
 *    
 *    Config/routes.php para exemplo de configuração das rotas de REST Client
 * 
 */

App::uses('OAuthAppController', 'OAuth.Controller');

class ClientController extends OAuthAppController {

    public $components = array('RequestHandler');

    /**
     * Não tem views, só exibe URLs e Responses do REST Server
     */
    public $autoRender = false; 
    /**
     * Id do Resource de Exemplo
     */
    public $id = null; 
    /**
     * Armazena o AccessToken para poder acessar o Resource do REST Server
     */
    public $accessToken = ''; 

	public function beforeFilter() {
        /**
         * Retirado o Auth só pra teste, mas em Apps reais pode ser necessário manter.
         */
        $this->Components->unload('Auth');
        /**
         * Se ainda não tiver AccessToken, faz a autenticação no REST Server
         */
        if (empty($this->accessToken)) {
            $this->getAccessToken();
        }
    }
    
    /**
     * Faz a autenticação no REST Server
     */
    private function getAccessToken() {
        /**
         * Credencial do Client.
         * Substituir por informações do client que quer testar.
         * Para criar um novo Client:
         * 
         *      /api/v1/oauth/add
         * 
         */
        $credencial = array(
            'response_type' => 'code',
            'client_id' => 'NWQ2Njg4MWIxNjRmNWY5', 
            'client_secret' => '62be871736ed29ebbf1ef055b058df23400bf432'
        );
        /**
         * Passo 1 - Solicita Authorization
         */
        $authorization = $this->authorizationRequest('authorize', $credencial);
        pr($authorization);
        
        /**
         * Autorização do Client
         * Já reaproveita informações da credencial do Client e Adiciona código de Autorização         * 
         */
        $authorization = array(
            'grant_type' => 'authorization_code',
            'code' => $authorization['code'],
            'client_id' => $credencial['client_id'], 
            'client_secret' => $credencial['client_secret']
        );
        /**
         * Passo 2 - Solicita AccessToken 
         */
        $token = $this->authorizationRequest('token', $authorization);
        pr($token);

        $this->accessToken = $token['access_token'];
    }

    /** 
     * Simulação de GET de Resources
     */
    public function index() {
        $response = $this->request('GET');
        pr('INDEX');
        pr($response);
    }

    /** 
     * Simulação de GET de Resource por ID
     */
    public function view($id = null) {
        $this->id = $id;
        $response = $this->request('GET');
        pr('VIEW');
        pr($response);
    }

    /** 
     * Simulação de POST de Resource
     */
    public function add() {
        $this->id = null;
        $resource = array('id' => '4', 'name' => 'milho');
        $response = $this->request('POST', $resource);
        pr('ADD');
        pr($response);
    }

    /** 
     * Simulação de POST de Resource por ID (Update)
     */
    public function edit($id = null) {
        $this->id = $id;
        $resource = array('id' => $id, 'name' => 'beterraba');
        $response = $this->request('POST', $resource);
        pr('EDIT');
        pr($response);
    }

    /** 
     * Simulação de DELETE de Resource por ID
     */
    public function delete($id = null) {
        $this->id = $id;
        $response = $this->request('DELETE');
        pr('DELETE');
        pr($response);
    }

    /**
     * Faz as requisições para autorização no REST Server
     */
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

    /**
     * Faz as requisições para os recursos no REST Server
     */
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
            /**
             * Pode optar por informar o AccessToken aqui e retirar da $url
             */
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
