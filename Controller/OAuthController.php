<?php

/**
 * CakePHP OAuth Server Plugin
 *
 * This is an example controller providing the necessary endpoints
 *
 * @author Thom Seddon <thom@seddonmedia.co.uk>
 * @see https://github.com/thomseddon/cakephp-oauth-server
 *
 */

App::uses('OAuthAppController', 'OAuth.Controller');

/**
 * OAuthController
 *
 */
class OAuthController extends OAuthAppController {

	public $components = array('OAuth.OAuth', 'Auth', 'Session', 'Security');

/**
 * beforeFilter
 *
 */
	public function beforeFilter() {
		parent::beforeFilter();
		$this->Auth->allow($this->OAuth->allowedActions);
	}

/**
 * Inclui novo client 
 *  
 */	
	public function add($userId = null) {
        $client = $this->OAuth->Client->add(
			array('Client' => array('redirect_uri' => 'localhost', 'user_id' => $userId))
		);
        pr($client);
        exit;
	}
	
/**
 * Example Authorize Endpoint
 *
 * Send users here first for authorization_code grant mechanism
 *
 * Required params (GET or POST):
 *	- response_type = code
 *	- client_id
 *	- redirect_url
 *
 */
	public function authorize() {
		$authorization = false;
		// Clickjacking prevention (supported by IE8+, FF3.6.9+, Opera10.5+, Safari4+, Chrome 4.1.249.1042+)
		$this->OAuth->setVariable('auth_code_lifetime', 60);
		$this->response->header('X-Frame-Options: DENY');
		try {
			$OAuthParams = $this->OAuth->getAuthorizeParams();
		} catch (Exception $e){
			$e->sendHttpResponse();
		}
		if (!empty($OAuthParams)) {
			$clientCredencials = $this->OAuth->checkClientCredentials($OAuthParams['client_id']);
			list($redirectUri, $authorization) = $this->OAuth->getAuthResult($clientCredencials, $clientCredencials['user_id'], $OAuthParams);
			if (!empty($authorization['query']['code'])) {
				$authorization = $authorization['query'];
			}
		}

		$this->set(array('authorization' => $authorization, '_serialize' => 'authorization'));
	}

/**
 * Example Token Endpoint - this is where clients can retrieve an access token
 *
 * Grant types and parameters:
 * 1) authorization_code - exchange code for token
 *	- code
 *	- client_id
 *	- client_secret
 *
 * 2) refresh_token - exchange refresh_token for token
 *	- refresh_token
 *	- client_id
 *	- client_secret
 *
 * 3) password - exchange raw details for token
 *	- username
 *	- password
 *	- client_id
 *	- client_secret
 *
 */
	public function token() {
		$this->autoRender = false;
		$this->OAuth->setVariable('access_token_lifetime', 60);
		try {
			$this->OAuth->grantAccessToken();
		} catch (OAuth2ServerException $e) {
			$e->sendHttpResponse();
		}
	}

}
