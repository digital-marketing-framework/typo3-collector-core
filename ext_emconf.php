<?php

$EM_CONF[$_EXTKEY] = [
    'title' => 'Digital Marketing Framework - Collector',
    'description' => 'Fetch user data from different external systems',
    'category' => 'be',
    'author_email' => 'info@mediatis.de',
    'author_company' => 'Mediatis AG',
    'state' => 'stable',
    'version' => '2.1.1',
    'constraints' => [
        'depends' => [
            'typo3' => '11.5.0-12.4.99',
            'dmf_core' => '2.1.11-2.99.99',
        ],
        'conflicts' => [
        ],
        'suggests' => [
        ],
    ],
];
