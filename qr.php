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
        <?php
        if ($_POST['upload']) {
            $target_dir = "uploads/";
            if (! is_dir($target_dir)) {
                mkdir($target_dir);
            }
            $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
            $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
            if (strpos($target_file, "..") !== false) {
                echo "Uplevel attack!";
                exit;
            } 
            if ($_FILES["fileToUpload"]["size"] > 500000) {
                echo "Sorry, your file is too large.";
                exit;
            }
            $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
            if ($check === false) {
                echo "Upload not an Image!";
                unlink($target_file);
                exit;
            }
            // Allow certain file formats
            if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg") {
                echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
                unlink($target_file);
                exit;
            }
            if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
                require __DIR__ . "/vendor/autoload.php";
                $qrcode = new \Zxing\QrReader($target_file);
                $text = $qrcode->text();        
                unlink($target_file);    
            } else {
                echo "Sorry, unable to upload";
                exit;
            }
            
            ?>
        <a id="copybtn" class='clipboard' title='Copy'></a>
        <textarea id="enc" rows="9" cols="80"></textarea>
        <script type="text/javascript">
            var text = "<?= $text ?>";
            var pwd = "<?= $pwd ?>";
            document.getElementById('enc').value = do_dec(text, pwd);         
        </script>
        <?php
        } else {   
        ?>
        <form method="POST" enctype="multipart/form-data">
            <label for="fileToUpload">Select QR image to upload:</label>
            <input type="file" name="fileToUpload" id="fileToUpload"><br><br>
            <label for="pwd">Group Password (leave, blank, if none)</label>
            <input type="password" name="pwd" /><br><br>
            <label for="upload">Start upload</label>
            <input type="submit" value="Upload" name="upload" id="upload" />
        </form>    
        <?php
        } 
        ?>
    
        <hr>
        <a href="index.html">Encode new message</a>    
        
    </body>
</html>