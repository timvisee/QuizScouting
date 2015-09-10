<?php

// Include the page top
require_once('ajaxinit.php');

// Return the result with JSON
returnJson(Array('a' => $question->hasTeamAnswer('a'), 'b' => $question->hasTeamAnswer('b'), 'c' => $question->hasTeamAnswer('c')));
