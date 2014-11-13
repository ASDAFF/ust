<? if(!defined("B_PROLOG_INCLUDED")||B_PROLOG_INCLUDED!==true)die();

$arComponentParameters = array(
	'PARAMETERS' => array(
		'ID_BLOCK' => array(
			'NAME' => 'Id Инфоблока',
			'TYPE' => 'INTEGER',
			'MULTIPLE' => 'N',
			'PARENT' => 'BASE',
			),
		'CACHE_TIME'  =>  array('DEFAULT'=>360000),
                'CACHE_TYPE'=> "A" 
	),
);
?>
