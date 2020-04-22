<?php 
$vc = $_GET['vc'] ?? '';
$id = $_GET['id'] ?? '';

$lc = "{$id}";
if (! empty($vc)) {
    $lc .= "/{$vc}";
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
        
        window.location = path + "/index.php#Message/<?= $lc ?>";
    </script>
    </head>
</html>    