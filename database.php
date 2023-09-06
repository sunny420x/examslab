<?php
$env = parse_ini_file('.env');

$db = mysqli_connect($env['MYSQL_HOST'], $env['MYSQL_USER'], $env['MYSQL_PASSWORD'], $env['MYSQL_DATABASE']);

if(mysqli_connect_errno()) {
    die(mysqli_connect_error());
}
?>