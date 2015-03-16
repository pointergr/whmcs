<?php

include_once("../../../dbconnect.php");
include_once("../../../includes/functions.php" );
include_once("../../../includes/registrarfunctions.php" );
include_once( dirname(__FILE__) . "/pointer.php" );
$cronreport = "Pointer Domain Sync Report<br>\n---------------------------------------------------<br>\n";
$params = getRegistrarConfigOptions("pointer");
$username = $params['Username'];
$password = $params['Password'];
$testmode = $params["TestMode"];
date_default_timezone_set('UTC');

$url = false;
if ($testmode == 'on' || $testmode == 1) {
    $url = "http://devpointer.ngrok.com/admin/reseller";
} else {
    $url = "http://admin.pointer.gr/reseller";
}

$resp = call_user_func_array('login', array($username, $password, $url));

if ($resp['code'] != '200') {
    $cronreport .= "Error " . $resp['code'] . " " . $resp['message'] . "<br>\n";
} else {
    $queryresult = mysql_query("SELECT * FROM `pointer_domain` WHERE `queued_on` < " . time());
    $key = $resp['key'];

    while ($data = mysql_fetch_array($queryresult)) {
        $domainname = $data['domain'];
        $args = array();
        $args['domain'] = $domainname;
        $args["Username"] = $username;
        $args["Password"] = $password;
        $args['action'] = $data['action'];
        $args['key'] = $key;
        $args['url'] = $url;
        $tries = $data['tries'];

        $resp = call_user_func_array('getExpireDate', array($args));

        if ($resp['code'] != '200' || !isset($resp['expire_date'])) {
            $tries++;
            $cronreport .= "Error for {$domainname}: " . $resp['code'] . " " . $resp['message'] . "<br>\n";
            if ($tries < 4) {
                $sql_string = "UPDATE `pointer_domain` SET `queued_on` = " . (time() + 86400) . ", `tries`={$tries} WHERE `domain` = '{$domainname}'";
            } else {
                $sql_string = " DELETE FROM `pointer_domain` WHERE `domain` = '{$domainname}'";
            }
            mysql_query($sql_string);
        } else {
            $date = date('Y-m-d', $resp['expire_date']);
            $queryresult = mysql_query("UPDATE `tbldomains` SET `expirydate` = '{$date}' WHERE `domain` = '{$domainname}'");

            if (!$queryresult) {
                $cronreport .= "Error " . mysql_error() . "<br>\n";
            } else {
                $cronreport .= "Updated {$domainname} expiry to " . $date . "<br>\n";
                $sql_string = " DELETE FROM `pointer_domain` WHERE `domain` = '{$domainname}'";
                mysql_query($sql_string);
            }
        }
    }
}
sendAdminNotification("system", "WHMCS Pointer Domain Syncronisation Report", $cronreport);
$resp = call_user_func_array('logout', array($username, $password, $url, $key));
?>