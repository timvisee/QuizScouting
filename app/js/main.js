// Store the map instance
var map = null;

// The page refresh timer instance
var pageRefreshTimer = null;

var clientUid = Math.random();

var teamAnswered = {
    'a': false,
    'b': false,
    'c': false
};

var teamNames = {
    'a': 'Team A',
    'b': 'Team B',
    'c': 'Team C'
};

var htmlWaiter = '<img src="style/image/loader/loader_gray.gif" />';

var PUBNUBchannel;

/**
 * Start or restart the refresh timer.
 */
function startRefreshTimer() {
    // Stop the current timer
    stopRefreshTimer();

    // Set up the timer
    pageRefreshTimer = setInterval(function () {
        if (getActivePageId() != 'page-map') {
            showLoader('Refreshing page...');
            nextPage();
            hideLoader();
        }
    }, 1000 * 15);
}

/**
 * Stop the refresh timer.
 */
function stopRefreshTimer() {
    // Clear the timer
    if (pageRefreshTimer != null)
        clearInterval(pageRefreshTimer);

    // Reset the variable
    pageRefreshTimer = null;
}

/**
 * Get the ID of the current active page.
 *
 * @returns string ID of active page.
 */
function getActivePageId() {
    return $.mobile.activePage.attr("id");
}

/**
 * Go to the next jQuery page.
 */
function nextPage(transition) {
    // Use the transition default if not set
    transition = typeof transition !== 'undefined' ? transition : 'none';

    // Append the team tag if set
    var teamTag = '';
    if (appTeam != null)
        teamTag = '&t=' + appTeam;

    // Reload the page with the specified transition
    jQuery.mobile.changePage('index.php?v=' + Math.random() + teamTag, {
        allowSamePageTransition: true,
        transition: transition,
        reloadPage: true,
        reverse: false,
        changeHash: true
    });
}

function setPictureApprovalStatus(pictureId, approvalStatus, successCallback, errorCallback) {
    // Show the loader
    showLoader('Approving picture...');

    // Make an AJAX request to load the station results
    $.ajax({
        type: "GET",
        url: "ajax/approval.php",
        data: {picture_id: pictureId, set_approval: approvalStatus},
        contentType: "application/json; charset=utf-8",
        dataType: "json",
        success: function (data) {
            // Show the error message if returned
            if (data.hasOwnProperty('error_msg')) {
                errorCallback(data.error_msg);
                return;
            }

            successCallback();
        },
        error: function (msg) {
            errorCallback(msg.statusText);
        },
        complete: function () {
            hideLoader();
        }
    });
}

/**
 * Check whether an element has an attribute.
 *
 * @param attrName The name of the attribute.
 *
 * @returns {boolean} True if the attribute exists, false otherwise.
 */
jQuery.fn.hasAttr = function (attrName) {
    // Get the attribute
    var attr = $(this[0]).attr(attrName);

    // Check if the attribute exists
    return (typeof attr !== typeof undefined && attr !== false);
};

// Initialize the map on page load
$(document).on('pageshow', function (event, ui) {
    // Check if the map page is unloaded
    if ($(ui.prevPage).hasAttr('id')) {
        // Get the element ID
        var attrId = $(ui.prevPage).attr('id').toLowerCase();

        // Check whether the map page is unloaded
        if (attrId == 'page-map') {
            // Unload the map
            if (map !== null) {
                map.remove();
                map = null;
            }
        }
    }

    // Determine whether to unload the previous page
    var unload = true;

    if ($(ui.prevPage).hasAttr('data-unload')) {
        // Get the element attribute
        var attrUnload = $(ui.prevPage).attr('data-unload').toLowerCase();

        // Check whether to unload
        if (attrUnload == "false")
            unload = false;
    }

    // Unload the page
    if (unload)
        $(ui.prevPage).remove();

    // Restart the refresh timer
    startRefreshTimer();
});

function showLoader(msgText) {
    $.mobile.loading("show", {
        text: msgText,
        textVisible: "true",
        theme: "b",
        textonly: false,
        html: ""
    });
}

function hideLoader() {
    $.mobile.loading("hide");
}

/**
 * Set a question answer.
 *
 * @param answer The answer.
 * @param successCallback
 */
function setAnswer(answer, successCallback) {
    showLoader('Antwoord sturen...');

    // Send an AJAX request to send the answer, call the success callback if the request succeed.
    $.ajax({
        dataType: "json",
        url: "ajax/setquestion.php?t=" + appTeam,
        data: {"answer": answer},
        success: successCallback,
        error: function () {
            alert('Er is een fout opgetreden! Probeer het opnieuw.');
        },
        complete: function () {
            // Hide the loader
            hideLoader();
        }
    });
}

function sendData(data) {
    // Set the source team
    data.sourceTeam = appTeam;
    data.uid = clientUid;

    // Send the data
    PUBNUBchannel.publish({
        channel: 'main',
        message: data
    });
}

function setTeamStatus(team, answer) {
    // Get the selector
    var selector = $('.team-status-box.team-' + team);
    var textSelector = $('.team-status-text.team-' + team);

    if (answer == null) {
        if (selector.hasClass('status-wait'))
            selector.removeClass('status-wait');
        if (selector.hasClass('status-good'))
            selector.removeClass('status-good');
        if (selector.hasClass('status-bad'))
            selector.removeClass('status-bad');

        // Set the content
        selector.html(teamNames[team] + '<br />' + htmlWaiter);

        // Set the status
        teamAnswered[team] = false;

        // Set the answer on the controller
        textSelector.html('Wachten op antwoord...');

    } else {
        selector.addClass('status-wait', 500, 'swing');
        if (selector.hasClass('status-good'))
            selector.removeClass('status-good');
        if (selector.hasClass('status-bad'))
            selector.removeClass('status-bad');

        // Set the content
        selector.html(teamNames[team]);

        // Set the status
        teamAnswered[team] = true;

        // Set the answer on the controller
        textSelector.html('Antwoord ' + answer.toUpperCase());
    }
}

$(document).ready(function () {
    PUBNUBchannel = PUBNUB.init({
        publish_key: 'pub-c-2b9b1d1f-f703-4c65-9324-481ea9c5635d',
        subscribe_key: 'sub-c-17475c7e-5355-11e5-b316-0619f8945a4f'
    });

    PUBNUBchannel.subscribe({
        channel: 'main',
        message: function (data) {
            // Make sure this msg is from a different client
            if (data.uid == clientUid)
                return;

            // Check if an answer is set
            if (typeof(data.a) !== 'undefined') {
                // Set the team status
                setTeamStatus(data.sourceTeam, data.a);

                // If this is the current team, go to the next page
                if (data.sourceTeam == appTeam)
                    nextPage("slide");
            }

            // Check if an answer is set
            if (typeof(data.action) !== 'undefined') {
                if (data.action == 'startQuiz' && data.sourceTeam != clientUid)
                    nextPage('flow');

                if (data.action == 'showAnswer' && data.sourceTeam != clientUid) {
                    if (appTeam != 'y')
                        nextPage('slide');
                    else
                        nextPage('flip');
                }

                if (data.action == 'showAnswers' && data.sourceTeam != clientUid)
                    if (appTeam != 'y')
                        nextPage('slide');
                    else
                        nextPage('fade');

                if (data.action == 'nextQuestion' && data.sourceTeam != clientUid)
                    nextPage('flow');

                if (data.action == 'showWelcome' && data.sourceTeam != clientUid)
                    nextPage('flow');

                if (data.action == 'reset' && data.sourceTeam != clientUid)
                    nextPage('flow');
            }

            // Continue to the next page if everybody answered
            if (appTeam == 'z' && teamAnswered['a'] && teamAnswered['b'] && teamAnswered['c'])
                nextPage('slide');
        }
    });
});

// Create the button click handlers
$(document).on("pagecreate", function () {
    // Create the button click handlers
    $('.button-a').click(function () {
        processButtonClick('a');
    });
    $('.button-b').click(function () {
        processButtonClick('b');
    });
    $('.button-c').click(function () {
        processButtonClick('c');
    });
    $('.button-d').click(function () {
        processButtonClick('d');
    });
    $('.button-start-quiz').click(function () {
        showLoader('Antwoord tonen...');
        $.ajax({
            dataType: "json",
            url: "ajax/setstep.php?t=" + appTeam,
            data: {"step": 1},
            success: function () {
                sendData({action: 'startQuiz'});
                nextPage('slide');
            },
            error: function () {
                alert('Er is een fout opgetreden! Probeer het opnieuw.');
            },
            complete: function () {
                // Hide the loader
                hideLoader();
            }
        });
    });
    $('.button-show-answers').click(function () {
        showLoader('Antwoord tonen...');
        $.ajax({
            dataType: "json",
            url: "ajax/setstep.php?t=" + appTeam,
            data: {"step": 2},
            success: function () {
                sendData({action: 'showAnswers'});
                nextPage('slide');
            },
            error: function () {
                alert('Er is een fout opgetreden! Probeer het opnieuw.');
            },
            complete: function () {
                // Hide the loader
                hideLoader();
            }
        });
    });
    $('.button-show-answer').click(function () {
        showLoader('Antwoord tonen...');
        $.ajax({
            dataType: "json",
            url: "ajax/setstep.php?t=" + appTeam,
            data: {"step": 3},
            success: function () {
                sendData({action: 'showAnswer'});
                nextPage('slide');
            },
            error: function () {
                alert('Er is een fout opgetreden! Probeer het opnieuw.');
            },
            complete: function () {
                // Hide the loader
                hideLoader();
            }
        });
    });
    $('.button-next-question').click(function () {
        showLoader('Volgende vraag aanvragen...');
        $.ajax({
            dataType: "json",
            url: "ajax/nextquestion.php?t=" + appTeam,
            data: {},
            success: function (data) {
                // Print the error if an error occurred
                if (typeof(data.error) !== 'undefined') {
                    // Print the error
                    alert('Error: ' + data.error);

                    // Refresh the page and return
                    nextPage();
                    return;
                }

                sendData({action: 'nextQuestion'});
                nextPage('flow');
            },
            error: function () {
                alert('Er is een fout opgetreden! Probeer het opnieuw.');
            },
            complete: function () {
                // Hide the loader
                hideLoader();
            }
        });
    });
    $('.button-show-welcome').click(function () {
        showLoader('Welkom tonen...');
        $.ajax({
            dataType: "json",
            url: "ajax/nextquestion.php?t=" + appTeam,
            data: {},
            success: function (data) {
                // Print the error if an error occurred
                if (typeof(data.error) !== 'undefined') {
                    // Print the error
                    alert('Error: ' + data.error);

                    // Refresh the page and return
                    nextPage();
                    return;
                }

                sendData({action: 'showWelcome'});
                nextPage('flow');
            },
            error: function () {
                alert('Er is een fout opgetreden! Probeer het opnieuw.');
            },
            complete: function () {
                // Hide the loader
                hideLoader();
            }
        });
    });
    $('.button-reset').click(function () {
        showLoader('Checklist resetten...');
        setTimeout(function () {
            sendData({action: 'reset'});
            hideLoader();
        }, 500);
    });

    /**
     * Process a button click.
     *
     * @param answer The answer.
     */
    function processButtonClick(answer) {
        setAnswer(answer, function (data) {
            // Print the error if an error occurred
            if (typeof(data.error) !== 'undefined') {
                // Print the error
                alert('Error: ' + data.error);

                // Refresh the page and return
                nextPage();
                return;
            }

            // Send the answer
            sendData({a: answer});

            // Refresh the page
            nextPage("slide");
        });

        return false;
    }
});

// Create the button click handlers
$(document).on("pagecreate", "#page-wait-for-answers", function () {
    // Send an AJAX request to send the answer, call the success callback if the request succeed.
    showLoader('Team status laden...');
    $.ajax({
        dataType: "json",
        url: "ajax/getteamanswered.php?t=" + appTeam,
        data: {},
        success: function (data) {
            teamAnswered['a'] = data.a;
            teamAnswered['b'] = data.b;
            teamAnswered['c'] = data.c;
        },
        complete: function () {
            // Hide the loader
            hideLoader();
        }
    });
});