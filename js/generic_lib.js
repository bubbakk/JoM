/*
 * Function: jsJOMlib__get_e_commerce_bullshit
 * Useful sentence generator
 *
 * Thank you:
 *   <http://www.dack.com/web/bullshit.html>
 */
function jsJOMlib__get_e_commerce_bullshit() {

    var max1 = 59;
    var max2 = 64;
    var max3 = 43;

    index1 = Math.round(Math.random() * max1);
    index2 = Math.round(Math.random() * max2);
    index3 = Math.round(Math.random() * max3);

    array1 = new Array("implement", "utilize", "integrate", "streamline", "optimize", "evolve", "transform", "embrace",
    "enable", "orchestrate", "leverage", "reinvent", "aggregate", "architect", "enhance", "incentivize", "morph", "empower",
    "envisioneer", "monetize", "harness", "facilitate", "seize", "disintermediate", "synergize", "strategize", "deploy",
    "brand", "grow", "target", "syndicate", "synthesize", "deliver", "mesh", "incubate", "engage", "maximize", "benchmark",
    "expedite", "reintermediate", "whiteboard", "visualize", "repurpose", "innovate", "scale", "unleash", "drive", "extend",
    "engineer", "revolutionize", "generate", "exploit", "transition", "e-enable", "iterate", "cultivate", "matrix",
    "productize", "redefine",
    "recontextualize");

    array2 = new Array("clicks-and-mortar", "value-added", "vertical", "proactive", "robust", "revolutionary", "scalable",
    "leading-edge", "innovative", "intuitive", "strategic", "e-business", "mission-critical", "sticky", "one-to-one",
    "24/7", "end-to-end", "global", "B2B", "B2C", "granular", "frictionless", "virtual", "viral", "dynamic", "24/365",
    "best-of-breed", "killer", "magnetic", "bleeding-edge", "web-enabled", "interactive", "dot-com", "sexy", "back-end",
    "real-time", "efficient", "front-end", "distributed", "seamless", "extensible", "turn-key", "world-class",
    "open-source", "cross-platform", "cross-media", "synergistic", "bricks-and-clicks", "out-of-the-box", "enterprise",
    "integrated", "impactful", "wireless", "transparent", "next-generation", "cutting-edge", "user-centric", "visionary",
    "customized", "ubiquitous", "plug-and-play", "collaborative", "compelling", "holistic", "rich");

    array3 = new Array("synergies", "web-readiness", "paradigms", "markets", "partnerships", "infrastructures", "platforms",
    "initiatives", "channels", "eyeballs", "communities", "ROI", "solutions", "e-tailers", "e-services", "action-items",
    "portals", "niches", "technologies", "content", "vortals", "supply-chains", "convergence", "relationships",
    "architectures", "interfaces", "e-markets", "e-commerce", "systems", "bandwidth", "infomediaries", "models",
    "mindshare", "deliverables", "users", "schemas", "networks", "applications", "metrics", "e-business", "functionalities",
    "experiences", "web services", "methodologies");

    index1 = Math.round(Math.random() * max1);
    index2 = Math.round(Math.random() * max2);
    index3 = Math.round(Math.random() * max3);

    return array1[index1] + " " + array2[index2] + " " + array3[index3];
}


/*
   Function: jsJOMlib__animate_opacity
   Animate opacity transitions with useful addictions such as show/hide and callbacks

   Parameters:
     target - jQuery target
     opacity_val - opacity to be applied
     callback - optional callback
 */
function jsJOMlib__animate_opacity(target, opacity_val, callback)
{
    if ( opacity_val > 0 ) target.show();

    if ( callback===undefined ) {
        target.animate({
            opacity: opacity_val,
        }, 500, function(){
            if ( opacity_val == 0 ) target.hide();
        });
    }
    else {
        target.animate({
            opacity: opacity_val,
        }, 500, function(){
            callback();
            if ( opacity_val == 0 ) target.hide();
        });
    }
}


/*
   Function: jsJOMlib__getParameterByName
   Extract from an URL a parameter value if present in GET querystring format

   Parameters:
     url - complete URL
     name - parameter's name the value is searched

   Returns:
     the value searched if found, empty string otherwise
*/
function jsJOMlib__getParameterByName(url, name)
{
    url = url.split("?");
    url = "?" + url[1];
    name = name.replace(/[\[]/, "\\\[").replace(/[\]]/, "\\\]");
    var regexS = "[\\?&]" + name + "=([^&#]*)";
    var regex = new RegExp(regexS);
    var results = regex.exec(url);
    if(results == null)
        return "";
    else
        return decodeURIComponent(results[1].replace(/\+/g, " "));
}


/*
   Function: jsJOMlib__isNumber
   Check whether the given parameter is numeric or not

   Parameters:
     n - variable to check

   Returns:
     true if the parameter is a number, false otherwise
*/
function jsJOMlib__isNumber(n) {
  return !isNaN(parseFloat(n)) && isFinite(n);
}
