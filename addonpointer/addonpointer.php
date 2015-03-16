<?php

function addonpointer_config() {
    $configarray = array(
        "name" => "Addon Pointer",
        "description" => "Activate this addon right after you activate Pointer registrar.",
        "version" => "1.0",
        "author" => "Pointer",
        "fields" => array()
    );
    return $configarray;
}

function addonpointer_activate() {
    $query = "CREATE TABLE IF NOT EXISTS `pointer_domain` (`domain` varchar(255) NOT NULL , `action` varchar(255) NOT NULL , `queued_on` int UNSIGNED NOT NULL , `tries` int NOT NULL DEFAULT 0);";
    $result = mysql_query($query);
    return array('status' => 'success', 'description' => 'The selected addon was activated successfully.');
}

function addonpointer_deactivate() {
    $query = "DROP TABLE IF EXISTS `pointer_domain`;";
    $result = mysql_query($query);
    return array('status' => 'success', 'description' => 'The selected addon was deactivated successfully.');
}

?>