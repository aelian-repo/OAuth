<?php

App::uses('AppController', 'Controller');

App::import('Vendor', 'OAuth.OAuth2', array('file' => 'oauth2-php' . DS . 'lib' . DS . 'OAuth2.php'));
App::import('Vendor', 'OAuth.IOAuth2Storage', array('file' => 'oauth2-php' . DS . 'lib' . DS . 'IOAuth2Storage.php'));
App::import('Vendor', 'OAuth.IOAuth2GrantCode', array('file' => 'oauth2-php' . DS . 'lib' . DS . 'IOAuth2GrantCode.php'));
App::import('Vendor', 'OAuth.IOAuth2RefreshTokens', array('file' => 'oauth2-php' . DS . 'lib' . DS . 'IOAuth2RefreshTokens.php'));

/**
 * Description of OAuthAppController
 *
 * @author Thom
 */
class OAuthAppController extends AppController {
	//put your code here
}
