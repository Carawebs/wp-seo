<?php
namespace Carawebs\SEO\Data;

/**
* Class to build meta description, og-tags & title tag
*
* @package Carawebs\SEO
* @author David Egan <david@carawebs.com>
*/
class MetaTags {

    protected $metaDescription;
    protected $title;
    protected $postId;

    public function __construct() {
        $this->setId();
        $this->setTitle();
        $this->setMetaDescription();
        //$this->og = $ogObject;
        $this->hook();
    }

    private function setId() {
        if (is_home()) {
            $this->postId = get_option( 'page_for_posts' );
        } else {
            $this->postId = get_the_ID();
        }
    }

    /**
    * Set meta description.
    * Uses the 'meta_description' postmeta field preferentially, falls back to the
    * custom excerpt.
    */
    private function setMetaDescription()
    {
        if(is_archive()) {
            $customDescription = get_the_archive_description();
        } else {
            $customDescription = get_post_meta( $this->postId, 'meta_description', TRUE );
        }
        if (empty($customDescription)) {
            $customDescription = $this->customExcerpt(155);
        }
        $this->MetaDescription = $customDescription;
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
    * Build a custom post excerpt
    * @param  integer $characters Number of characters in the excerpt
    * @return string The custom excerpt
    */
    private function customExcerpt($characters = 155)
    {
        $postObject = get_post( $this->postId );
        if ( empty( $postObject->post_excerpt ) ) {
            return wp_strip_all_tags( substr( $postObject->post_content, 0, $characters ), TRUE );
        } else {
            return wp_kses_post( $postObject->post_excerpt );
        }
    }

    /**
    * Preferentially set title as content of 'title_tag' post meta field.
    * Falls back to `$this->customTitle()`, which is the first 55 characters of
    * the title.
    * @return string Title tag
    */
    private function setTitle()
    {
        if( is_archive() ) {
            $customTitle = get_bloginfo() . ' | ' . get_the_archive_title();
        } else {
            $customTitle = get_post_meta( $this->postId, 'title_tag', TRUE );
        }
        if ( empty( $customTitle ) ) {
            $customTitle = $this->customTitle( 55 );
        }
        $this->title = wp_strip_all_tags( $customTitle, TRUE );
    }

    /**
    * Return the first n characters of the post title
    *
    * @param  integer $characters Number of leading characters (default = 55)
    * @return string First $character characters of the title
    */
    private function customTitle($characters = 55)
    {
        return substr(get_the_title( $this->postId), 0, $characters);
    }

    /**
    * Return the title tag
    * @return string title tag
    */
    public function getTitle()
    {
        return $this->title;
    }

    /**
    * Get open graph tags
    * @return void
    */
    public function ogTags()
    {
        $og->og_tags();
    }

    /**
    * Output into the document
    * @return void
    */
    public function hook() {
        add_filter('pre_get_document_title', [$this, 'getTitle'], 10);
        add_filter('wp_head', [$this, 'outputMetaDescription'], 1);
        //add_filter('wp_head', [$this, 'ogTags'], 2 );
    }
}
