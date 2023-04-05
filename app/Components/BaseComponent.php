<?php

namespace App\Components;

class BaseComponent {
    protected $composite = false;
    protected static $rendered_icons = false;

    protected $children     = [];
    protected $css          = [];
    protected $css_external = [];
    protected $js           = [];
    protected $js_config    = [];
    protected $js_external  = [];
    protected $icons        = [];

    public function __construct($children = []) {
        if ($this->isComposite()) {
            $this->children = $children;

            foreach ($children as $child) {
                if (is_array($child)) {
                    foreach ($child as $grandchild) {
                        $this->css = array_merge($this->css, $grandchild->getCSS());
                        $this->js = array_merge($this->js, $grandchild->getJS());
                        $this->icons = array_merge($this->getIcons(), $grandchild->getIcons());

                        $name = substr(get_class($grandchild), strrpos(get_class($grandchild), '\\') + 1);
                        $this->js_config[$name] = $grandchild->getJSConfiguration();
                    }
                } else {
                    $this->css = array_merge($this->css, $child->getCSS());
                    $this->js = array_merge($this->js, $child->getJS());
                    $this->icons = array_merge($this->getIcons(), $child->getIcons());

                    $name = substr(get_class($child), strrpos(get_class($child), '\\') + 1);
                    $this->js_config[$name] = $child->getJSConfiguration();
                }
            }
        }
    }

    public function renderHTML() {
        return "";
    }

    public function getCSS() {
        return $this->css;
    }

    public function getJS() {
        return $this->js;
    }

    public function getIcons() {
        return $this->icons;
    }

    public function getJSExternal() {
        return $this->js_external;
    }

    public function getCSSExternal() {
        return $this->css_external;
    }

    public function getJSConfiguration() {
        return $this->js_config;
    }

    public function isComposite() {
        return $this->composite;
    }
}
