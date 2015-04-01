<?php
include_once '../Moves.php';
include_once 'config.php';
$m = new PHPMoves\Moves(Config::$client_id,Config::$client_secret,Config::$redirect_url);
$request_url = $m->requestURL();
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8"/>
    </head>
    <body>
        <a href="<?php echo $request_url; ?>">Click Here to Register</a>
    </body>
</html>




 

