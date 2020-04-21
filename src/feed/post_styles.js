    function fetch_styles() {
      postAjax('style.php', {}, function(text) {
        document.getElementById('styles').innerHTML = text;
        var c = sme_get_cookie('sme_r_styles');
        if (c !== "") {
             var s = atob(c);
             var j = JSON.parse(s);
             set_index_for_cbo('cursive', j.cursive);
             set_index_for_cbo('align', j.align);
             set_index_for_cbo('size', j.size);
             set_index_for_cbo('color', j.color);
        }
      });
    }    

    /* Set Cookie */
    function sme_set_cookie(cname, cvalue, exdays) {
        if (!localStorage.getItem('cookieconsent')) {
            return "";
        }
        var d = new Date();
        d.setTime(d.getTime() + (exdays*24*60*60*1000));
        var expires = "expires="+d.toUTCString();
        document.cookie = cname + "=" + cvalue + "; " + expires;
    }

    /* Get Cookie */
    function sme_get_cookie(cname) {
        var name = cname + "=";
        var ca = document.cookie.split(';');
        for(var i=0; i<ca.length; i++) {
            var c = ca[i];
            while (c.charAt(0)==' ') c = c.substring(1);
            if (c.indexOf(name) != -1) return c.substring(name.length,c.length);
        }
        return "";
    }    

    function get_index_from_cbo(elme_id) {
        var me = document.getElementById(elme_id);
        return me.selectedIndex;
    }
    
    function set_index_for_cbo(elme_id, index) {
        var me = document.getElementById(elme_id);
        me.selectedIndex = index;
    }
    
    function remember_styles() {
        var oj = { cursive: get_index_from_cbo('cursive'), align: get_index_from_cbo('align'), size: get_index_from_cbo('size'), color: get_index_from_cbo('color') };
        var s = btoa(JSON.stringify(oj));
        sme_set_cookie('sme_r_styles', s, 2);
    }
    
    function get_styles() {
        remember_styles();
        return document.getElementById('cursive').value + document.getElementById('align').value + document.getElementById('size').value + document.getElementById('color').value; 
    }
    
(function() {
	if (!localStorage.getItem('cookieconsent')) {
		document.body.innerHTML += '\
		<div class="cookieconsent" style="position:fixed;padding:4px;left:0;bottom:0;background-color:#000;color:#FFF;text-align:center;width:100%;z-index:99999;">\
			This site uses cookies. By continuing to use this website, you agree to their use. \
			<a href="#" style="color:#CCCCCC;">I Understand</a>\
		</div>\
		';
		document.querySelector('.cookieconsent a').onclick = function(e) {
			e.preventDefault();
			document.querySelector('.cookieconsent').style.display = 'none';
			localStorage.setItem('cookieconsent', true);
		};
	}
    
})();