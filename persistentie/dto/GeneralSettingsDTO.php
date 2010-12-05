<?php
class GeneralSettingsDTO {
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

    public function __construct($url, $title, $description, $contact, $support, $send_mail, $reciprocal, $fight_spam, $target, $nofollow) {
        $this->url          = $url;
        $this->title        = $title;
        $this->description  = $description;
        $this->contact      = $contact;
        $this->support      = $support;
        $this->send_mail    = $send_mail;
        $this->reciprocal   = $reciprocal;
        $this->fight_spam   = $fight_spam;
        $this->target       = $target;
        $this->nofollow     = $nofollow;
    }
}
?>