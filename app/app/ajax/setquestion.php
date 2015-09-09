<?php

<?php

use app\question\Question;
use app\registry\Registry;
use carbon\core\cookie\CookieManager;
use carbon\core\util\StringUtils;

// Include the page top
require_once('top.php');

// Team cookie key
define('REG_TEAM_COOKIE_KEY', 'team');
define('REG_QUESTION_CURRENT', 'question.current');

// Valid teams and team names
$VALID_TEAMS = Array('a', 'b', 'c', 'y', 'z');
$TEAM_NAMES = Array(
    'a' => 'Team A',
    'b' => 'Team B',
    'c' => 'Team C'
);

// Define the team variable
$team = null;

// Get the team
if(isset($_GET['t'])) {
    $team = trim($_GET['t']);

    // Make sure the team is valid
    if(!StringUtils::equals($team, $VALID_TEAMS, true))
        showErrorPage();

    // Set the cookie
    CookieManager::setCookie(REG_TEAM_COOKIE_KEY, $team, '+1 year');
}

// Get the team
if($team == null)
    $team = CookieManager::getCookie(REG_TEAM_COOKIE_KEY);

/**
 * Get the current question.
 *
 * @return Question
 * @throws Exception
 */
function getCurrentQuestion() {
    return new Question((int) Registry::getValue(REG_QUESTION_CURRENT));
}


// Get the current question
$question = getCurrentQuestion();

