<?php

$tasks = array();

$tasks['build'] = array(
    'required' => array('blah1', 'blah2'),
);

$tasks['chmod'] = array(
    'command' => 'chmod',
    'file'    => __DIR__ . '/bin/daedalus',
    'mode'    => '+x',
);

$daedalus = array(
    'daedalus' => array(
        'config' => array(
            'cache' => __DIR__ . '/build/cache'
        ),
        'tasks' => $tasks,
    ),
);

return $daedalus;
