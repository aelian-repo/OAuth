<?php

Router::connect('/oauth/:action/*', array('controller' => 'OAuth', 'plugin' => 'o_auth', '[method]' => 'GET', 'ext' => 'json'));
