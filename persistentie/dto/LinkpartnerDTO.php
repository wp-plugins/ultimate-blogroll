<?php
class LinkpartnerDTO {
    private $vars = array();
    public function __isset($key) {
        return isset($this->vars[$key]);
    }

    public function __get($name) {
        return $this->vars[$name];
    }

    private function __set($key, $value) {
        $this->vars[$key] = $value;
    }
    
    public function __construct($name, $email, $url, $title, $description, $domain, $reciprocal, $image_url) {
        $this->name         = $name;
        $this->email        = $email;
        $this->url          = $url;
        $this->title        = $title;
        $this->description  = $description;
        $this->domain       = $domain;
        $this->reciprocal   = $reciprocal;
        $this->image_url    = $image_url;
    }

    public function SetLinkBack($status) {
        $this->has_backlink = $status;
    }
}
?>