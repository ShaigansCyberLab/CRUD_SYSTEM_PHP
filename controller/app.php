<?php

define(
    'APP_NAME',
    dirname(__DIR__) . '/'
);

require_once __DIR__ . '/functions/config.php';
require_once __DIR__ . '/functions/GlossaryTerm.class.php';
require_once __DIR__ . '/functions/dataprovider.class.php';
require_once __DIR__ . '/functions/data.class.php';
require_once __DIR__ . '/functions/filedataprovider.class.php';
require_once __DIR__ . '/functions/mysqldataprovider.class.php';
require_once __DIR__ . '/functions/routing_functions.php';

Data::initialize(
    new MySqlDataProvider(CONFIG['db'])
);
