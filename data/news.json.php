<?php
$prop = $_REQUEST['prop'] ?? 'default';
$title = ucfirst($prop);
$uid = $_REQUEST['uid'] ?? 'SID-'.crc32($prop.time());
$cuid = 'content-'.$uid;

//that should load the variable directly into this file
//it contains replacer var so it must be after all ned vars are initialized
$scripted = null;
require_once($_SERVER["DOCUMENT_ROOT"].'/php/scripted.php');

$colors = $prop === 'news.soccer' ? [
    'color' => '#673ab7', 
    'background' => '#ede7f677',
    'h2-color' => '#311b92'
] : [
    'color' => '#1976d2', 
    'background' => '#e3f2fd77',
    'h2-color' => '#0d47a1'
];

$news = [
    "cuid" => $cuid,
    "title" => "Some {$title} News!",
    "listing" => [
        [
            "title" => "Good {$title} News",
            "date" => "01-01-1970",
            "content" => "At vero eos et accusamus et iusto odio dignissimos ducimus qui blanditiis praesentium voluptatum deleniti atque corrupti quos dolores et quas molestias excepturi sint occaecati cupiditate non provident, similique sunt in culpa qui officia deserunt mollitia animi, id est laborum et dolorum fuga. Et harum quidem rerum facilis est et expedita distinctio."
        ],
        [
            "title" => "Bad {$title} News",
            "date" => "02-01-1970",
            "content" => "Nam libero tempore, cum soluta nobis est eligendi optio cumque nihil impedit quo minus id quod maxime placeat facere possimus, omnis voluptas assumenda est, omnis dolor repellendus."
        ]
    ],
    "styles" =><<<STYLES
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
        #{$cuid} h3{
            padding: 20px 0 0 0;
            margin: 10px 0 0 0;
            width: unset;
            text-align: left;
            border-top: 1px dotted #ccc;
        }
        #{$cuid} ul{
            margin: 0;
            padding: 0;
            list-style: none;
        }
        #{$cuid} li{
            padding: 5px 0;
            font-size: 1rem;
            font-weight: normal;
            cursor: pointer;
        }
        #{$cuid} a{
            color: #673ab7;
            text-decoration: none;
        }
STYLES,
    "script" =><<<JS

            //set timeout is essential to stack the call,
            //since the element is not there yet
            //because the functions:scripted inserting that script 
            //will pass before doing is render(text)

            setTimeout(async () => {
                const find = () => {
                    document.querySelectorAll("#{$cuid} LI").forEach((el) => {
                        el.onclick = onClickable;
                    });    
                }
                //our parent container of the news which use the template
                let el = document.getElementById('{$cuid}');
                //minor check because typing text is faster then the net
                if(el === null){
                    return;
                }
                //we want the parent
                el = el.parentElement;
                //set an observer in case content was removed and put back, 
                //we need to remap the event to it
                const observer = new MutationObserver((ev) => {
                    ev.forEach((mutation) => {
                        //check if it was cleared so we can remove the event from it
                        //just for debug
                        [...mutation.removedNodes].forEach((entry) => {
                            if(entry.id === '{$cuid}'){
                                console.log('MUTATION-REMOVEDNODES[{$cuid}]');     
                            }
                        });
                        //check if it was readded like a undo states, to put the event back
                        [...mutation.addedNodes].forEach((entry) => {
                            if(entry.id === '{$cuid}'){
                                console.log('MUTATION-ADDEDNODES[{$cuid}]');             
                                find();
                            }
                        });
                    })
                });
                // Start observing the target node for configured mutations
                observer.observe(el, { childList: true });
                //stop observing
                //observer.disconnect();
                console.log("SCRIPT-NEWS:", el, observer);
                //apply the event to the LI
                find();
            });
           
JS,
    "scripts" => [
        [
            "src" =>  'js/libs/calendar-loader.js',
            //do we need only 1 copy for the entire site 
            //or reload it each time
            "standalone" => true,
            "async" => true
        ]
    ],   
    "csss" => [
        [
            "href" =>  'css/libs/calendar-loader.css',
            //do we need only 1 copy for the entire site 
            //or reload it each time
            "standalone" => true
        ]
    ],      
    "functions" => [
        "scripted" => $scripted
    ]
];

$json = json_encode($news);

exit($json);

//EOF