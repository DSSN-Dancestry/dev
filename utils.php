<?php
// Function for basic field validation (present and neither empty nor only white space

function checkAdmin(){
    if (isset($_SESSION["user_type"])) {
        $user_type = $_SESSION["user_type"];
        if ($user_type != "Admin") {
            header("Location: ./login.php");
            die();

        }
    } else {
        header("Location: ./login.php");
        die();
    }
}

function IsNullOrEmpty($question)
{
    return (!isset($question) || trim($question)==='');
}
function isDateBetweenDates(DateTime $date, DateTime $startDate, DateTime $endDate)
{
    return $date > $startDate && $date < $endDate;
}

function isValidJSON($str)
{
    json_decode($str);
    return json_last_error() == JSON_ERROR_NONE;
}

function formatNull($str)
{
    if (IsNullOrEmpty($str)) {
        return null;
    }
    return $str;
}

// Function to check string starting
// with given substring
function startsWith($string, $startString)
{
    $len = strlen($startString);
    return (substr($string, 0, $len) === $startString);
}
