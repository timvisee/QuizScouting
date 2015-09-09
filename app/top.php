<?php

use app\config\Config;
use app\question\Question;
use app\template\PageFooterBuilder;
use app\template\PageHeaderBuilder;

// Initialize the app
require_once('app/init.php');

// Set the site's path
$site_root = Config::getValue('general', 'site_url', '');
$site_root = '';

?>
<!DOCTYPE>
<html>
<head>

    <title><?=APP_NAME; ?></title>

    <!-- Meta -->
    <meta charset="UTF-8">
    <meta name="description" content="BARbapAPPa by Tim Vis&eacute;e">
    <meta name="keywords" content="BARbapAPPa,Bar,App">
    <meta name="author" content="Tim Vis&eacute;e">
    <link rel="copyright" href="about.php">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#2A2A2A">
    <meta name="application-name" content="BARbapAPPa">
    <meta name="msapplication-TileColor" content="#2a2a2a">
    <meta name="msapplication-config" content="<?=$site_root; ?>style/image/favicon/browserconfig.xml">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta property="og:title" content="BARbapAPPa">
    <meta property="og:image" content="<?=$site_root; ?>style/image/favicon/favicon-194x194.png">
    <meta property="og:description" content="BARbapAPPa by Tim Vis&eacute;e">
    <meta name="twitter:card" content="summary" />
    <meta name="twitter:title" content="BARbapAPPa">
    <meta name="twitter:image" content="<?=$site_root; ?>style/image/favicon/apple-touch-icon-120x120.png">
    <meta name="twitter:description" content="BARbapAPPa by Tim Vis&eacute;e">

    <!-- Favicon -->
    <link rel="apple-touch-icon" sizes="57x57" href="<?=$site_root; ?>style/image/favicon/apple-touch-icon-57x57.png">
    <link rel="apple-touch-icon" sizes="60x60" href="<?=$site_root; ?>style/image/favicon/apple-touch-icon-60x60.png">
    <link rel="apple-touch-icon" sizes="72x72" href="<?=$site_root; ?>style/image/favicon/apple-touch-icon-72x72.png">
    <link rel="apple-touch-icon" sizes="76x76" href="<?=$site_root; ?>style/image/favicon/apple-touch-icon-76x76.png">
    <link rel="apple-touch-icon" sizes="114x114" href="<?=$site_root; ?>style/image/favicon/apple-touch-icon-114x114.png">
    <link rel="apple-touch-icon" sizes="120x120" href="<?=$site_root; ?>style/image/favicon/apple-touch-icon-120x120.png">
    <link rel="apple-touch-icon" sizes="144x144" href="<?=$site_root; ?>style/image/favicon/apple-touch-icon-144x144.png">
    <link rel="apple-touch-icon" sizes="152x152" href="<?=$site_root; ?>style/image/favicon/apple-touch-icon-152x152.png">
    <link rel="apple-touch-icon" sizes="180x180" href="<?=$site_root; ?>style/image/favicon/apple-touch-icon-180x180.png">
    <link rel="icon" type="image/png" href="<?=$site_root; ?>style/image/favicon/favicon-32x32.png" sizes="32x32">
    <link rel="icon" type="image/png" href="<?=$site_root; ?>style/image/favicon/favicon-194x194.png" sizes="194x194">
    <link rel="icon" type="image/png" href="<?=$site_root; ?>style/image/favicon/favicon-96x96.png" sizes="96x96">
    <link rel="icon" type="image/png" href="<?=$site_root; ?>style/image/favicon/android-chrome-192x192.png" sizes="192x192">
    <link rel="icon" type="image/png" href="<?=$site_root; ?>style/image/favicon/favicon-16x16.png" sizes="16x16">
    <link rel="manifest" href="<?=$site_root; ?>style/image/favicon/manifest.json">
    <link rel="shortcut icon" href="<?=$site_root; ?>style/image/favicon/favicon.ico">
    <meta name="apple-mobile-web-app-title" content="BARbapAPPa">
    <meta name="msapplication-TileImage" content="<?=$site_root; ?>style/image/favicon/mstile-144x144.png">

    <!-- Script -->
    <script src="<?=$site_root; ?>lib/jquery/jquery-1.11.3.min.js"></script>
    <script src="<?=$site_root; ?>js/jquery.mobile.settings.js"></script>
    <script src="<?=$site_root; ?>js/main.js"></script>

    <!-- Library: jQuery Mobile -->
    <link rel="stylesheet" href="<?=$site_root; ?>lib/jquery-mobile/jquery.mobile-1.4.5.min.css" />
    <script src="<?=$site_root; ?>lib/jquery-mobile/jquery.mobile-1.4.5.min.js"></script>

    <!-- Style -->
    <link rel="stylesheet" type="text/css" href="<?=$site_root; ?>style/style.css">


    <!-- Include the PubNub Library -->
    <script src="https://cdn.pubnub.com/pubnub-dev.js"></script>

    <script type="text/javascript">
        $(document).ready(function() {
            var PUBNUBchannel = PUBNUB.init({
                publish_key: 'pub-c-2b9b1d1f-f703-4c65-9324-481ea9c5635d',
                subscribe_key: 'sub-c-17475c7e-5355-11e5-b316-0619f8945a4f'
            });

            // Publish a simple message to the demo_tutorial channel
            setTimeout(function() {
                PUBNUBchannel.publish({
                    channel: 'main',
                    message: {"action": "next"}
                });
            }, 3000);

            PUBNUBchannel.subscribe({
                channel: 'main',
                message: function(m){
                    if(m.action == 'next')
                        refreshPage();
                }
            });
        });
    </script>

</head>
<body>

<?php

/**
 * Show a regular error page.
 *
 * @param string|null $errorMsg [optional] A custom error message, or null to show the default.
 */
function showErrorPage($errorMsg = null) {
    // Show an error page
    ?>
    <div data-role="page" id="page-main">
        <?php PageHeaderBuilder::create('Oeps!')->setBackButton('index.php')->build();

        if($errorMsg === null): ?>
            <div data-role="main" class="ui-content">
                <p>Er is een fout opgetreden die niet hersteld kon worden.<br />Ga terug en probeer het opnieuw.</p><br />

                <fieldset data-role="controlgroup" data-type="vertical">
                    <a href="index.php" data-ajax="false" data-rel="back" class="ui-btn ui-icon-back ui-btn-icon-left" data-direction="reverse">Ga terug</a>
                </fieldset>
            </div>
        <?php else: ?>
            <div data-role="main" class="ui-content">
                <p><?=$errorMsg; ?></p><br />

                <fieldset data-role="controlgroup" data-type="vertical">
                    <a href="index.php" data-ajax="false" data-rel="back" class="ui-btn ui-icon-back ui-btn-icon-left" data-direction="reverse">Ga terug</a>
                </fieldset>
            </div>
        <?php endif;

        PageFooterBuilder::create()->build(); ?>
    </div>
    <?php

    // Print the bottom of the page
    require('bottom.php');
    die();
}

/**
 * Get the current question.
 *
 * @return Question The current question.
 * @throws Exception
 */
function getCurrentQuestion() {
    return new Question((int) Registry::getValue(REG_QUESTION_CURRENT));
}