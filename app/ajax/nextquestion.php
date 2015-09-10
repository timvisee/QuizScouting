<?php

use app\question\Question;
use app\question\QuestionManager;
use app\registry\Registry;
use carbon\core\cookie\CookieManager;
use carbon\core\util\StringUtils;

// Include the page top
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

// Get the current step
$step = (int) Registry::getValue('question.step')->getValue();

// Make sure the current step is 3 or 4
if($step != 3 && $step != 4)
    returnError('Unable to go to next question, current step is invalid!');

// Set the current step to 5 if it's 4 now
if($step == 4) {
    Registry::setValue('question.step', 5);
    returnJson(Array('result' => 'Showing welcome message.'));
    die();
}

// If this is the last question, show the score page
if($question->getId() == QuestionManager::getLastQuestion()->getId()) {
    Registry::setValue('question.step', 4);
    returnJson(Array('result' => 'All questions answered, showing results.'));
    die();
}

// Set the next question and set
Registry::setValue('question.current', $question->getId() + 1);
Registry::setValue('question.step', 1);

returnJson(Array('result' => 'Next question set.'));