// XORCipher - Super simple encryption using XOR and Base64
//
// Depends on [Underscore](http://underscorejs.org/).
//
// As a warning, this is **not** a secure encryption algorythm. It uses a very
// simplistic keystore and will be easy to crack.
// Examples
// --------
//
//     XORCipher.encode("test", "foobar");   // => "EgocFhUX"
//     XORCipher.decode("test", "EgocFhUX"); // => "foobar"
//
// Copyright © 2013 Devin Weaver <suki@tritarget.org>
//
// This program is free software. It comes without any warranty, to
// the extent permitted by applicable law. You can redistribute it
// and/or modify it under the terms of the Do What The Fuck You Want
// To Public License, Version 2, as published by Sam Hocevar. See
// http://www.wtfpl.net/ for more details.

/* jshint forin:true, noarg:true, noempty:true, eqeqeq:true, strict:true,
   undef:true, unused:true, curly:true, browser:true, indent:2, maxerr:50 */
/* global _ */
(function(exports) {
  "use strict";

  var XORCipher = {
    encode: function(key, data) {
      return xor_encrypt(key, data);
    },
    decode: function(key, data) {
      return xor_decrypt(key, data);
    }
  };

  function keyCharAt(key, i) {
    return key.charCodeAt( Math.floor(i % key.length) );
  }

  function xor_encrypt(key, data) {
    return Array.prototype.map.call(data, function(c, i) {
      return c.charCodeAt(0) ^ keyCharAt(key, i);
    });
  }

  function xor_decrypt(key, data) {
    return Array.prototype.map.call(data, function(c, i) {
      return String.fromCharCode( c ^ keyCharAt(key, i) );
    }).join("");
  }

  exports.XORCipher = XORCipher;

})(this);