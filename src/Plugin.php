<?php
namespace Carawebs\SEO;

use Carawebs\SEO\Data\Display;

if (!defined('ABSPATH')) exit;
/**
* Main plugin class
*/
class Plugin
{
    private $config;
    private $bail = false;

    public function __construct($basePath, $namePrefix)
    {
        $continue = $this->setPaths($basePath);
        if (true === $continue) {
            $this->namePrefix = $namePrefix;
            $this->initialiseObjects();
        } else {
            $this->bail = true;
        }
    }

    public function setPaths($basePath)
    {
        $this->basePath = $basePath;
        return true;
    }

    /**
     * Initialise Objects
     */
    private function initialiseObjects()
    {
        if (true === $this->bail) return;
        add_action('wp', function() {
            $this->autoloader = new Autoloader;
        });
    }

    public function init()
    {
        if (true === $this->bail) return;
        add_action('wp', function() {
            // new MetaTags();
            // new OpenGraph();
            new Display();
            $this->onActivation();
            $this->onDeactivation();
        });
    }

    private function onActivation()
    {
        register_activation_hook( __FILE__, function() {
            flush_rewrite_rules();
        });
    }

    private function onDeactivation()
    {
        register_deactivation_hook( __FILE__, function(){
            flush_rewrite_rules();
        });
    }
}
