<?php

use app\question\Question;
use app\registry\Registry;
use app\template\PageFooterBuilder;
use app\template\PageHeaderBuilder;
use carbon\core\util\StringUtils;

// Include the page top
require_once('top.php');

// Team names
$TEAM_NAMES = Array(
    'a' => 'Team A',
    'b' => 'Team B',
    'c' => 'Team C'
);
$GLOBALS['team_names'] = $TEAM_NAMES;

// Get the team
$team = $GLOBALS['team'];

// Make sure the team is valid
if($team === null) {
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
            <a href="index.php?t=b" class="ui-btn">Team B</a>
            <a href="index.php?t=c" class="ui-btn">Team C</a>
            <a href="index.php?t=y" class="ui-btn">Overzicht</a>
            <a href="index.php?t=z" class="ui-btn">Quiz Master</a>
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

    // Get the current step
    $step = (int) Registry::getValue('question.step')->getValue();

    if($step == 0) {
        ?>
        <div data-role="page" id="page-login">
            <?php PageHeaderBuilder::create($TEAM_NAMES[$team])->build(); ?>

            <div data-role="main" class="ui-content">
                <center>
                    <p>
                        De security checklist staat klaar.<br /><br />
                        <img src="style/image/loader/loader.gif" /><br /><br />
                        Wachten tot de checklist word gestart...<br />
                    </p>
                </center>
            </div>

            <?php PageFooterBuilder::create()->build(); ?>
        </div>
        <?php

    } else if($step == 1) {
        ?>
        <div data-role="page" id="page-login">
            <?php PageHeaderBuilder::create($TEAM_NAMES[$team])->build(); ?>

            <div data-role="main" class="ui-content">
                <center>
                    <p>
                        <b><?=$question->getQuestion(); ?></b><br />
                        <br />
                        <img src="style/image/loader/loader.gif" /><br /><br />
                        Wachten op antwoorden...<br />
                    </p>
                </center>
            </div>

            <?php PageFooterBuilder::create()->build(); ?>
        </div>
        <?php

    } else if($step == 2) {
        if(!$question->hasTeamAnswer($team)) {
            ?>
            <div data-role="page" id="page-login">
                <?php PageHeaderBuilder::create($TEAM_NAMES[$team])->build(); ?>

                <div data-role="main" class="ui-content">
                    <center>
                        <p>
                            <b><?=$question->getQuestion(); ?></b><br />
                            <br />
                            <b>A.</b> <?= $question->getAnswerA(); ?><br />
                            <b>B.</b> <?= $question->getAnswerB(); ?><br />
                            <b>C.</b> <?= $question->getAnswerC(); ?><br />
                            <b>D.</b> <?= $question->getAnswerD(); ?>
                        </p>
                    </center>
                    <br />

                    <table style="width: 100%;">
                        <tr style="width: 50%">
                            <td style="width: 50%;"><a href="#" class="ui-btn ui-corner-all ui-shadow ui-btn-big button-a">A</a></td>
                            <td style="width: 50%;"><a href="#" class="ui-btn ui-corner-all ui-shadow ui-btn-big button-b">B</a></td>
                        </tr>
                        <tr style="width: 50%">
                            <td><a href="#" class="ui-btn ui-corner-all ui-shadow ui-btn-big button-c">C</a></td>
                            <td><a href="#" class="ui-btn ui-corner-all ui-shadow ui-btn-big button-d">D</a></td>
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
                        <p>
                            Je hebt antwoord <b><?= strtoupper($question->getTeamAnswer($team)); ?></b>
                            gekozen.<br />
                            <br />
                            <img src="style/image/loader/loader.gif" /><br /><br />
                            Wachten op het antwoord...<br />
                        </p>
                    </center>
                </div>

                <?php PageFooterBuilder::create()->build(); ?>
            </div>
            <?php
        }

    } else if($step == 3) {
        ?>
        <div data-role="page" id="page-login">
            <?php PageHeaderBuilder::create($TEAM_NAMES[$team])->build(); ?>

            <div data-role="main" class="ui-content">
                <center>
                    <p>
                        <?php
                        $answer = $question->getTeamAnswer($team);
                        $correct = $question->getCorrectAnswer();
                        ?>
                        Je hebt antwoord <b><?= strtoupper($answer); ?></b>
                        gekozen.<br />
                        <br />
                        <span style="color: <?=(StringUtils::equals($correct, 'a') ? 'green' : (StringUtils::equals($answer, 'a') ? 'red' : 'gray')); ?>;"><b>A.</b> <?=$question->getAnswerA(); ?></span><br />
                        <span style="color: <?=(StringUtils::equals($correct, 'b') ? 'green' : (StringUtils::equals($answer, 'b') ? 'red' : 'gray')); ?>;"><b>B.</b> <?=$question->getAnswerB(); ?></span><br />
                        <span style="color: <?=(StringUtils::equals($correct, 'c') ? 'green' : (StringUtils::equals($answer, 'c') ? 'red' : 'gray')); ?>;"><b>C.</b> <?=$question->getAnswerC(); ?></span><br />
                        <span style="color: <?=(StringUtils::equals($correct, 'd') ? 'green' : (StringUtils::equals($answer, 'd') ? 'red' : 'gray')); ?>;"><b>D.</b> <?=$question->getAnswerD(); ?></span><br /><br />

                        <?php
                        if(StringUtils::equals($correct, $answer))
                            echo '<span style="color: green; font-weight: bold;">Correct!</span>';
                        else
                            echo '<span style="color: red; font-weight: bold;">Incorrect!</span>';
                        ?>
                    </p>
                </center>
            </div>

            <?php PageFooterBuilder::create()->build(); ?>
        </div>
        <?php

    } else if($step == 4) {
        ?>
        <div data-role="page" id="page-login">
            <?php PageHeaderBuilder::create($TEAM_NAMES[$team])->build(); ?>

            <div data-role="main" class="ui-content">
                <center>
                    <p>
                        <b>Checklist Resultaten</b><br />
                        <br />

                        <span style="<?php if(StringUtils::equals($team, 'a')) { echo 'color: green; font-weight: bold;'; } ?>"><b>Team A:</b>&nbsp;&nbsp;<?=getTeamScore('a'); ?> punten</span><br />
                        <span style="<?php if(StringUtils::equals($team, 'b')) { echo 'color: green; font-weight: bold;'; } ?>"><b>Team B:</b>&nbsp;&nbsp;<?=getTeamScore('b'); ?> punten</span><br />
                        <span style="<?php if(StringUtils::equals($team, 'c')) { echo 'color: green; font-weight: bold;'; } ?>"><b>Team C:</b>&nbsp;&nbsp;<?=getTeamScore('c'); ?> punten</span><br />
                        <br />
                    </p>
                </center>
            </div>

            <?php PageFooterBuilder::create()->build(); ?>
        </div>
        <?php

    } else if($step == 5) {
        ?>
        <div data-role="page" id="page-login">
            <?php PageHeaderBuilder::create($TEAM_NAMES[$team])->build(); ?>

            <div data-role="main" class="ui-content">
                    <img style="width: 100%; box-shadow: 0 0 25px rgba(0, 0, 0, .5)" src="style/image/welcome.png" />
            </div>

            <?php PageFooterBuilder::create()->build(); ?>
        </div>
        <?php
    } else {
        showErrorPage('Internal error occurred. Invalid question step.');
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
    // Get the current step
    $step = (int) Registry::getValue('question.step')->getValue();

    if($step == 0) {
        ?>
        <div data-role="page" id="page-login">
            <?php PageHeaderBuilder::create()->build(); ?>

            <div data-role="main" class="ui-content">
                <center>
                    <p style="font-size: 400%;" class="question">
                        <br /><br /><b>Security Checklist</b><br /><br /><br />
                        <i>Wachten tot de checklist word gestart...</i>
                    </p>
                </center>
            </div>

            <?php PageFooterBuilder::create()->setFixed(true)->build(); ?>
        </div>
        <?php

    } else if($step == 1) {
        ?>
        <div data-role="page" id="page-login">
            <?php PageHeaderBuilder::create()->build(); ?>

            <div data-role="main" class="ui-content">
                <?php

                function printStatusBox($team, $question) {
                    $answered = $question->hasTeamAnswer($team);

                    echo '<div class="team-status-box team-' . $team . ' ' . ($answered ? ' status-wait' : '' ). '">';
                    echo $GLOBALS['team_names'][$team] . '<br />' . getTeamScore($team);

                    echo '</div>';
                }

                ?>

                <table style="width: 100%;">
                    <tr style="width: 50%">
                        <td style="width: 33%;">
                            <?php printStatusBox('a', $question); ?>
                        </td>
                        <td style="width: 33%;">
                            <?php printStatusBox('b', $question); ?>
                        </td>
                        <td style="width: 33%;">
                            <?php printStatusBox('c', $question); ?>
                        </td>
                    </tr>
                </table>

                <center>
                    <p style="font-size: 400%;" class="question">
                        <b><?=$question->getQuestion(); ?></b>
                    </p>
                </center><br />
            </div>

            <?php PageFooterBuilder::create()->setFixed(true)->build(); ?>
        </div>
        <?php

    } else if($step == 2) {
        if(!$question->hasTeamAnswer($team)) {
            ?>
            <div data-role="page" id="page-login">
                <?php PageHeaderBuilder::create()->build(); ?>

                <div data-role="main" class="ui-content">
                    <?php

                    function printStatusBox($team, $question) {
                        $answered = $question->hasTeamAnswer($team);

                        echo '<div class="team-status-box team-' . $team . ' ' . ($answered ? ' status-wait' : '' ). '">';
                        echo $GLOBALS['team_names'][$team] . '<br />';

                        if(!$answered)
                            echo '<img src="style/image/loader/loader_gray.gif" />';

                        echo '</div>';
                    }

                    ?>

                    <table style="width: 100%;">
                        <tr style="width: 50%">
                            <td style="width: 33%;">
                                <?php printStatusBox('a', $question); ?>
                            </td>
                            <td style="width: 33%;">
                                <?php printStatusBox('b', $question); ?>
                            </td>
                            <td style="width: 33%;">
                                <?php printStatusBox('c', $question); ?>
                            </td>
                        </tr>
                    </table>

                    <center>
                        <p style="font-size: 400%;" class="question">
                            <b><?=$question->getQuestion(); ?></b><br />
                            <br />
                            <b>A.</b> <?=$question->getAnswerA(); ?><br />
                            <b>B.</b> <?=$question->getAnswerB(); ?><br />
                            <b>C.</b> <?=$question->getAnswerC(); ?><br />
                            <b>D.</b> <?=$question->getAnswerD(); ?>
                        </p>
                    </center>
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
    } else if($step == 3) {
        if(!$question->hasTeamAnswer($team)) {
            ?>
            <div data-role="page" id="page-login">
                <?php PageHeaderBuilder::create()->build(); ?>

                <div data-role="main" class="ui-content">
                    <?php

                    function printStatusBox($team, Question $question) {
                        $correct = StringUtils::equals($question->getCorrectAnswer(), $question->getTeamAnswer($team));

                        echo '<div class="team-status-box team-' . $team . ' ' . ($correct ? 'status-good' : 'status-bad'). '">';
                        echo strtoupper($question->getTeamAnswer($team)) . '<br />' . getTeamScore($team);

                        echo '</div>';
                    }

                    ?>

                    <table style="width: 100%;">
                        <tr style="width: 50%">
                            <td style="width: 33%;">
                                <?php printStatusBox('a', $question); ?>
                            </td>
                            <td style="width: 33%;">
                                <?php printStatusBox('b', $question); ?>
                            </td>
                            <td style="width: 33%;">
                                <?php printStatusBox('c', $question); ?>
                            </td>
                        </tr>
                    </table>

                    <center>
                        <p style="font-size: 400%;" class="question">
                            <b><?=$question->getQuestion(); ?></b><br />
                            <br />
                            <span style="color: <?=(StringUtils::equals($question->getCorrectAnswer(), 'a') ? 'green' : 'gray'); ?>;"><b>A.</b> <?=$question->getAnswerA(); ?></span><br />
                            <span style="color: <?=(StringUtils::equals($question->getCorrectAnswer(), 'b') ? 'green' : 'gray'); ?>;"><b>B.</b> <?=$question->getAnswerB(); ?></span><br />
                            <span style="color: <?=(StringUtils::equals($question->getCorrectAnswer(), 'c') ? 'green' : 'gray'); ?>;"><b>C.</b> <?=$question->getAnswerC(); ?></span><br />
                            <span style="color: <?=(StringUtils::equals($question->getCorrectAnswer(), 'd') ? 'green' : 'gray'); ?>;"><b>D.</b> <?=$question->getAnswerD(); ?></span><br /><br />
                        </p>
                    </center><br />
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
    } else if($step == 4) {
        ?>
        <div data-role="page" id="page-login">
            <?php PageHeaderBuilder::create()->build(); ?>

            <div data-role="main" class="ui-content">
                <?php

                function printStatusBox($team, Question $question) {
                    echo '<div class="team-status-box team-' . $team . '">';
                    echo $GLOBALS['team_names'][$team] . '<br />' . getTeamScore($team);

                    echo '</div>';
                }

                ?>
                <br />
                <br />
                <br />
                <br />
                <br />
                <br />
                <br />
                <br />
                <br />
                <br />
                <br />
                <table style="width: 100%;">
                    <tr style="width: 50%">
                        <td style="width: 33%;">
                            <?php printStatusBox('a', $question); ?>
                        </td>
                        <td style="width: 33%;">
                            <?php printStatusBox('b', $question); ?>
                        </td>
                        <td style="width: 33%;">
                            <?php printStatusBox('c', $question); ?>
                        </td>
                    </tr>
                </table>
            </div>

            <?php PageFooterBuilder::create()->setFixed(true)->build(); ?>
        </div>
        <?php

    } else if($step == 5) {
        ?>
        <div data-role="page" id="page-login">
            <?php PageHeaderBuilder::create()->build(); ?>

            <div data-role="main" class="ui-content">
                <br /><br />
                <center><img style="max-width: 60%; box-shadow: 0 0 25px rgba(0, 0, 0, .5)" src="style/image/welcome.png" /></center>
            </div>

            <?php PageFooterBuilder::create()->setFixed(true)->build(); ?>
        </div>
        <?php
    } else {
        showErrorPage('Internal error occurred. Invalid question step.');
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

    // Get the current step
    $step = (int) Registry::getValue('question.step')->getValue();

    if($step == 0) {
        ?>
        <div data-role="page" id="page-login">
            <?php PageHeaderBuilder::create($TEAM_NAMES[$team])->build(); ?>

            <div data-role="main" class="ui-content">
                <center>
                    <p>De checklist staat klaar.<br /><br />Start de checklist met de onderstaande knop als alle teams verbonden zijn.</p>
                </center>
                <br />

                <a href="#" class="ui-btn ui-corner-all ui-shadow button-start-quiz">Start Checklist</a>
            </div>

            <?php PageFooterBuilder::create()->build(); ?>
        </div>
        <?php

    } else if($step == 1) {
        ?>
        <div data-role="page" id="page-wait-for-answers">
            <?php PageHeaderBuilder::create()->build(); ?>

            <div data-role="main" class="ui-content">
                <center>
                    <?=$question->getQuestion(); ?><br />
                    <br />
                    <span style="color: #9A9A9A;"><b>A.</b> <?=$question->getAnswerA(); ?></span><br />
                    <span style="color: #9A9A9A;"><b>B.</b> <?=$question->getAnswerB(); ?></span><br />
                    <span style="color: #9A9A9A;"><b>C.</b> <?=$question->getAnswerC(); ?></span><br />
                    <span style="color: #9A9A9A;"><b>D.</b> <?=$question->getAnswerD(); ?></span><br /><br />
                    <hr /><br />
                    <a href="#" class="ui-btn ui-corner-all ui-shadow button-show-answers">Toon antwoorden</a>
                </center>
            </div>

            <?php PageFooterBuilder::create()->build(); ?>
        </div>
        <?php

    } else if($step == 2) {
        if(!$question->hasTeamAnswer('a') || !$question->hasTeamAnswer('b') || !$question->hasTeamAnswer('c')) {
            ?>
            <div data-role="page" id="page-wait-for-answers">
                <?php PageHeaderBuilder::create()->build(); ?>

                <div data-role="main" class="ui-content">
                    <center>
                        <?=$question->getQuestion(); ?><br />
                        <br />
                        <b>A.</b> <?=$question->getAnswerA(); ?><br />
                        <b>B.</b> <?=$question->getAnswerB(); ?><br />
                        <b>C.</b> <?=$question->getAnswerC(); ?><br />
                        <b>D.</b> <?=$question->getAnswerD(); ?><br /><br />
                        <hr /><br />
                        <b><?=$GLOBALS['team_names']['a']; ?>:</b> <span class="team-status-text team-a">Wachten op antwoord...</span><br />
                        <b><?=$GLOBALS['team_names']['b']; ?>:</b> <span class="team-status-text team-b">Wachten op antwoord...</span><br />
                        <b><?=$GLOBALS['team_names']['c']; ?>:</b> <span class="team-status-text team-c">Wachten op antwoord...</span><br /><br />
                        <img src="style/image/loader/loader.gif" /><br /><br />
                        Wachten op antwoorden van teams...
                    </center>
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
                        <?php

                        // Get the correct answer
                        $correct = $question->getCorrectAnswer();

                        echo $question->getQuestion();
                        ?><br />
                        <br />
                        <span style="color: gray"><b>A.</b> <?=$question->getAnswerA(); ?></span><br />
                        <span style="color: gray"><b>B.</b> <?=$question->getAnswerB(); ?></span><br />
                        <span style="color: gray"><b>C.</b> <?=$question->getAnswerC(); ?></span><br />
                        <span style="color: gray"><b>D.</b> <?=$question->getAnswerD(); ?></span><br /><br />

                        <span style="color: gray"><b>Team A:</b> <?php
                        if(StringUtils::equals($correct, $question->getTeamAnswer('a')))
                            echo 'Antwoord ' . strtoupper($question->getTeamAnswer('a')) . ' <span style="color: green;">(Correct)</span>';
                        else
                            echo 'Antwoord ' . strtoupper($question->getTeamAnswer('a')) . ' <span style="color: red;">(Incorrect)</span> <span style="color: #9A9A9A;"></span>';
                        ?></span><br />
                        <span style="color: gray"><b>Team B:</b> <?php
                        if(StringUtils::equals($correct, $question->getTeamAnswer('b')))
                            echo 'Antwoord ' . strtoupper($question->getTeamAnswer('b')) . ' <span style="color: green;">(Correct)</span>';
                        else
                            echo 'Antwoord ' . strtoupper($question->getTeamAnswer('b')) . ' <span style="color: red;">(Incorrect)</span> <span style="color: #9A9A9A;"></span>';
                        ?></span><br />
                        <span style="color: gray"><b>Team C:</b> <?php
                        if(StringUtils::equals($correct, $question->getTeamAnswer('c')))
                            echo 'Antwoord ' . strtoupper($question->getTeamAnswer('c')) . ' <span style="color: green;">(Correct)</span>';
                        else
                            echo 'Antwoord ' . strtoupper($question->getTeamAnswer('c')) . ' <span style="color: red;">(Incorrect)</span> <span style="color: #9A9A9A;"></span>';
                        ?></span><br />
                    </center>
                    <br /><hr /><br />

                    <center><p>Alle teams hebben antwoord gegeven.</p></center><br />
                    <a href="#" class="ui-btn ui-corner-all ui-shadow button-show-answer">Resultaat bekijken</a>
                </div>

                <?php PageFooterBuilder::create()->build(); ?>
            </div>
            <?php
        }

    } else if($step == 3) {
        ?>
        <div data-role="page" id="page-wait-for-answers">
            <?php PageHeaderBuilder::create()->build(); ?>

            <div data-role="main" class="ui-content">
                <center>
                    <?php

                    // Get the correct answer
                    $correct = $question->getCorrectAnswer();

                    echo $question->getQuestion();
                    ?><br />
                    <br />
                    <span style="color: <?=(StringUtils::equals($question->getCorrectAnswer(), 'a') ? 'green' : 'gray'); ?>;"><b>A.</b> <?=$question->getAnswerA(); ?></span><br />
                    <span style="color: <?=(StringUtils::equals($question->getCorrectAnswer(), 'b') ? 'green' : 'gray'); ?>;"><b>B.</b> <?=$question->getAnswerB(); ?></span><br />
                    <span style="color: <?=(StringUtils::equals($question->getCorrectAnswer(), 'c') ? 'green' : 'gray'); ?>;"><b>C.</b> <?=$question->getAnswerC(); ?></span><br />
                    <span style="color: <?=(StringUtils::equals($question->getCorrectAnswer(), 'd') ? 'green' : 'gray'); ?>;"><b>D.</b> <?=$question->getAnswerD(); ?></span><br /><br />

                    <b>Team A:</b> <?php
                    if(StringUtils::equals($correct, $question->getTeamAnswer('a')))
                        echo 'Antwoord ' . strtoupper($question->getTeamAnswer('a')) . ' <span style="color: green;">(Correct)</span>';
                    else
                        echo 'Antwoord ' . strtoupper($question->getTeamAnswer('a')) . ' <span style="color: red;">(Incorrect)</span> <span style="color: #9A9A9A;"></span>';
                    ?><br />
                    <b>Team B:</b> <?php
                    if(StringUtils::equals($correct, $question->getTeamAnswer('b')))
                        echo 'Antwoord ' . strtoupper($question->getTeamAnswer('b')) . ' <span style="color: green;">(Correct)</span>';
                    else
                        echo 'Antwoord ' . strtoupper($question->getTeamAnswer('b')) . ' <span style="color: red;">(Incorrect)</span> <span style="color: #9A9A9A;"></span>';
                    ?><br />
                    <b>Team C:</b> <?php
                    if(StringUtils::equals($correct, $question->getTeamAnswer('c')))
                        echo 'Antwoord ' . strtoupper($question->getTeamAnswer('c')) . ' <span style="color: green;">(Correct)</span>';
                    else
                        echo 'Antwoord ' . strtoupper($question->getTeamAnswer('c')) . ' <span style="color: red;">(Incorrect)</span> <span style="color: #9A9A9A;"></span>';
                    ?><br />

                    <br /><hr /><br />
                    <a href="#" class="ui-btn ui-corner-all ui-shadow button-next-question">Volgende</a>
                </center>
            </div>

            <?php PageFooterBuilder::create()->build(); ?>
        </div>
        <?php

    } else if($step == 4) {
        ?>
        <div data-role="page" id="page-wait-for-answers">
            <?php PageHeaderBuilder::create()->build(); ?>

            <div data-role="main" class="ui-content">
                <center>
                    <b>Checklist Resultaten</b><br />
                    <br />

                    <b>Team A:</b>&nbsp;&nbsp;<?=getTeamScore('a'); ?> punten<br />
                    <b>Team B:</b>&nbsp;&nbsp;<?=getTeamScore('b'); ?> punten<br />
                    <b>Team C:</b>&nbsp;&nbsp;<?=getTeamScore('c'); ?> punten<br />

                    <br /><br /><hr /><br />
                    <a href="#" class="ui-btn ui-corner-all ui-shadow button-show-welcome">Toon welkom bij Explorers</a>
                </center>
            </div>

            <?php PageFooterBuilder::create()->build(); ?>
        </div>
        <?php

    } else if($step == 5) {
        ?>
        <div data-role="page" id="page-wait-for-answers">
            <?php PageHeaderBuilder::create()->build(); ?>

            <div data-role="main" class="ui-content">
                <center>
                    Deez lijst is afgelopen bitches!<br />
                    <br />
                    YAAAY!<br /><br />
                    <hr /><br />
                    <a href="index.php?action=reset&t=z" class="ui-btn ui-corner-all ui-shadow button-reset" data-transition="slide" data-direction="reverse">Reset Checklist</a>
                </center>
            </div>

            <?php PageFooterBuilder::create()->build(); ?>
        </div>
        <?php

    } else
        showErrorPage('Internal error occurred. Invalid question step.');
}

// Include the page bottom
require_once('bottom.php');
