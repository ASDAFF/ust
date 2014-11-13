<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
TownDefinition();
IncludeTemplateLangFile(__FILE__);?>
<!DOCTYPE html>
<html xml:lang="<?=LANGUAGE_ID?>" lang="<?=LANGUAGE_ID?>">
	<head>
		<meta charset="utf-8" />
		<!--[if IE]><meta http-equiv='X-UA-Compatible' content='IE=edge,chrome=1'><![endif]-->
		<meta name="viewport" content="width=device-width, initial-scale=1" />
		<meta name="SKYPE_TOOLBAR" content="SKYPE_TOOLBAR_PARSER_COMPATIBLE" /> 
		<meta content="telephone=no" name="format-detection" />
		<?$APPLICATION->ShowHead();?>
		<title><?$APPLICATION->ShowTitle()?></title>
		<link rel="shortcut icon" href="/favicon.ico" type="image/x-icon" />
		<link rel="icon" href="/favicon.ico" type="image/x-icon" />
		<link rel="stylesheet" type="text/css" href="/design/css/printing.css?ver=<?=time()?>" />
		<!--[if lt IE 9]>
			<script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
		<![endif]-->
		<script type="text/javascript" src="/design/js/jquery-1.10.2.min.js"></script>
		<script type="text/javascript" src="/design/js/printing.js"></script>
		<script type="text/javascript" src="http://api-maps.yandex.ru/2.0-stable/?load=package.standard&amp;lang=ru-RU"></script>
	</head>
	<body>
		<div id="panel"><?$APPLICATION->ShowPanel();?></div>
		<div class="wrapper">
			<header class="header">
				<div class="left">
					<hr class="red" />
					<div class="logo"><img src="/design/images/ust-co-logo-print.png" width="182" height="56" alt="Универсал-Спецтехника" title="Универсал-Спецтехника" /></div>
					<div class="phone">
						<span><?$APPLICATION->IncludeComponent("bitrix:main.include", "", array("AREA_FILE_SHOW" => "file", "PATH" => SITE_DIR."include/phone.php"), false);?></span>
						<span><?=GetPhoneFromTown()?></span>
					</div>
					<div class="slogan">Ведущая компания по поставкам и обслуживанию дорожно-строительной и складской техники. Сервис, аренда, доставка запчастей по всей России.</div>
					<div class="clear"></div>
					<div class="desciprion">Работаем с 2001 г.</div>
					<hr />
				</div>
				<div class="print"></div>
				<div class="clear"></div>
			</header>
			<div class="content">	
				<h1><?$APPLICATION->ShowTitle()?></h1>