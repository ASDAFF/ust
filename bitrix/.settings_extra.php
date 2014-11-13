<?php
return array (
  'cache' =>  array(
     'value' => array (
        'type' => 'xcache'
     ),
  ),
    
    'exception_handling' => array (
    'value' => array (
      'debug' => false,
      'handled_errors_types' => E_ALL & ~E_NOTICE & ~E_STRICT & ~E_USER_NOTICE,
      'exception_errors_types' => E_ALL & ~E_NOTICE & ~E_WARNING & ~E_STRICT & ~E_USER_WARNING & ~E_USER_NOTICE & ~E_COMPILE_WARNING,
      'ignore_silence' => false,
      'assertion_throws_exception' => true,
      'assertion_error_type' => 256,
      'log' => array (
        'settings' => array (
          'file' => 'bitrix/modules/error.log',
          'log_size' => 1000000,
        ),
      ),
    ),
    'readonly' => false,
  ),
);
/*
return array (
  'cache' => array (
    'value' => array (
      'type' => 'memcache',
     'host' => 'localhost'
    ),
    'readonly' => false,
  )
  );
/**/
?>