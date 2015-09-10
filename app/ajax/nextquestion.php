<?php

// Include the page top
use app\question\QuestionManager;
use app\registry\Registry;

// Include the page top
require_once('ajaxinit.php');

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