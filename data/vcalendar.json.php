<?php
$prop = $_REQUEST['prop'] ?? 'default';
$title = ucfirst($prop);
$uid = $_REQUEST['uid'] ?? 'SID-'.crc32($prop.time());
$cuid = 'content-'.$uid;

switch($prop){
    case 'vcalendar.soccer':
        $settings = [
            'selection' => [
                'day' => 'multiple'
            ],
            'selected' => [
                'dates' =>  ['2022-08-11', '2022-08-12', '2022-08-13'],
                'month' => 7,
                'year' => 2022
            ]
        ];
        break;
    case 'vcalendar.foot':
        $settings = [
            'selection' => [
                'day' => 'multiple'
            ],
            'selected' => [
                'dates' =>  ['2022-08-09', '2022-08-10'],
                'month' => 7,
                'year' => 2022
            ]
        ];
        break;
    default:
        $settings = [];
        break;        
}

//that should load the variable directly into this file
//it contains replacer var so it must be after all ned vars are initialized
$scripted = null;
require_once($_SERVER["DOCUMENT_ROOT"].'/php/scripted.php');

$vcalendar = [
    "cuid" => $cuid,
    "settings" => $settings,
    "scripts" => [
        [
            "src" =>  'js/libs/vanilla-calendar.min.js',
            //do we need only 1 copy for the entire site 
            //or reload it each time
            "standalone" => true,
            "async" => true
        ]
    ],   
    "csss" => [
        [
            "href" =>  'css/libs/vanilla-calendar.min.css',
            //do we need only 1 copy for the entire site 
            //or reload it each time
            "standalone" => true
        ]
    ],      
    "functions" => [
        "scripted" => $scripted
    ]
];

$json = json_encode($vcalendar);

exit($json);

//EOF