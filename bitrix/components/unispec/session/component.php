<?php
// include keys file
$keys = include(dirname(__FILE__).'/keys.php');
$currentHost = $_SERVER['HTTP_HOST'];
$currentKeys = null;

// search for keys for current host
foreach ( $keys as $index=>$key ) {
    if ( strpos($currentHost, $key[0])!==false ) {
        $currentKeys = array($key[1], $key[2]);
        $currentHost = $key[0];
        $arResult['source'] = $index;
        break;
    }
}

// list all hosts except current
$hosts = array();
foreach ( $keys as $key ) {
    if ( $key[0]!=$currentHost ) {
        $hosts[] = $key[0];
    }
}
$arResult['hosts'] = $hosts;

// encrypt session id
$td = mcrypt_module_open(MCRYPT_3DES, '', MCRYPT_MODE_CBC, '');
$iv = mcrypt_create_iv(mcrypt_enc_get_iv_size($td), MCRYPT_DEV_URANDOM);
$ks = mcrypt_enc_get_key_size($td);
$key = hash('sha256', $currentKeys[0], TRUE);
mcrypt_generic_init($td, $key, $iv);
$data = mcrypt_generic($td, session_id());
mcrypt_generic_deinit($td);
mcrypt_module_close($td);
$arResult['session'] = base64_encode($data);
$arResult['iv'] = base64_encode($iv);

/*echo '<!--test';
var_dump($key);
var_dump($data);
var_dump($iv);
echo '-->';*/

// create control hash
$arResult['hash'] = sha1($currentKeys[1].session_id());

// if everything is ok, include template
if ( $currentKeys!==null ) {
    $this->IncludeComponentTemplate();
}