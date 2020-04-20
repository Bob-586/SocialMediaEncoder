<?php 
$vc = $_GET['vc'] ?? '';
$id = $_GET['id'] ?? '';

$lc = "?id={$id}";
if (! empty($vc)) {
    $lc .= "&vc={$vc}";
}

$cwd = basename(__DIR__);
?>
<!DOCTYPE html>
<html>
    <head>
    <script type="text/javascript">
        var uri = window.location.href.toString().split(window.location.host)[1];
        var grr = uri.split('/<?= $cwd ?>/');
        var path = grr[0] + '/<?= $cwd ?>';
        
        window.location = path + "/fetch.php<?= $lc ?>";
    </script>
    </head>
</html>    