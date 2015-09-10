<?php

ob_start();

// Make sure the app is only initialized once
if(defined('APP_INIT_DONE') && APP_INIT_DONE === true)
    return;

// Define the site root for Carbon
define('CARBON_SITE_ROOT', dirname(__DIR__));

// Define various app constants
/** The app namespace. */
define('APP_NAMESPACE', 'app\\');
/** The required PHP version to run the app. */
define('APP_PHP_VERSION_REQUIRED', '5.3.1');
/** The root directory of the app. */
define('APP_ROOT', __DIR__);
/** The application name. */
define('APP_NAME', 'Security Checklist v1.2');
/** The version name of the currently installed app instance. */
define('APP_VERSION_NAME', '0.1');
/** The version code of the currently installed app instance. */
define('APP_VERSION_CODE', 1);

// Make sure the current PHP version is supported
if(version_compare(phpversion(), APP_PHP_VERSION_REQUIRED, '<'))
    // PHP version the server is running is not supported, show an error message
    // TODO: Show proper error message
    die('This server is running PHP ' . phpversion() . ', the required PHP version to start the application is PHP ' . APP_PHP_VERSION_REQUIRED . ' or higher,
            please install PHP ' . APP_PHP_VERSION_REQUIRED . ' or higher on your server!');

/** Defines whether app is initializing or initialized. */
define('APP_INIT', true);

// Initialize, load and set up Carbon Core
require_once(CARBON_SITE_ROOT . '/carbon/core/init.php');

// Make sure Carbon Core is initialized successfully
if(!defined('CARBON_CORE_INIT_DONE') || CARBON_CORE_INIT_DONE != true)
    die('Failed to load the application because Carbon Core couldn\'t be initialized');

// Include the loader for the app and set it up
require_once(APP_ROOT . '/autoloader/loader/AppLoader.php');
use app\autoloader\loader\AppLoader;
use app\question\Question;
use app\question\QuestionManager;
use app\registry\Registry;
use carbon\core\autoloader\Autoloader;
Autoloader::addLoader(new AppLoader());

// Load the configuration
use app\config\Config;
Config::load();

// Set up the error handler
use carbon\core\ErrorHandler;
ErrorHandler::init(true, true, Config::getValue('app', 'debug'));

// Set up the cookie manager
use carbon\core\cookie\CookieManager;
CookieManager::setCookieDomain(Config::getValue('cookie', 'domain', ''));
CookieManager::setCookiePath(Config::getValue('cookie', 'path', '/'));
CookieManager::setCookiePrefix(Config::getValue('cookie', 'prefix', ''));

// Connect to the database
use app\database\Database;
use carbon\core\util\StringUtils;

Database::connect();

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

// Reset the quiz if specified
if(isset($_GET['action']) && StringUtils::equals($_GET['action'], 'reset', false, true)) {
    // Reset the current registry
    Registry::deleteAll();

    // Set the default value
    Registry::setValue('question.current', 1);
    Registry::setValue('question.step', 0);
}

// Define the team variable
$team = null;

// Get the team
if(isset($_GET['t'])) {
    $team = trim($_GET['t']);

    // Make sure any team is set
    if(strlen($team) > 0) {
        // Make sure the team is valid
        if(!StringUtils::equals($team, $VALID_TEAMS, true))
            showErrorPage();

        // Set the cookie
        CookieManager::setCookie(REG_TEAM_COOKIE_KEY, $team, '+1 year');

    } else
        $team = null;
}

// Get the team
if($team == null)
    $team = CookieManager::getCookie(REG_TEAM_COOKIE_KEY);

// Make sure the team is valid or reset the var
if(!StringUtils::equals($team, $VALID_TEAMS, true))
    $team = null;

// Set the team in a global variable
$GLOBALS['team'] = $team;

/**
 * Get the score for a team.
 *
 * @param string $team The team to get the score for.
 *
 * @return int The score
 */
function getTeamScore($team) {
    // Determine the score
    $score = 0;

    // Get all answers
    foreach(QuestionManager::getQuestions() as $question) {
        // Validate the instance
        if(!($question instanceof Question))
            continue;

        // Check weather this answer is correct
        if(StringUtils::equals($question->getCorrectAnswer(), $question->getTeamAnswer($team)))
            $score++;
    }

    // Return the score
    return $score;
}

// The app initialized successfully, define the APP_INIT_DONE constant to store the initialization state
/** Defines whether the app is initialized successfully. */
define('APP_INIT_DONE', true);
