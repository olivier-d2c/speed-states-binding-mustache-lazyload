<?php

$prop = $_REQUEST['prop'] ?? 'default';
$interval = $_REQUEST['interval'] ?? 5000;

$template =<<<HTML

    <!-- a template to be used by data-templated -->
    <!-- ref: https://css-tricks.com/can-get-pretty-far-making-slider-just-html-css/ -->
    {{#{$prop}}}
        <!-- parse for sscript -->
    {{#{$prop}.functions.scripted}}
        <script>

            //@IMPORTANT: dont forget we have to backslash the $ sign in the js we are inside a php string

            //set an automatic sliding                
            setTimeout(async () => {
                //this will be injected by the functions::scripted from the json data
                //to access the element inside of it only 
                const scopeElementId = "{{{$prop}.cuid}}";
                console.log("SCOPEELEMENTID:", scopeElementId);
                //set timeout is essential to stack the call,
                //since the element is not there yet
                //because the functions:scripted inserting that script 
                //will pass before doing is render(text)
                //get all of them in order of appearence
                let stacked = false; 
                let stack = [];
                const automatic = (ev) => {
                    try{
                        ev.preventDefault();
                        const el =  ev.target;
                        const num = parseInt(el.textContent) ?? 1;
                        const slide = el.getAttribute('href').replace('#', '');
                        const sel = document.getElementById(slide);
                        //minor check because typing text is faster then the net
                        if(sel === null){
                            return;
                        }
                        //so will do a fake scroll
                        sel.parentElement.scrollLeft = (num - 1) * gwidth(sel);
                        //remove theother ficus and put that one
                        document.querySelectorAll(`#\${scopeElementId} .sliding A`).forEach((e) => {
                            e.classList.remove("focus");    
                        })
                        el.classList.add("focus");
                        //console.log("SLIDE:", slide, ev);
                    }catch(e){
                        //stop the automation, the slider is probably gone with clear state
                        console.error(e);
                    }    
                }
                const stacking = () => {
                    stack = [];
                    document.querySelectorAll(`#\${scopeElementId} .sliding A`).forEach((el) => {
                        //we will change the default behavior
                        stack.push(el);
                        el.onclick = automatic;
                    })
                    //swap the last one to the end, 
                    //since we start at the second one
                    stack.push(stack.shift());
                    //flag
                    stacked = true;
                }
                //make it change in X seconds    
                setInterval(() => {
                    //does it still exist
                    if(document.getElementById(scopeElementId)){
                        if(!stacked){
                            stacking();
                        }
                        const el = stack.shift();
                        stack.push(el);
                        el.click();
                    }else{
                        //if the lement is recreated it will re stack them
                        stacked = false;
                    }    
                }, {$interval});

                //@TODO: do a scroll bar observer to place the good A.focus
                //
                //

            });
            
        </script>  
    {{/{$prop}.functions.scripted}}
    {{#{$prop}.styles}}
        <style>
        {{{{$prop}.styles}}}
        </style>  
    {{/{$prop}.styles}}  
    <div id="{{{$prop}.cuid}}">  
        <h2>Slider: <small>{$prop}</small></h2>
        <div class="slider">
        <!-- the container slides -->
        <div class="slides">
            {{#{$prop}.listing}}
            <div id="{{{$prop}.cuid}}-slide-{{num}}">
            <img src="{{src}}" title="{{title}}" alt="{{title}}">
            </div>
            {{/{$prop}.listing}}
        </div>
        <!-- the number sliding too -->
        <div class="sliding">
            {{#{$prop}.listing}}
            <a href="#{{{$prop}.cuid}}-slide-{{num}}">{{num}}</a>
            {{/{$prop}.listing}}
        </div>
        </div>
    </div>  
    {{/{$prop}}}

HTML;


exit($template);

//EOF