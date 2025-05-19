<?php

$EM_CONF[$_EXTKEY] = [
    'title' => 'Anyrel - Collector',
    'description' => 'Fetch user data from different external systems',
    'category' => 'be',
    'author_email' => 'info@mediatis.de',
    'author_company' => 'Mediatis AG',
    'state' => 'stable',
    'version' => '2.2.1',
    'constraints' => [
        'depends' => [
            'typo3' => '12.4.0-13.4.99',
            'dmf_core' => '2.2.5-2.99.99',
        ],
        'conflicts' => [
        ],
        'suggests' => [
        ],
    ],
];
