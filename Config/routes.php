<?php
Router::connect('/api/v1/oauth/:action/*', array('controller' => 'OAuth', 'plugin' => 'o_auth', 'ext' => 'json'));

/**
 * Exemplos de rotas padrão para REST Server
 */
Router::connect(
    '/api/v1/:controller', 
    array('action' => 'index', '[method]' => 'GET', 'ext' => 'json', 'plugin' => 'o_auth') 
);
Router::connect(
    '/api/v1/:controller/:id', 
    array('action' => 'view', '[method]' => 'GET', 'ext' => 'json', 'plugin' => 'o_auth'), 
    array('id' => '[0-9]+', 'pass' => array('id'))
);
Router::connect(
    '/api/v1/:controller', 
    array('action' => 'add', '[method]' => 'POST', 'ext' => 'json', 'plugin' => 'o_auth')
);
Router::connect(
    '/api/v1/:controller/:id', 
    array('action' => 'edit', '[method]' => 'POST', 'ext' => 'json', 'plugin' => 'o_auth'), 
    array('pass' => array('id'))
);
Router::connect(
    '/api/v1/:controller/:id', 
    array('action' => 'delete', '[method]' => 'DELETE', 'ext' => 'json', 'plugin' => 'o_auth'), 
    array('pass' => array('id'))
);

/**
 * Rota para REST Client de exemplo
 */
Router::connect('/client/:action/*', array('controller' => 'Client', 'plugin' => 'o_auth'));


