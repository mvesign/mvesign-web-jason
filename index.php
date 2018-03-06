<?php
error_reporting(E_ALL);
ini_set('display_errors', 'on');
setlocale(LC_ALL, 'nl_NL');

require_once("Settings.php");
require_once("models/Transaction.php");
require_once("services/DataService.php");
require_once("controlers/TransactionsControler.php");

$transaction_controller = new TransactionsController();
$transactions_display = "";
$new_transaction = null;
$error_message = null;
$total_amount = 0;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['input-submit'])) {
        if ($_POST['input-password'] == Settings::PASSWORD) {
            $new_transaction = $transaction_controller->convert($_POST['input-amount'], $_POST['input-date'], $error_message);
            if ($new_transaction != null) {
                $transaction_controller->insert($new_transaction);
            }
        }
        else {
            $error_message = "Please insert the correct password.";
        }
    }
}

if ($new_transaction == null) {
    $new_transaction = new Transaction();
}

$transactions = $transaction_controller->retrieve();
if (sizeof($transactions) > 0) {
    foreach ($transactions as $transaction) {
        $transactions_display .= "<tr><td>".$transaction->created_on."</td><td class='text-right'>&euro; ".number_format($transaction->amount, 2, ",", ".")."</td></tr>";

        $total_amount += $transaction->amount;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta http-equiv="content-type" content="text/html;charset=UTF-8">
    <title>Jason Centjes.</title>
    <meta name="author" content="MJoy">
    <meta name="description" content="Bereken eenvoudig en snel de BTW over een bedrag.">
    <meta name="robots" content="NOODP">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" href="http://www.mvesign.com/content/images/favicon.ico" type="image/x-icon">
    <link rel="canonical" href="http://btw.mvesign.com">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link rel="stylesheet" href="view.min.css">
</head>
<body>
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="row">
                    <form method="post">
                        <table class="table">
                            <tr>
                                <td class="col-md-3 form-group">
                                    <input class="form-control" type="date" id="input-date" name="input-date" placeholder="Datum" value="<?php echo $new_transaction->created_on; ?>"/>
                                </td>
                                <td class="col-md-3 form-group">
                                    <input class="form-control" type="number" id="input-amount" name="input-amount" placeholder="Bedrag" step="0.01" value="<?php echo $new_transaction->amount; ?>" autofocus/>
                                </td>
                                <td class="col-md-3 form-group">
                                    <input class="form-control" type="password" id="input-password" name="input-password"/>
                                </td>
                                <td class="col-md-3 form-group">
                                    <input class="form-control" type="submit" id="input-submit" name="input-submit" value="Opslaan"/>
                                </td>
                            </tr>
                        </table>
                    </form>
                </div>
                <?php
                if (strlen($error_message) > 0) {
                ?>
                <div class="row">
                    <div class="alert alert-danger">
                        <?php echo $error_message; ?>
                    </div>
                </div>
                <?php
                }
                ?>
                <div class="row">
                    <table class="overview table table-striped">
                        <thead>
                            <tr>
                                <th>TOTAAL</th>
                                <th class="text-right">&euro; <?php echo number_format($total_amount, 2, ",", "."); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php echo $transactions_display; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</body>
</html>