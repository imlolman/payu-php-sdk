<form action="<?= $url ?>" id="payment_form_submit" method="post">
        <input type="hidden" id="surl" name="surl" value="<?= $success_url ?>" />
        <input type="hidden" id="furl" name="furl" value="<?= $failure_url ?>" />
        <input type="hidden" id="key" name="key" value="<?= $key ?>" />
        <input type="hidden" id="txnid" name="txnid" value="<?= $params['txnid'] ?>" />
        <input type="hidden" id="amount" name="amount" value="<?= $params['amount'] ?>" />
        <input type="hidden" id="productinfo" name="productinfo" value="<?= $params['productinfo'] ?>" />
        <input type="hidden" id="firstname" name="firstname" value="<?= $params['firstname'] ?>" />
        <input type="hidden" id="lastname" name="lastname" value="<?= $params['lastname'] ?>" />
        <input type="hidden" id="zipcode" name="zipcode" value="<?= $params['zipcode'] ?>" />
        <input type="hidden" id="email" name="email" value="<?= $params['email'] ?>" />
        <input type="hidden" id="phone" name="phone" value="<?= $params['phone'] ?>" />
        <input type="hidden" id="address1" name="address1" value="<?= $params['address1'] ?>" />
        <input type="hidden" id="city" name="city" value="<?= $params['city'] ?>" />
        <input type="hidden" id="state" name="state" value="<?= $params['state'] ?>" />
        <input type="hidden" id="country" name="country" value="<?= $params['country'] ?>" />
        <?php
        if (!empty($params['api_version'])) {
                ?>
                <input type="hidden" id="api_version" name="api_version" value="<?= $params['api_version'] ?>" />
                <?php
        }
        ?>
        <?php
        if (!empty($params['udf1'])) {
                ?>
                <input type="hidden" id="udf1" name="udf1" value="<?= $params['udf1'] ?>" />
                <?php
        } else {
                $params['udf1'] = "";
        }
        ?>
        <?php
        if (!empty($params['udf2'])) {
                ?>
                <input type="hidden" id="udf2" name="udf2" value="<?= $params['udf2'] ?>" />
                <?php
        } else {
                $params['udf2'] = "";
        }
        ?>
        <?php
        if (!empty($params['udf3'])) {
                ?>
                <input type="hidden" id="udf3" name="udf3" value="<?= $params['udf3'] ?>" />
                <?php
        } else {
                $params['udf3'] = "";
        }
        ?><?php
        if (!empty($params['udf4'])) {
                ?>
                <input type="hidden" id="udf4" name="udf4" value="<?= $params['udf4'] ?>" />
                <?php
        } else {
                $params['udf4'] = "";
        }
        ?>
        <?php
        if (!empty($params['udf5'])) {
                ?>
                <input type="hidden" id="udf5" name="udf5" value="<?= $params['udf5'] ?>" />
                <?php
        } else {
                $params['udf5'] = "";
        }
        ?>
        <input type="hidden" id="hash" name="hash" value="<?= $hash ?>" />
</form>
<script type="text/javascript">
        document.getElementById("payment_form_submit").submit();
</script>