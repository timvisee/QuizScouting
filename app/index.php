<?php

use app\question\Question;
use app\registry\Registry;
use app\template\PageFooterBuilder;
use app\template\PageHeaderBuilder;
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

// Make sure the team is valid
if(!StringUtils::equals($team, $VALID_TEAMS, true)) {
    ?>
    <div data-role="page" id="page-login">
        <?php PageHeaderBuilder::create()->build(); ?>

        <div data-role="main" class="ui-content">
            <center>
                <p>
                    Kies een van de onderstaande gebruikers:
                </p>
            </center>
            <br />

            <a href="index.php?t=a" class="ui-btn">Team A</a>
            <a href="index.php?t=a" class="ui-btn">Team B</a>
            <a href="index.php?t=a" class="ui-btn">Team C</a>
            <a href="index.php?t=y" class="ui-btn">Overzicht</a>
            <a href="index.php?t=z" class="ui-btn">Controleur</a>
        </div>

        <?php PageFooterBuilder::create()->build(); ?>
    </div>
    <?php

    // Print the page bottom
    require_once('bottom.php');
    die();
}

if(StringUtils::equals($team, Array('a', 'b', 'c'))) {
    $question = getCurrentQuestion();

    // Check whether an answer is set
    if(isset($_GET['a']) && !$question->hasTeamAnswer($team)) {
        // Get the answer
        $answer = $_GET['a'];

        // Make sure the answer is valid
        if(!StringUtils::equals($answer, Array('a', 'b', 'c', 'd')))
            showErrorPage();

        // Set the answer
        $question->setTeamAnswer($team, $answer);
    }

    if(!$question->hasTeamAnswer($team)) {
        ?>
        <div data-role="page" id="page-login">
            <?php PageHeaderBuilder::create($TEAM_NAMES[$team])->build(); ?>

            <div data-role="main" class="ui-content">
                <center>
                    <p>
                        <?=$question->getQuestion(); ?><br />
                        <br />
                        <b>A.</b> <?=$question->getAnswerA(); ?><br />
                        <b>B.</b> <?=$question->getAnswerB(); ?><br />
                        <b>C.</b> <?=$question->getAnswerC(); ?><br />
                        <b>D.</b> <?=$question->getAnswerD(); ?>
                    </p>
                </center><br />

                <table style="width: 100%;">
                    <tr>
                        <td style="width: 50%;"><a href="index.php?a=a" class="ui-btn ui-btn-big button-a">A</a></td>
                        <td style="width: 50%;"><a href="index.php?a=b" class="ui-btn ui-btn-big button-b">B</a></td>
                    </tr>
                    <tr>
                        <td><a href="index.php?a=c" class="ui-btn ui-btn-big button-c">C</a></td>
                        <td><a href="index.php?a=d" class="ui-btn ui-btn-big button-d">D</a></td>
                    </tr>
                </table>
            </div>

            <?php PageFooterBuilder::create()->build(); ?>
        </div>
        <?php

    } else {
        ?>
        <div data-role="page" id="page-login">
            <?php PageHeaderBuilder::create($TEAM_NAMES[$team])->build(); ?>

            <div data-role="main" class="ui-content">
                <center>
                    <p>Antwoord is gekozen!</p>
                </center>
            </div>

            <?php PageFooterBuilder::create()->build(); ?>
        </div>
        <?php
    }
}

if(StringUtils::equals($team, 'y')) {
    $question = getCurrentQuestion();

    // Check whether an answer is set
    if(isset($_GET['a']) && !$question->hasTeamAnswer($team)) {
        // Get the answer
        $answer = $_GET['a'];

        // Make sure the answer is valid
        if(!StringUtils::equals($answer, Array('a', 'b', 'c', 'd')))
            showErrorPage();

        // Set the answer
        $question->setTeamAnswer($team, $answer);
    }

    if(!$question->hasTeamAnswer($team)) {
        ?>
        <div data-role="page" id="page-login">
            <?php PageHeaderBuilder::create()->setFixed(true)->build(); ?>

            <div data-role="main" class="ui-content">
                <center>
                    <p style="font-size: 400%;" class="question">
                        <?=$question->getQuestion(); ?><br />
                        <br />
                        <b>A.</b> <?=$question->getAnswerA(); ?><br />
                        <b>B.</b> <?=$question->getAnswerB(); ?><br />
                        <b>C.</b> <?=$question->getAnswerC(); ?><br />
                        <b>D.</b> <?=$question->getAnswerD(); ?>
                    </p>
                </center><br />


                <table style="width: 100%;">
                    <tr>
                        <td style="width: 33%;">
                            <div class="team-status-box">
                                A<br />
                                V
                            </div>
                        </td>
                        <td style="width: 33%;">
                            <div class="team-status-box status-good">
                                B<br />
                                <img src="style/image/loader/loader.gif" />
                            </div>
                        </td>
                        <td style="width: 33%;">
                            <div class="team-status-box status-bad">
                                Team C<br />
                                <img src="style/image/loader/loader.gif" />
                            </div>
                        </td>
                    </tr>
                </table>
            </div>

            <?php PageFooterBuilder::create()->setFixed(true)->build(); ?>
        </div>
        <?php

    } else {
        ?>
        <div data-role="page" id="page-login">
            <?php PageHeaderBuilder::create($TEAM_NAMES[$team])->build(); ?>

            <div data-role="main" class="ui-content">
                <center>
                    <p>Answered</p>
                </center>
            </div>

            <?php PageFooterBuilder::create()->build(); ?>
        </div>
        <?php
    }
}

if(StringUtils::equals($team, 'z')) {
    $question = getCurrentQuestion();

    // Check whether an answer is set
    if(isset($_GET['a']) && !$question->hasTeamAnswer($team)) {
        // Get the answer
        $answer = $_GET['a'];

        // Make sure the answer is valid
        if(!StringUtils::equals($answer, Array('a', 'b', 'c', 'd')))
            showErrorPage();

        // Set the answer
        $question->setTeamAnswer($team, $answer);
    }

    if(!$question->hasTeamAnswer($team)) {
        ?>
        <div data-role="page" id="page-login">
            <?php PageHeaderBuilder::create()->build(); ?>

            <div data-role="main" class="ui-content">
                <center>
                    <p>Kies een antwoord:</p>
                </center><br />

                <a href="index.php?a=d" class="ui-btn">Continue</a>
            </div>

            <?php PageFooterBuilder::create()->build(); ?>
        </div>
        <?php

    } else {
        ?>
        <div data-role="page" id="page-login">
            <?php PageHeaderBuilder::create($TEAM_NAMES[$team])->build(); ?>

            <div data-role="main" class="ui-content">
                <center>
                    <p>Antwoord is gekozen!</p>
                </center>
            </div>

            <?php PageFooterBuilder::create()->build(); ?>
        </div>
        <?php
    }
}

// Include the page bottom
require_once('bottom.php');
