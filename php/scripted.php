<?php

//this is just some repetitive scripted mustache functions task
//to load js/style/files for injection
//there are replacer in that file 
//so it must be require after the variable are initiated

$scripted =<<<JS
            
    //this will run right away and render it too at the end
    //we dont need to wait anything, it will be the first runner script
    if(text.indexOf('<script>')  !== -1){
        const n = 'scripted-inner-{$uid}';
        let script = text.replace('<script>', '').replace('</script>', '');
        //maybe we have some replacer mustache in the script too like a scope id maybe
        script = render(script)
        //this one will be created right now and will be there at the next scipter target
        //so it wontt duplicate
        if(document.getElementById(n) === null){
            //console.log("SCRIPTED-INNER-INJECTION[{$prop}.script]:", n, script);
            const sc = cnode("script", {id: n});
            sc.appendChild(document.createTextNode(script)); 
            const csc = document.getElementById("{$uid}");
            //dont forget everything is async
            if(csc !== null){
                csc.appendChild(sc);        
            }
        }    
    }
    //that will be later added
    Appz().then(async (appz) => {
        //common use
        let sc = null;    
        //injected csss files if some needed
        const ncsss = 'csss-{$uid}';
        const csss = await appz.gstates('{$prop}.csss'); 
        if(csss !== null && typeof csss === 'object'){
            let count = 0;
            csss.forEach((item) => {
                count++;
                //is it a standlone mean only 1 copy for the entire site or reinject it
                let standalone = false;
                if(item.standalone ?? false){
                    //will need to check if we already have it injected by any components
                    if(document.querySelector('link[href="' + item.href + '"]')){
                        console.warn("CSS ALREADY PRESENT:", item.href);
                        standalone = true;
                    }        
                }
                let nncsss = 'css-' + ncsss + '-' + count;
                //we want to inject it once only no more needed for that compoenent
                if(!standalone && document.getElementById(nncsss) === null){
                    //console.log("SCRIPTED-CSS-INJECTION[{$prop}.csss]:", nncsss, item);
                    sc = cnode(
                        "link", 
                        {
                            id: nncsss, 
                            href: item.href,
                            rel: "stylesheet"
                        }, 
                        {
                            iscsssed: true
                        }
                    );        
                    //@NOTES: 
                    //this one have no scope so will put it in the body end
                    //since its a generic library
                    document.getElementById("body").appendChild(sc);
                }    
            });
        }   
        //script id to check if it was already injected for that specific component
        const n = 'scripted-{$uid}';
        //do we have extern dependencies libs like calendar-loader.js test
        //that one should be an array in order of injection
        const scripts = await appz.gstates('{$prop}.scripts'); 
        if(scripts !== null && typeof scripts === 'object'){
            let count = 0;
            scripts.forEach((item) => {
                count++;
                //is it a standlone mean only 1 copy for the entire site or reinject it
                let standalone = false;
                if(item.standalone ?? false){
                    //will need to check if we already have it injected by any components
                    if(document.querySelector('script[src="' + item.src + '"]')){
                        console.warn("SCRIPT ALREADY PRESENT:", item.src);
                        standalone = true;
                    }        
                }
                let nn = 'src-' + n + '-' + count;
                //we want to inject it once only no more needed for that compoenent
                if(!standalone && document.getElementById(nn) === null){
                    //console.log("SCRIPTED-SCRIPTS-INJECTION[{$prop}.scripts]:", nn, item);
                    sc = cnode(
                        "script", 
                        {
                            id: nn, 
                            src: item.src, 
                            async: item.async ?? true
                        }, 
                        {
                            isscripts: true
                        }
                    );        
                    //@NOTES: 
                    //this one have no scope so will put it in the body end
                    //since its a generic library
                    document.getElementById("body").appendChild(sc);
                }    
            });
        }   
        //we need to await here or if we have multiple scripted
        //they will all be to null
        const script = await appz.gstates('{$prop}.script');
        if(script !== null && document.getElementById(n) === null){
            //console.log("SCRIPTED-INJECTION[{$prop}.script]:", n, script);
            sc = cnode("script", {id: n});
            sc.appendChild(document.createTextNode(script)); 
            const csc = document.getElementById("{$uid}");
            //dont forget everything is async
            if(csc !== null){
                csc.appendChild(sc);        
            }
        }    
    }); 
    //we dont want to show the inner script
    return render('')

JS;


//EOF