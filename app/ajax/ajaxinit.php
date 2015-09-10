<?php

// Include the page top
use app\question\Question;
use app\registry\Registry;
use carbon\core\cookie\CookieManager;
use carbon\core\util\StringUtils;

// Init the app
require_once('../app/init.php');

// Team cookie key
define('REG_TEAM_COOKIE_KEY', 'team');
define('REG_QUESTION_CURRENT', 'question.current');

// Get the team
if($team == null)
    $team = CookieManager::getCookie(REG_TEAM_COOKIE_KEY);

/**
* Return an error.
*
* @param string $msg The message
* @return string
*/
function returnError($msg) {
    // Return the error as JSON
    returnJson(Array('error' => $msg));
}

/**
* Return an array with data as JSON.
*
* @param Array $array The array to return as JSON.
*/
function returnJson($array) {
    // Encode the json
    $json = json_encode($array);

    // Set the page headers
    header('Content-Type: application/json');

    // Print the json
    die($json);
}

/**
* Get the current question.
*
* @return Question The current question.
* @throws Exception
*/
function getCurrentQuestion() {
    return new Question(Registry::getValue(REG_QUESTION_CURRENT)->getValue());
}

// Make sure the team is valid
if(!StringUtils::equals($team, $VALID_TEAMS, true))
    returnError('Invalid team.');

// Get the current question
$question = getCurrentQuestion();