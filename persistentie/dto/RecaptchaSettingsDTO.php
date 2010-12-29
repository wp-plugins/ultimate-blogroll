<?php
/**
 * Description of RecaptchaSettingsDTO
 *
 * @author Jens
 */
class RecaptchaSettingsDTO {
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

    public function __construct($recaptcha_public_key, $recaptcha_private_key) {
        $this->recaptcha_public_key     = $recaptcha_public_key;
        $this->recaptcha_private_key    = $recaptcha_private_key;
    }
}
?>