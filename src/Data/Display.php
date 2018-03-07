<?php
namespace Carawebs\SEO\Data;

use Carawebs\SEO\Traits\PartialSelector;

/**
* Class to build Facebook Open Graph tags.
* @package Carawebs\SEO
* @author David Egan <david@carawebs.com>
*/
class Display extends Base {

    use PartialSelector;

    public function __construct() {
        parent::__construct();
        $this->hook();
    }

    /**
    * Output meta-description
    * @return string The meta description
    */
    public function outputMetaDescription()
    {
        ob_start();
        ?>
        <meta name="description" content="<?= $this->MetaDescription; ?>">
        <?php
        echo ob_get_clean();
    }

    /**
    * Build the open graph tags
    * @return [type] [description]
    */
    public function ogTags()
    {
        include $this->partial_selector( 'head/og' );
    }

    /**
    * Output into the document
    * @return void
    */
    public function hook() {
        add_filter('pre_get_document_title', [$this, 'getTitle'], 10);
        add_filter('wp_head', [$this, 'outputMetaDescription'], 1);
        add_filter('wp_head', [$this, 'ogTags'], 2 );
    }
}
