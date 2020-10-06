<?php
/* Tell search engines that the site is temporarily unavilable */
$protocol = $_SERVER["SERVER_PROTOCOL"];
if ('HTTP/1.1' != $protocol && 'HTTP/1.0' != $protocol) $protocol = 'HTTP/1.0';
header("$protocol 503 Service Unavailable", true, 503);
header('Content-Type: text/html; charset=utf-8');
header('Retry-After: 600');
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <?php require_once(PLUGIN_DIR . 'template-parts/content-head.php'); ?>
</head>

<body>
    <?php

    require_once(PLUGIN_DIR . 'template-parts/content-page.php');
    ?>
</body>

</html>

<?php
/* This passes control back to the wordpress upgrade routine */
die();
/* Don't change this */
?>