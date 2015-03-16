<?php

function pointer_getConfigArray() {
    $configarray = array(
        "Username" => array("Type" => "text", "Size" => "20", "Description" => "Enter your reseller username here"),
        "Password" => array("Type" => "password", "Size" => "20", "Description" => "Enter your reseller password here"),
        "TestMode" => array("FriendlyName" => "Test Mode", "Type" => "yesno", "Description" => "")
    );
    return $configarray;
}

function pointer_GetNameservers($params) {
    $username = $params["Username"];
    $password = $params["Password"];
    $tld = $params["tld"];
    $sld = $params["sld"];
    $url = getUrl($params);

    //LOGIN
    $resp = call_user_func_array('login', array($username, $password, $url));
    if ($resp['code'] != '200') {
        $values["error"] = $resp['code'] . ' ' . $resp['message'];
        call_user_func_array('logout', array($username, $password, $url, $key));
        return $values;
    }
    $key = $resp['key'];


    //GET DOMAIN INFO
    $chksum = md5($username . $password . 'getInfoDomain' . $key);
    $xml = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>
            <pointer>
                <domain>
                <get>
                	<domain>{$sld}.{$tld}</domain>
                </get>
                </domain>
                <username>{$username}</username>
                <chksum>{$chksum}</chksum>
            </pointer>";


    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_HTTPHEADER, Array("Content-Type:text/xml"));
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_HEADER, 0);
    curl_setopt($curl, CURLOPT_POST, 1);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $xml); // post the xml
    curl_setopt($curl, CURLOPT_TIMEOUT, 120); // set timeout in secon
    $result = curl_exec($curl);


    $resp = array();
    $resp_xml = new SimpleXMLElement($result);
    $resp['code'] = (string) $resp_xml->code;
    $resp['message'] = (string) $resp_xml->message;
    if ($resp['code'] != '200') {
        $values["error"] = $resp['code'] . ' ' . $resp['message'];
        call_user_func_array('logout', array($username, $password, $url, $key));
        return $values;
    }

    $values["ns1"] = (string) $resp_xml->domain->ns1;
    $values["ns2"] = (string) $resp_xml->domain->ns2;
    $values["ns3"] = (string) $resp_xml->domain->ns3;
    $values["ns4"] = (string) $resp_xml->domain->ns4;

    $resp = call_user_func_array('logout', array($username, $password, $url, $key));

    return $values;
}

function pointer_SaveNameservers($params) {
    $username = $params["Username"];
    $password = $params["Password"];
    $tld = $params["tld"];
    $sld = $params["sld"];
    $nameserver1 = $params["ns1"];
    $nameserver2 = $params["ns2"];
    $nameserver3 = $params["ns3"];
    $nameserver4 = $params["ns4"];
    $url = getUrl($params);

    //LOGIN
    $resp = call_user_func_array('login', array($username, $password, $url));
    if ($resp['code'] != '200') {
        $values["error"] = $resp['code'] . ' ' . $resp['message'];
        call_user_func_array('logout', array($username, $password, $url, $key));
        return $values;
    }
    $key = $resp['key'];

    $ns_string = '';
    for ($i = 0; $i < 5; $i++) {
        $tmp_ns = 'nameserver' . $i;
        if ($$tmp_ns) {
            $ns_string.="<ns{$i}>{$$tmp_ns}</ns{$i}>";
        }
    }


    $chksum = md5($username . $password . 'updateNsDomain' . $key);
    $xml = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>
            <pointer>
                <domain>
                <updatens>
                	<domain>{$sld}.{$tld}</domain>
                	{$ns_string}
                </updatens>
                </domain>
                <username>{$username}</username>
                <chksum>{$chksum}</chksum>
            </pointer>";

    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_HTTPHEADER, Array("Content-Type:text/xml"));
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_HEADER, 0);
    curl_setopt($curl, CURLOPT_POST, 1);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $xml); // post the xml
    curl_setopt($curl, CURLOPT_TIMEOUT, 120); // set timeout in secon


    $result = curl_exec($curl);

    $resp = array();
    $resp_xml = new SimpleXMLElement($result);
    $resp['code'] = (string) $resp_xml->code;
    $resp['message'] = (string) $resp_xml->message;

    if ($resp['code'] != '200') {
        $values["error"] = $resp['code'] . ' ' . $resp['message'];
        call_user_func_array('logout', array($username, $password, $url, $key));
        return $values;
    }

    $resp = call_user_func_array('logout', array($username, $password, $url, $key));
    return $values;
}

function pointer_GetDNS($params) {
    $username = $params["Username"];
    $password = $params["Password"];
    $tld = $params["tld"];
    $sld = $params["sld"];
    # Put your code here to get the current DNS settings - the result should be an array of hostname, record type, and address
    $hostrecords = array();
    $hostrecords[] = array("hostname" => "ns1", "type" => "A", "address" => "192.168.0.1",);
    $hostrecords[] = array("hostname" => "ns2", "type" => "A", "address" => "192.168.0.2",);
    return $hostrecords;
}

function pointer_SaveDNS($params) {
    $username = $params["Username"];
    $password = $params["Password"];
    $tld = $params["tld"];
    $sld = $params["sld"];
    # Loop through the submitted records
    foreach ($params["dnsrecords"] AS $key => $values) {
        $hostname = $values["hostname"];
        $type = $values["type"];
        $address = $values["address"];
        # Add your code to update the record here
    }
    # If error, return the error message in the value below
    $values["error"] = $Enom->Values["Err1"];
    return $values;
}

function pointer_RegisterDomain($params) {
    //include_once(ROOTDIR . "/dbconnect.php");
    $username = $params["Username"];
    $password = $params["Password"];
    $tld = $params["tld"];
    $sld = $params["sld"];
    $regperiod = $params["regperiod"];
    $nameserver1 = $params["ns1"];
    $nameserver2 = $params["ns2"];
    $nameserver3 = $params["ns3"];
    $nameserver4 = $params["ns4"];
    # Registrant Details
    $registrantFirstName = $params["firstname"];
    $registrantLastName = $params["lastname"];
    $registrantName = trim($registrantLastName . ' ' . $registrantFirstName);
    $registrantAddress1 = $params["address1"];
    $registrantAddress2 = $params["address2"];
    $registrantCity = $params["city"];
    $registrantStateProvince = $params["state"];
    $registrantPostalCode = $params["postcode"];
    $registrantCountry = $params["country"];
    $registrantEmailAddress = $params["email"];
    $registrantPhone = $params["fullphonenumber"];
    $url = getUrl($params);

    //LOGIN
    $resp = call_user_func_array('login', array($username, $password, $url));
    $key = $resp['key'];
    if ($resp['code'] != '200') {
        $values["error"] = $resp['code'] . ' ' . $resp['message'];
        call_user_func_array('logout', array($username, $password, $url, $key));
        return $values;
    }

    //ADD ADDONS IF NEEDED
    $addon = '';

    // .EU ADDITIONAL INFO
    if ($tld == 'eu') {
        $addon.= "<addon>";
        if (!isset($params['additionalfields']) || !isset($params['additionalfields']['euregistrantcountry'])) {
            $values["error"] = 'Please define the registrant country';
            call_user_func_array('logout', array($username, $password, $url, $key));
            return $values;
        }
        $addon.= "<item><code>EU-REG-COU</code><value>{$params['additionalfields']['euregistrantcountry']}</value></item>";
        $addon.= "</addon>";

        /**
         * FOR .EU DOMAINS WE NEED TO DEFINE THE COUNTRY OF THE REGISTRANT
         * THUS, WE OVERRIDE THIS VALUE FROM THE ADDITIONALFILEDS
         */
        $registrantCountry = $params['additionalfields']['euregistrantcountry'];
    }

    if ($tld == 'fr') {
        $addon.= "<addon>";
        if (!isset($params['additionalfields']) || !isset($params['additionalfields']['frbirthdate']) || !isset($params['additionalfields']['frtechorg'])) {
            $values["error"] = 'Please define the birth date';
            call_user_func_array('logout', array($username, $password, $url, $key));
            return $values;
        }
        $addon.= "<item><code>FR-DATE</code><value>{$params['additionalfields']['frbirthdate']}</value></item>";
        $addon.= "<item><code>FR-TECH-OR</code><value>{$params['additionalfields']['frtechorg']}</value></item>";
        $addon.= "</addon>";
    }

    // .CO.UK ADDITIONAL INFO
    if ($tld == 'co.uk') {
        $addon.= "<addon>";
        if (!isset($params['additionalfields']) || !isset($params['additionalfields']['ukcompanytype']) || !isset($params['additionalfields']['ukcompanyregistrationnumber']) || !isset($params['additionalfields']['ukregistranttradingname'])) {
            $values["error"] = 'Please define all required additional fields';
            call_user_func_array('logout', array($username, $password, $url, $key));
            return $values;
        }
        $addon.= "<item><code>UK-REGTYP</code><value>{$params['additionalfields']['ukcompanytype']}</value></item>";
        $addon.= "<item><code>UK-COMPN</code><value>{$params['additionalfields']['ukcompanyregistrationnumber']}</value></item>";
        $addon.= "<item><code>UK-TRAD</code><value>{$params['additionalfields']['ukregistranttradingname']}</value></item>";
        $addon.= "</addon>";
    }

    // .US ADDITIONAL INFO
    $nc = false;
    $ap = false;
    if ($tld == 'us') {
        $addon.= "<addon>";
        if (!isset($params['additionalfields']) || !isset($params['additionalfields']['usnexuscategory']) || !isset($params['additionalfields']['usregistrationpurpose'])) {
            $values["error"] = 'Please define all required fields';
            call_user_func_array('logout', array($username, $password, $url, $key));
            return $values;
        }
        $nc = $params['additionalfields']['usnexuscategory'];
        $ap = $params['additionalfields']['usregistrationpurpose'];
        $addon.= "<item><code>USNEXUS</code><value>{$nc}</value></item>";
        $addon.= "<item><code>USAPP</code><value>{$ap}</value></item>";
        $addon.= "</addon>";
    }

    // .ES ADDITIONAL INFO
    if ($tld == 'es') {
        $addon.= "<addon>";
        if (!isset($params['additionalfields']) || !isset($params['additionalfields']['esidtype']) || !isset($params['additionalfields']['esidnumber']) || !isset($params['additionalfields']['escontacttype'])) {
            $values["error"] = 'Please define all required additional fields';
            call_user_func_array('logout', array($username, $password, $url, $key));
            return $values;
        }
        $addon.= "<item><code>ESIDTYPE</code><value>{$params['additionalfields']['esidtype']}</value></item>";
        $addon.= "<item><code>ESIDNUMB</code><value>{$params['additionalfields']['esidnumber']}</value></item>";
        $addon.= "<item><code>ESCONTTYP</code><value>{$params['additionalfields']['escontacttype']}</value></item>";
        $addon.= "</addon>";
    }


    // .PRO ADDITIONAL INFO
    if ($tld == 'pro') {
        $addon.= "<addon>";
        if (!isset($params['additionalfields']) || !isset($params['additionalfields']['proprofession'])) {
            $values["error"] = 'Please define profession';
            call_user_func_array('logout', array($username, $password, $url, $key));
            return $values;
        }
        $addon.= "<item><code>PROFESSION</code><value>{$params['additionalfields']['proprofession']}</value></item>";
        $addon.= "</addon>";
    }

    // .IT ADDITIONAL INFO
    if ($tld == 'it') {
        $addon.= "<addon>";
        if (!isset($params['additionalfields']) || !isset($params['additionalfields']['itnationality']) || !isset($params['additionalfields']['itentrytype']) || !isset($params['additionalfields']['itregistrationcode'])) {
            $values["error"] = 'Please define all required additional fields';
            call_user_func_array('logout', array($username, $password, $url, $key));
            return $values;
        }
        $addon.= "<item><code>NATIONALIT</code><value>{$params['additionalfields']['itnationality']}</value></item>";
        $addon.= "<item><code>ENTRYTYP</code><value>{$params['additionalfields']['itentrytype']}</value></item>";
        $addon.= "<item><code>REGCODE</code><value>{$params['additionalfields']['itregistrationcode']}</value></item>";
        $addon.= "</addon>";
    }


    //ADDITIONAL FIELDS IF NEEDED
    $additional_fields = '';
    if (isset($params['additionalfields']) && count($params['additionalfields'])) {
        $additional_fields.= "<additional_fields>";
        foreach ($params['additionalfields'] as $index => $value) {
            $additional_fields.= "<field><index>{$index}</index><value>{$value}</value></field>";
        }
        $additional_fields.= "</additional_fields>";
    }

    //CREATE CONTACT
    $chksum = md5($username . $password . 'createContactDomain' . $key);
    $xml = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>
            <pointer>
                <contact-domain>
                    <create>
                        <domain>{$sld}</domain>
                        <tld>{$tld}</tld>
                        <name>{$registrantName}</name>
                        <street>{$registrantAddress1}</street>
                        <city>{$registrantCity}</city>
                        <sp>{$registrantStateProvince}</sp>
                        <pc>{$registrantPostalCode}</pc>
                        <country>{$registrantCountry}</country>
                        <phone>{$registrantPhone}</phone>
                        <email>{$registrantEmailAddress}</email>
                        {$additional_fields}
                    </create>
                </contact-domain>
                <username>{$username}</username>
                <chksum>{$chksum}</chksum>
            </pointer>";

    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_HTTPHEADER, Array("Content-Type:text/xml"));
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_HEADER, 0);
    curl_setopt($curl, CURLOPT_POST, 1);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $xml); // post the xml
    curl_setopt($curl, CURLOPT_TIMEOUT, 120); // set timeout in secon
    $result = curl_exec($curl);
    $resp_xml = new SimpleXMLElement($result);

    $resp = array();
    $resp['code'] = (string) $resp_xml->code;
    $resp['message'] = (string) $resp_xml->message;
    if ($resp['code'] != '200') {
        $values["error"] = $resp['code'] . ' ' . $resp['message'];
        call_user_func_array('logout', array($username, $password, $url, $key));
        return $values;
    }

    $contact_code = (string) $resp_xml->contact_domain->contact_code;

    //REGISTER DOMAIN
    $chksum = md5($username . $password . 'createDomain' . $key);

    // ADDITIONAL FIELDS
    $additional_fields = '';
    if ($tld == 'de') {
        if (isset($params['additionalfields']) && isset($params['additionalfields']['deproxyadmin']) && ($params['additionalfields']['deproxyadmin'] == 'on' || $params['additionalfields']['deproxyadmin'] == 1)) {
            /**
             * CLIENT HAS CHOSEN TO USE PROXY ADMIN
             * DO NOT GET ADDITIONAL FIELDS DATA
             * WE LEAVE IT EMPTY
             */
        } else {
            $additional_fields.= "<additional_fields>";
            $additional_fields.= "  <de_proxy_admin>";
            $additional_fields.= "      <name>{$params['additionalfields']['a_name']}</name>";
            $additional_fields.= "      <street>{$params['additionalfields']['a_street']}</street>";
            $additional_fields.= "      <city>{$params['additionalfields']['a_city']}</city>";
            $additional_fields.= "      <country>{$params['additionalfields']['a_country']}</country>";
            $additional_fields.= "      <phone>{$params['additionalfields']['a_phone']}</phone>";
            $additional_fields.= "      <email>{$params['additionalfields']['a_email']}</email>";
            $additional_fields.= "      <pc>{$params['additionalfields']['a_pc']}</pc>";
            $additional_fields.= "      <sp>{$params['additionalfields']['a_sp']}</sp>";
            $additional_fields.= "  </de_proxy_admin>";
            $additional_fields.= "</additional_fields>";
        }
    }

    // ID SHIELD
    $id_prot = '';
    if (isset($params['idprotection']) && $params['idprotection']) {
        $id_prot.="<id_shield>1</id_shield>";
    }

    $xml = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>
            <pointer>
                <domain>
                	<create>
                		<domain>{$sld}.{$tld}</domain>
                		<duration>{$regperiod}</duration>
                		<ns1>{$nameserver1}</ns1>
                		<ns2>{$nameserver2}</ns2>
                		{$id_prot}
                		{$addon}
                                {$additional_fields}
                		<description></description>
                		<registrant>{$contact_code}</registrant>
                	</create>
                </domain>
                <username>" . $username . "</username>
                <chksum>$chksum</chksum>
            </pointer>";

    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_HTTPHEADER, Array("Content-Type:text/xml"));
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_HEADER, 0);
    curl_setopt($curl, CURLOPT_POST, 1);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $xml); // post the xml
    curl_setopt($curl, CURLOPT_TIMEOUT, 120); // set timeout in secon
    $result = curl_exec($curl);
    $resp_xml = new SimpleXMLElement($result);

    $resp = array();
    $resp['code'] = (string) $resp_xml->code;
    $resp['message'] = (string) $resp_xml->message;
    if ($resp['code'] != '200') {
        $values["error"] = $resp['code'] . ' ' . $resp['message'];
        call_user_func_array('logout', array($username, $password, $url, $key));
        return $values;
    }

    $queryresult = mysql_query("INSERT INTO `pointer_domain` (`domain`, `action`, `queued_on`) VALUES ('{$sld}.{$tld}','create'," . (time() + 600) . ")");
    if (!$queryresult) {
        $values["error"] = mysql_error();
        call_user_func_array('logout', array($username, $password, $url, $key));
        return $values;
    }

    //LOGOUT
    $resp = call_user_func_array('logout', array($username, $password, $url, $key));
    return $values;
}

function pointer_TransferDomain($params) {
    //include_once(ROOTDIR . "/dbconnect.php");
    $username = $params["Username"];
    $password = $params["Password"];
    $tld = $params["tld"];
    $sld = $params["sld"];
    $transfersecret = $params["transfersecret"];
    $nameserver1 = $params["ns1"];
    $nameserver2 = $params["ns2"];
    $nameserver3 = $params["ns3"];
    $nameserver4 = $params["ns4"];
    $url = getUrl($params);

    //LOGIN
    $resp = call_user_func_array('login', array($username, $password, $url));
    if ($resp['code'] != '200') {
        $values["error"] = $resp['code'] . ' ' . $resp['message'];
        call_user_func_array('logout', array($username, $password, $url, $key));
        return $values;
    }

    $key = $resp['key'];

    $chksum = md5($username . $password . 'transferDomain' . $key);
    $xml = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>
            <pointer>
                <domain>
                <transfer>
                	<domain>{$sld}.{$tld}</domain>
                	<code>{$transfersecret}</code>
                </transfer>
                </domain>
                <username>{$username}</username>
                <chksum>{$chksum}</chksum>
            </pointer>";


    $curl = curl_init();

    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_HTTPHEADER, Array("Content-Type:text/xml"));
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_HEADER, 0);
    curl_setopt($curl, CURLOPT_POST, 1);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $xml); // post the xml
    curl_setopt($curl, CURLOPT_TIMEOUT, 120); // set timeout in secon


    $result = curl_exec($curl);
    $resp = array();
    $resp_xml = new SimpleXMLElement($result);
    $resp['code'] = (string) $resp_xml->code;
    $resp['message'] = (string) $resp_xml->message;

    if ($resp['code'] != '200') {
        $values["error"] = $resp['code'] . ' ' . $resp['message'];
        call_user_func_array('logout', array($username, $password, $url, $key));
        return $values;
    }

    $queryresult = mysql_query("INSERT INTO `pointer_domain` (`domain`) VALUES ('{$sld}.{$tld}')");
    if (!$queryresult) {
        $values["error"] = mysql_error();
        call_user_func_array('logout', array($username, $password, $url, $key));
        return $values;
    }

    //UPDATE NAMESERVERS
    $ns_string = '';
    for ($i = 0; $i < 5; $i++) {
        $tmp_ns = 'nameserver' . $i;
        if ($$tmp_ns) {
            $ns_string.="<ns{$i}>{$$tmp_ns}</ns{$i}>";
        }
    }


    $chksum = md5($username . $password . 'updateNsDomain' . $key);
    $xml = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>
            <pointer>
                <domain>
                <updatens>
                	<domain>{$sld}.{$tld}</domain>
                	{$ns_string}
                </updatens>
                </domain>
                <username>{$username}</username>
                <chksum>{$chksum}</chksum>
            </pointer>";

    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_HTTPHEADER, Array("Content-Type:text/xml"));
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_HEADER, 0);
    curl_setopt($curl, CURLOPT_POST, 1);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $xml); // post the xml
    curl_setopt($curl, CURLOPT_TIMEOUT, 120); // set timeout in secon

    $result = curl_exec($curl);

    $resp = array();
    $resp_xml = new SimpleXMLElement($result);
    $resp['code'] = (string) $resp_xml->code;
    $resp['message'] = (string) $resp_xml->message;

    if ($resp['code'] != '200') {
        $values["error"] = $resp['code'] . ' ' . $resp['message'];
        call_user_func_array('logout', array($username, $password, $url, $key));
        return $values;
    }

    //LOGOUT
    $resp = call_user_func_array('logout', array($username, $password, $url, $key));
    return $values;
}

function pointer_RenewDomain($params) {
    $username = $params["Username"];
    $password = $params["Password"];
    $tld = $params["tld"];
    $sld = $params["sld"];
    $regperiod = $params["regperiod"];
    $url = getUrl($params);

    //LOGIN
    $resp = call_user_func_array('login', array($username, $password, $url));
    if ($resp['code'] != '200') {
        $values["error"] = $resp['code'] . ' ' . $resp['message'];
        call_user_func_array('logout', array($username, $password, $url, $key));
        return $values;
    }
    $key = $resp['key'];

    $chksum = md5($username . $password . 'renewDomain' . $key);
    $xml = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>
            <pointer>
                <domain>
                <renew>
                	<domain>{$sld}.{$tld}</domain>
                	<duration>{$regperiod}</duration>
                </renew>
                </domain>
                <username>{$username}</username>
                <chksum>{$chksum}</chksum>
            </pointer>";

    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_HTTPHEADER, Array("Content-Type:text/xml"));
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_HEADER, 0);
    curl_setopt($curl, CURLOPT_POST, 1);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $xml); // post the xml
    curl_setopt($curl, CURLOPT_TIMEOUT, 120); // set timeout in secon


    $result = curl_exec($curl);

    $resp = array();
    $resp_xml = new SimpleXMLElement($result);
    $resp['code'] = (string) $resp_xml->code;
    $resp['message'] = (string) $resp_xml->message;

    if ($resp['code'] != '200') {
        $values["error"] = $resp['code'] . ' ' . $resp['message'];
        call_user_func_array('logout', array($username, $password, $url, $key));
        return $values;
    }

    //LOGOUT
    $resp = call_user_func_array('logout', array($username, $password, $url, $key));
    return $values;
}

function pointer_GetContactDetails($params) {
    $username = $params["Username"];
    $password = $params["Password"];
    $tld = $params["tld"];
    $sld = $params["sld"];
    $url = getUrl($params);

    //LOGIN
    $resp = call_user_func_array('login', array($username, $password, $url));
    if ($resp['code'] != '200') {
        $values["error"] = $resp['code'] . ' ' . $resp['message'];
        call_user_func_array('logout', array($username, $password, $url, $key));
        return $values;
    }
    $key = $resp['key'];

    //GET DOMAIN INFO
    $chksum = md5($username . $password . 'getInfoDomain' . $key);
    $xml = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>
            <pointer>
                <domain>
                <get>
                	<domain>{$sld}.{$tld}</domain>
                </get>
                </domain>
                <username>{$username}</username>
                <chksum>{$chksum}</chksum>
            </pointer>";


    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_HTTPHEADER, Array("Content-Type:text/xml"));
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_HEADER, 0);
    curl_setopt($curl, CURLOPT_POST, 1);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $xml); // post the xml
    curl_setopt($curl, CURLOPT_TIMEOUT, 120); // set timeout in secon

    $result = curl_exec($curl);
    $resp = array();
    $resp_xml = new SimpleXMLElement($result);
    $resp['code'] = (string) $resp_xml->code;
    $resp['message'] = (string) $resp_xml->message;

    if ($resp['code'] != '200') {
        $values["error"] = $resp['code'] . ' ' . $resp['message'];
        call_user_func_array('logout', array($username, $password, $url, $key));
        return $values;
    }

    $action = (string) $resp_xml->domain->action;

    $admin_code = (string) $resp_xml->domain->domain_admin;
    $tech_code = (string) $resp_xml->domain->domain_tech;
    $bill_code = (string) $resp_xml->domain->domain_billing;


    //ADMIN
    if (in_array($action, array('Pointer_Product_DomainCom', 'Pointer_Product_Enom', 'Pointer_Product_Domainbox'))) {
        $values["Admin"]["Full Name"] = (string) $resp_xml->domain->a_name;
        $values["Admin"]["Email"] = (string) $resp_xml->domain->a_email;
        $values["Admin"]["Phone Number"] = (string) $resp_xml->domain->a_phone;
        $values["Admin"]["Address"] = (string) $resp_xml->domain->a_address;
        $values["Admin"]["Postcode"] = (string) $resp_xml->domain->a_pc;
        $values["Admin"]["City"] = (string) $resp_xml->domain->a_city;
        $values["Admin"]["Region"] = (string) $resp_xml->domain->a_sp;
        $values["Admin"]["Country"] = (string) $resp_xml->domain->a_country;
    } else {
        if ($admin_code) {
            $chksum = md5($username . $password . 'getContactInfoDomain' . $key);
            $xml = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>
	            <pointer>
	                <contact-domain>
	                <get>
	                	<tld>{$tld}</tld>
	                	<code>{$admin_code}</code>
	                </get>
	                </contact-domain>
	                <username>{$username}</username>
	                <chksum>{$chksum}</chksum>
	            </pointer>";


            $curl = curl_init();
            curl_setopt($curl, CURLOPT_URL, $url);
            curl_setopt($curl, CURLOPT_HTTPHEADER, Array("Content-Type:text/xml"));
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_HEADER, 0);
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $xml); // post the xml
            curl_setopt($curl, CURLOPT_TIMEOUT, 120); // set timeout in secon
            $result = curl_exec($curl);

            $resp = array();
            $resp_xml = new SimpleXMLElement($result);
            $resp['code'] = (string) $resp_xml->code;
            $resp['message'] = (string) $resp_xml->message;

            if ($resp['code'] != '200') {
                $values["error"] = $resp['code'] . ' ' . $resp['message'];
                call_user_func_array('logout', array($username, $password, $url, $key));
                return $values;
            }

            $values["Admin"]["Full Name"] = (string) $resp_xml->contact_domain->contact_name;
            $values["Admin"]["Email"] = (string) $resp_xml->contact_domain->contact_email;
            $values["Admin"]["Phone Number"] = (string) $resp_xml->contact_domain->contact_voice;
            $values["Admin"]["Address"] = (string) $resp_xml->contact_domain->contact_street;
            $values["Admin"]["Postcode"] = (string) $resp_xml->contact_domain->contact_pc;
            $values["Admin"]["City"] = (string) $resp_xml->contact_domain->contact_city;
            $values["Admin"]["Region"] = (string) $resp_xml->contact_domain->contact_sp;
            $values["Admin"]["Country"] = (string) $resp_xml->contact_domain->contact_cc;
        } else {
            $values["Admin"]["Full Name"] = '';
            $values["Admin"]["Email"] = '';
            $values["Admin"]["Phone Number"] = '';
            $values["Admin"]["Address"] = '';
            $values["Admin"]["Postcode"] = '';
            $values["Admin"]["City"] = '';
            $values["Admin"]["Region"] = '';
            $values["Admin"]["Country"] = '';
        }
    }


    //TECH
    if (in_array($action, array('Pointer_Product_DomainCom', 'Pointer_Product_Enom', 'Pointer_Product_Domainbox'))) {
        $values["Tech"]["Full Name"] = (string) $resp_xml->domain->t_name;
        $values["Tech"]["Email"] = (string) $resp_xml->domain->t_email;
        $values["Tech"]["Phone Number"] = (string) $resp_xml->domain->t_phone;
        $values["Tech"]["Address"] = (string) $resp_xml->domain->t_address;
        $values["Tech"]["Postcode"] = (string) $resp_xml->domain->t_pc;
        $values["Tech"]["City"] = (string) $resp_xml->domain->t_city;
        $values["Tech"]["Region"] = (string) $resp_xml->domain->t_sp;
        $values["Tech"]["Country"] = (string) $resp_xml->domain->t_country;
    } else {
        if ($tech_code) {
            $chksum = md5($username . $password . 'getContactInfoDomain' . $key);
            $xml = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>
	            <pointer>
	                <contact-domain>
	                <get>
	                	<tld>{$tld}</tld>
	                	<code>{$tech_code}</code>
	                </get>
	                </contact-domain>
	                <username>{$username}</username>
	                <chksum>{$chksum}</chksum>
	            </pointer>";

            $curl = curl_init();
            curl_setopt($curl, CURLOPT_URL, $url);
            curl_setopt($curl, CURLOPT_HTTPHEADER, Array("Content-Type:text/xml"));
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_HEADER, 0);
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $xml); // post the xml
            curl_setopt($curl, CURLOPT_TIMEOUT, 120); // set timeout in secon
            $result = curl_exec($curl);

            $resp = array();
            $resp_xml = new SimpleXMLElement($result);
            $resp['code'] = (string) $resp_xml->code;
            $resp['message'] = (string) $resp_xml->message;

            if ($resp['code'] != '200') {
                $values["error"] = $resp['code'] . ' ' . $resp['message'];
                call_user_func_array('logout', array($username, $password, $url, $key));
                return $values;
            }


            $values["Tech"]["Full Name"] = (string) $resp_xml->contact_domain->contact_name;
            $values["Tech"]["Email"] = (string) $resp_xml->contact_domain->contact_email;
            $values["Tech"]["Phone Number"] = (string) $resp_xml->contact_domain->contact_voice;
            $values["Tech"]["Address"] = (string) $resp_xml->contact_domain->contact_street;
            $values["Tech"]["Postcode"] = (string) $resp_xml->contact_domain->contact_pc;
            $values["Tech"]["City"] = (string) $resp_xml->contact_domain->contact_city;
            $values["Tech"]["Region"] = (string) $resp_xml->contact_domain->contact_sp;
            $values["Tech"]["Country"] = (string) $resp_xml->contact_domain->contact_cc;
        } else {
            $values["Tech"]["Full Name"] = '';
            $values["Tech"]["Email"] = '';
            $values["Tech"]["Phone Number"] = '';
            $values["Tech"]["Address"] = '';
            $values["Tech"]["Postcode"] = '';
            $values["Tech"]["City"] = '';
            $values["Tech"]["Region"] = '';
            $values["Tech"]["Country"] = '';
        }
    }


    //BILLING
    if (in_array($action, array('Pointer_Product_DomainCom', 'Pointer_Product_Enom', 'Pointer_Product_Domainbox'))) {
        $values["Bill"]["Full Name"] = (string) $resp_xml->domain->b_name;
        $values["Bill"]["Email"] = (string) $resp_xml->domain->b_email;
        $values["Bill"]["Phone Number"] = (string) $resp_xml->domain->b_phone;
        $values["Bill"]["Address"] = (string) $resp_xml->domain->b_address;
        $values["Bill"]["Postcode"] = (string) $resp_xml->domain->b_pc;
        $values["Bill"]["City"] = (string) $resp_xml->domain->b_city;
        $values["Bill"]["Region"] = (string) $resp_xml->domain->b_sp;
        $values["Bill"]["Country"] = (string) $resp_xml->domain->b_country;
    } else {
        if ($bill_code) {
            $chksum = md5($username . $password . 'getContactInfoDomain' . $key);
            $xml = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>
	            <pointer>
	                <contact-domain>
	                <get>
	                	<tld>{$tld}</tld>
	                	<code>{$bill_code}</code>
	                </get>
	                </contact-domain>
	                <username>{$username}</username>
	                <chksum>{$chksum}</chksum>
	            </pointer>";

            $curl = curl_init();
            curl_setopt($curl, CURLOPT_URL, $url);
            curl_setopt($curl, CURLOPT_HTTPHEADER, Array("Content-Type:text/xml"));
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_HEADER, 0);
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $xml); // post the xml
            curl_setopt($curl, CURLOPT_TIMEOUT, 120); // set timeout in secon
            $result = curl_exec($curl);

            $resp = array();
            $resp_xml = new SimpleXMLElement($result);
            $resp['code'] = (string) $resp_xml->code;
            $resp['message'] = (string) $resp_xml->message;

            if ($resp['code'] != '200') {
                $values["error"] = $resp['code'] . ' ' . $resp['message'];
                call_user_func_array('logout', array($username, $password, $url, $key));
                return $values;
            }

            $values["Bill"]["Full Name"] = (string) $resp_xml->contact_domain->contact_name;
            $values["Bill"]["Email"] = (string) $resp_xml->contact_domain->contact_email;
            $values["Bill"]["Phone Number"] = (string) $resp_xml->contact_domain->contact_voice;
            $values["Bill"]["Address"] = (string) $resp_xml->contact_domain->contact_street;
            $values["Bill"]["Postcode"] = (string) $resp_xml->contact_domain->contact_pc;
            $values["Bill"]["City"] = (string) $resp_xml->contact_domain->contact_city;
            $values["Bill"]["Region"] = (string) $resp_xml->contact_domain->contact_sp;
            $values["Bill"]["Country"] = (string) $resp_xml->contact_domain->contact_cc;
        } else {
            $values["Bill"]["Full Name"] = '';
            $values["Bill"]["Email"] = '';
            $values["Bill"]["Phone Number"] = '';
            $values["Bill"]["Address"] = '';
            $values["Bill"]["Postcode"] = '';
            $values["Bill"]["City"] = '';
            $values["Bill"]["Region"] = '';
            $values["Bill"]["Country"] = '';
        }
    }

    //LOGOUT
    $resp = call_user_func_array('logout', array($username, $password, $url, $key));
    return $values;
}

function pointer_SaveContactDetails($params) {
    $username = $params["Username"];
    $password = $params["Password"];
    $tld = $params["tld"];
    $sld = $params["sld"];
    $url = getUrl($params);

    //LOGIN
    $resp = call_user_func_array('login', array($username, $password, $url));
    if ($resp['code'] != '200') {
        $values["error"] = $resp['code'] . ' ' . $resp['message'];
        call_user_func_array('logout', array($username, $password, $url, $key));
        return $values;
    }
    $key = $resp['key'];


    /**
     * ADMIN
     */
    //CREATE CONTACT
    $adminName = $params["contactdetails"]["Admin"]["Full Name"];
    $adminAddress1 = $params["contactdetails"]["Admin"]["Address"];
    $adminCity = $params["contactdetails"]["Admin"]["City"];
    $adminStateProvince = $params["contactdetails"]["Admin"]["Region"];
    $adminPostalCode = $params["contactdetails"]["Admin"]["Postcode"];
    $adminCountry = $params["contactdetails"]["Admin"]["Country"];
    $adminEmailAddress = $params["contactdetails"]["Admin"]["Email"];
    $adminPhone = $params["contactdetails"]["Admin"]["Phone Number"];
    $chksum = md5($username . $password . 'createContactDomain' . $key);
    $xml = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>
            <pointer>
                <contact-domain>
                    <create>
                        <domain>{$sld}</domain>
                        <tld>{$tld}</tld>
                        <name>{$adminName}</name>
                        <street>{$adminAddress1}</street>
                        <city>{$adminCity}</city>
                        <sp>{$adminStateProvince}</sp>
                        <pc>{$adminPostalCode}</pc>
                        <country>{$adminCountry}</country>
                        <phone>{$adminPhone}</phone>
                        <email>{$adminEmailAddress}</email>
                    </create>
                </contact-domain>
                <username>{$username}</username>
                <chksum>{$chksum}</chksum>
            </pointer>";
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_HTTPHEADER, Array("Content-Type:text/xml"));
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_HEADER, 0);
    curl_setopt($curl, CURLOPT_POST, 1);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $xml); // post the xml
    curl_setopt($curl, CURLOPT_TIMEOUT, 120); // set timeout in secon
    $result = curl_exec($curl);
    $resp = array();
    $resp_xml = new SimpleXMLElement($result);
    $resp['code'] = (string) $resp_xml->code;
    $resp['message'] = (string) $resp_xml->message;
    if ($resp['code'] != '200') {
        $values["error"] = $resp['code'] . ' ' . $resp['message'];
        call_user_func_array('logout', array($username, $password, $url, $key));
        return $values;
    }
    $admin_code = (string) $resp_xml->contact_domain->contact_code;
    $admin_existing_str = (string) $resp_xml->contact_domain->existing;
    $admin_existing = ($admin_existing_str == 'true') ? TRUE : FALSE;


    //UPDATE CONTACT
    if ($admin_existing) {
        $chksum = md5($username . $password . 'updateContactInfoDomain' . $key);
        $xml = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>
            <pointer>
                <contact-domain>
                    <update>
                        <domain>{$sld}.{$tld}</domain>
                        <tld>{$tld}</tld>
                        <code>{$admin_code}</code>
                        <name>{$adminName}</name>
                        <street>{$adminAddress1}</street>
                        <city>{$adminCity}</city>
                        <sp>{$adminStateProvince}</sp>
                        <pc>{$adminPostalCode}</pc>
                        <country>{$adminCountry}</country>
                        <phone>{$adminPhone}</phone>
                        <email>{$adminEmailAddress}</email>
                    </update>
                </contact-domain>
                <username>{$username}</username>
                <chksum>{$chksum}</chksum>
            </pointer>";
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HTTPHEADER, Array("Content-Type:text/xml"));
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HEADER, 0);
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $xml); // post the xml
        curl_setopt($curl, CURLOPT_TIMEOUT, 120); // set timeout in secon
        $result = curl_exec($curl);
        $resp = array();
        $resp_xml = new SimpleXMLElement($result);
        $resp['code'] = (string) $resp_xml->code;
        $resp['message'] = (string) $resp_xml->message;
        if ($resp['code'] != '200') {
            $values["error"] = $resp['code'] . ' ' . $resp['message'];
            call_user_func_array('logout', array($username, $password, $url, $key));
            return $values;
        }
    }


    //UPDATE DOMAIN CONTACT
    $chksum = md5($username . $password . 'updateContactDomain' . $key);
    $xml = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>
            <pointer>
                <domain>
	                <updatecontact>
	                	<domain>{$sld}.{$tld}</domain>
	                	<type>admin</type>
	                	<contact_code>{$admin_code}</contact_code>
	                </updatecontact>
                </domain>
                <username>{$username}</username>
                <chksum>{$chksum}</chksum>
            </pointer>";
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_HTTPHEADER, Array("Content-Type:text/xml"));
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_HEADER, 0);
    curl_setopt($curl, CURLOPT_POST, 1);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $xml); // post the xml
    curl_setopt($curl, CURLOPT_TIMEOUT, 120); // set timeout in secon
    $result = curl_exec($curl);
    $resp = array();
    $resp_xml = new SimpleXMLElement($result);
    $resp['code'] = (string) $resp_xml->code;
    $resp['message'] = (string) $resp_xml->message;
    if ($resp['code'] != '200') {
        $values["error"] = $resp['code'] . ' ' . $resp['message'];
        call_user_func_array('logout', array($username, $password, $url, $key));
        return $values;
    }


    /**
     * TECH
     */
    //CREATE CONTACT
    $techName = $params["contactdetails"]["Tech"]["Full Name"];
    $techAddress1 = $params["contactdetails"]["Tech"]["Address"];
    $techCity = $params["contactdetails"]["Tech"]["City"];
    $techStateProvince = $params["contactdetails"]["Tech"]["Region"];
    $techPostalCode = $params["contactdetails"]["Tech"]["Postcode"];
    $techCountry = $params["contactdetails"]["Tech"]["Country"];
    $techEmailAddress = $params["contactdetails"]["Tech"]["Email"];
    $techPhone = $params["contactdetails"]["Tech"]["Phone Number"];
    $chksum = md5($username . $password . 'createContactDomain' . $key);
    $xml = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>
            <pointer>
                <contact-domain>
                <create>
                	<domain>{$sld}</domain>
                	<tld>{$tld}</tld>
                	<name>{$techName}</name>
					<street>{$techAddress1}</street>
					<city>{$techCity}</city>
					<sp>{$techStateProvince}</sp>
					<pc>{$techPostalCode}</pc>
					<country>{$techCountry}</country>
					<phone>{$techPhone}</phone>
					<email>{$techEmailAddress}</email>
                </create>
                </contact-domain>
                <username>{$username}</username>
                <chksum>{$chksum}</chksum>
            </pointer>";
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_HTTPHEADER, Array("Content-Type:text/xml"));
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_HEADER, 0);
    curl_setopt($curl, CURLOPT_POST, 1);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $xml); // post the xml
    curl_setopt($curl, CURLOPT_TIMEOUT, 120); // set timeout in secon
    $result = curl_exec($curl);
    $resp = array();
    $resp_xml = new SimpleXMLElement($result);
    $resp['code'] = (string) $resp_xml->code;
    $resp['message'] = (string) $resp_xml->message;
    if ($resp['code'] != '200') {
        $values["error"] = $resp['code'] . ' ' . $resp['message'];
        call_user_func_array('logout', array($username, $password, $url, $key));
        return $values;
    }
    $tech_code = (string) $resp_xml->contact_domain->contact_code;
    $tech_existing_str = (string) $resp_xml->contact_domain->existing;
    $tech_existing = ($tech_existing_str == 'true') ? TRUE : FALSE;


    //UPDATE CONTACT
    if ($tech_existing) {
        $chksum = md5($username . $password . 'updateContactInfoDomain' . $key);
        $xml = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>
            <pointer>
                <contact-domain>
                <update>
                	<domain>{$sld}.{$tld}</domain>
                	<tld>{$tld}</tld>
                	<code>{$tech_code}</code>
					<name>{$techName}</name>
					<street>{$techAddress1}</street>
					<city>{$techCity}</city>
					<sp>{$techStateProvince}</sp>
					<pc>{$techPostalCode}</pc>
					<country>{$techCountry}</country>
					<phone>{$techPhone}</phone>
					<email>{$techEmailAddress}</email>
                </update>
                </contact-domain>
                <username>{$username}</username>
                <chksum>{$chksum}</chksum>
            </pointer>";
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HTTPHEADER, Array("Content-Type:text/xml"));
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HEADER, 0);
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $xml); // post the xml
        curl_setopt($curl, CURLOPT_TIMEOUT, 120); // set timeout in secon
        $result = curl_exec($curl);
        $resp = array();
        $resp_xml = new SimpleXMLElement($result);
        $resp['code'] = (string) $resp_xml->code;
        $resp['message'] = (string) $resp_xml->message;
        if ($resp['code'] != '200') {
            $values["error"] = $resp['code'] . ' ' . $resp['message'];
            call_user_func_array('logout', array($username, $password, $url, $key));
            return $values;
        }
    }


    //UPDATE DOMAIN CONTACT
    $chksum = md5($username . $password . 'updateContactDomain' . $key);
    $xml = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>
            <pointer>
                <domain>
	                <updatecontact>
	                	<domain>{$sld}.{$tld}</domain>
	                	<type>tech</type>
	                	<contact_code>{$tech_code}</contact_code>
	                </updatecontact>
                </domain>
                <username>{$username}</username>
                <chksum>{$chksum}</chksum>
            </pointer>";
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_HTTPHEADER, Array("Content-Type:text/xml"));
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_HEADER, 0);
    curl_setopt($curl, CURLOPT_POST, 1);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $xml); // post the xml
    curl_setopt($curl, CURLOPT_TIMEOUT, 120); // set timeout in secon
    $result = curl_exec($curl);
    $resp = array();
    $resp_xml = new SimpleXMLElement($result);
    $resp['code'] = (string) $resp_xml->code;
    $resp['message'] = (string) $resp_xml->message;
    if ($resp['code'] != '200') {
        $values["error"] = $resp['code'] . ' ' . $resp['message'];
        call_user_func_array('logout', array($username, $password, $url, $key));
        return $values;
    }


    /**
     * BILL
     */
    //CREATE CONTACT
    $billName = $params["contactdetails"]["Bill"]["Full Name"];
    $billAddress1 = $params["contactdetails"]["Bill"]["Address"];
    $billCity = $params["contactdetails"]["Bill"]["City"];
    $billStateProvince = $params["contactdetails"]["Bill"]["Region"];
    $billPostalCode = $params["contactdetails"]["Bill"]["Postcode"];
    $billCountry = $params["contactdetails"]["Bill"]["Country"];
    $billEmailAddress = $params["contactdetails"]["Bill"]["Email"];
    $billPhone = $params["contactdetails"]["Bill"]["Phone Number"];
    $chksum = md5($username . $password . 'createContactDomain' . $key);
    $xml = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>
            <pointer>
                <contact-domain>
                <create>
                	<domain>{$sld}</domain>
                	<tld>{$tld}</tld>
                	<name>{$billName}</name>
					<street>{$billAddress1}</street>
					<city>{$billCity}</city>
					<sp>{$billStateProvince}</sp>
					<pc>{$billPostalCode}</pc>
					<country>{$billCountry}</country>
					<phone>{$billPhone}</phone>
					<email>{$billEmailAddress}</email>
                </create>
                </contact-domain>
                <username>{$username}</username>
                <chksum>{$chksum}</chksum>
            </pointer>";
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_HTTPHEADER, Array("Content-Type:text/xml"));
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_HEADER, 0);
    curl_setopt($curl, CURLOPT_POST, 1);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $xml); // post the xml
    curl_setopt($curl, CURLOPT_TIMEOUT, 120); // set timeout in secon
    $result = curl_exec($curl);
    $resp = array();
    $resp_xml = new SimpleXMLElement($result);
    $resp['code'] = (string) $resp_xml->code;
    $resp['message'] = (string) $resp_xml->message;
    if ($resp['code'] != '200') {
        $values["error"] = $resp['code'] . ' ' . $resp['message'];
        call_user_func_array('logout', array($username, $password, $url, $key));
        return $values;
    }
    $bill_code = (string) $resp_xml->contact_domain->contact_code;
    $bill_existing_str = (string) $resp_xml->contact_domain->existing;
    $bill_existing = ($bill_existing_str == 'true') ? TRUE : FALSE;


    //UPDATE CONTACT
    if ($bill_existing) {
        $chksum = md5($username . $password . 'updateContactInfoDomain' . $key);
        $xml = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>
            <pointer>
                <contact-domain>
                <update>
                	<domain>{$sld}.{$tld}</domain>
                	<tld>{$tld}</tld>
                	<code>{$bill_code}</code>
					<name>{$billName}</name>
					<street>{$billAddress1}</street>
					<city>{$billCity}</city>
					<sp>{$billStateProvince}</sp>
					<pc>{$billPostalCode}</pc>
					<country>{$billCountry}</country>
					<phone>{$billPhone}</phone>
					<email>{$billEmailAddress}</email>
                </update>
                </contact-domain>
                <username>{$username}</username>
                <chksum>{$chksum}</chksum>
            </pointer>";
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HTTPHEADER, Array("Content-Type:text/xml"));
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HEADER, 0);
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $xml); // post the xml
        curl_setopt($curl, CURLOPT_TIMEOUT, 120); // set timeout in secon
        $result = curl_exec($curl);
        $resp = array();
        $resp_xml = new SimpleXMLElement($result);
        $resp['code'] = (string) $resp_xml->code;
        $resp['message'] = (string) $resp_xml->message;
        if ($resp['code'] != '200') {
            $values["error"] = $resp['code'] . ' ' . $resp['message'];
            call_user_func_array('logout', array($username, $password, $url, $key));
            return $values;
        }
    }


    //UPDATE DOMAIN CONTACT
    $chksum = md5($username . $password . 'updateContactDomain' . $key);
    $xml = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>
            <pointer>
                <domain>
	                <updatecontact>
	                	<domain>{$sld}.{$tld}</domain>
	                	<type>billing</type>
	                	<contact_code>{$bill_code}</contact_code>
	                </updatecontact>
                </domain>
                <username>{$username}</username>
                <chksum>{$chksum}</chksum>
            </pointer>";
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_HTTPHEADER, Array("Content-Type:text/xml"));
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_HEADER, 0);
    curl_setopt($curl, CURLOPT_POST, 1);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $xml); // post the xml
    curl_setopt($curl, CURLOPT_TIMEOUT, 120); // set timeout in secon
    $result = curl_exec($curl);
    $resp = array();
    $resp_xml = new SimpleXMLElement($result);
    $resp['code'] = (string) $resp_xml->code;
    $resp['message'] = (string) $resp_xml->message;
    if ($resp['code'] != '200') {
        $values["error"] = $resp['code'] . ' ' . $resp['message'];
        call_user_func_array('logout', array($username, $password, $url, $key));
        return $values;
    }

    //LOGOUT
    $resp = call_user_func_array('logout', array($username, $password, $url, $key));

    return $values;
}

function pointer_GetEPPCode($params) {
    $username = $params["Username"];
    $password = $params["Password"];
    $tld = $params["tld"];
    $sld = $params["sld"];
    $url = getUrl($params);

    //LOGIN
    $resp = call_user_func_array('login', array($username, $password, $url));
    if ($resp['code'] != '200') {
        $values["error"] = $resp['code'] . ' ' . $resp['message'];
        call_user_func_array('logout', array($username, $password, $url, $key));
        return $values;
    }
    $key = $resp['key'];

    $chksum = md5($username . $password . 'getAuthCodeDomain' . $key);
    $xml = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>
            <pointer>
                <domain>
                <get-authcode>
                	<domain>{$sld}.{$tld}</domain>
                </get-authcode>
                </domain>
                <username>{$username}</username>
                <chksum>{$chksum}</chksum>
            </pointer>";


    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_HTTPHEADER, Array("Content-Type:text/xml"));
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_HEADER, 0);
    curl_setopt($curl, CURLOPT_POST, 1);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $xml); // post the xml
    curl_setopt($curl, CURLOPT_TIMEOUT, 120); // set timeout in secon


    $result = curl_exec($curl);

    # Put your code to request the EPP code here - if the API returns it, pass back as below - otherwise return no value and it will assume code is emailed
    $resp = array();
    $resp_xml = new SimpleXMLElement($result);
    $resp['code'] = (string) $resp_xml->code;
    $resp['message'] = (string) $resp_xml->message;

    if ($resp['code'] != '200') {
        $values["error"] = $resp['code'] . ' ' . $resp['message'];
        call_user_func_array('logout', array($username, $password, $url, $key));
        return $values;
    }
    $values["eppcode"] = (string) $resp_xml->domain->auth_code;

    //LOGOUT
    $resp = call_user_func_array('logout', array($username, $password, $url, $key));

    return $values;
}

function pointer_RegisterNameserver($params) {
    $username = $params["Username"];
    $password = $params["Password"];
    $tld = $params["tld"];
    $sld = $params["sld"];
    $nameserver = $params["nameserver"];
    $ipaddress = $params["ipaddress"];
    $url = getUrl($params);

    //LOGIN
    $resp = call_user_func_array('login', array($username, $password, $url));
    if ($resp['code'] != '200') {
        $values["error"] = $resp['code'] . ' ' . $resp['message'];
        call_user_func_array('logout', array($username, $password, $url, $key));
        return $values;
    }
    $key = $resp['key'];

    //CREATE NS
    $chksum = md5($username . $password . 'createNsDomain' . $key);
    $xml = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>
            <pointer>
                <domain>
                	<createns>
                		<ns>{$nameserver}</ns>
                		<ip>{$ipaddress}</ip>
                	</createns>
                </domain>
                <username>{$username}</username>
                <chksum>{$chksum}</chksum>
            </pointer>";

    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_HTTPHEADER, Array("Content-Type:text/xml"));
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_HEADER, 0);
    curl_setopt($curl, CURLOPT_POST, 1);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $xml); // post the xml
    curl_setopt($curl, CURLOPT_TIMEOUT, 120); // set timeout in secon


    $result = curl_exec($curl);

    $resp = array();
    $resp_xml = new SimpleXMLElement($result);
    $resp['code'] = (string) $resp_xml->code;
    $resp['message'] = (string) $resp_xml->message;

    if ($resp['code'] != '200') {
        $values["error"] = $resp['code'] . ' ' . $resp['message'];
        call_user_func_array('logout', array($username, $password, $url, $key));
        return $values;
    }

    $resp = call_user_func_array('logout', array($username, $password, $url, $key));
    return $values;
}

/*
  function pointer_ModifyNameserver($params) {
  $username = $params["Username"];
  $password = $params["Password"];
  $tld = $params["tld"];
  $sld = $params["sld"];
  $nameserver = $params["nameserver"];
  $currentipaddress = $params["currentipaddress"];
  $newipaddress = $params["newipaddress"];
  # Put your code to update the nameserver here
  # If error, return the error message in the value below
  $values["error"] = $error;
  return $values;
  }

  function pointer_DeleteNameserver($params) {
  $username = $params["Username"];
  $password = $params["Password"];
  $tld = $params["tld"];
  $sld = $params["sld"];
  $nameserver = $params["nameserver"];
  # Put your code to delete the nameserver here
  # If error, return the error message in the value below
  $values["error"] = $error;
  return $values;
  }
 */

function getExpireDate($params) {
    $username = $params["Username"];
    $password = $params["Password"];
    $testmode = (isset($params["TestMode"]) && $params["TestMode"]) ? TRUE : FALSE;
    $action = $params['action'];
    $domain = $params['domain'];
    $key = $params['key'];
    $url = $params['url'];

    $chksum = md5($username . $password . 'getExpireDateDomain' . $key);
    $xml = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>
            <pointer>
                <domain>
                <get-expire>
                	<domain>{$domain}</domain>
                	<type>{$action}</type>
                </get-expire>
                </domain>
                <username>{$username}</username>
                <chksum>{$chksum}</chksum>
            </pointer>";

    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_HTTPHEADER, Array("Content-Type:text/xml"));
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_HEADER, 0);
    curl_setopt($curl, CURLOPT_POST, 1);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $xml); // post the xml
    curl_setopt($curl, CURLOPT_TIMEOUT, 120); // set timeout in secon

    $result = curl_exec($curl);

    $resp = array();
    $resp_xml = new SimpleXMLElement($result);

    $resp['code'] = (string) $resp_xml->code;
    $resp['message'] = (string) $resp_xml->message;
    $values['code'] = $resp['code'];
    $values['message'] = $resp['message'];
    $exp_date = false;
    /*
      if(isset($resp_xml->domain->date)){
      $exp_date  = (string)$resp_xml->domain->date;
      }
     */

    if ($resp['code'] != '200') {
        $values["message"] = $resp['code'] . ' ' . $resp['message'];
        return $values;
    }

    $exp_date = false;
    if (isset($resp_xml->domain->date)) {
        $exp_date = (string) $resp_xml->domain->date;
    }
    if (!$exp_date) {
        $values["message"] = $resp['code'] . ' ' . $resp['message'];
        return $values;
    }

    $values["expire_date"] = $exp_date;

    return $values;
}

function login($username, $password, $url) {
    $chksum = md5($username . $password . 'login');
    $xml = "<?xml version=\"1.0\" encoding=\"UTF-8\" standalone=\"no\"?>
            <pointer>
                <login>
                    	<password>" . md5($password) . "</password>
                </login>
                <username>{$username}</username>
                <chksum>{$chksum}</chksum>
            </pointer>";


    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_HTTPHEADER, Array("Content-Type:text/xml"));
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_HEADER, 0);
    curl_setopt($curl, CURLOPT_POST, 1);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $xml); // post the xml
    curl_setopt($curl, CURLOPT_TIMEOUT, 120); // set timeout in secon

    $result = curl_exec($curl);

    $resp = array();
    $resp_xml = new SimpleXMLElement($result);
    $resp['code'] = (string) $resp_xml->code;
    $resp['message'] = (string) $resp_xml->message;
    $resp['key'] = (string) $resp_xml->login->key;

    return $resp;
}

function logout($username, $password, $url, $key) {
    $chksum = md5($username . $password . 'logout' . $key);
    $xml = "<?xml version=\"1.0\" encoding=\"UTF-8\" standalone=\"no\"?>
            <pointer>
                <logout>
                </logout>
                <key>" . $key . "</key>
                <username>{$username}</username>
                <chksum>{$chksum}</chksum>
            </pointer>";

    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_HTTPHEADER, Array("Content-Type:text/xml"));
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_HEADER, 0);
    curl_setopt($curl, CURLOPT_POST, 1);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $xml); // post the xml
    curl_setopt($curl, CURLOPT_TIMEOUT, 120); // set timeout in secon

    $result = curl_exec($curl);

    $resp = array();
    $resp_xml = new SimpleXMLElement($result);
    $resp['code'] = (string) $resp_xml->code;
    $resp['message'] = (string) $resp_xml->message;
    $resp['key'] = (string) $resp_xml->login->key;

    return $resp;
}

function getUrl($params) {
    $testmode = (isset($params["TestMode"]) && $params["TestMode"]) ? TRUE : FALSE;
    $url = '';
    if ($testmode) {
        $url = "http://devpointer.ngrok.com/admin/reseller";
    } else {
        $url = "http://admin.pointer.gr/reseller";
    }
    return $url;
}

?>