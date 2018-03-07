<?php
namespace Carawebs\SEO\Data;

/**
* Class to build meta description, og-tags & title tag
*
* @package Carawebs\SEO
* @author David Egan <david@carawebs.com>
*/
class Base {
    protected $metaDescription;
    protected $title;
    protected $postId;
    protected $type;
    protected $image;
    protected $url;
    protected $locale;
    protected $siteName;

    public function __construct() {
        $this->setId();
        $this->setTitle();
        $this->setMetaDescription();
        $this->setType();
        $this->setImage();
        $this->setUrl();
        $this->setLocale();
        $this->setSiteName();
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
            $customDescription = wp_strip_all_tags(wp_strip_all_tag(get_the_archive_description(), 0, 155), TRUE);
        } else {
            $customDescription = get_post_meta( $this->postId, 'meta_description', TRUE );
        }
        if (empty($customDescription)) {
            $customDescription = $this->customExcerpt(155);
        }
        $this->MetaDescription = $customDescription;
    }

    /**
    * Build a custom post excerpt
    * @param  integer $characters Number of characters in the excerpt
    * @return string The custom excerpt
    */
    private function customExcerpt($characters = 155)
    {
        $postObject = get_post( $this->postId );
        if (empty($postObject)) return;
        if (empty( $postObject->post_excerpt)) {
            return wp_strip_all_tags(substr($postObject->post_content, 0, $characters ), TRUE);
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
        if(is_archive()) {
            $customTitle = get_bloginfo() . ' | ' . get_the_archive_title();
        } else {
            $customTitle = get_post_meta($this->postId, 'title_tag', TRUE);
        }
        if (empty($customTitle )) {
            $customTitle = $this->customTitle(55);
        }
        $this->title = wp_strip_all_tags($customTitle, TRUE);
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
            $this->image = esc_url(home_url()) . esc_attr($thumbnail_src[0]);
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
}
