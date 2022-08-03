# js-php-mustache-states-client-server

First Draft.

GOALS:

1. make everything faster for page speed.
2. must be totally non-blocking.
3. have a States manager accessible across the page.
4. have a template that could be render on server/client side.
5. lazely inject style/js on demand.
6. have a before/after render javascript catcher to manipulate the rendered template.
7. binding, double-binding, binding listener on html element and javascript on states mutation.
8. load data on demand (binders) in 2 different ways: page, javascript.
9. garbage collector of element/listener/states cleanup.
10. globally can have a states view in console.
11. scoped js on the ondemand element.
12. scoped style on the ondemand element.
13. manipulate template/states in php on the page.
14. manipulate template/states in php on the data loader.
15. manipulate template/states in php on the template loader.
16. stack priorities element/js.
17. make a global js containing all utilities commonly used by all elements/js.



TODOS:

1. import script from outside like whatever.js (done).
2. inject data-binder from a element content instead of an input value without it being base64 encoded (done).
3. lazyload css fro outside like whatever.css.

