<?php

namespace distantnative\Retour\Retour;

require dirname(__DIR) . '/vendor/autoload.php';

load([
    'distantnative\\retour\\retour'    => 'models/Retour.php',
    'distantnative\\retour\\log'       => 'models/Log.php',
    'distantnative\\retour\\logs'      => 'models/Logs.php',
    'distantnative\\retour\\redirects' => 'models/Redirects.php',
    'distantnative\\retour\\stats'     => 'models/Stats.php',
    'distantnative\\retour\\system'    => 'models/System.php'
], dirname(__DIR__));

require __DIR__ . '/TestCase.php';