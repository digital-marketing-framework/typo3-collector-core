<?php

if (!defined('TYPO3')) {
    die('Access denied.');
}

$ll = 'LLL:EXT:digitalmarketingframework_collector/Resources/Private/Language/locallang_db.xlf:';
$readOnly = true;

$GLOBALS['TCA']['tx_digitalmarketingframework_domain_model_invalidrequest'] = [
    'ctrl' => [
        'label' => 'tstamp',
        'tstamp' => 'tstamp',
        'title' => $ll . 'tx_digitalmarketingframework_domain_model_invalidrequest',
        'iconfile' => 'EXT:digitalmarketingframework_collector/Resources/Public/Icons/InvalidRequest.svg',
        'adminOnly' => true,
        'default_sortby' => 'tstamp',
    ],
    'interface' => [
        'showRecordFieldList' => 'tstamp,identifier,count',
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
            'label' => $ll . 'tx_digitalmarketingframework_domain_model_invalidrequest.tstamp',
            'config' => [
                'type' => 'input',
                'size' => '13',
                'eval' => 'datetime',
                'readOnly' => $readOnly,
                'renderType' => 'inputDateTime',
            ],
        ],
        'identifier' => [
            'label' => $ll . 'tx_digitalmarketingframework_domain_model_invalidrequest.identifier',
            'config' => [
                'type' => 'input',
                'size' => '20',
                'readOnly' => $readOnly,
            ],
        ],
        'count' => [
            'label' => $ll . 'tx_digitalmarketingframework_domain_model_invalidrequest.count',
            'config' => [
                'type' => 'input',
                'size' => '20',
                'eval' => 'int',
                'readOnly' => $readOnly,
            ],
        ],
    ],
];
