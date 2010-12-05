<?php
class WidgetSettingsDTO {
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

    public function __construct($title, $limit, $order_by, $ascending, $permalink) {
        $this->title = $title;
        $this->limit = $limit;
        $this->order_by = $order_by;
        $this->ascending = $ascending;
        $this->permalink = $permalink;
    }
}
?>