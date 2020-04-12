<!DOCTYPE html>
<html>
    <head>
        <title>Social Media Encoder</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <style>
.clipboard {
	background-image: url(./images/clipboard.png);
    float: right;
    display: block;
    height: 32px;
    width: 32px;
    cursor: pointer;
    background-repeat: no-repeat;
    background-position: 0px 0px;
    margin: 0 0 0 10px;    
}            
        </style>
        <script type="text/javascript" src="./dist/all.min.js"></script>
    </head>
    <body>
    <a id="copybtn" class='clipboard' title='Copy'></a>    
<?php
    $code = $_REQUEST['text'] ?? '';
    $parts = $_REQUEST['parts'] ?? '';
    $ds = $_REQUEST['ds'] ?? '';
    $mode = $_REQUEST['mode'] ?? '';
    $was_set = $_REQUEST['was'] ?? false;
    $pwd = $_POST['pwd'] ?? '';
    
    if ($was_set && empty($pwd)) {
        ?>
        <form method="POST">
            <input type="hidden" name="code" value="<?= $code ?>" />
            <input type="hidden" name="parts" value="<?= $parts ?>" />
            <input type="hidden" name="was" value="true" />
            <input type="password" name="pwd" />
            <input type="submit" value="Decode" />
        </form>    
        <?php
    } else {
        ?>
        <textarea id="enc" rows="9" cols="80"></textarea>
        <script type="text/javascript">
   var text = "<?= $code ?>";
   var pwd = "<?= $pwd ?>";
   var parts = "<?= $parts ?>";
   var ds = "<?= $ds ?>";
   var mode = "<?= $mode ?>";
   document.getElementById('enc').value = url_dec(text, pwd, parts, ds, mode);         
        </script>
        <?php    
    }
?>

        <a href="index.html">Encode new message</a>    
        
    </body>
</html>    
