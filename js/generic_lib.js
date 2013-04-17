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


/*
   Function: jsJOMlib__date_formatted
   Convert a Javascript Date object into a formatted string.

   Parameters:
     format - string format made of a combination of "dd", "mm" and "yyyy", separated by a splitter character or string
     splitter - day month and year separator. Must be the same as the format given string
     date - optional parameter, must be a javascript Date object

   Returns:
     string formatted date, with leading zeros, according to given format; false if something goes wrong
*/
function jsJOMlib__date_formatted(format, splitter, date)
{
    if ( date==undefined ) date = new Date();
    if ( toString.call(date) != "[object Date]" ) {
        console.error("Passed date is not a javascript Date object");
        return false;
    }

    var day   = PHPjs_str_pad( date.getDate(),     2, '0', 'STR_PAD_LEFT' );
    var month = PHPjs_str_pad( (date.getMonth() + 1),    2, '0', 'STR_PAD_LEFT' );
    var year  = PHPjs_str_pad( date.getFullYear(), 4, '0', 'STR_PAD_LEFT' );

    var retval = "";

    var split = format.split(splitter);

    // first part
    for ( var i = 0 ; i < split.length ; i++ )
    {
        if ( split[i]=='dd' )   retval += day;
        else
        if ( split[i]=='mm' )   retval += month;
        else
        if ( split[i]=='yyyy' ) retval += year;

        if ( i != (split.length - 1) ) retval += splitter;
    }

    return retval;
}


function jsJOMlib__string_date_to_object(format, splitter, string_date)
{
    if ( toString.call(string_date) != "[object String]" ) {
        console.error("Passed date wrong type: must be a string");
        return false;
    }

    var format_splitted      = format.split(splitter);
    var string_date_splitted = string_date.split(splitter);

    if ( format_splitted.length != 3 || string_date_splitted.length != 3 ) {
        console.error("Format and/or date string passed have wrong format");
        return false;
    }

    var day   = undefined;
    var month = undefined;
    var year  = undefined;

    for ( var i = 0 ; i < 3 ; i++ ) {
        if ( format_splitted[i]=='dd' )   day   = parseInt(string_date_splitted[i], 10);
        else
        if ( format_splitted[i]=='mm' )   month = parseInt(string_date_splitted[i], 10) - 1;
        else
        if ( format_splitted[i]=='yyyy' ) year  = parseInt(string_date_splitted[i], 10);
    }

    return new Date(year, month, day);
}


/*
   Function: jsJOMlib__check_date_string
   Check DATE against formats dd/mm/yyyy , d/mm/yyyy , dd/m/yyyy , d/m/yyyy. Also allowed day and month inverse (american) position. Default separator
   between day, month and year is "/", but can be custom.

   Parameters:
     date - string date to parse/check
     format - string containing one of the allowed date format: dd/mm/yyyy or mm/dd/yyyy
     separator - (*optional*) day-month-year inbetween character. Must be the same as the format

   Depends on:
     - <jsSPESlib_math__is_number> function in jsSPESlib_math.js library file

   Returns:
     - *false* if any check is not passed. Some debug can be sent to console
     - *true* if all checks are passed
 *
 */
function jsJOMlib__check_date_string(date, format, separator)
{
    var DEFAULT_SEPARATOR  = '/';
    var date_splitted       = undefined;
    var format_splitted     = undefined;

    // check dependecy
    if ( !typeof(jsJOMlib__isNumber)==="function" ) {
        console.error("[JOM Debug] - This function depends on jsJOMlib__isNumber() function. Please include it." );
        return false;
    }

    if ( format === undefined || format == '' )
    {
        console.error("[JOM Debug] - format parameter missing");
        return false;
    }

    // set default separator if not passed. Can be ANY string or character
    if ( separator === undefined || separator==='' ) separator = DEFAULT_SEPARATOR;
    if ( toString.call(separator) != "[object String]" ) {
        return false;       // separator passed is not a string
    }
    // must be one and one only character
    if ( separator.length != 1 ) {
        return false;
    }

    // date must be a string
    if ( toString.call(date) != "[object String]" ) {
        return false;       // date passed is not a string
    }

    // not less than 8 chars and no more than 10
    if ( date.length < 8 || date.length > 10 ) {
        return false;       // too many or not not enough characters
    }

    // splitting date and format
    date_splitted   = date.split(separator);
    format_splitted = format.split(separator);

    // separator check
    if ( date_splitted.length < 3 ) {
        return false;       // separator do not match or not present
    }
    if ( format_splitted.length < 3 ) {
        console.error("[JOM Debug] - Format parameter error");
        return false;       // separator do not match or not present
    }


    // check three parts
    var d = undefined;
    var m = undefined;
    var y = undefined;
    for ( var i = 0 ; i < 3 ; i++ ) {
        if ( !jsJOMlib__isNumber( date_splitted[i] ) ) {
            return false;       // day/month/year part must be numeric
        }
        switch(format_splitted[i]) {
            case "dd":
                d = parseInt( date_splitted[i], 10 );
                if ( d < 1 || d > 31 ) {
                    return false;       // day number must be included into 1-31 interval
                }
                break;
            case "mm":
                m = parseInt( date_splitted[i], 10 );
                if ( m < 1 || m > 12 ) {
                    return false;       // month number must be included into 1-12 interval
                }
                break;
            case "yyyy":
                y = parseInt( date_splitted[i], 10 );
                if ( y < 2000 ) {
                    console.warn("[JoM Debug] - Sorry, but years before 2000 are not useable.");
                    return false;       // year at least 2000
                }
                break;
        }
    }


    // check if the day exists
    var date_obj = new Date(y, (m - 1), d);
    if ( date_obj.getDate() != d || date_obj.getMonth()  != (m - 1) || date_obj.getFullYear() != y ) {
        return false;      // date format is goot, but the date is not valid (does not exist)
    }

    return true;
}


function PHPjs_str_pad (input, pad_length, pad_string, pad_type) {
  // http://kevin.vanzonneveld.net
  // +   original by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
  // + namespaced by: Michael White (http://getsprink.com)
  // +      input by: Marco van Oort
  // +   bugfixed by: Brett Zamir (http://brett-zamir.me)
  // *     example 1: str_pad('Kevin van Zonneveld', 30, '-=', 'STR_PAD_LEFT');
  // *     returns 1: '-=-=-=-=-=-Kevin van Zonneveld'
  // *     example 2: str_pad('Kevin van Zonneveld', 30, '-', 'STR_PAD_BOTH');
  // *     returns 2: '------Kevin van Zonneveld-----'
  var half = '',
    pad_to_go;

  var str_pad_repeater = function (s, len) {
    var collect = '',
      i;

    while (collect.length < len) {
      collect += s;
    }
    collect = collect.substr(0, len);

    return collect;
  };

  input += '';
  pad_string = pad_string !== undefined ? pad_string : ' ';

  if (pad_type != 'STR_PAD_LEFT' && pad_type != 'STR_PAD_RIGHT' && pad_type != 'STR_PAD_BOTH') {
    pad_type = 'STR_PAD_RIGHT';
  }
  if ((pad_to_go = pad_length - input.length) > 0) {
    if (pad_type == 'STR_PAD_LEFT') {
      input = str_pad_repeater(pad_string, pad_to_go) + input;
    } else if (pad_type == 'STR_PAD_RIGHT') {
      input = input + str_pad_repeater(pad_string, pad_to_go);
    } else if (pad_type == 'STR_PAD_BOTH') {
      half = str_pad_repeater(pad_string, Math.ceil(pad_to_go / 2));
      input = half + input + half;
      input = input.substr(0, pad_length);
    }
  }

  return input;
}


function trim(str, charlist) {
  // http://kevin.vanzonneveld.net
  // +   original by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
  // +   improved by: mdsjack (http://www.mdsjack.bo.it)
  // +   improved by: Alexander Ermolaev (http://snippets.dzone.com/user/AlexanderErmolaev)
  // +      input by: Erkekjetter
  // +   improved by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
  // +      input by: DxGx
  // +   improved by: Steven Levithan (http://blog.stevenlevithan.com)
  // +    tweaked by: Jack
  // +   bugfixed by: Onno Marsman
  // *     example 1: trim('    Kevin van Zonneveld    ');
  // *     returns 1: 'Kevin van Zonneveld'
  // *     example 2: trim('Hello World', 'Hdle');
  // *     returns 2: 'o Wor'
  // *     example 3: trim(16, 1);
  // *     returns 3: 6
  var whitespace, l = 0,
    i = 0;
  str += '';

  if (!charlist) {
    // default list
    whitespace = " \n\r\t\f\x0b\xa0\u2000\u2001\u2002\u2003\u2004\u2005\u2006\u2007\u2008\u2009\u200a\u200b\u2028\u2029\u3000";
  } else {
    // preg_quote custom list
    charlist += '';
    whitespace = charlist.replace(/([\[\]\(\)\.\?\/\*\{\}\+\$\^\:])/g, '$1');
  }

  l = str.length;
  for (i = 0; i < l; i++) {
    if (whitespace.indexOf(str.charAt(i)) === -1) {
      str = str.substring(i);
      break;
    }
  }

  l = str.length;
  for (i = l - 1; i >= 0; i--) {
    if (whitespace.indexOf(str.charAt(i)) === -1) {
      str = str.substring(0, i + 1);
      break;
    }
  }

  return whitespace.indexOf(str.charAt(0)) === -1 ? str : '';
}
