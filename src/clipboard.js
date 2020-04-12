function clipboard_select(element) {
    if ( typeof(element) != 'undefined' && element != null ) {
        element.focus();
        if (element.nodeName === 'INPUT' || element.nodeName === 'TEXTAREA') {
            var lng = element.value.length;
            element.setSelectionRange(0, lng);
        } else {
            var selection = window.getSelection();
            var range = document.createRange();
            range.selectNodeContents(element);
            selection.removeAllRanges();
            selection.addRange(range);  
        }
    }
}

function clipboard_copy() {
    var worked = false;
    try {
        worked = document.execCommand('copy');
    } catch (err) {
        console.error(err);
        worked = false;
    }
    return worked;
}

document.addEventListener("DOMContentLoaded", function() {
    var copybtn = document.getElementById('copybtn');
    if (typeof(copybtn) != 'undefined' && copybtn != null) {
        copybtn.addEventListener('click', function() {
           var elm = document.getElementById('enc');
           if (typeof(elm) != 'undefined' && elm != null) {
                clipboard_select(elm);
                clipboard_copy();
           }
        });    
    }
    
});