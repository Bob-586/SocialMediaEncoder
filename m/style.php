<?php

function do_options_backwards(array $options, string $default = '', string $select_by = 'text', array $a = array()): string {
    $more = '';
    if (count($a)) {
      foreach ($a as $k => $v) {
        $more .= " {$k}=\"{$v}\"";
      }
    }

    $values = '';
    foreach ($options as $text => $value) {
      $compair_to = ($select_by == 'text') ? $text : $value;
      $selected = (!empty($default) && $default == $compair_to) ? 'selected' : '';
      $values .= "<option value=\"{$value}\" " . $selected . " " . $more . ">{$text}</option>" . PHP_EOL;
    }
    return $values;
}

$styles = [
    "default" => "",
    "blue_background" => "background-color: cornflowerblue; color: whitesmoke;",
    "red_background" => "background-color: red; color: whitesmoke;",
    "dark_red_background" => "background-color: darkred; color: whitesmoke;",
    "red_text" => "background-color: white; color: red;",
    "dark_blue_text" => "background-color: white; color: dark_blue;",
    "fiesta" => "color:#fff; background-color: #DD4132;",
    "jester-red" => "color:#fff; background-color: #9E1030;",
    "turmeric" => "color:#fff; background-color: #FE840E;",
    "living-coral" => "color:#fff; background-color: #FF6F61;",
    "pink-peacock" => "color:#fff; background-color: #C62168;",
    "pepper-stem" => "color:#fff; background-color: #8D9440;",
    "aspen-gold" => "color:#000; background-color: #FFD662;",
    "princess-blue" => "color:#fff; background-color: #00539C;",
    "toffee" => "color:#fff; background-color: #755139;",
    "mango-mojito" => "color:#000; background-color: #D69C2F;",
    "terrarium-moss" => "color:#fff; background-color: #616247;",
    "sweet-lilac" => "color:#000; background-color: #E8B5CE;",
    "soybean" => "color:#000; background-color: #D2C29D;",
    "eclipse" => "color:#fff; background-color: #343148;",
    "sweet-corn" => "color:#000; background-color: #F0EAD6;",
    "brown-granite" => "color:#fff; background-color: #615550;",
    "chili-pepper" => "color:#fff; background-color: #9B1B30;",
    "biking-red" => "color:#fff; background-color: #77212E;",
    "creme-de-peche" => "color:#000; background-color: #F5D6C6;",
    "peach-pink" => "color:#fff; background-color: #FA9A85;",
    "rocky-road" => "color:#fff; background-color: #5A3E36;",
    "fruit-dove" => "color:#fff; background-color: #CE5B78;",
    "sugar-almond" => "color:#fff; background-color: #935529;",
    "dark-cheddar" => "color:#fff; background-color: #E08119;",
    "galaxy-blue" => "color:#fff; background-color: #2A4B7C;",
    "bluestone" => "color:#fff; background-color: #577284;",
    "orange-tiger" => "color:#fff; background-color: #F96714;",
    "eden" => "color:#fff; background-color: #264E36;",
    "vanilla-custard" => "color:#000; background-color: #F3E0BE;",
    "evening-blue" => "color:#fff; background-color: #2A293E;",
    "paloma" => "color:#fff; background-color: #9F9C99;",
    "guacamole" => "color:#fff; background-color: #797B3A;",
    "red-pear" => "color:#fff; background-color: #7F4145;",
    "valiant-poppy" => "color:#fff; background-color: #BD3D3A;",
    "nebulas-blue" => "color:#fff; background-color: #3F69AA;",
    "ceylon-yellow" => "color:#000; background-color: #D5AE41;",
    "martini-olive" => "color:#fff; background-color: #766F57;",
    "russet-orange" => "color:#fff; background-color: #E47A2E;",
    "crocus-petal" => "color:#000; background-color: #BE9EC9;",
    "limelight" => "color:#000; background-color: #F1EA7F;",
    "quetzal-green" => "color:#fff; background-color: #006E6D;",
    "sargasso-sea" => "color:#fff; background-color: #485167;",
    "tofu" => "color:#000; background-color: #EAE6DA;",
    "almond-buff" => "color:#000; background-color: #D1B894;",
    "quiet-gray" => "color:#000; background-color: #BCBCBE;",
    "meerkat" => "color:#000; background-color: #95dee3;",
    "meadowlark" => "color:#000; background-color: #ECDB54;",
    "cherry-tomato" => "color:#fff; background-color: #E94B3C;",
    "little-boy-blue" => "color:#000; background-color: #6F9FD8;",
    "chili-oil" => "color:#fff; background-color: #944743;",
    "pink-lavender" => "color:#000; background-color: #DBB1CD;",
    "blooming-dahlia" => "color:#000; background-color: #EC9787;",
    "arcadia" => "color:#fff; background-color: #00A591;",
    "emperador" => "color:#fff; background-color: #6C4F3D;",
    "ultra-violet" => "color:#fff; background-color: #6B5B95;",
    "almost-mauve" => "color:#000; background-color: #EADEDB;",
    "spring-crocus" => "color:#fff; background-color: #BC70A4;",
    "lime-punch" => "color:#000; background-color: #BFD641;",
    "sailor-blue" => "color:#fff; background-color: #2E4A62;",
    "harbor-mist" => "color:#000; background-color: #B4B7BA;",
    "warm-sand" => "color:#000; background-color: #C0AB8E;",
    "coconut-milk" => "color:#000; background-color: #F0EDE5;"
];

$cursive = [
    "Default Font" => "",
    "Girassol_Title_Text" => "font-family: 'Girassol', cursive;",
    "Dancing Script" => "font-family: 'Dancing Script', cursive;",
    "Permanent_Marker" => "font-family: 'Permanent Marker', cursive;",
    "Gochi_Hand" => "font-family: 'Gochi Hand', cursive;",
    "Amiri" => "font-family: 'Amiri', serif;"
];

$text_align = [
    "Left" => "text-align: left;",
    "Center" => "text-align: center;",
    "Right" => "text-align: right;"
];

$fonts_size = [];
for($size = 16; $size < 47; $size++ ) {
    $fonts_size[$size] = "font-size: {$size}px;";
}
?>
<section>
<fieldset>
    <legend>Customize Message Style</legend>
<label for="cursive">Use Font</label> <select id="cursive" onchange="document.getElementById('sample-text').style = get_styles();"><?= do_options_backwards($cursive); ?></select>
<label for="align">Text Align</label> <select id="align" onchange="document.getElementById('sample-text').style = get_styles();"><?= do_options_backwards($text_align); ?></select>
<label for="size">Font Size</label> <select id="size" onchange="document.getElementById('sample-text').style = get_styles();"><?= do_options_backwards($fonts_size, '20'); ?></select>
<label for="color">Colors</label> <select id="color" onchange="document.getElementById('sample-text').style = get_styles();"><?= do_options_backwards($styles); ?></select>

<p id="sample-text" style="display: none;">
    Sample Text , Welcome . . . .<br>
    Line #2 , More TEXT for you !
</p>
</fieldset>
</section>

<script type="text/javascript">
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
    
    var c = sme_get_cookie('sme_r_styles');
    if (c !== "") {
        var s = atob(c);
        var j = JSON.parse(s);
        set_index_for_cbo('cursive', j.cursive);
        set_index_for_cbo('align', j.align);
        set_index_for_cbo('size', j.size);
        set_index_for_cbo('color', j.color);
    }
</script>

<script type="text/javascript">
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
</script>    