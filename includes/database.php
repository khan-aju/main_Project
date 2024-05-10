<?php

/**
 * 
 */
function getDB()
{
    $db_host = "localhost";
    $db_name = "mainproject";
    $db_user = "cms_www";
    $db_pass = "6p@MJoesi]HLejMh";

    $conn = mysqli_connect($db_host, $db_user, $db_pass, $db_name);

    if (mysqli_connect_error()) {
        echo mysqli_connect_error();
        exit;
    }
    return $conn;
}