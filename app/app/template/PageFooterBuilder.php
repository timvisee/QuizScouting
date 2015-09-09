<?php

namespace app\template;

use app\session\SessionManager;
use app\util\ColorUtils;

class PageFooterBuilder {

    /** The default footer title. */
    public static $FOOTER_TITLE_DEFAULT = '';

    /** @var bool Has fixed. */
    private $fixed = false;

    /**
     * Constructor.
     */
    public function __construct() {
        static::$FOOTER_TITLE_DEFAULT = 'Copyright &copy; Tim Vis&eacute;e ' . date('Y');
    }

    /**
     * Alternate constructor.
     * This constructor allows method chaining.
     *
     * @return PageHeaderBuilder The instance.
     */
    public static function create() {
        return new self();
    }

    /**
     * Set fixed.
     *
     * @param bool $fixed Set fixed.
     *
     * @return self The current instance to allow method chaining.
     */
    public function setFixed($fixed = null) {
        $this->fixed = $fixed;
        return $this;
    }

    /**
     * Has fixed.
     *
     * @return bool True if fixed, false if not.
     */
    public function hasFixed() {
        return $this->fixed;
    }

    /**
     * Build and print the header.
     */
    public function build() {
        // TODO: Remove coloring from OVRally

        // Define the footer background
        $teamColor = null;
        $footerDivStyle = '';
        /*if(SessionManager::isLoggedIn() && SessionManager::getLoggedInTeam()->hasColorHex()) {
            $teamColor = SessionManager::getLoggedInTeam()->getColorHex();
            $footerDivStyle .= 'background: #' . $teamColor . ';';
        }*/

        // Determine the footer text color
        $footerColor = '333333';
        $footerColorShadow = 'EEEEEE';
        $footerShadow = 'CCCCCC';
        if($teamColor != null)
            $footerShadow = ColorUtils::adjustHexBrightness($teamColor, -25);
        if($teamColor != null && ColorUtils::getHexBrightness($teamColor) < 150) {
            $footerColor = 'F8F8F8';
            $footerColorShadow = '333333';
        }

        // Set the footer shadow/border
        $footerDivStyle .= 'border-color: #' . $footerShadow . ';';

        // Print div opening tag, of the header
        echo '<div data-role="footer" style="' . $footerDivStyle . '" ' . ($this->hasFixed() ? ' data-position="fixed"' : '') . '>';

        // Print the title
        echo '<h1 style="color: #' . $footerColor . '; text-shadow: 0 1px 0 #' . $footerColorShadow . ';">' . static::$FOOTER_TITLE_DEFAULT . '</h1>';

        // Print div closing tag
        echo '</div>';

        // TODO: Implement this!
        //echo '<center><p style="font-size: 80%; color: darkgray; padding: 16px 0;">KvK: 123.45.678</p></center>';
    }
}