<?php

$prop = $_REQUEST['prop'] ?? 'default';

$template =<<<HTML

  <!-- a template to be used by data-templated -->
  {{#{$prop}}}
    {{#{$prop}.script}}
      {{#{$prop}.functions.scripted}}{{/{$prop}.functions.scripted}}
    {{/{$prop}.script}}  
      <!-- parse for sscript -->
    {{#{$prop}.functions.scripted}}
      <script>
        (async () => {
          //this will be injected by the functions::scripted from the json data
          //to access the element inside of it only 
          const scopeElementId = "{{{$prop}.cuid}}"
          console.log("SCOPEELEMENTID:", scopeElementId)

        })()
      </script>  
    {{/{$prop}.functions.scripted}}
    {{#{$prop}.styles}}
      <style>
        {{{$prop}.styles}}
      </style>  
    {{/{$prop}.styles}}  
    <div id="{{{$prop}.cuid}}">  
      <h2>News:</h2>
      <h4>{{{{$prop}.title}}}</4>
      <ul>
        {{#{$prop}.listing}}
          <li>
            <h3>{{{title}}}</h3>
            <p>{{{date}}}</p>
            <p>{{{content}}}</p>
          </li>
        {{/{$prop}.listing}}
      </ul>
    </div>  
  {{/{$prop}}}

HTML;

exit($template);

//EOF