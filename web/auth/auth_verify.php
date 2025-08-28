<?php
ini_set('session.save_path', '/tmp');
ini_set('session.name', 'PERLITE_AUTH');
session_start();

if (isset($_SESSION['auth']) && $_SESSION['auth'] === true) {
    http_response_code(200);
    exit;
} else {
    http_response_code(401);
    exit;
}
