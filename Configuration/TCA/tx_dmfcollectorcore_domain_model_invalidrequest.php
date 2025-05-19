<?php

defined('TYPO3') || exit('Access denied.');

$ll = 'LLL:EXT:dmf_collector_core/Resources/Private/Language/locallang_db.xlf:';
$readOnly = true;

$GLOBALS['TCA']['tx_dmfcollectorcore_domain_model_invalidrequest'] = [
    'ctrl' => [
        'label' => 'tstamp',
        'tstamp' => 'tstamp',
        'title' => $ll . 'tx_dmfcollectorcore_domain_model_invalidrequest',
        'iconfile' => 'EXT:dmf_collector_core/Resources/Public/Icons/InvalidRequest.svg',
        'adminOnly' => true,
        'default_sortby' => 'tstamp',
    ],
    'types' => [
        '0' => [
            'showitem' => 'tstamp,identifier,count',
        ],
    ],
    'palettes' => [
        '0' => ['showitem' => 'tstamp,identifier,count'],
    ],
    'columns' => [
        'tstamp' => [
            'label' => $ll . 'tx_dmfcollectorcore_domain_model_invalidrequest.tstamp',
            'config' => [
                'type' => 'datetime',
                'size' => '13',
                'readOnly' => $readOnly,
            ],
        ],
        'identifier' => [
            'label' => $ll . 'tx_dmfcollectorcore_domain_model_invalidrequest.identifier',
            'config' => [
                'type' => 'input',
                'size' => '20',
                'readOnly' => $readOnly,
            ],
        ],
        'count' => [
            'label' => $ll . 'tx_dmfcollectorcore_domain_model_invalidrequest.count',
            'config' => [
                'type' => 'number',
                'size' => '20',
                'readOnly' => $readOnly,
            ],
        ],
    ],
];
