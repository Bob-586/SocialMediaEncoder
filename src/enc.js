/**
 * @author Robert Strutts
 * @copyright 2020 LGPL-v3.0
 * @version 1.3
 */

// Remove Duplicates from an array
Array.prototype.unique = function() {
  return this.filter(function (value, index, self) { 
    return self.indexOf(value) === index;
  });
};

// Make a Key as Password
function rnd_hex_gen() {
    var min = 1001;
    var max = 999999999;
    var yourNumber = Math.floor(Math.random()*(max-min+1)+min);
    hexString = yourNumber.toString(16);
    return hexString;
}

function search_words(mode, key, pass, ds) {
    var bad = was_found(words['banned'], key);
    if (bad !== false) {
        throw "You are a very Bad Man, I shall report you!";
    }
    for(var skey in search_new) {
        if (search_new.hasOwnProperty(skey)) {
            var sw = search_new[skey];
        } else {
            continue;
        }
        var found_at = was_found(words[sw], key);
        if (found_at !== false) {
            var data = {};
            if (pass === '') {
                data[sw] = found_at;
                return data;
            }
            data[sw] = hideme(mode, found_at.toString(), pass + ds);
            return data;
        }
    }
    return false;
}

function fetch_word(search, mode, input, pass, ds) {
    if (input.hasOwnProperty('n')) {
        return false; // It was a custom Unique word, so bail as false
    }
    for(var skey in search) {
        if (search.hasOwnProperty(skey)) {
            var sw = search[skey];
        } else {
            continue;
        }
        if (input.hasOwnProperty(skey)) {
            var w = input[skey];
            if (pass === '') {
                return words[sw][w];
            } 
            var index = unhideme(mode, w, pass + ds);
            return words[sw][Number(index)];
        } 
    }
}

// Check if key was found from array called data 
function was_found(data, key) {
   for(z=0; z < data.length; z++) {
       var word = data[z];
       if (word === key) return z; 
   }
   return false;
}

function hideme(mode, data, key) {
    if (mode === 1) {
       var encrypted = CryptoJS.AES.encrypt(data, key, { format: JsonFormatter });
       return btoa(encrypted);
    } else if (mode === 2) {
        var encrypted = CryptoJS.DES.encrypt(data, key, { format: JsonFormatter });
        return btoa(encrypted);
    } else if (mode === 3) {
        var encrypted = XORCipher.encode(key, data);
        return encrypted;
    } else {
        return data;
    }
}

function unhideme(mode, data, key) {
    if (mode === 1) {
      var code = atob(data);
      var decrypted = CryptoJS.AES.decrypt(code, key, { format: JsonFormatter });
      return decrypted.toString(CryptoJS.enc.Utf8) + " ";
    } else if (mode === 2) {
      var code = atob(data);
      var decrypted = CryptoJS.DES.decrypt(code, key, { format: JsonFormatter });
      return decrypted.toString(CryptoJS.enc.Utf8) + " ";
    } else if (mode === 3) {
      var code = data;
      var decrypted = XORCipher.decode(key, code);
      return decrypted + " ";
    } else {
      return data + " ";  
    }
}

function btn_enc() {
   try { 
        var mode = document.getElementById('mode').value;
        var omode = document.getElementById('omode').value;
        var text = document.getElementById('enc').value;
        var pwd = document.getElementById('pwd').value;
                        
        var ret = do_enc(mode, omode, text, pwd);
        document.getElementById('enc').value = ret;
        
        // Enable make image button, now that we have cypher-text
        var element =  document.getElementById('make');
        if (typeof(element) != 'undefined' && element != null) {
            element.disabled = false;
        }
        
   } catch (err) {
     if (typeof(err.message) != 'undefined') { 
        console.warn(err.message);
        alert(err.message);
     } else {
        console.warn(err);
        alert(err);         
     }
   }
}

function phone_check(input, e) {
   var max = input.length;
   var check = input[e];
   var d1 = /^\+?[-. ]?([0-9]{3})$/;
   var d2 = /^\+?[-. ]?([0-9]{4})$/;
   var d3 = /^\+?[-. ]?([0-9]{3})[-. ]?([0-9]{4})$/;
   var d4 = /^\+?[-. ]?([0-9]{4})[-. ]?([0-9]{4})$/;

   if (e+1 < max) {
       var s1 = input[e+1];
       if (s1.match(d1) || s1.match(d2) || s1.match(d3) || s1.match(d4)) {
          check += s1;
       }
   }
   if (e+2 < max) {
       var s2 = input[e+2];
       if (s2.match(d1) || s2.match(d2) || s2.match(d3) || s2.match(d4)) {
          check += s2;
       }
   }
   var phoneno1 = /^(\+?1 ?)?\(?([0-9]{3})\)?[-. ]?([0-9]{3})[-. ]?([0-9]{4})$/;
   var phoneno2 = /^(\+?1 ?)?\(?([0-9]{2})\)?[-. ]?([0-9]{4})[-. ]?([0-9]{4})$/;
   if (check.match(phoneno1) || check.match(phoneno2)) {
       throw "No phone numbers allowed!";
   }
}

function email_check(email) {
    var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    if (re.test(String(email).toLowerCase())) {
        throw "No emails allowed!";
    }
}

function better_chr_encoder(text) {
    var ret_text = "";
    for (var xe = 0; xe < text.length; xe++) {
        var st = text.charAt(xe);
        switch(st) {
            case "!": ret_text += " !"; break;
            case ".": ret_text += " ."; break;
            case ",": ret_text += " ,"; break;     
            case ";": ret_text += " ;"; break;
            case ":": ret_text += " :"; break;
            case "(": ret_text += " ("; break;
            case ")": ret_text += " )"; break;
            case "'": ret_text += " '"; break;
            case '"': ret_text += ' "'; break;
            default: ret_text += st;
        }
    }
    return ret_text;
}

function do_enc(mode, omode, text, pwd) {
    
   if (text.length > 40 && text.trim().indexOf(" ") === -1) {
       throw "text only allowed, encode once";
   } 
    
   var d = new Date();
   var ds = d.toString();
   switch(omode) {
       case 'aes': omode = 1; break;
       case 'des': omode = 2; break;
       case 'xor': omode = 3; break;
       default: omode = 4;
   }
   switch(mode) {
       case 'aes': mode = 1; break;
       case 'des': mode = 2; break;
       case 'xor': mode = 3; break;
       default: mode = 4;
   }
   
   var str = text.toString(); // Cast to String
   var str_spaced = better_chr_encoder(str);
   var clean = str_spaced.replace(/<[^>]*>/g, ''); // Strip HTML Tags
   var arr = clean.split(" "); // Explode on spaces
   var u = arr.unique(); // Remove duplicate words from text
   
   var my_arr = [];
   for(e=0; e < u.length; e++) {
       phone_check(u, e);
       email_check(u[e]);
       var sw_ans = search_words(mode, u[e], pwd, ds);
       if (sw_ans !== false) {
           continue; // Skip words that exist in words.js file
       }
       my_arr.push(u[e]); // Array of only cumtom words not defined by words.js
   }
 
   var sort = my_arr.sort(); // Get in alphabetical order
   var rev = sort.reverse(); // Do backwards, to make harder to guess
   
   var order = [];
   for(x=0; x < arr.length; x++) {
       var key = arr[x];
       var sw_ans = search_words(mode, key, pwd, ds); // Get key + index from words.js
       if (sw_ans !== false) {
           order.push(sw_ans); // Save order on defined word from words.js
           continue;
       }
       var ans = was_found(rev, key); // Get Position of your word
       if (ans === false) {
           
       } else {
         order.push({ n: ans }); // Save order on your  
       }   
   }
   
   var beef = {}; // beef is the Unique Words not in words.js file
   for(i=0; i < rev.length; i++) {
       beef[i] = hideme(mode, rev[i], pwd + ds); // save/encrypt your word as it's, now defined
   }
  
   var random_hex_key = rnd_hex_gen();
   var ok = rnd_hex_gen();
   var pork = hideme(1, JSON.stringify(beef), random_hex_key + pwd);
   var my_order = hideme(omode, JSON.stringify(order), ok + pwd);
   var a = { order: my_order, ds: ds, mode:mode, omode: omode, beef: pork, ok: ok, hk: random_hex_key, v:"1.3" };
   // console.log(JSON.stringify(a));
   return btoa(JSON.stringify(a));   
}

function btn_dec() {
   try {  
     var text = document.getElementById('enc').value;
     var pwd = document.getElementById('pwd').value; 
     document.getElementById('enc').value = do_dec(text, pwd);
   } catch (err) {
     if (typeof(err.message) != 'undefined') { 
        console.warn(err.message);
        alert(err.message);
     } else {
        console.warn(err);
        alert(err);         
     }
   }
   // Disable make image button, as we no longer have cypher-text to use
   var element =  document.getElementById('make');
   if (typeof(element) != 'undefined' && element != null) {
       element.disabled = true;
   }
}

function get_hyper_links(s_text) {
    var tt = s_text.trim();
    if (tt.indexOf("http://") === 0 || tt.indexOf("https://") === 0) {
        return '<a href="'+tt+'" target="_blank" class="linkmsg">Link To: '+tt+'</a> &nbsp;';
    }
    return false;
}

function get_reformatted(s_text) {
    var tt = s_text.trim();
    if (tt.indexOf("[") === 0 && tt.indexOf("]") === tt.length -1) {
        return '<b>'+s_text+'</b>';
    }
    if (tt.indexOf("(") === 0 && tt.indexOf(")") === tt.length -1) {
        return '<i>'+s_text+'</i>';
    }
    if (tt.indexOf("{") === 0 && tt.indexOf("}") === tt.length -1) {
        return '<del>'+s_text+'</del>';
    }
    if (tt.indexOf("---") !== -1) {
        return s_text.replace(/-{2,}/g, "<hr class=\"msghr\">"); // Replace Dashes w/ HR
    }
    return s_text;
}

function format_text(s_text) {
    var links = get_hyper_links(s_text);
    if (links !== false) {
        return links;
    }
    return get_reformatted(s_text);
}

function do_dec(text, pwd) {
   var s = atob(text);
   var j = JSON.parse(s);
   
   if (j.hasOwnProperty('v')) {
       var v = j['v'];
   } else {
       var v = "1";
   }
   
   if (v !== "1") {
       var beef = j['beef'];
       var hk = j['hk'];
       var pork = JSON.parse(unhideme(1, beef, hk + pwd));
   }
   
   if (v === "1" || v === "1.1") {
       var order = j['order'];
   } else {
       var omode = j['omode'];
       var ok = j['ok'];
       var o = j['order'];
       var order = JSON.parse(unhideme(omode, o, ok + pwd));
   }
   // Fetch words from words.js
   if (v === "1" || v === "1.1" || v === "1.2") {
       var search = search_old;
   } else {
       var search = search_new;
   }
   
//   console.log(order);   
   var ds = j['ds'];
   var mode = j['mode'];
   
   var ret = '';
   for(i=0; i < order.length; i++) {
       var fn = order[i]; // Get index of existing word from words.js
       var found = fetch_word(search, mode, fn, pwd, ds);
       if (found !== false) {
           ret += found + " "; // fetched word from words.js
           continue;
       } 
       var n = order[i].n; // Get index of new word
       if (v !== "1") {
           ret += format_text(unhideme(mode, pork[n], pwd + ds));
       } else {
           ret += format_text(unhideme(mode, j[n], pwd + ds));
       }
   }
   return ret;  
}