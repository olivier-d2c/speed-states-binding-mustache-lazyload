<?php

$prop = $_REQUEST['prop'] ?? 'default';
$uid = $_REQUEST['uid'] ?? 'SID-'.crc32($prop.time());
$title = ucfirst($prop);
$cuid = 'content-'.$uid;
$defaultColors = [
    'color' => '#aaa', 
    'background' => '#cccccc77',
    'h2-color' => '#333'
];

//that should load the variable directly into this file
//it contains replacer var so it must be after all ned vars are initialized
$scripted = null;
require_once($_SERVER["DOCUMENT_ROOT"].'/php/scripted.php');

switch($prop){
    case 'slider.flowers':
        $colors = [
            'color' => '#4caf50', 
            'background' => '#e8f5e977',
            'h2-color' => '#1b5e20'
        ];
        $listing = [
            [
                "num" => 1,
                "title" => "{$prop} #1",
                "src" => "/images/slider/flowers/1.webp"
            ],
            [
                "num" => 2,
                "title" => "{$prop} #2",
                "src" => "/images/slider/flowers/2.webp"
            ],
            [
                "num" => 3,
                "title" => "{$prop} #3",
                "src" => "/images/slider/flowers/3.webp"
            ],
            [
                "num" => 4,
                "title" => "{$prop} #4",
                "src" => "/images/slider/flowers/4.webp"
            ],
            [
                "num" => 5,
                "title" => "{$prop} #5",
                "src" => "/images/slider/flowers/5.webp"
            ]
        ];
        break;
    case 'slider.cats':
        $colors = [
            'color' => '#00bcd4', 
            'background' => '#e0f7fa77',
            'h2-color' => '#00838f'
        ];
        $listing = [
            [
                "num" => 1,
                "title" => "{$prop} #1",
                "src" => "/images/slider/cats/1.webp"
            ],
            [
                "num" => 2,
                "title" => "{$prop} #2",
                "src" => "/images/slider/cats/2.webp"
            ]
        ];
        break;
    case 'slider.dogs':
            $colors = [
                'color' => '#00bcd4', 
                'background' => '#e0f7fa77',
                'h2-color' => '#00838f'
            ];
            $listing = [
                [
                    "num" => 1,
                    "title" => "{$prop} #1",
                    "src" => "/images/slider/dogs/1.webp"
                ],
                [
                    "num" => 2,
                    "title" => "{$prop} #2",
                    "src" => "/images/slider/dogs/2.webp"
                ],
                [
                    "num" => 3,
                    "title" => "{$prop} #3",
                    "src" => "/images/slider/dogs/3.webp"
                ]
            ];
            break;    
    default:
        $listing = [];
        $colors = $defaultColors;
        break;        
}

//minor check
if(empty($colors)){
    $colors = $defaultColors;
}

$slider = [
    "cuid" => $cuid,
    "title" => "Some {$prop} Slider!",
    "listing" => $listing,
    "styles" =><<<STYLES
        
        /* our basic */
        
        #{$cuid}{
            color: {$colors['color']};
            padding: 10px;
            background: {$colors['background']};
        }
        #{$cuid} h2{
            font-size: 2rem;
            padding: 0;
            margin: 0 0 10px 0;
            color: {$colors['h2-color']};
        }
        #{$cuid} small{
            font-size: 0.8rem;
        }

        /* scoped slider style */

        #{$cuid} * {
            box-sizing: border-box;
        }
        #{$cuid} .slider {
            width: calc(100vw - (100vw - 100%));
            text-align: center;
            overflow: hidden;
            position: relative;
        }
        #{$cuid} .slides {
            display: flex;
            overflow-x: auto;
            scroll-snap-type: x mandatory;
            scroll-behavior: smooth;
            -webkit-overflow-scrolling: touch;
            position: relative;
        }
        #{$cuid} .slides::-webkit-scrollbar {
            width: 10px;
            height: 10px;
        }
        #{$cuid} .slides::-webkit-scrollbar-thumb {
            background: #ccc;
            border-radius: 5px;
            margin-top:5px;
        }
        #{$cuid} .slides::-webkit-scrollbar-track {
            background: transparent;
        }
        #{$cuid} .slides > div {
            scroll-snap-align: start;
            flex-shrink: 0;
            width: calc(100vw - (100vw - 100%));
            /*height: calc((100vw - (100vw - 100%)) / 1.3);*/
            height:100%;
            margin-right: 10px;
            border-radius: 10px;
            background: #eee;
            transform-origin: center center;
            transform: scale(1);
            transition: transform 0.5s;
            position: relative;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 100px;
        }
        #{$cuid} img {
            /*object-fit: cover;*/
            /*position: absolute;*/
            width: calc(100vw - (100vw - 100%));
            /*height: 100%;*/
            top: 0;
            left: 0;
            width: 100%;
            height: calc((100vw - (100vw - 100%)) / 1.3);
        }
        #{$cuid} .slider .sliding{
            position: absolute;
            display: flex;
            justify-content: center;
            align-items: center;
            left: 0;
            right: 0;
            bottom: 1.5rem;
        }
        #{$cuid} .slider .sliding > a {
            display: inline-flex;
            width: 1.5rem;
            height: 1.5rem;
            background: white;
            text-decoration: none;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            margin: 0 0.25rem;
            position: relative;
            opacity: 0.5;
        }
        #{$cuid} .slider .sliding > a:active {
            top: 1px;
        }
        #{$cuid} .slider .sliding > a.focus {
            background: #000;
            color: #333;
        }
        #{$cuid} .slider .sliding > a:focus{
            background: #333;
            color: #666;
        }
        @media only screen and (min-width: 480px) {
            #{$cuid} img {
                max-height: 400px;
                width: auto;
            }        
        }
        @supports (scroll-snap-type) {
            #{$cuid} .slider > a {
                display: none;
            }
        }

        
STYLES,
    "functions" => [
        "scripted" => $scripted
    ]
];

$json = json_encode($slider);

exit($json);

//EOF