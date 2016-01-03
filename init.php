<?php

register_shutdown_function( "fatal_handler" );

date_default_timezone_set('UTC');

function display_errors($on = TRUE){
    if($on){
        ini_set('display_errors','1');
        header('Content-Type: text/plain');
    }
    else{
        ini_set('display_errors','0');
        header('Content-Type: application/json');
    }
}

display_errors(FALSE);

function disable_debug_mode(){
}

function fatal_handler() {
    
    $error = error_get_last();

    if( $error !== NULL) {
        log_error( $error);
        error_response(500, "There is some technical problem. Kindly contact the administrator.");
    }
}

function log_error( $error ) {
    $e = get_error_json($error);
    //print $e;
}

function error_response($code, $message){
    http_response_code($code);
    $r = new stdClass();
    $r->error = new StdClass();
    $r->error->code = $code;
    $r->error->message = $message;
    $res = json_encode($r);
    print $res;
    return $res;
}

function get_error_json($error){
    $e = json_decode(json_encode($error));
    $e->timestamp = date("Y-m-d H:i:s");
    $err = json_encode($e);
    return $err;
}

?>