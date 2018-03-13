<?php
class Transaction {
    public function __construct() {
        $this->amount = null;
        $this->created_on = date('Y-m-d');
    }

    public static function from_form($amount, $created_on) {
        $instance = new self();
        $instance->amount = $amount;
        $instance->created_on = $created_on;
        return $instance;
    }

    public static function from_result_set($result_set) {
        $instance = new self();
        $instance->amount = $result_set->amount;
        $instance->created_on = $result_set->created_on;
        return $instance;
    }

    public $amount;
    public $created_on;
}