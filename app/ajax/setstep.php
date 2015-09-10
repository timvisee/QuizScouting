<?php

// Include the page top
use app\registry\Registry;

// Include the page top
require_once('ajaxinit.php');

// Make sure the step is set
if(!isset($_GET['step']))
    returnError('Step not set!');

// Get the step and make sure it's valid
$step = trim($_GET['step']);
if(!is_numeric($step) || $step < 0 || $step > 3)
    returnError('Invalid step.');

// Set the step
Registry::setValue('question.step', $step);

// Return the result with JSON
returnJson(Array('result' => 'Answer set.'));
