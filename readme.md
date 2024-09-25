# PayU SDK for PHP
The PayU SDK for PHP enables you to easily work with Payment APIs of PayU by easily integrating this SDK within your base system.
With our SDK, you do not need to worry about low level details for doing API integration and with few lines of code and a function calling, it can be done within few minutes.

## Features Supported
Following features are supported in the Payment link PHP SDK:
 - Create Payment Link
 - Verify transactions, check the transaction status, transaction details, or discount rate for a transaction
 - Initiated refunds, cancel refund, check refund status.
 - Retrieve settlement details which the bank has to settle you.
 - Get information of the payment options, offers, recommendations, and downtime details.
 - Check the customer’s eligibility for EMI and get the EMI amount according to interest 
 - Pay by link
To get started with PayU, visit our [Developer Guide](https://devguide.payu.in/)

# Table of Contents
 1. [Installation](#installation)
 2. [Getting Started](#getting-started)
 3. [Documentation for various Methods](#documentation-for-various-methods)

## Installation

### Download

Download the latest web-sdk-php.zip file from the releases section on GitHub. The web-sdk-php.zip is pre-compiled to include all dependencies.


## Getting Started

Please follow the [installation](#installation) instruction and execute the following PHP code for creating the instance of PayU Object:

Add the following code in your composer.json file so that you can use the SDK in your project.
```json
"repositories": [
        {
            "type": "vcs",
            "url": "https://github.com/imlolman/payu-php-sdk.git"
        }
]
```

```bash
composer require imlolman/payu-php-sdk:dev-main
```


```PHP
$payu_obj = new Imlolman\PayuPhpSdk\PayU();
```
```Set the credentials data and URL here
    $payu_obj->env_prod = 0;  //  1 for Live Environment/ 0 for SandBox Environment
    $payu_obj->key = '<key>';
    $payu_obj->salt = '<salt>';

    $res = $payu_obj->initGateway();
```
                       

## Documentation for test setup

```PHP
// config.php
define('MERCHANT_KEY', 'merchant_key');
define('PAYU_SALT', 'payu_salt');
```


```PHP
<?php
// index.phps
require_once 'vendor/autoload.php';
require_once('config.php');

$payu_obj = new Imlolman\PayuPhpSdk\PayU();

$payu_obj->env_prod = 0;  //  1 for Live Environment/ 0 for SandBox Environment
$payu_obj->key = MERCHANT_KEY;
$payu_obj->salt = PAYU_SALT;

$res = $payu_obj->initGateway();

// Generate random data for the payment form
$random_txnid = 'TXN' . uniqid();  // Random unique transaction ID
$random_amount = 5; // Random amount between 100 and 10000
$random_firstname = 'John' . rand(1, 100);
$random_lastname = 'Doe' . rand(1, 100);
$random_zipcode = str_pad(rand(10000, 99999), 5, '0', STR_PAD_LEFT);  // Random 5-digit zipcode
$random_email = 'user' . rand(1, 100) . '@example.com';
$random_phone = str_pad(rand(6000000000, 9999999999), 10, '0', STR_PAD_LEFT); // Random 10-digit phone number
$random_address1 = 'Address ' . rand(1, 100);
$random_city = 'City' . rand(1, 50);
$random_state = 'State' . rand(1, 50);
$random_country = 'Country' . rand(1, 50);
$random_productinfo = 'Product ' . rand(1, 100);

// Call the function with generated data
$form = $payu_obj->getPaymentForm([
    'txnid' => $random_txnid,
    'amount' => $random_amount,
    'productinfo' => $random_productinfo,
    'firstname' => $random_firstname,
    'lastname' => $random_lastname,
    'zipcode' => $random_zipcode,
    'email' => $random_email,
    'phone' => $random_phone,
    'address1' => $random_address1,
    'city' => $random_city,
    'state' => $random_state,
    'country' => $random_country,
    'udf1' => '',
    'udf2' => '',
    'udf3' => '',
    'udf4' => '',
    'udf5' => '',
    'success_url' => 'https://test.payu.in/admin/test_response',
    'failure_url' => 'https://test.payu.in/admin/test_response',
    // 'enforce_paymethod' => 'upi|card|...', // Optional [https://docs.payu.in/docs/enforce-pay-method-or-remove-category#enforce-payment-customization](Read Methods Here)
]);

echo $form;

?>
```


```PHP
// Webhook.php file to verify webhooks
<?php

// index.phps
require_once 'vendor/autoload.php';
require_once('config.php');

$payu_obj = new Imlolman\PayuPhpSdk\PayU();

$payu_obj->env_prod = 0;  //  1 for Live Environment/ 0 for SandBox Environment
$payu_obj->key = MERCHANT_KEY;
$payu_obj->salt = PAYU_SALT;

$res = $payu_obj->initGateway();

if($payu_obj->verifyHash($_REQUEST)){
    $transaction = $payu_obj->verifyPayment($_REQUEST);

    $transactionInJson = json_encode($transaction);
    // save in request.log
    file_put_contents('request_success.json', $transactionInJson);
}else{
    $transactionInJson = json_encode($transaction);
    // save in request.log
    file_put_contents('request_failed.json', $transactionInJson);
}
```


## Documentation for various Methods 


## paymentInitiate

This method can be used to submit HTML form code with the required parameters.

```PHP
    public function get($params) {
        ?>
        <form action="<?= $this->url; ?>" id="payment_form_submit" method="post">
            <input type="hidden" id="surl" name="surl" value="<?= self::SUCCESS_URL; ?>" />
            <input type="hidden" id="furl" name="furl" value="<?= self::FAILURE_URL; ?>" />
            <input type="hidden" id="key" name="key" value="<?= $this->key; ?>" />
            <input type="hidden" id="txnid" name="txnid" value="<?= $params['txnid'] ?>" />
            <input type="hidden" id="amount" name="amount" value="<?= $params['amount']; ?>" />
            <input type="hidden" id="productinfo" name="productinfo" value="<?= $params['productinfo']; ?>" />
            <input type="hidden" id="firstname" name="firstname" value="<?= $params['firstname']; ?>" />
            <input type="hidden" id="lastname" name="lastname" value="<?= $params['lastname']; ?>" />
            <input type="hidden" id="zipcode" name="zipcode" value="<?= $params['zipcode']; ?>" />
            <input type="hidden" id="email" name="email" value="<?= $params['email']; ?>" />
            <input type="hidden" id="phone" name="phone" value="<?= $params['phone']; ?>" />
            <input type="hidden" id="address1" name="address1" value="<?= $params['address1']; ?>" />
            <input type="hidden" id="city" name="city" value="<?= $params['city']; ?>" />
            <input type="hidden" id="state" name="state" value="<?= $params['state']; ?>" />
            <input type="hidden" id="country" name="country" value="<?= $params['country']; ?>" />
            <input type="hidden" id="hash" name="hash" value="<?= $this->getHashKey($params); ?>" />
        </form>
        <script type="text/javascript">
            document.getElementById("payment_form_submit").submit();
        </script>
        <?php
        return null;
    }

    private function getHashKey($params) {
        return hash('sha512', $this->key . '|' . $params['txnid'] . '|' . $params['amount'] . '|' . $params['productinfo'] . '|' . $params['firstname'] . '|' . $params['email'] . '|' . $params['udf1'] . '|' . $params['udf2'] . '|' . $params['udf3'] . '|' . $params['udf4'] . '|' . $params['udf5'] . '||||||' . $this->salt);
    }
```

## verifyPayment

This method can be used to fetch the status/details of a transaction using txnid or payuid.

```PHP
    public function verifyPayment($params) {
       if(!empty($params['txnid'])){
            $transaction = $this->getTransactionByTxnId($params['txnid']);
        }
        else{
            $transaction = $this->getTransactionByPayuId($params['payuid']);
        }
        return $transaction;
    }
```

## getTransactionDetails

This method This method can be used to fetch the details of the transactions within a date and time range.

``` PHP
  public function getTransaction($params) {
        $command = ($params['type'] == 'time') ? self::GET_TRANSACTION_INFO_API : self::GET_TRANSACTION_DETAILS_API;
        $this->params['data'] = ['var1' => $params['from'], 'var2' => $params['to'], 'command' => $command];
        return $this->execute();
    }
```

## validateVPA

This method can be used to validate VPA of a user.

```PHP
 public function validateUpi($params) {
        $this->params['data'] = ['var1' => $params['vpa'], 'var2' => $params['auto_pay_vpa'], 'command' => self::VALIDATE_UPI_HANLE_API];
        return $this->execute();
    }
```
## cancelRefundTransaction

This method can be used to initiate refunds for a specific transaction.

```PHP
 public function cancelRefundTransaction($params) {
        $this->params['data'] = ['var1' => $params['payuid'], 'var2' => $params['txnid'], 'var3' => $params['amount'], 'command' => self::CANCEL_REFUND_API];
        return $this->execute();
    }
```
## checkActionStatus

This method can be used to check the status of a refund.

```PHP
    public function checkRefundStatus($params) {
        $this->params['data'] = ['var1' => $params['request_id'], 'command' => self::CHECK_ACTION_STATUS];
        return $this->execute();
    }

    public function checkRefundStatusByPayuId($params) {
        $this->params['data'] = ['var1' => $params['payuid'], 'var2' => 'payuid', 'command' => self::CHECK_ACTION_STATUS];
        return $this->execute();
    }
```
## getNetbankingStatus

This method can be used to check status (down/up) of PGs.

```PHP
    public function getTransaction($params) {
        $this->params['data'] = ['var1' => $params['netbanking_code'], 'command' => self::GET_NETBANKING_STATUS_API];
        return $this->execute();
    }
```
## getIssuingBankStatus

This method can be used to check downtime through bin number.

```PHP
 public function getIssuingBankStatus($params) {
        $this->params['data'] = ['var1' => $params['cardnum'], 'command' => self::GET_ISSUING_BANK_STATUS_API];
        return $this->execute();
    }
```
## checkIsDomestic

This method can be used to check the bin information.

```PHP
 public function getCardBin($params) {
        $this->params['data'] = ['var1' => $params['cardnum'], 'command' => self::GET_CARD_BIN_API];
        return $this->execute();
    }
```

## createInvoice

This method can be used to create email and SMS invoice ( Pay by link ).

```PHP
 public function createPaymentInvoice($params) {
        $this->params['data'] = ['var1' => $params['details'], 'command' => self::CREATE_INVOICE_API];
        return $this->execute();
    }
```

## expireInvoice

This method can be used to expire email and SMS invoice ( Pay by link ).

```PHP
 public function expirePaymentInvoice($params) {
        $this->params['data'] = ['var1' => $params['txnid'], 'command' => self::EXPIRE_INVOICE_API];
        return $this->execute();
    }
```

## eligibleBinsForEMI

This method can be used to check the card eligibilty for EMI through the bin number.

```PHP
 public function checkEligibleEMIBins($params) {
        $this->params['data'] = ['var1' => $params['bin'], 'var2' => $params['card_num'], 'var3' => $params['bank_name'], 'command' => self::CHECK_ELIGIBLE_BIN_FOR_EMI_API];
        return $this->execute();
    }
```

## getEmiAmountAccordingToInterest

This method can be used to fetch EMI interest amount according to Banks and tenure.

```PHP
 public function getEmiAmount($params) {
        $this->params['data'] = ['var1' => $params['amount'], 'command' => self::GET_EMI_AMOUNT_ACCORDING_TO_INTEREST_API];
        return $this->execute();
    }
```

## getSettlementDetails

This method can be used to fetch settlement details for a particular date or UTR number.

```PHP
 public function getSettlementDetails($params) {
        $this->params['data'] = ['var1' => $params['data'], 'command' => self::GET_SETTLEMENT_DETAILS_API];
        return $this->execute();
    }

```

## getCheckoutDetails

This method can be used to fetch payment options, eligibility, recommendations, and downtime details.

```PHP
 public function getCheckoutDetails($params) {
        $this->params['data'] = ['var1' => $params['data'], 'command' => self::GET_CHECKOUT_DETAILS_API];
        return $this->execute();
    }
```