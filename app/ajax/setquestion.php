<?php

// Include the page top
use carbon\core\util\StringUtils;

// Include the page top
require_once('ajaxinit.php');

// Make sure the answer is set
if(!isset($_GET['answer']))
    returnError('Answer not set!');

// Get the answer and make sure it's valid
$answer = trim($_GET['answer']);
if(!StringUtils::equals($answer, Array('a', 'b', 'c', 'd', true, false)))
    returnError('Invalid answer.');

// Make sure the answer isn't set yet
if($question->hasTeamAnswer(($team)))
    returnJson(Array('result' => 'Answer already set.'));

// Set the answer
$question->setTeamAnswer($team, $answer);

// Return the result with JSON
returnJson(Array('result' => 'Answer set.'));
