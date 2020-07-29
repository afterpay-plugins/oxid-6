<?php

/**
 *
 */

/**
 * Test server for WebServiceClientTest.
 */
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    echo 'GET works';
} else {
    $data = file_get_contents('php://input');

    if ($data == 'POST data') {
        echo 'POST works';
    } elseif ($data == '{"data":"json"}') {
        echo '{"status":"success"}';
    }
}
