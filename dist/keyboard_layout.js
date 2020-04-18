let Keyboard = window.SimpleKeyboard.default;
let myKeyboard = new Keyboard({
  onChange: input => onChange(input),
  onKeyPress: button => onKeyPress(button),
  newLineOnEnter: true,
  layout: {
  'default': [
    '` 1 2 3 4 5 6 7 8 9 0 - = {bksp}',
    'q w e r t y u i o p [ ] \\',
    '{lock} a s d f g h j k l ; \' {enter}',
    '{shift} z x c v b n m , . / {shift}',
    'https:// .com @ {space}'
  ],
  'shift': [
    '~ ! @ # $ % ^ & * ( ) _ + {bksp}',
    'Q W E R T Y U I O P { } |',
    '{lock} A S D F G H J K L : " {enter}',
    '{shift} Z X C V B N M < > ? {shift}',
    'http:// .com @ {space}'
  ]
}
});

function onChange(input) { document.querySelector(".input").value = input; }
function onKeyPress(button) {  if (button === "{shift}" || button === "{lock}") handleShift(); }
function handleShift() {
  let currentLayout = myKeyboard.options.layoutName;
  let shiftToggle = currentLayout === "default" ? "shift" : "default";

  myKeyboard.setOptions({
    layoutName: shiftToggle
  });
}

document.addEventListener("DOMContentLoaded", function() {
    document.getElementById('main').style.visibility = "visible"; 
    if (screen.width < 1080) {
        var x = document.getElementsByClassName('nomobile');
        var i;
        for (i = 0; i < x.length; i++) {
            x[i].style.display = "none";
        }
    }            
});

function show_vkb() {
   document.getElementById('dvkb').style.display = "block";
   document.getElementById('btn-svkb').style.display = "none";
}