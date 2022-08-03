<?php

$prop = $_REQUEST['prop'] ?? 'default';
$scopedcss = str_replace('.', '-', $prop);

$template =<<<HTML
    {{#{$prop}}}
        <style>
            .{$scopedcss}{
                padding: 10px;
                background: #f0f4c377;
            }
            .{$scopedcss} .calendar-container{
                display: flex;
                justify-content: center;
            }    
            .{$scopedcss} h2{
                font-size: 2rem;
                color: #afb42b;
                padding: 0;
                margin: 0 0 10px 0;
            }
            .{$scopedcss} h2 small{
                font-size: 0.8rem;
            }
            .{$scopedcss} .vanilla-calendar {
                padding: 10px;
                margin: 0;
                width: 100%;
                box-sizing: border-box;
            }
            .{$scopedcss} .vanilla-calendar button{
                margin: 0;
                border:1px solid #ccc;
            }
            .{$scopedcss} .vanilla-calendar-day__btn {
                font-size: 0.75rem;
                line-height: 0.75rem;
                font-weight: normal;
                width: 2rem;
                height: 2rem;
                margin: 0.25rem 0 !important;
                border: 1px solid #ccc;
            }
            .{$scopedcss} .vanilla-calendar-month,
            .{$scopedcss} .vanilla-calendar-year {
                font-size: 1rem;
                line-height: 1rem;
                font-weight: normal;
                color: #333;
                border-radius: 5px;
                padding: 5px;
                margin: 5px !important;
            }
            .{$scopedcss} .vanilla-calendar-week__day{
                font-size: 0.7rem;
                line-height: 1rem;
                font-weight: normal;
                width: unset;
                height: 2rem;
            }
            .{$scopedcss} .vanilla-calendar-arrow{
                right: unset;
                margin: 10px;
                height: 1.5rem;
                width: 1.5rem;
                border: 1px solid #ccc;
            }
            .{$scopedcss} .vanilla-calendar-arrow::before {
                content: ">";
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
                height: unset;
                background-color: unset; 
                -webkit-transform: unset; 
                -ms-transform: unset;
                transform: unset; 
                color: #000;
                bottom: 0;
                right: 0;
                font-size: 1rem;
                line-height: 1rem;
                display: flex;
                justify-content: center;
                align-items: center;
            }
            .{$scopedcss} .vanilla-calendar-arrow::after {
                content: "";
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
                height: unset;
                background-color: unset; 
                -webkit-transform: unset; 
                -ms-transform: unset;
                transform: unset; 
                color: #000;
                bottom: 0;
                right: 0;
                font-size: 1rem;
                line-height: 1rem;
                display: flex;
                justify-content: center;
                align-items: center;
            }
            .{$scopedcss} .vanilla-calendar-arrow:hover::before, 
            .{$scopedcss} .vanilla-calendar-arrow:hover::after{
                background: unset;
            }
            @media only screen and (min-width: 480px) {
                .{$scopedcss} .vanilla-calendar{
                    width: 400px;
                }
            }    
            
        </style>
        <div class="{$scopedcss}">
            <h2>Calendar: <small>{$prop}</small></h2>
            {{#{$prop}.functions.scripted}}{{/{$prop}.functions.scripted}}
            <div class="calendar-container">
                <div class="vanilla-calendar"></div>
            </div>    
        </div>
    {{/{$prop}}}    
HTML;

exit($template);

//EOF