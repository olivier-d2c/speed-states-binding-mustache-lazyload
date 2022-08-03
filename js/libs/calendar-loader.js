

//by loading this will ionject another one to load after
//they are related to the interest.sport which is loaded from the news compoent

//some docs to try things : https://www.npmjs.com/package/@uvarov.frontend/vanilla-calendar

/*
//@NOTES: to do an update it works like this:
    thecalendar.date.today = new Date('2022-01-25');
    thecalendar.settings.lang = 'en';
    thecalendar.settings.iso8601 = false;
    thecalendar.settings.selected.date = '2022-01-15';
    thecalendar.update();
*/


Appz().then(async (appz) => {

    //@IMPORTANT: 
    //remember that this script will remain and wont be reinjected 
    //thats why we need those gobal vars to make some cleanup 
    //before reinjecting a new calendar
    let lastObsId = null;
    let lastContainer = null;
    let lastProp = null;
    let isLoaded = false;
    //what state are we listening
    const state = 'interest.sport';
    //remove the container created with anode
    const del = (id) => {
        if(id !== null && id !== undefined){
            const el = document.getElementById(id);
            if(el !== null){
                el.remove();
            }
        }    
    }
    //will inject a vcalendar binders, 
    const addCalendar = async (calendar) => {
        //remove the previous observer if some
        if(lastObsId !== null && lastProp !== null){
            await appz.robsstates(lastProp, lastObsId);
        }
        //remove the previous calendar if some
        del(lastContainer); 
        //flag
        const prop = `vcalendar.${calendar}`;
        const container = 'vcalendar-' + Math.random().toString().replace('.', '');
        const mutator = `mutator-${container}`;
        //keep them for later cleanup
        //to mreove if we have multiple call
        lastContainer = container;
        lastProp = prop;
        //create the node
        await anode('interest-calendar', 'div', {id: container}, `
            <input type="hidden" value="${prop}" data-binders="@vcalendar.json.php?prop=${prop}&uid=${container}">
            <div class="container wrap">
                <div class="response">
                    <span>VCalendar ${calendar} Data:</span>
                    <div data-binded="${prop}"></div>        
                    <button class="clear" data-action="delete" data-prop="${prop}">clear state</button>
                </div>    
                <div class="text infos" id="${mutator}" data-binded="${prop}" data-templated="@vcalendar.html.php?prop=${prop}">
                    <div class="loading"></div>
                </div>
            </div>
        `);
        //before traversing to get the data-binders
        //will put an observer to know when we receive the data to set range data maybe if some
        lastObsId = await appz.obsstates(prop, async (obj, p) => {
            //that means its alredy there its just a come back from the state observer
            //but dont forget that we are async so an obj can change at any time
            if(obj === null){
                return;
            }
            //we have to wait for the calendar object is available
            //which depends on connection speed also
            const interval = setInterval(() => {
                if( typeof VanillaCalendar !== 'undefined'){
                    //we can stop
                    clearInterval(interval);
                    let settings = {}
                    if(obj !== null && typeof obj.settings === 'object'){
                        settings = {...obj.settings};
                    }
                    //load the calendar with all options
                    isLoaded = false;
                    loaded(settings, container);
                }
            }, 10);
        });    
        //traverse the scoped eelment
        await traverse(container);
        //debug
        //we are done return the container element id (NOT the element)
        return container;
    }
    //get the current state value
    let sport = await appz.gstates(state);
    //is our top element id scope
    let calendar = await addCalendar(sport);
    //since its lazy loaded 
    const loaded = (settings, id) => {
        //but this is tricky if we type very fast and we are async on a slow conn
        //it may try on old ones so we have to check if its the last one and is not loaded yet
        if(isLoaded){
            return;
        }
        const path = `#${id} .vanilla-calendar`;
        //the container may not be writed yet since we are in a pages->news->calndar template mode
        //and it will probably go in here before finishing writing his mustache template content
        //remember we are in full async/await mode
        //so will try untill its ready
        if(document.querySelector(path) === null){
            //lets retry untill its rendered, but check if it still the same calendar and not yet loaded
            if(id === lastContainer && !isLoaded){
                //non blocking or it will never be writed hehe!
                setTimeout(() => loaded(settings, id), 10);
            }    
            //and we go away
            return;
        }
        //we need the absolute final state from the 3 of those to init it
        if(id === calendar && id === lastContainer){
            //must be {setting: { ... }}
            (new VanillaCalendar(path, {settings})).init();
            console.log(`CALENDAR-IS-INIT[${id}]:`, {id, path, calendar, lastContainer, settings});
            isLoaded = true
        }    
    }
    //some obserer on is our state have chaned and should we remove and reinject another one
    //dont forget that is a second level script mean js is : page -> news -> calendar
    const list = ['soccer', 'foot'];
    await appz.obsstates(state, async (s, p) => {
        if(list.indexOf(s) !== -1){
            //if we are here the libs were alerady there for sure
            //so no check on libs dependencies exitence needed
            //reinit those values
            calendar = await addCalendar(s);
        }else{
            //remove the calendar
            del(calendar);     
        }
    })
    //just some visual helper
    console.log(`CALENDAR-LOADER-EXTERNSCRIPT[${sport}]:`, {calendar});
});    

//EOF