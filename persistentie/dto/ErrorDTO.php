<?php
class ErrorDTO {
    private $errorMessages, $errorFields;
    public function AddErrorMessage($msg) {
        $this->errorMessages[] = "<li>".$msg."</li>";
    }

    public function AddErrorField($field) {
        $this->errorFields[$field] = true;
    }

    public function GetErrorMessages() {
        return $this->errorMessages;
    }

    public function GetErrorFields() {
        return $this->errorFields;
    }

    public function IsError($field) {
        return isset($this->errorFields[$field]);
    }

    public function ContainsErrors() {
        if(count($this->errorFields) > 0)
            return true;
        return false;
    }
}
?>