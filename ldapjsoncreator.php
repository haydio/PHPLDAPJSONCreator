<?php

//Created by Haydn Cockayne - http://hayd.io
//Requires PHP LDAP module

// Put your details here
$ldapuser  = 'user@domain.com';     // LDAP Bind User
$ldappass = 'password';  // Password
$ldapserver = 'domain.com'; // Server IP or DNS name
$dn = "OU=Users,DC=domain,DC=com"; // Base DN


// connect to ldap server
$ldapconn = ldap_connect($ldapserver)
    or die("Could not connect to LDAP server.");

if ($ldapconn) {

    // binding to ldap server
    $ldapbind = ldap_bind($ldapconn, $ldapuser, $ldappass);

    // verify binding
    if ($ldapbind) {

    $filter="(objectClass=user)";
    
    $attributes = array("displayName", "mail"); // AD Attributes in double quotes and separated by commas 

    $searchRequest=ldap_search($ldapconn, $dn, $filter, $attributes);
    $results = ldap_get_entries($ldapconn, $searchRequest);

//creating users array
$users =array();

//loop through results
for ($i=0; $i<$results["count"]; $i++)
    {

        list($firstname, $lastname) = explode(" ", $results[$i]["displayname"][0], 2); //Splits Display name into First and Last Names
        $mail = $results[$i]["mail"][0]; // If you are collecting more AD Attributes than me, you can copy this line and change the variable and attribute name

array_push($users,
	array(
    "first_name" => $firstname,
    "last_name" => $lastname,
    "email" => $mail
     )
);

}

    } else {
        echo "LDAP bind failed";
    }

}

//encodes PHP array as JSON
echo json_encode($users);

?>