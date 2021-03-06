<?php

const X_MAX = 5;
const X_MIN = -3;
const y_values = array(-4, -3, -2, -1, 0, 1, 2, 3);
const r_values = array(1, 2, 3, 4, 5);

function isExist($arg)
{
    return isset($arg);
}

function checkFirstQuarter($x_arg, $y_arg, $r_arg)
{
    return $x_arg <= 0 && $y_arg >= 0 && $x_arg >= -$r_arg/2 && $y_arg <= $r_arg;
}

function checkSecondQuarter($x_arg, $y_arg, $r_arg)
{
    return $x_arg >= 0 && $y_arg >= 0 && $x_arg * $x_arg + $y_arg * $y_arg <= $r_arg * $r_arg;
}

function checkThirdQuarter($x_arg, $y_arg, $r_arg)
{
    return $x_arg <= 0 && $y_arg <= 0 && 2 * $y_arg >= -$x_arg - $r_arg;
}

function checkQuarters($x_arg, $y_arg, $r_arg)
{
    if (checkFirstQuarter($x_arg, $y_arg, $r_arg) || checkSecondQuarter($x_arg, $y_arg, $r_arg) || checkThirdQuarter($x_arg, $y_arg, $r_arg)) {
        return "входит в ОДЗ";
    } else {
        return "не входит в ОДЗ";
    }
}

function isCoordinatesExist($x_arg, $y_arg, $r_arg)
{
    return isExist($x_arg) && isExist($y_arg) && isExist($r_arg);
}

function isNumbers($x_arg, $y_arg, $r_arg)
{
    return is_numeric($x_arg) && is_numeric($y_arg) && is_numeric($r_arg);
}

function checkValues($x_arg, $y_arg, $r_arg)
{
    return isCoordinatesExist($x_arg, $y_arg, $r_arg) && isNumbers($x_arg, $y_arg, $r_arg)
        && ($x_arg <= X_MAX && $x_arg >= X_MIN)
        && in_array($y_arg, y_values) && in_array($r_arg, r_values);
}

//main
session_start();
if (!isExist($_SESSION['table-raw'])) {
    $_SESSION['table-raw'] = array();
}

$x = $_POST['x_value'];
$y = $_POST['y_value'];
$radius = $_POST['r_value'];
date_default_timezone_set($_POST['timezone']) * 60;

if (checkValues($x, $y, $radius)) {
    $status = checkQuarters($x, $y, $radius);
    $currentTime = date("H:i:s", time());
    $workTime = round(microtime(true) - $_SERVER["REQUEST_TIME_FLOAT"], 5);


    $table_template = "<tr id='test'>";
    $table_template .= "<td>$x</td>";
    $table_template .= "<td>$y</td>";
    $table_template .= "<td>$radius</td>";
    $table_template .= "<td>$currentTime</td>";
    $table_template .= "<td>$workTime</td>";
    $table_template .= "<td>$status</td>";
    $table_template .= "</tr>";

    $_SESSION['table-raw'][] = $table_template;

    foreach ($_SESSION['table-raw'] as $table_row) echo $table_row;

} else {
    http_response_code(400);
    return;
}






