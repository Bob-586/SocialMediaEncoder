function onChange(input) { document.querySelector(".input").value = input; }
function onKeyPress(button) {  if (button === "{shift}" || button === "{lock}") handleShift(); }
function handleShift() {
  var currentLayout = myKeyboard.options.layoutName;
  var shiftToggle = currentLayout === "default" ? "shift" : "default";

  myKeyboard.setOptions({
    layoutName: shiftToggle
  });
}

var Keyboard = window.SimpleKeyboard.default;
var myKeyboard = new Keyboard({
    onChange: function(input) { onChange(input); },
    onKeyPress: function(button) { onKeyPress(button); } ,
    newLineOnEnter: true,
    layout: {
        'default': [
          '` 1 2 3 4 5 6 7 8 9 0 - = {bksp}',
          'q w e r t y u i o p [ ] \\',
          '{lock} a s d f g h j k l ; \' {enter}',
          '{shift} z x c v b n m , . / {shift}',
          'https:// .com {space}'
        ],
        'shift': [
          '~ ! @ # $ % ^ & * ( ) _ + {bksp}',
          'Q W E R T Y U I O P { } |',
          '{lock} A S D F G H J K L : " {enter}',
          '{shift} Z X C V B N M < > ? {shift}',
          'http:// .com {space}'
        ]
    }
});
    
function show_vkb() {
   var sq = document.getElementById('dvkb').style.display;
   if (sq === "block") {
        document.getElementById('dvkb').style.display = "none";
        document.getElementById('btn-svkb').innerHTML = "Show Virtual Keyboard, to avoid key stroke logging";   
   } else {
        document.getElementById('dvkb').style.display = "block";
        document.getElementById('btn-svkb').innerHTML = "Hide Virtual Keyboard";       
   }
}    