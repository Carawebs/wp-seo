<?php
namespace Carawebs\SEO\Data;

use Carawebs\SEO\Traits\PartialSelector;

/**
* Class to build Facebook Open Graph tags.
* @package Carawebs\SEO
* @author David Egan <david@carawebs.com>
*/
class OpenGraph extends MetaTags {

    use PartialSelector;

    private $type;
    private $image;
    private $url;
    private $locale;
    private $siteName;

    public function __construct() {
        parent::__construct();
        $this->setType();
        $this->setImage();
        $this->setUrl();
        $this->setLocale();
        $this->setSiteName();
        $this->hook();
    }

    /**
    * Set og type
    */
    private function setType()
    {
        $this->type = is_front_page() ? 'website' : 'article';
    }

    /**
    * Set image URL for this post
    */
    private function setImage()
    {
        $this->image = wp_get_attachment_url(get_post_thumbnail_id($this->postId));
        if(!has_post_thumbnail($this->postId)) {
            $this->image = esc_url( home_url( '/wp-content/uploads/2016/01/0086-1small.jpg' ) );
        } else {
            $thumbnail_src = wp_get_attachment_image_src(get_post_thumbnail_id($this->postId), 'medium');
            $this->image = esc_url(home_url('/')) . esc_attr($thumbnail_src[0]);
        }
    }

    /**
    * Set the post URL
    */
    private function setUrl()
    {
        $this->url = get_the_permalink();
    }

    /**
    * Set the locale
    */
    private function setLocale()
    {
        $this->locale = "en_US";
    }

    /**
    * Set the site name
    */
    private function setSiteName()
    {
        $this->siteName = esc_html( get_bloginfo('name') );
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
        add_filter('wp_head', [$this, 'ogTags'], 2 );
    }
}
