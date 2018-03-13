<?php
class TransactionsController {
    private $context;

    public function __construct() {
        $this->context = new DataService(
            Settings::CONTEXT_NAME, Settings::CONTEXT_USERNAME, Settings::CONTEXT_PASSWORD, Settings::CONTEXT_HOST
        );
    }

    public function convert($amount, $created_on, &$error_reason) {
        if (!$this->is_valid_date($created_on)) {
            $error_reason = "Please insert a valid date in the format 'yyyy-mm-dd'.";
            return null;
        }
    
        if (!preg_match('/^[0-9]+(\.[0-9]+)?$/', $amount)) {
            $error_reason = "Please insert a valid numeric amount in the format 'xx0.xx'.";
            return null;
        }
    
        if ($amount == 0) {
            $error_reason = "Please insert a positive or negative numeric amount.";
            return null;
        }
    
        return Transaction::from_form($amount, $created_on);
    }

    public function insert($transaction) {
        $this->context->perform_query(
            "INSERT INTO transactions (
                amount, created_on
            ) VALUES (
                '".$this->context->escape($transaction->amount)."', '".$this->context->escape($transaction->created_on)."'
            )"
        );
    }

    public function retrieve() {
        $articles = $this->context->retrieve_rows(
            $this->context->perform_query(
                "SELECT amount, DATE_FORMAT(created_on, '%d %M, %Y') AS created_on
                FROM (
                    SELECT amount, created_on
                    FROM transactions
                    ORDER BY created_on DESC
                ) AS temp_transactions"
            )
        );

        $result = array();

        for($count = 0; $count < count($articles); $count++)
        {
            $result[$count] = Transaction::from_result_set($articles[$count]);
        }

        return $result;
    }

    private function is_valid_date($date) {
        if (strtotime($date) === false) {
            return false;
        } 
    
        list($year, $month, $day) = explode('-', $date); 
        return checkdate($month, $day, $year);
    }
}