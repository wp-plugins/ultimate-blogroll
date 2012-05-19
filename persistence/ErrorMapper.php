<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Jens
 * Date: 22/12/11
 * Time: 23:44
 * To change this template use File | Settings | File Templates.
 */
 
class ErrorMapper {
    private $error = array();
    /**
     * Get all the errors as an array
     * @return array
     */
    public function getError() {
        return $this->error;
    }

    /**
     * Add an error
     * @param $key
     * @param $value
     */
    public function addError($key, $value) {
        $this->error[$key] = $value;
    }

    /**
     * Get all the errors ready to be displayed in an <ul> tag
     * @return string
     */
    public function getErrorAsString() {
        $output = "";
        if(count($this->error) > 0)
            foreach($this->error as $error) {
                $output .= "<li>".$error."</li>\n";
            }
        return $output;
    }
    /**
     * Check if a particular field is wrong
     * @param $key
     * @return bool
     */
    public function isWrong($key) {
        if(isset($this->error[$key]))
            return true;
        return false;
    }
}
