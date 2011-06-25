function keys(ob) {
  var ret=[];

  for(var i in ob) {
    ret.push(i);
  }

  return ret;
}

function split_semicolon(str) {
  var x=str.split(/;/);
  var ret=new Array();

  for(var i=0; i<x.length; i++) {
    if(x[i].substr(0, 1)=="\"") {
      var j=i;
      while(x[j].substr(-1)!="\"")
	j++;
      var y=x.slice(i, j+1).join(";");
      ret.push(y.substr(1, y.length-2));
      i=j;
    }
    else
      ret.push(x[i]);
  }

  return ret;
}

function hash_to_string(ar) {
  var ret=[];

  for(var i in ar) {
    ret.push(i+"="+ar[i]);
  }

  return ret.join("&");
}

function string_to_hash(str) {
  var ret={};
  var ar=str.split(/&/);
  for(var i=0; i<ar.length; i++) {
    var x=ar[i].split(/=/);
    var k=x[0];
    x.shift();
    ret[k]=x.join("=");
  }

  return ret;
}

// array_unfold ... removes depth of convoluted arrays
function array_unfold(arr) {
  arr=arr.concat([]);

  for(var i=0; i<arr.length; i++) {
    if((typeof arr[i]==='object')&&(arr[i].length)) {
      var part2=arr[i];
      var part3=arr.splice(i+1);
      var part1=arr.splice(0, i);
      arr=part1.concat(part2).concat(part3);
      i--;
    }
  }

  return arr;
}

// array_remove_undefined ... remove all undefined/null values in array
function array_remove_undefined(arr) {
  var undef;
  arr=arr.concat([]);

  for(var i=0; i<arr.length; i++) {
    if((arr[i]===undef)||(arr[i]===null)) {
      arr=array_remove(arr, i);
      i--;
    }
  }

  return arr;
}

// array_delete removes an element from the array at the pos position
// example:
//   array_remove([ 1, 2, 3, 4, 5 ], 2)
//   -> [ 1, 2, 4, 5 ]
function array_remove(arr, pos) {
  var part1=arr.concat([]);
  var part2=part1.splice(pos+1);
  return part1.splice(0, pos).concat(part2);
}

// source: phpjs.org
function array_merge () {
    // http://kevin.vanzonneveld.net
    // +   original by: Brett Zamir (http://brett-zamir.me)
    // +   bugfixed by: Nate
    // +   input by: josh
    // +   bugfixed by: Brett Zamir (http://brett-zamir.me)
    // *     example 1: arr1 = {"color": "red", 0: 2, 1: 4}
    // *     example 1: arr2 = {0: "a", 1: "b", "color": "green", "shape": "trapezoid", 2: 4}
    // *     example 1: array_merge(arr1, arr2)
    // *     returns 1: {"color": "green", 0: 2, 1: 4, 2: "a", 3: "b", "shape": "trapezoid", 4: 4}
    // *     example 2: arr1 = []
    // *     example 2: arr2 = {1: "data"}
    // *     example 2: array_merge(arr1, arr2)
    // *     returns 2: {0: "data"}
    
    var args = Array.prototype.slice.call(arguments),
                            retObj = {}, k, j = 0, i = 0, retArr = true;
    
    for (i=0; i < args.length; i++) {
        if (!(args[i] instanceof Array)) {
            retArr=false;
            break;
        }
    }
    
    if (retArr) {
        retArr = [];
        for (i=0; i < args.length; i++) {
            retArr = retArr.concat(args[i]);
        }
        return retArr;
    }
    var ct = 0;
    
    for (i=0, ct=0; i < args.length; i++) {
        if (args[i] instanceof Array) {
            for (j=0; j < args[i].length; j++) {
                retObj[ct++] = args[i][j];
            }
        } else {
            for (k in args[i]) {
                if (args[i].hasOwnProperty(k)) {
                    if (parseInt(k, 10)+'' === k) {
                        retObj[ct++] = args[i][k];
                    } else {
                        retObj[k] = args[i][k];
                    }
                }
            }
        }
    }
    return retObj;
}

// weight_sort(arr)
// Parameters:
// arr ... an array of form [ [ weight, var], ... ]
//         [ [ -3, A ], [ -1, B ], [ 5, C ], [ -1, D ] ]
//         
// Returns:
// An array sorted by the weight of the source, e.g.
//         [ A, B, D, C ]
//
// Notes:
// Entries in the source array with the same weight are returned in the
// same order
function weight_sort(arr) {
  function numerical_cmp(a, b) {
    return a-b;
  }

  var ret1={};

  // first put all elements into an assoc. array
  for(var i=0; i<arr.length; i++) {
    var cur=arr[i];
    var wgt=cur[0];

    if(!ret1[wgt])
      ret1[wgt]=[];

    ret1[wgt].push(cur[1]);
  }

  // get the keys, convert to value, order them
  var keys1=keys(ret1);
  keys1.sort(numerical_cmp);
  var ret2=[];

  // iterate through array and compile final return value
  for(var i=0; i<keys1.length; i++) {
    for(var j=0; j<ret1[keys1[i]].length; j++) {
      ret2.push(ret1[keys1[i]][j]);
    }
  }

  return ret2;
}

// Source: http://www.hardcode.nl/subcategory_1/article_414-copy-or-clone-javascript-array-object
// use as: var b=new clone(a);
function clone(source) {
    for (i in source) {
        if (typeof source[i] == 'source') {
            this[i] = new clone_object(source[i]);
        }
        else{
            this[i] = source[i];
	}
    }
}

// Source: http://der-albert.com/archive/2006/01/05/mit-javascript-dom-events-manuell-ausloesen.aspx
function raise_event (eventType, element)
{ 
    if (document.createEvent) { 
        var evt = document.createEvent("Events"); 
        evt.initEvent(eventType, true, true); 
        element.dispatchEvent(evt); 
    } 
    else if (document.createEventObject) 
    {
        var evt = document.createEventObject(); 
        element.fireEvent('on' + eventType, evt); 
    } 
}

// Source: http://www.w3schools.com/Xml/xml_parser.asp
function parse_xml(txt) {
  if (window.DOMParser) {
    parser=new DOMParser();
    xmlDoc=parser.parseFromString(txt,"text/xml");
  }
  else { // Internet Explorer
    xmlDoc=new ActiveXObject("Microsoft.XMLDOM");
    xmlDoc.async="false";
    xmlDoc.loadXML(txt);
  }  

  return xmlDoc;
}

// Source: PHPJS
function in_array (needle, haystack, argStrict) {
    // http://kevin.vanzonneveld.net
    // +   original by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
    // +   improved by: vlado houba
    // +   input by: Billy
    // +   bugfixed by: Brett Zamir (http://brett-zamir.me)
    // *     example 1: in_array('van', ['Kevin', 'van', 'Zonneveld']);
    // *     returns 1: true
    // *     example 2: in_array('vlado', {0: 'Kevin', vlado: 'van', 1: 'Zonneveld'});
    // *     returns 2: false
    // *     example 3: in_array(1, ['1', '2', '3']);
    // *     returns 3: true
    // *     example 3: in_array(1, ['1', '2', '3'], false);
    // *     returns 3: true
    // *     example 4: in_array(1, ['1', '2', '3'], true);
    // *     returns 4: false

    var key = '', strict = !!argStrict;

    if (strict) {
        for (key in haystack) {
            if (haystack[key] === needle) {
                return true;
            }
        }
    } else {
        for (key in haystack) {
            if (haystack[key] == needle) {
                return true;
            }
        }
    }

    return false;
}

// Source: PHPJS (needed for strtr)
function krsort (inputArr, sort_flags) {
    // http://kevin.vanzonneveld.net
    // +   original by: GeekFG (http://geekfg.blogspot.com)
    // +   improved by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
    // +   improved by: Brett Zamir (http://brett-zamir.me)
    // %          note 1: The examples are correct, this is a new way
    // %        note 2: This function deviates from PHP in returning a copy of the array instead
    // %        note 2: of acting by reference and returning true; this was necessary because
    // %        note 2: IE does not allow deleting and re-adding of properties without caching
    // %        note 2: of property position; you can set the ini of "phpjs.strictForIn" to true to
    // %        note 2: get the PHP behavior, but use this only if you are in an environment
    // %        note 2: such as Firefox extensions where for-in iteration order is fixed and true
    // %        note 2: property deletion is supported. Note that we intend to implement the PHP
    // %        note 2: behavior by default if IE ever does allow it; only gives shallow copy since
    // %        note 2: is by reference in PHP anyways
    // %        note 3: Since JS objects' keys are always strings, and (the
    // %        note 3: default) SORT_REGULAR flag distinguishes by key type,
    // %        note 3: if the content is a numeric string, we treat the
    // %        note 3: "original type" as numeric.
    // -    depends on: i18n_loc_get_default
    // *     example 1: data = {d: 'lemon', a: 'orange', b: 'banana', c: 'apple'};
    // *     example 1: data = krsort(data);
    // *     results 1: {d: 'lemon', c: 'apple', b: 'banana', a: 'orange'}
    // *     example 2: ini_set('phpjs.strictForIn', true);
    // *     example 2: data = {2: 'van', 3: 'Zonneveld', 1: 'Kevin'};
    // *     example 2: krsort(data);
    // *     results 2: data == {3: 'Kevin', 2: 'van', 1: 'Zonneveld'}
    // *     returns 2: true

    var tmp_arr={}, keys=[], sorter, i, k, that=this, strictForIn = false, populateArr = {};

    switch (sort_flags) {
        case 'SORT_STRING': // compare items as strings
            sorter = function (a, b) {
                return that.strnatcmp(b, a);
            };
            break;
        case 'SORT_LOCALE_STRING': // compare items as strings, based on the current locale (set with  i18n_loc_set_default() as of PHP6)
            var loc = this.i18n_loc_get_default();
            sorter = this.php_js.i18nLocales[loc].sorting;
            break;
        case 'SORT_NUMERIC': // compare items numerically
            sorter = function (a, b) {
                return (b - a);
            };
            break;
        case 'SORT_REGULAR': // compare items normally (don't change types)
        default:
            sorter = function (b, a) {
                var aFloat = parseFloat(a),
                    bFloat = parseFloat(b),
                    aNumeric = aFloat+'' === a,
                    bNumeric = bFloat+'' === b;
                if (aNumeric && bNumeric) {
                    return aFloat > bFloat ? 1 : aFloat < bFloat ? -1 : 0;
                }
                else if (aNumeric && !bNumeric) {
                    return 1;
                }
                else if (!aNumeric && bNumeric) {
                    return -1;
                }
                return a > b ? 1 : a < b ? -1 : 0;
            };
            break;
    }

    // Make a list of key names
    for (k in inputArr) {
        if (inputArr.hasOwnProperty(k)) {
            keys.push(k);
        }
    }
    keys.sort(sorter);

    // BEGIN REDUNDANT
    this.php_js = this.php_js || {};
    this.php_js.ini = this.php_js.ini || {};
    // END REDUNDANT

    strictForIn = this.php_js.ini['phpjs.strictForIn'] && this.php_js.ini['phpjs.strictForIn'].local_value && 
                    this.php_js.ini['phpjs.strictForIn'].local_value !== 'off';
    populateArr = strictForIn ? inputArr : populateArr;


    // Rebuild array with sorted key names
    for (i = 0; i < keys.length; i++) {
        k = keys[i];
        tmp_arr[k] = inputArr[k];
        if (strictForIn) {
            delete inputArr[k];
        }
    }
    for (i in tmp_arr) {
        if (tmp_arr.hasOwnProperty(i)) {
            populateArr[i] = tmp_arr[i];
        }
    }

    return strictForIn || populateArr;
}

// Source: PHPJS (needed for strtr)
function ini_set (varname, newvalue) {
    // http://kevin.vanzonneveld.net
    // +   original by: Brett Zamir (http://brett-zamir.me)
    // %        note 1: This will not set a global_value or access level for the ini item
    // *     example 1: ini_set('date.timezone', 'America/Chicago');
    // *     returns 1: 'Asia/Hong_Kong'

    var oldval = '', that = this;
    this.php_js = this.php_js || {};
    this.php_js.ini = this.php_js.ini || {};
    this.php_js.ini[varname] = this.php_js.ini[varname] || {};
    oldval = this.php_js.ini[varname].local_value;
    
    var _setArr = function (oldval) { // Although these are set individually, they are all accumulated
        if (typeof oldval === 'undefined') {
            that.php_js.ini[varname].local_value = [];
        }
        that.php_js.ini[varname].local_value.push(newvalue);
    };

    switch (varname) {
        case 'extension':
            if (typeof this.dl === 'function') {
                this.dl(newvalue); // This function is only experimental in php.js
            }
            _setArr(oldval, newvalue);
            break;
        default:
            this.php_js.ini[varname].local_value = newvalue;
            break;
    }
    return oldval;
}

// Source: http://phpjs.org/functions/strtr:556
function strtr (str, from, to) {
    // http://kevin.vanzonneveld.net
    // +   original by: Brett Zamir (http://brett-zamir.me)
    // +      input by: uestla
    // +   bugfixed by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
    // +      input by: Alan C
    // +   bugfixed by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
    // +      input by: Taras Bogach
    // +   bugfixed by: Brett Zamir (http://brett-zamir.me)
    // +      input by: jpfle
    // +   bugfixed by: Brett Zamir (http://brett-zamir.me)
    // -   depends on: krsort
    // -   depends on: ini_set
    // *     example 1: $trans = {'hello' : 'hi', 'hi' : 'hello'};
    // *     example 1: strtr('hi all, I said hello', $trans)
    // *     returns 1: 'hello all, I said hi'
    // *     example 2: strtr('äaabaåccasdeöoo', 'äåö','aao');
    // *     returns 2: 'aaabaaccasdeooo'
    // *     example 3: strtr('ääääääää', 'ä', 'a');
    // *     returns 3: 'aaaaaaaa'
    // *     example 4: strtr('http', 'pthxyz','xyzpth');
    // *     returns 4: 'zyyx'
    // *     example 5: strtr('zyyx', 'pthxyz','xyzpth');
    // *     returns 5: 'http'
    // *     example 6: strtr('aa', {'a':1,'aa':2});
    // *     returns 6: '2'

    var fr = '', i = 0, j = 0, lenStr = 0, lenFrom = 0, tmpStrictForIn = false, fromTypeStr = '', toTypeStr = '', istr = '';
    var tmpFrom = [];
    var tmpTo = [];
    var ret = '';
    var match = false;

    // Received replace_pairs?
    // Convert to normal from->to chars
    if (typeof from === 'object') {
        tmpStrictForIn = this.ini_set('phpjs.strictForIn', false); // Not thread-safe; temporarily set to true
        from = this.krsort(from);
        this.ini_set('phpjs.strictForIn', tmpStrictForIn);

        for (fr in from) {
            if (from.hasOwnProperty(fr)) {
                tmpFrom.push(fr);
                tmpTo.push(from[fr]);
            }
        }

        from = tmpFrom;
        to = tmpTo;
    }
    
    // Walk through subject and replace chars when needed
    lenStr  = str.length;
    lenFrom = from.length;
    fromTypeStr = typeof from === 'string';
    toTypeStr = typeof to === 'string';

    for (i = 0; i < lenStr; i++) {
        match = false;
        if (fromTypeStr) {
            istr = str.charAt(i);
            for (j = 0; j < lenFrom; j++) {
                if (istr == from.charAt(j)) {
                    match = true;
                    break;
                }
            }
        }
        else {
            for (j = 0; j < lenFrom; j++) {
                if (str.substr(i, from[j].length) == from[j]) {
                    match = true;
                    // Fast forward
                    i = (i + from[j].length)-1;
                    break;
                }
            }
        }
        if (match) {
            ret += toTypeStr ? to.charAt(j) : to[j];
        } else {
            ret += str.charAt(i);
        }
    }

    return ret;
}

function array_search (needle, haystack, argStrict) {
    // Searches the array for a given value and returns the corresponding key if successful  
    // 
    // version: 1102.614
    // discuss at: http://phpjs.org/functions/array_search    // +   original by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
    // +      input by: Brett Zamir (http://brett-zamir.me)
    // +   bugfixed by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
    // *     example 1: array_search('zonneveld', {firstname: 'kevin', middle: 'van', surname: 'zonneveld'});
    // *     returns 1: 'surname'    var strict = !! argStrict;
    var key = '', strict = !!argStrict;
 
    for (key in haystack) {
        if ((strict && haystack[key] === needle) || (!strict && haystack[key] == needle)) {            return key;
        }
    }
 
    return false;
}

// Source: http://phpjs.org/functions/strftime:590
function strftime (fmt, timestamp) {
    // http://kevin.vanzonneveld.net
    // +      original by: Blues (http://tech.bluesmoon.info/)
    // + reimplemented by: Brett Zamir (http://brett-zamir.me)
    // +   input by: Alex
    // +   bugfixed by: Brett Zamir (http://brett-zamir.me)
    // +   improved by: Brett Zamir (http://brett-zamir.me)
    // -       depends on: setlocale
    // %        note 1: Uses global: php_js to store locale info
    // *        example 1: strftime("%A", 1062462400); // Return value will depend on date and locale
    // *        returns 1: 'Tuesday'
    // BEGIN REDUNDANT
    this.php_js = this.php_js || {};
    this.setlocale('LC_ALL', 0); // ensure setup of localization variables takes place
    // END REDUNDANT
    var phpjs = this.php_js;

    // BEGIN STATIC
    var _xPad = function (x, pad, r) {
        if (typeof r === 'undefined') {
            r = 10;
        }
        for (; parseInt(x, 10) < r && r > 1; r /= 10) {
            x = pad.toString() + x;
        }
        return x.toString();
    };

    var locale = phpjs.localeCategories.LC_TIME;
    var locales = phpjs.locales;
    var lc_time = locales[locale].LC_TIME;

    var _formats = {
        a: function (d) {
            return lc_time.a[d.getDay()];
        },
        A: function (d) {
            return lc_time.A[d.getDay()];
        },
        b: function (d) {
            return lc_time.b[d.getMonth()];
        },
        B: function (d) {
            return lc_time.B[d.getMonth()];
        },
        C: function (d) {
            return _xPad(parseInt(d.getFullYear() / 100, 10), 0);
        },
        d: ['getDate', '0'],
        e: ['getDate', ' '],
        g: function (d) {
            return _xPad(parseInt(this.G(d) / 100, 10), 0);
        },
        G: function (d) {
            var y = d.getFullYear();
            var V = parseInt(_formats.V(d), 10);
            var W = parseInt(_formats.W(d), 10);

            if (W > V) {
                y++;
            } else if (W === 0 && V >= 52) {
                y--;
            }

            return y;
        },
        H: ['getHours', '0'],
        I: function (d) {
            var I = d.getHours() % 12;
            return _xPad(I === 0 ? 12 : I, 0);
        },
        j: function (d) {
            var ms = d - new Date('' + d.getFullYear() + '/1/1 GMT');
            ms += d.getTimezoneOffset() * 60000; // Line differs from Yahoo implementation which would be equivalent to replacing it here with:
            // ms = new Date('' + d.getFullYear() + '/' + (d.getMonth()+1) + '/' + d.getDate() + ' GMT') - ms;
            var doy = parseInt(ms / 60000 / 60 / 24, 10) + 1;
            return _xPad(doy, 0, 100);
        },
        k: ['getHours', '0'],
        // not in PHP, but implemented here (as in Yahoo)
        l: function (d) {
            var l = d.getHours() % 12;
            return _xPad(l === 0 ? 12 : l, ' ');
        },
        m: function (d) {
            return _xPad(d.getMonth() + 1, 0);
        },
        M: ['getMinutes', '0'],
        p: function (d) {
            return lc_time.p[d.getHours() >= 12 ? 1 : 0];
        },
        P: function (d) {
            return lc_time.P[d.getHours() >= 12 ? 1 : 0];
        },
        s: function (d) { // Yahoo uses return parseInt(d.getTime()/1000, 10);
            return Date.parse(d) / 1000;
        },
        S: ['getSeconds', '0'],
        u: function (d) {
            var dow = d.getDay();
            return ((dow === 0) ? 7 : dow);
        },
        U: function (d) {
            var doy = parseInt(_formats.j(d), 10);
            var rdow = 6 - d.getDay();
            var woy = parseInt((doy + rdow) / 7, 10);
            return _xPad(woy, 0);
        },
        V: function (d) {
            var woy = parseInt(_formats.W(d), 10);
            var dow1_1 = (new Date('' + d.getFullYear() + '/1/1')).getDay();
            // First week is 01 and not 00 as in the case of %U and %W,
            // so we add 1 to the final result except if day 1 of the year
            // is a Monday (then %W returns 01).
            // We also need to subtract 1 if the day 1 of the year is
            // Friday-Sunday, so the resulting equation becomes:
            var idow = woy + (dow1_1 > 4 || dow1_1 <= 1 ? 0 : 1);
            if (idow === 53 && (new Date('' + d.getFullYear() + '/12/31')).getDay() < 4) {
                idow = 1;
            } else if (idow === 0) {
                idow = _formats.V(new Date('' + (d.getFullYear() - 1) + '/12/31'));
            }
            return _xPad(idow, 0);
        },
        w: 'getDay',
        W: function (d) {
            var doy = parseInt(_formats.j(d), 10);
            var rdow = 7 - _formats.u(d);
            var woy = parseInt((doy + rdow) / 7, 10);
            return _xPad(woy, 0, 10);
        },
        y: function (d) {
            return _xPad(d.getFullYear() % 100, 0);
        },
        Y: 'getFullYear',
        z: function (d) {
            var o = d.getTimezoneOffset();
            var H = _xPad(parseInt(Math.abs(o / 60), 10), 0);
            var M = _xPad(o % 60, 0);
            return (o > 0 ? '-' : '+') + H + M;
        },
        Z: function (d) {
            return d.toString().replace(/^.*\(([^)]+)\)$/, '$1');
/*
            // Yahoo's: Better?
            var tz = d.toString().replace(/^.*:\d\d( GMT[+-]\d+)? \(?([A-Za-z ]+)\)?\d*$/, '$2').replace(/[a-z ]/g, '');
            if(tz.length > 4) {
                tz = Dt.formats.z(d);
            }
            return tz;
            */
        },
        '%': function (d) {
            return '%';
        }
    };
    // END STATIC
/* Fix: Locale alternatives are supported though not documented in PHP; see http://linux.die.net/man/3/strptime
Ec
EC
Ex
EX
Ey
EY
Od or Oe
OH
OI
Om
OM
OS
OU
Ow
OW
Oy
*/

    var _date = ((typeof(timestamp) == 'undefined') ? new Date() : // Not provided
    (typeof(timestamp) == 'object') ? new Date(timestamp) : // Javascript Date()
    new Date(timestamp * 1000) // PHP API expects UNIX timestamp (auto-convert to int)
    );

    var _aggregates = {
        c: 'locale',
        D: '%m/%d/%y',
        F: '%y-%m-%d',
        h: '%b',
        n: '\n',
        r: 'locale',
        R: '%H:%M',
        t: '\t',
        T: '%H:%M:%S',
        x: 'locale',
        X: 'locale'
    };


    // First replace aggregates (run in a loop because an agg may be made up of other aggs)
    while (fmt.match(/%[cDFhnrRtTxX]/)) {
        fmt = fmt.replace(/%([cDFhnrRtTxX])/g, function (m0, m1) {
            var f = _aggregates[m1];
            return (f === 'locale' ? lc_time[m1] : f);
        });
    }

    // Now replace formats - we need a closure so that the date object gets passed through
    var str = fmt.replace(/%([aAbBCdegGHIjklmMpPsSuUVwWyYzZ%])/g, function (m0, m1) {
        var f = _formats[m1];
        if (typeof f === 'string') {
            return _date[f]();
        } else if (typeof f === 'function') {
            return f(_date);
        } else if (typeof f === 'object' && typeof(f[0]) === 'string') {
            return _xPad(_date[f[0]](), f[1]);
        } else { // Shouldn't reach here
            return m1;
        }
    });
    return str;
}

// Source: http://phpjs.org/functions/setlocale:589
function setlocale (category, locale) {
    // http://kevin.vanzonneveld.net
    // +   original by: Brett Zamir (http://brett-zamir.me)
    // +   derived from: Blues at http://hacks.bluesmoon.info/strftime/strftime.js
    // +   derived from: YUI Library: http://developer.yahoo.com/yui/docs/YAHOO.util.DateLocale.html
    // -    depends on: getenv
    // %          note 1: Is extensible, but currently only implements locales en,
    // %          note 1: en_US, en_GB, en_AU, fr, and fr_CA for LC_TIME only; C for LC_CTYPE;
    // %          note 1: C and en for LC_MONETARY/LC_NUMERIC; en for LC_COLLATE
    // %          note 2: Uses global: php_js to store locale info
    // %          note 3: Consider using http://demo.icu-project.org/icu-bin/locexp as basis for localization (as in i18n_loc_set_default())
    // *     example 1: setlocale('LC_ALL', 'en_US');
    // *     returns 1: 'en_US'
    var categ = '',
        cats = [],
        i = 0,
        d = this.window.document;

    // BEGIN STATIC
    var _copy = function _copy(orig) {
        if (orig instanceof RegExp) {
            return new RegExp(orig);
        } else if (orig instanceof Date) {
            return new Date(orig);
        }
        var newObj = {};
        for (var i in orig) {
            if (typeof orig[i] === 'object') {
                newObj[i] = _copy(orig[i]);
            } else {
                newObj[i] = orig[i];
            }
        }
        return newObj;
    };

    // Function usable by a ngettext implementation (apparently not an accessible part of setlocale(), but locale-specific)
    // See http://www.gnu.org/software/gettext/manual/gettext.html#Plural-forms though amended with others from
    // https://developer.mozilla.org/En/Localization_and_Plurals (new categories noted with "MDC" below, though
    // not sure of whether there is a convention for the relative order of these newer groups as far as ngettext)
    // The function name indicates the number of plural forms (nplural)
    // Need to look into http://cldr.unicode.org/ (maybe future JavaScript); Dojo has some functions (under new BSD),
    // including JSON conversions of LDML XML from CLDR: http://bugs.dojotoolkit.org/browser/dojo/trunk/cldr
    // and docs at http://api.dojotoolkit.org/jsdoc/HEAD/dojo.cldr
    var _nplurals1 = function (n) { // e.g., Japanese
        return 0;
    };
    var _nplurals2a = function (n) { // e.g., English
        return n !== 1 ? 1 : 0;
    };
    var _nplurals2b = function (n) { // e.g., French
        return n > 1 ? 1 : 0;
    };
    var _nplurals2c = function (n) { // e.g., Icelandic (MDC)
        return n % 10 === 1 && n % 100 !== 11 ? 0 : 1;
    };
    var _nplurals3a = function (n) { // e.g., Latvian (MDC has a different order from gettext)
        return n % 10 === 1 && n % 100 !== 11 ? 0 : n !== 0 ? 1 : 2;
    };
    var _nplurals3b = function (n) { // e.g., Scottish Gaelic
        return n === 1 ? 0 : n === 2 ? 1 : 2;
    };
    var _nplurals3c = function (n) { // e.g., Romanian
        return n === 1 ? 0 : (n === 0 || (n % 100 > 0 && n % 100 < 20)) ? 1 : 2;
    };
    var _nplurals3d = function (n) { // e.g., Lithuanian (MDC has a different order from gettext)
        return n % 10 === 1 && n % 100 !== 11 ? 0 : n % 10 >= 2 && (n % 100 < 10 || n % 100 >= 20) ? 1 : 2;
    };
    var _nplurals3e = function (n) { // e.g., Croatian
        return n % 10 === 1 && n % 100 !== 11 ? 0 : n % 10 >= 2 && n % 10 <= 4 && (n % 100 < 10 || n % 100 >= 20) ? 1 : 2;
    };
    var _nplurals3f = function (n) { // e.g., Slovak
        return n === 1 ? 0 : n >= 2 && n <= 4 ? 1 : 2;
    };
    var _nplurals3g = function (n) { // e.g., Polish
        return n === 1 ? 0 : n % 10 >= 2 && n % 10 <= 4 && (n % 100 < 10 || n % 100 >= 20) ? 1 : 2;
    };
    var _nplurals3h = function (n) { // e.g., Macedonian (MDC)
        return n % 10 === 1 ? 0 : n % 10 === 2 ? 1 : 2;
    };
    var _nplurals4a = function (n) { // e.g., Slovenian
        return n % 100 === 1 ? 0 : n % 100 === 2 ? 1 : n % 100 === 3 || n % 100 === 4 ? 2 : 3;
    };
    var _nplurals4b = function (n) { // e.g., Maltese (MDC)
        return n === 1 ? 0 : n === 0 || (n % 100 && n % 100 <= 10) ? 1 : n % 100 >= 11 && n % 100 <= 19 ? 2 : 3;
    };
    var _nplurals5 = function (n) { // e.g., Irish Gaeilge (MDC)
        return n === 1 ? 0 : n === 2 ? 1 : n >= 3 && n <= 6 ? 2 : n >= 7 && n <= 10 ? 3 : 4;
    };
    var _nplurals6 = function (n) { // e.g., Arabic (MDC) - Per MDC puts 0 as last group
        return n === 0 ? 5 : n === 1 ? 0 : n === 2 ? 1 : n % 100 >= 3 && n % 100 <= 10 ? 2 : n % 100 >= 11 && n % 100 <= 99 ? 3 : 4;
    };
    // END STATIC
    // BEGIN REDUNDANT
    this.php_js = this.php_js || {};

    var phpjs = this.php_js;

    // Reconcile Windows vs. *nix locale names?
    // Allow different priority orders of languages, esp. if implement gettext as in
    //     LANGUAGE env. var.? (e.g., show German if French is not available)
    if (!phpjs.locales) {
        // Can add to the locales
        phpjs.locales = {};

        phpjs.locales.en = {
            'LC_COLLATE': // For strcoll


            function (str1, str2) { // Fix: This one taken from strcmp, but need for other locales; we don't use localeCompare since its locale is not settable
                return (str1 == str2) ? 0 : ((str1 > str2) ? 1 : -1);
            },
            'LC_CTYPE': { // Need to change any of these for English as opposed to C?
                an: /^[A-Za-z\d]+$/g,
                al: /^[A-Za-z]+$/g,
                ct: /^[\u0000-\u001F\u007F]+$/g,
                dg: /^[\d]+$/g,
                gr: /^[\u0021-\u007E]+$/g,
                lw: /^[a-z]+$/g,
                pr: /^[\u0020-\u007E]+$/g,
                pu: /^[\u0021-\u002F\u003A-\u0040\u005B-\u0060\u007B-\u007E]+$/g,
                sp: /^[\f\n\r\t\v ]+$/g,
                up: /^[A-Z]+$/g,
                xd: /^[A-Fa-f\d]+$/g,
                CODESET: 'UTF-8',
                // Used by sql_regcase
                lower: 'abcdefghijklmnopqrstuvwxyz',
                upper: 'ABCDEFGHIJKLMNOPQRSTUVWXYZ'
            },
            'LC_TIME': { // Comments include nl_langinfo() constant equivalents and any changes from Blues' implementation
                a: ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'],
                // ABDAY_
                A: ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'],
                // DAY_
                b: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                // ABMON_
                B: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
                // MON_
                c: '%a %d %b %Y %r %Z',
                // D_T_FMT // changed %T to %r per results
                p: ['AM', 'PM'],
                // AM_STR/PM_STR
                P: ['am', 'pm'],
                // Not available in nl_langinfo()
                r: '%I:%M:%S %p',
                // T_FMT_AMPM (Fixed for all locales)
                x: '%m/%d/%Y',
                // D_FMT // switched order of %m and %d; changed %y to %Y (C uses %y)
                X: '%r',
                // T_FMT // changed from %T to %r  (%T is default for C, not English US)
                // Following are from nl_langinfo() or http://www.cptec.inpe.br/sx4/sx4man2/g1ab02e/strftime.4.html
                alt_digits: '',
                // e.g., ordinal
                ERA: '',
                ERA_YEAR: '',
                ERA_D_T_FMT: '',
                ERA_D_FMT: '',
                ERA_T_FMT: ''
            },
            // Assuming distinction between numeric and monetary is thus:
            // See below for C locale
            'LC_MONETARY': { // Based on Windows "english" (English_United States.1252) locale
                int_curr_symbol: 'USD',
                currency_symbol: '$',
                mon_decimal_point: '.',
                mon_thousands_sep: ',',
                mon_grouping: [3],
                // use mon_thousands_sep; "" for no grouping; additional array members indicate successive group lengths after first group (e.g., if to be 1,23,456, could be [3, 2])
                positive_sign: '',
                negative_sign: '-',
                int_frac_digits: 2,
                // Fractional digits only for money defaults?
                frac_digits: 2,
                p_cs_precedes: 1,
                // positive currency symbol follows value = 0; precedes value = 1
                p_sep_by_space: 0,
                // 0: no space between curr. symbol and value; 1: space sep. them unless symb. and sign are adjacent then space sep. them from value; 2: space sep. sign and value unless symb. and sign are adjacent then space separates
                n_cs_precedes: 1,
                // see p_cs_precedes
                n_sep_by_space: 0,
                // see p_sep_by_space
                p_sign_posn: 3,
                // 0: parentheses surround quantity and curr. symbol; 1: sign precedes them; 2: sign follows them; 3: sign immed. precedes curr. symbol; 4: sign immed. succeeds curr. symbol
                n_sign_posn: 0 // see p_sign_posn
            },
            'LC_NUMERIC': { // Based on Windows "english" (English_United States.1252) locale
                decimal_point: '.',
                thousands_sep: ',',
                grouping: [3] // see mon_grouping, but for non-monetary values (use thousands_sep)
            },
            'LC_MESSAGES': {
                YESEXPR: '^[yY].*',
                NOEXPR: '^[nN].*',
                YESSTR: '',
                NOSTR: ''
            },
            nplurals: _nplurals2a
        };
        phpjs.locales.en_US = _copy(phpjs.locales.en);
        phpjs.locales.en_US.LC_TIME.c = '%a %d %b %Y %r %Z';
        phpjs.locales.en_US.LC_TIME.x = '%D';
        phpjs.locales.en_US.LC_TIME.X = '%r';
        // The following are based on *nix settings
        phpjs.locales.en_US.LC_MONETARY.int_curr_symbol = 'USD ';
        phpjs.locales.en_US.LC_MONETARY.p_sign_posn = 1;
        phpjs.locales.en_US.LC_MONETARY.n_sign_posn = 1;
        phpjs.locales.en_US.LC_MONETARY.mon_grouping = [3, 3];
        phpjs.locales.en_US.LC_NUMERIC.thousands_sep = '';
        phpjs.locales.en_US.LC_NUMERIC.grouping = [];

        phpjs.locales.en_GB = _copy(phpjs.locales.en);
        phpjs.locales.en_GB.LC_TIME.r = '%l:%M:%S %P %Z';

        phpjs.locales.en_AU = _copy(phpjs.locales.en_GB);
        phpjs.locales.C = _copy(phpjs.locales.en); // Assume C locale is like English (?) (We need C locale for LC_CTYPE)
        phpjs.locales.C.LC_CTYPE.CODESET = 'ANSI_X3.4-1968';
        phpjs.locales.C.LC_MONETARY = {
            int_curr_symbol: '',
            currency_symbol: '',
            mon_decimal_point: '',
            mon_thousands_sep: '',
            mon_grouping: [],
            p_cs_precedes: 127,
            p_sep_by_space: 127,
            n_cs_precedes: 127,
            n_sep_by_space: 127,
            p_sign_posn: 127,
            n_sign_posn: 127,
            positive_sign: '',
            negative_sign: '',
            int_frac_digits: 127,
            frac_digits: 127
        };
        phpjs.locales.C.LC_NUMERIC = {
            decimal_point: '.',
            thousands_sep: '',
            grouping: []
        };
        phpjs.locales.C.LC_TIME.c = '%a %b %e %H:%M:%S %Y'; // D_T_FMT
        phpjs.locales.C.LC_TIME.x = '%m/%d/%y'; // D_FMT
        phpjs.locales.C.LC_TIME.X = '%H:%M:%S'; // T_FMT
        phpjs.locales.C.LC_MESSAGES.YESEXPR = '^[yY]';
        phpjs.locales.C.LC_MESSAGES.NOEXPR = '^[nN]';

        phpjs.locales.fr = _copy(phpjs.locales.en);
        phpjs.locales.fr.nplurals = _nplurals2b;
        phpjs.locales.fr.LC_TIME.a = ['dim', 'lun', 'mar', 'mer', 'jeu', 'ven', 'sam'];
        phpjs.locales.fr.LC_TIME.A = ['dimanche', 'lundi', 'mardi', 'mercredi', 'jeudi', 'vendredi', 'samedi'];
        phpjs.locales.fr.LC_TIME.b = ['jan', 'f\u00E9v', 'mar', 'avr', 'mai', 'jun', 'jui', 'ao\u00FB', 'sep', 'oct', 'nov', 'd\u00E9c'];
        phpjs.locales.fr.LC_TIME.B = ['janvier', 'f\u00E9vrier', 'mars', 'avril', 'mai', 'juin', 'juillet', 'ao\u00FBt', 'septembre', 'octobre', 'novembre', 'd\u00E9cembre'];
        phpjs.locales.fr.LC_TIME.c = '%a %d %b %Y %T %Z';
        phpjs.locales.fr.LC_TIME.p = ['', ''];
        phpjs.locales.fr.LC_TIME.P = ['', ''];
        phpjs.locales.fr.LC_TIME.x = '%d.%m.%Y';
        phpjs.locales.fr.LC_TIME.X = '%T';

        phpjs.locales.fr_CA = _copy(phpjs.locales.fr);
        phpjs.locales.fr_CA.LC_TIME.x = '%Y-%m-%d';
    }
    if (!phpjs.locale) {
        phpjs.locale = 'en_US';
        var NS_XHTML = 'http://www.w3.org/1999/xhtml';
        var NS_XML = 'http://www.w3.org/XML/1998/namespace';
        if (d.getElementsByTagNameNS && d.getElementsByTagNameNS(NS_XHTML, 'html')[0]) {
            if (d.getElementsByTagNameNS(NS_XHTML, 'html')[0].getAttributeNS && d.getElementsByTagNameNS(NS_XHTML, 'html')[0].getAttributeNS(NS_XML, 'lang')) {
                phpjs.locale = d.getElementsByTagName(NS_XHTML, 'html')[0].getAttributeNS(NS_XML, 'lang');
            } else if (d.getElementsByTagNameNS(NS_XHTML, 'html')[0].lang) { // XHTML 1.0 only
                phpjs.locale = d.getElementsByTagNameNS(NS_XHTML, 'html')[0].lang;
            }
        } else if (d.getElementsByTagName('html')[0] && d.getElementsByTagName('html')[0].lang) {
            phpjs.locale = d.getElementsByTagName('html')[0].lang;
        }
    }
    phpjs.locale = phpjs.locale.replace('-', '_'); // PHP-style
    // Fix locale if declared locale hasn't been defined
    if (!(phpjs.locale in phpjs.locales)) {
        if (phpjs.locale.replace(/_[a-zA-Z]+$/, '') in phpjs.locales) {
            phpjs.locale = phpjs.locale.replace(/_[a-zA-Z]+$/, '');
        }
    }

    if (!phpjs.localeCategories) {
        phpjs.localeCategories = {
            'LC_COLLATE': phpjs.locale,
            // for string comparison, see strcoll()
            'LC_CTYPE': phpjs.locale,
            // for character classification and conversion, for example strtoupper()
            'LC_MONETARY': phpjs.locale,
            // for localeconv()
            'LC_NUMERIC': phpjs.locale,
            // for decimal separator (See also localeconv())
            'LC_TIME': phpjs.locale,
            // for date and time formatting with strftime()
            'LC_MESSAGES': phpjs.locale // for system responses (available if PHP was compiled with libintl)
        };
    }
    // END REDUNDANT
    if (locale === null || locale === '') {
        locale = this.getenv(category) || this.getenv('LANG');
    } else if (Object.prototype.toString.call(locale) === '[object Array]') {
        for (i = 0; i < locale.length; i++) {
            if (!(locale[i] in this.php_js.locales)) {
                if (i === locale.length - 1) {
                    return false; // none found
                }
                continue;
            }
            locale = locale[i];
            break;
        }
    }

    // Just get the locale
    if (locale === '0' || locale === 0) {
        if (category === 'LC_ALL') {
            for (categ in this.php_js.localeCategories) {
                cats.push(categ + '=' + this.php_js.localeCategories[categ]); // Add ".UTF-8" or allow ".@latint", etc. to the end?
            }
            return cats.join(';');
        }
        return this.php_js.localeCategories[category];
    }

    if (!(locale in this.php_js.locales)) {
        return false; // Locale not found
    }

    // Set and get locale
    if (category === 'LC_ALL') {
        for (categ in this.php_js.localeCategories) {
            this.php_js.localeCategories[categ] = locale;
        }
    } else {
        this.php_js.localeCategories[category] = locale;
    }
    return locale;
}

// Source: http://phpjs.org/functions/urldecode:572
function urldecode (str) {
    return decodeURIComponent((str + '').replace(/\+/g, '%20'));
}

// Source: http://phpjs.org/functions/urlencode:573
function urlencode (str) {
    str = (str + '').toString();

    // Tilde should be allowed unescaped in future versions of PHP (as reflected below), but if you want to reflect current
    // PHP behavior, you would need to add ".replace(/~/g, '%7E');" to the following.
    return encodeURIComponent(str).replace(/!/g, '%21').replace(/'/g, '%27').replace(/\(/g, '%28').
    replace(/\)/g, '%29').replace(/\*/g, '%2A').replace(/%20/g, '+');
}

// from http://phpjs.org/functions/sprintf:522
function sprintf () {
    var regex = /%%|%(\d+\$)?([-+\'#0 ]*)(\*\d+\$|\*|\d+)?(\.(\*\d+\$|\*|\d+))?([scboxXuidfegEG])/g;
    var a = arguments,
        i = 0,
        format = a[i++];

    // pad()
    var pad = function (str, len, chr, leftJustify) {
        if (!chr) {
            chr = ' ';
        }
        var padding = (str.length >= len) ? '' : Array(1 + len - str.length >>> 0).join(chr);
        return leftJustify ? str + padding : padding + str;
    };

    // justify()
    var justify = function (value, prefix, leftJustify, minWidth, zeroPad, customPadChar) {
        var diff = minWidth - value.length;
        if (diff > 0) {
            if (leftJustify || !zeroPad) {
                value = pad(value, minWidth, customPadChar, leftJustify);
            } else {
                value = value.slice(0, prefix.length) + pad('', diff, '0', true) + value.slice(prefix.length);
            }
        }
        return value;
    };

    // formatBaseX()
    var formatBaseX = function (value, base, prefix, leftJustify, minWidth, precision, zeroPad) {
        // Note: casts negative numbers to positive ones
        var number = value >>> 0;
        prefix = prefix && number && {
            '2': '0b',
            '8': '0',
            '16': '0x'
        }[base] || '';
        value = prefix + pad(number.toString(base), precision || 0, '0', false);
        return justify(value, prefix, leftJustify, minWidth, zeroPad);
    };

    // formatString()
    var formatString = function (value, leftJustify, minWidth, precision, zeroPad, customPadChar) {
        if (precision != null) {
            value = value.slice(0, precision);
        }
        return justify(value, '', leftJustify, minWidth, zeroPad, customPadChar);
    };

    // doFormat()
    var doFormat = function (substring, valueIndex, flags, minWidth, _, precision, type) {
        var number;
        var prefix;
        var method;
        var textTransform;
        var value;

        if (substring == '%%') {
            return '%';
        }

        // parse flags
        var leftJustify = false,
            positivePrefix = '',
            zeroPad = false,
            prefixBaseX = false,
            customPadChar = ' ';
        var flagsl = flags.length;
        for (var j = 0; flags && j < flagsl; j++) {
            switch (flags.charAt(j)) {
            case ' ':
                positivePrefix = ' ';
                break;
            case '+':
                positivePrefix = '+';
                break;
            case '-':
                leftJustify = true;
                break;
            case "'":
                customPadChar = flags.charAt(j + 1);
                break;
            case '0':
                zeroPad = true;
                break;
            case '#':
                prefixBaseX = true;
                break;
            }
        }

        // parameters may be null, undefined, empty-string or real valued
        // we want to ignore null, undefined and empty-string values
        if (!minWidth) {
            minWidth = 0;
        } else if (minWidth == '*') {
            minWidth = +a[i++];
        } else if (minWidth.charAt(0) == '*') {
            minWidth = +a[minWidth.slice(1, -1)];
        } else {
            minWidth = +minWidth;
        }

        // Note: undocumented perl feature:
        if (minWidth < 0) {
            minWidth = -minWidth;
            leftJustify = true;
        }

        if (!isFinite(minWidth)) {
            throw new Error('sprintf: (minimum-)width must be finite');
        }

        if (!precision) {
            precision = 'fFeE'.indexOf(type) > -1 ? 6 : (type == 'd') ? 0 : undefined;
        } else if (precision == '*') {
            precision = +a[i++];
        } else if (precision.charAt(0) == '*') {
            precision = +a[precision.slice(1, -1)];
        } else {
            precision = +precision;
        }

        // grab value using valueIndex if required?
        value = valueIndex ? a[valueIndex.slice(0, -1)] : a[i++];

        switch (type) {
        case 's':
            return formatString(String(value), leftJustify, minWidth, precision, zeroPad, customPadChar);
        case 'c':
            return formatString(String.fromCharCode(+value), leftJustify, minWidth, precision, zeroPad);
        case 'b':
            return formatBaseX(value, 2, prefixBaseX, leftJustify, minWidth, precision, zeroPad);
        case 'o':
            return formatBaseX(value, 8, prefixBaseX, leftJustify, minWidth, precision, zeroPad);
        case 'x':
            return formatBaseX(value, 16, prefixBaseX, leftJustify, minWidth, precision, zeroPad);
        case 'X':
            return formatBaseX(value, 16, prefixBaseX, leftJustify, minWidth, precision, zeroPad).toUpperCase();
        case 'u':
            return formatBaseX(value, 10, prefixBaseX, leftJustify, minWidth, precision, zeroPad);
        case 'i':
        case 'd':
            number = (+value) | 0;
            prefix = number < 0 ? '-' : positivePrefix;
            value = prefix + pad(String(Math.abs(number)), precision, '0', false);
            return justify(value, prefix, leftJustify, minWidth, zeroPad);
        case 'e':
        case 'E':
        case 'f':
        case 'F':
        case 'g':
        case 'G':
            number = +value;
            prefix = number < 0 ? '-' : positivePrefix;
            method = ['toExponential', 'toFixed', 'toPrecision']['efg'.indexOf(type.toLowerCase())];
            textTransform = ['toString', 'toUpperCase']['eEfFgG'.indexOf(type) % 2];
            value = prefix + Math.abs(number)[method](precision);
            return justify(value, prefix, leftJustify, minWidth, zeroPad)[textTransform]();
        default:
            return substring;
        }
    };

    return format.replace(regex, doFormat);
}
