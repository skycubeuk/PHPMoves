<?php
include_once '../Moves.php';
include_once 'config.php';
$m = new PHPMoves\Moves(Config::$client_id,Config::$client_secret,Config::$redirect_url);

if (isset($_GET['code'])) {
    $request_token = $_GET['code'];
    $tokens = $m->auth($request_token);
    //Save this token for all future request for this user
    $access_token = $tokens['access_token'];
    //Save this token for refeshing the token in the future
    $refresh_token = $tokens['refresh_token'];
    echo json_encode($m->get_profile($access_token));
    
}
?>