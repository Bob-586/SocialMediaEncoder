<!DOCTYPE html>
<html>
    <head>
        <title>Social Media Encoder v1.3</title>
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
        <link rel="stylesheet" href="./dist/uikit.css">
        <script type="text/javascript" src="./dist/all.min.js?v=1.3"></script>
    </head>
    <body>
        <?php
        
        $pwd = $_POST['pwd'] ?? '';
        
function file_contains_php(string $file): bool {
    $file_handle = fopen($file, "r");
    while (!feof($file_handle)) {
      $line = fgets($file_handle);
      $pos = strpos($line, '<?php');
      if ($pos !== false) {
        return true;
      }
    }
    fclose($file_handle);
    return false;
  }        
        $upload = $_POST['upload'] ?? false;
        if ($upload) {
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
            if ($_FILES["fileToUpload"]["size"] > 2000000) {
                echo "Sorry, your file is too large.";
                exit;
            }
            $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
            if ($check === false) {
                echo "Upload not an Image!";
                exit;
            }
            // Allow certain file formats
            if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
                echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
                exit;
            }
            if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
                if (file_contains_php($target_file)) {
                    echo "PHP Attack!";
                    unlink($target_file); // Delete this Dangerious file
                    exit;
                }
                
                try {
                    require_once 'steganography.php';
                    $processor = new \steganography();
                    $text = $processor->decode($target_file);
                } catch (Exception $ex) {
                    echo $ex->getMessage();
                }
                    
                unlink($target_file); // Remove if you want to keep records of messages  
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
            
        <section>
        <fieldset>
        <legend>Upload Image to Decode</legend>
            <label for="fileToUpload">Select Encoded image to upload:</label>
            <input type="file" name="fileToUpload" id="fileToUpload" accept="image/*">
            <input type="submit" value="Start Up-load" name="upload" id="upload" class="uk-button uk-button-secondary uk-button-small" />
        </fieldset>
        </section>    
            
        <section>
        <fieldset>
        <legend>Group Password</legend>   
            <label for="pwd">If none, leave blank.</label>
            <input type="password" name="pwd" />
        </fieldset>
        </section>        
           
        </form>
        
        <script type="text/javascript">
var uploadField = document.getElementById("fileToUpload");

uploadField.onchange = function() {
    if(this.files[0].size > 2000000){
       alert("File is too big!");
       this.value = "";
    };
};        
        </script>
        
        <?php
        } 
        ?>
    
        <hr>
        <a href="index.html" class="uk-button uk-button-primary uk-button-large">Encode new message</a>    
        
    </body>
</html>