<?php

App::uses('OAuthAppModel', 'OAuth.Model');

/**
 * AccessToken Model
 *
 * @property Client $Client
 * @property User $User
 */
class AccessToken extends OAuthAppModel {

/**
 * Primary key field
 *
 * @var string
 */
	public $primaryKey = 'oauth_token';

/**
 * Display field
 *
 * @var string
 */
	public $displayField = 'oauth_token';

/**
 * Validation rules
 *
 * @var array
 */
	public $validate = array(
		'oauth_token' => array(
			'notEmpty' => array(
				'rule' => array('notEmpty'),
			),
			'isUnique' => array(
				'rule' => array('isUnique'),
			)
		),
		'client_id' => array(
			'notEmpty' => array(
				'rule' => array('notEmpty'),
			),
		),
		'user_id' => array(
			'notEmpty' => array(
				'rule' => array('notEmpty'),
			),
		),
		'expires' => array(
			'numeric' => array(
				'rule' => array('numeric'),
			),
		),
	);

/**
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = array(
		'Client' => array(
			'className' => 'OAuth.Client',
			'foreignKey' => 'client_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);

}
