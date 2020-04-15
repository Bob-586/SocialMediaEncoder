/**
 * @author Robert Strutts
 * @copyright 2020 LGPL-v3.0
 * @version 1.2
 */


Array.prototype.unique = function() {
  return this.filter(function (value, index, self) { 
    return self.indexOf(value) === index;
  });
};

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
        var grr = "You are a very Bad Man, I shall report you!";
      //  alert(grr);
        throw grr;
    }
    for(p=0; p < search.length; p++) {
        var sw = search[p];
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

function fetch_word(mode, input, pass, ds) {
    if (input.hasOwnProperty('n')) {
        return false;
    }
    for(f=0; f < search.length; f++) {
        var sw = search[f];
        if (input.hasOwnProperty(sw)) {
            var w = input[sw];
            if (pass === '') {
                return words[sw][w];
            } 
            var index = unhideme(mode, w, pass + ds);
            return words[sw][Number(index)];
        } 
    }
}

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
        
        switch(omode) {
            case 'aes': omode = 1; break;
            case 'des': omode = 2; break;
            case 'xor': omode = 3; break;
            default: omode = 4;
        }
        
        var text = document.getElementById('enc').value;
        var pwd = document.getElementById('pwd').value;
        var ret = do_enc(mode, text, pwd);
        var beef = ret.beef;
        var main = ret.main;
        
        var random_hex_key = rnd_hex_gen();
        var ok = rnd_hex_gen();
        
        var pork = hideme(1, JSON.stringify(beef), random_hex_key + pwd);

        var mo = main.order;
        console.log(mo);
        var order = hideme(omode, JSON.stringify(mo), ok + pwd);
        
        var ds = main.ds;
        var mode = main.mode;

        var a = { order: order, ds: ds, mode:mode, omode: omode, beef: pork, ok: ok, hk: random_hex_key, v:"1.2" };

//        console.log(JSON.stringify(a));
        
        var secret = btoa(JSON.stringify(a)); 
        document.getElementById('enc').value = secret;
   } catch (err) {
     console.warn(err.message);
   }
   var element =  document.getElementById('make');
   if (typeof(element) != 'undefined' && element != null) {
       element.disabled = false;
   }
}

function do_enc(mode, text, pwd) {
   var d = new Date();
   var ds = d.toString();  
   switch(mode) {
       case 'aes': mode = 1; break;
       case 'des': mode = 2; break;
       case 'xor': mode = 3; break;
       default: mode = 4;
   }
   
   var str = text.toString();
   var clean = str.replace(/<[^>]*>/g, '');
   var arr = clean.split(" ");
   var u = arr.unique();
   
   var my_arr = [];
   for(e=0; e < u.length; e++) {
       var sw_ans = search_words(mode, u[e], pwd, ds);
       if (sw_ans !== false) {
           continue;
       }
       my_arr.push(u[e]);
   }
 
   var sort = my_arr.sort(); 
   var rev = sort.reverse();
   
   var order = [];
   for(x=0; x < arr.length; x++) {
       var key = arr[x];
       var sw_ans = search_words(mode, key, pwd, ds);
       if (sw_ans !== false) {
           order.push(sw_ans);
           continue;
       }
       var ans = was_found(rev, key);
       if (ans === false) {
           
       } else {
         order.push({ n: ans });  
       }   
   }
   
   var a = { order: order, ds: ds, mode: mode };
   var beef = {};
   for(i=0; i < rev.length; i++) {
       beef[i] = hideme(mode, rev[i], pwd + ds);
   }
  
   return { main: a, beef: beef };
}

function btn_dec() {
   try {  
     var text = document.getElementById('enc').value;
     var pwd = document.getElementById('pwd').value; 
     document.getElementById('enc').value = do_dec(text, pwd);
   } catch (err) {
     console.warn(err.message);
   }
   var element =  document.getElementById('make');
   if (typeof(element) != 'undefined' && element != null) {
       element.disabled = true;
   }
}

function do_dec(text, pwd) {
   var s = atob(text);
   var j = JSON.parse(s);
   
   if (j.hasOwnProperty('v')) {
       var v = j['v'];
   } else {
       var v = "1";
   }
   
   if (v === "1.1" || v === "1.2") {
       var beef = j['beef'];
       var hk = j['hk'];
       var pork = JSON.parse(unhideme(1, beef, hk + pwd));
   }
   
   if (v === "1.2") {
       var omode = j['omode'];
       var ok = j['ok'];
       var o = j['order'];
       var order = JSON.parse(unhideme(omode, o, ok + pwd));
   } else {
       var order = j['order'];
   }
   
   var ds = j['ds'];
   var mode = j['mode'];
   
   var ret = '';
   for(i=0; i < order.length; i++) {
       var fn = order[i];
       var found = fetch_word(mode, fn, pwd, ds);
       if (found !== false) {
           ret += found + " ";
           continue;
       } 
       var n = order[i].n;
       if (v === "1.1" || v === "1.2") {
           ret += unhideme(mode, pork[n], pwd + ds);
       } else {
           ret += unhideme(mode, j[n], pwd + ds);
       }
   }
   return ret;  
}