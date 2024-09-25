<?php

namespace Imlolman\PayuPhpSdk;

class PayU
{
    public $params;
    public $url, $api_url;
    public $env_prod, $key, $salt, $txnid, $amount, $payuid;

    CONST VERIFY_PAYMENT_API = 'verify_payment';
    CONST VERIFY_PAYMENT_BY_PAYU_ID_API = 'check_payment';
    CONST GET_TRANSACTION_DETAILS_API = 'get_Transaction_Details';
    CONST GET_TRANSACTION_INFO_API = 'get_transaction_info';
    CONST GET_CARD_BIN_API = 'check_isDomestic';
    CONST GET_BIN_INFO_API = 'getBinInfo';   
    CONST CANCEL_REFUND_API = 'cancel_refund_transaction';
    CONST CHECK_ACTION_STATUS = 'check_action_status';
    CONST GET_ALL_TRANSACTION_ID_REFUND_DETAILS_API = 'getAllRefundsFromTxnIds';
    CONST GET_NETBANKING_STATUS_API = 'getNetbankingStatus';
    CONST GET_ISSUING_BANK_STATUS_API = 'getIssuingBankStatus';
    CONST GET_ISSUING_BANK_DOWN_BIN_API = 'gettingIssuingBankDownBins';
    CONST VALIDATE_UPI_HANLE_API = 'validateVPA';
    CONST CHECK_ELIGIBLE_BIN_FOR_EMI_API = 'eligibleBinsForEMI';
    CONST GET_EMI_AMOUNT_ACCORDING_TO_INTEREST_API = 'getEmiAmountAccordingToInterest';
    CONST CREATE_INVOICE_API = 'create_invoice';
    CONST EXPIRE_INVOICE_API = 'expire_invoice';
    CONST GET_SETTLEMENT_DETAILS_API = 'get_settlement_details';
    CONST GET_CHECKOUT_DETAILS_API = 'get_checkout_details';
    CONST DEFAULT_SUCCESS_URL = 'https://test.payu.in/admin/test_response';
    CONST DEFAULT_FAILURE_URL = 'https://test.payu.in/admin/test_response';


    public function __construct() {

    }
    
    public function initGateway() {
        if ($this->env_prod) {
            $this->url = 'https://secure.payu.in/_payment';
            
        } else {
            $this->url = 'https://test.payu.in/_payment';
            
        }
    }

    /**
     * Displays and submits a payment form with hidden fields based on provided parameters.
     *
     * @param array $params Array containing payment details, including:
     *     - string 'txnid': Transaction ID.
     *     - string 'amount': Payment amount.
     *     - string 'productinfo': Product information.
     *     - string 'firstname': Customer's first name.
     *     - string 'lastname': Customer's last name.
     *     - string 'zipcode': Customer's zip code.
     *     - string 'email': Customer's email address.
     *     - string 'phone': Customer's phone number.
     *     - string 'address1': Customer's address.
     *     - string 'city': Customer's city.
     *     - string 'state': Customer's state.
     *     - string 'country': Customer's country.
     *     - string 'api_version' (optional): API version for the transaction.
     *     - string 'udf1' (optional): User-defined field 1.
     *     - string 'udf2' (optional): User-defined field 2.
     *     - string 'udf3' (optional): User-defined field 3.
     *     - string 'udf4' (optional): User-defined field 4.
     *     - string 'udf5' (optional): User-defined field 5.
     *     - string 'success_url' (optional): URL to redirect to after successful payment.
     *     - string 'failure_url' (optional): URL to redirect to after failed payment.
     * @return null This function renders the form and submits it via JavaScript.
     */
    public function getPaymentForm($params) {
        $url = $this->url;
        $key = $this->key;
        $hash = $this->getHashKey($params);
        $success_url = isset($params['success_url']) ? $params['success_url'] : self::DEFAULT_SUCCESS_URL;
        $failure_url = isset($params['failure_url']) ? $params['failure_url'] : self::DEFAULT_FAILURE_URL;

        ob_start();

        include __DIR__ .'/../views/form.php';

        $form = ob_get_clean();
        return $form;
    }

    public function showPaymentForm($params) {
        $form = $this->getPaymentForm($params);
        echo $form;
    }

    private function getHashKey($params) {
        return hash('sha512', $this->key . '|' . $params['txnid'] . '|' . $params['amount'] . '|' . $params['productinfo'] . '|' . $params['firstname'] . '|' . $params['email'] . '|' . $params['udf1'] . '|' . $params['udf2'] . '|' . $params['udf3'] . '|' . $params['udf4'] . '|' . $params['udf5'] . '||||||' . $this->salt);
    }

    public function verifyHash($params) {
        $key = $params['key'];
        $txnid = $params['txnid'];
        $amount = $params['amount'];
        $productInfo = $params['productinfo'];
        $firstname = $params['firstname'];
        $email = $params['email'];
        $udf1 = $params['udf1'];
        $udf2 = $params['udf2'];
        $udf3 = $params['udf3'];
        $udf4 = $params['udf4'];
        $udf5 = $params['udf5'];
        $status = $params['status'];
        $resphash = $params['hash'];
        $keyString = $key . '|' . $txnid . '|' . $amount . '|' . $productInfo . '|' . $firstname . '|' . $email . '|' . $udf1 . '|' . $udf2 . '|' . $udf3 . '|' . $udf4 . '|' . $udf5 . '|||||';
        $keyArray = explode("|", $keyString);
        $reverseKeyArray = array_reverse($keyArray);
        $reverseKeyString = implode("|", $reverseKeyArray);
        $CalcHashString = strtolower(hash('sha512', $this->salt . '|' . $status . '|' . $reverseKeyString)); //hash without additionalcharges
        //check for presence of additionalcharges parameter in response.
        $additionalCharges = "";

        If (isset($params["additionalCharges"])) {
            $additionalCharges = $params["additionalCharges"];
            //hash with additionalcharges
            $CalcHashString = strtolower(hash('sha512', $additionalCharges . '|' . $this->salt . '|' . $status . '|' . $reverseKeyString));
        }
        
        return ($resphash == $CalcHashString) ? true : false;
    }

    public function verifyPayment($params) {
        if(!empty($params['txnid'])){
            $transaction = $this->getTransactionByTxnId($params['txnid']);
        }
        else{
            $transaction = $this->getTransactionByPayuId($params['payuid']);
        }
       // if ($transaction && $transaction['status'] == 'success') {
       //     return true;
       // }
        return $transaction;
    }

    public function getTransactionByTxnId($txnid) {
        $this->params['data'] = ['var1' => $txnid, 'command' => self::VERIFY_PAYMENT_API];
        $response = $this->execute();
        if ($response['status']) {
            $transactions = $response['transaction_details'];
            $transaction = $transactions[$txnid];
            return $transaction;
        }
        return false;
    }

    public function getTransactionByPayuId($payuid) {
        $this->params['data'] = ['var1' => $payuid, 'command' => self::VERIFY_PAYMENT_BY_PAYU_ID_API];
        $response = $this->execute();
        if ($response['status']) {
            $transaction = $response['transaction_details'];
            return $transaction;
        }
        return false;
    }

    public function getTransaction($params) {
        $command = ($params['type'] == 'time') ? self::GET_TRANSACTION_INFO_API : self::GET_TRANSACTION_DETAILS_API;
        $this->params['data'] = ['var1' => $params['from'], 'var2' => $params['to'], 'command' => $command];
        return $this->execute();
    }

    public function getCardBin($params) {
        $this->params['data'] = ['var1' => $params['cardnum'], 'command' => self::GET_CARD_BIN_API];
        return $this->execute();
    }

    public function getBinDetails($params) {
        $this->params['data'] = ['var1' => $params['type'], 'var2' => $params['card_info'],'var3' => $params['index'], 'var4' => $params['offset'], 'var5' => $params['zero_redirection_si_check'], 'command' => self::GET_BIN_INFO_API];   
        return $this->execute();
    }

    public function cancelRefundTransaction($params) {
        $this->params['data'] = ['var1' => $params['payuid'], 'var2' => $params['txnid'], 'var3' => $params['amount'], 'command' => self::CANCEL_REFUND_API];
        return $this->execute();
    }

    public function checkRefundStatus($params) {
        $this->params['data'] = ['var1' => $params['request_id'], 'command' => self::CHECK_ACTION_STATUS];
        return $this->execute();
    }

    public function checkRefundStatusByPayuId($params) {
        $this->params['data'] = ['var1' => $params['payuid'], 'var2' => 'payuid', 'command' => self::CHECK_ACTION_STATUS];
        return $this->execute();
    }

    public function checkAllRefundOfTransactionId($params) {
        $this->params['data'] = ['var1' => $params['txnid'], 'command' => self::GET_ALL_TRANSACTION_ID_REFUND_DETAILS_API];
        return $this->execute();
    }

    public function getNetbankingStatus($params) {
        $this->params['data'] = ['var1' => $params['netbanking_code'], 'command' => self::GET_NETBANKING_STATUS_API];
        return $this->execute();
    }

    public function getIssuingBankStatus($params) {
        $this->params['data'] = ['var1' => $params['cardnum'], 'command' => self::GET_ISSUING_BANK_STATUS_API];
        return $this->execute();
    }

    public function validateUpi($params) {
        $this->params['data'] = ['var1' => $params['vpa'], 'var2' => $params['auto_pay_vpa'], 'command' => self::VALIDATE_UPI_HANLE_API];
        return $this->execute();
    }

    public function checkEmiEligibleBins($params) {
        $this->params['data'] = ['var1' => $params['payuid'], 'var2' => $params['txnid'], 'var3' => $params['amount'], 'command' => self::VALIDATE_UPI_HANLE_API];
        return $this->execute();
    }

    public function createPaymentInvoice($params) {
        $this->params['data'] = ['var1' => $params['details'], 'command' => self::CREATE_INVOICE_API];
        return $this->execute();
    }

    public function expirePaymentInvoice($params) {
        $this->params['data'] = ['var1' => $params['txnid'], 'command' => self::EXPIRE_INVOICE_API];
        return $this->execute();
    }

    public function checkEligibleEMIBins($params) {
        $this->params['data'] = ['var1' => $params['bin'], 'var2' => $params['card_num'], 'var3' => $params['bank_name'], 'command' => self::CHECK_ELIGIBLE_BIN_FOR_EMI_API];
        return $this->execute();
    }

    public function getEmiAmount($params) {
        $this->params['data'] = ['var1' => $params['amount'], 'command' => self::GET_EMI_AMOUNT_ACCORDING_TO_INTEREST_API];
        return $this->execute();
    }

    public function getSettlementDetails($params) {
        $this->params['data'] = ['var1' => $params['data'], 'command' => self::GET_SETTLEMENT_DETAILS_API];
        return $this->execute();
    }

    public function getCheckoutDetails($params) {
        $this->params['data'] = ['var1' => $params['data'], 'command' => self::GET_CHECKOUT_DETAILS_API];
        return $this->execute();
    }

    private function createFormPostHash($params) {
        return hash('sha512', $params['key']. '|' . $params['txnid'] . '|' . $params['amount'] . '|' . $params['productinfo'] . '|' . $params['firstname'] . '|' . $params['email'] . '|||||||||||' . $this->salt);
    }
    
    public function execute() {
        $this->api_url = $this->env_prod ? 'https://info.payu.in/merchant/postservice.php?form=2' : 'https://test.payu.in/merchant/postservice.php?form=2';
        $hash_str = $this->key . '|' . $this->params['data']['command'] . '|' . $this->params['data']['var1'] . '|' . $this->salt;
        $this->params['data']['key'] = $this->key;
        $this->params['data']['hash'] = strtolower(hash('sha512', $hash_str));
        $response = $this->cUrl();
        return $response;
    }

    private function cUrl() {
        
        $data = $this->params['data'] ? http_build_query($this->params['data']) : NULL;
        $url = $this->api_url;
        try {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_SSLVERSION, 6); //TLS 1.2 mandatory
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

            if ($this->params['data'])
                curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

            $response = curl_exec($ch);
            curl_close($ch);
            return $response ? json_decode($response, true) : NULL;
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }
}
?>
