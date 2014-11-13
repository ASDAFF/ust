<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<div class="right-side">
	<div class="tooltip">
		<form name="career" method="post" action="<?=$APPLICATION->GetCurPage(false)?>#form" class="protection" enctype="multipart/form-data">
			<?=bitrix_sessid_post()?>
            <div class="description">
				Вы можете связаться с нашими специалистами по телефону:
				<div class="phone"><?$APPLICATION->IncludeComponent("bitrix:main.include", "", array("AREA_FILE_SHOW" => "file", "PATH" => SITE_DIR."include/phone.php"), false);?></div>
				Или заполнить форму:
			</div>
			<?if($_REQUEST["success"] == "Y"):?>
				<?echo ShowNote($arResult["SUCCESS_MESSAGE"])?>
			<?endif;?>
			<div class="line">
				<label class="top3">ФИО и Название компании<span class="required">*</span></label>
				<input type="text" name="NAME" class="required" value="<?=$_REQUEST["NAME"]?>" />
				<div class="clear"></div>
			</div>
			<div class="line">
				<label>Телефон<span class="required">*</span></label>
				<input type="text" name="PHONE" class="required phone" value="<?=$_REQUEST["PHONE"]?>" />
				<div class="clear"></div>
			</div>
			<div class="line">
				<label>E-mail</label>
				<input type="text" name="EMAIL" class="email" value="<?=$_REQUEST["EMAIL"]?>" />
				<div class="clear"></div>
			</div>
			<div class="line autocomplete small">
				<label>Город<span class="required">*</span></label>				
				<input type="text" class="autocomplete town required" name="TOWN" value="<?=$_REQUEST["TOWN"] ? $_REQUEST["TOWN"] : $_SESSION["SELECTED_TOWN"]?>" />
				<div class="b-places"></div>
				<div class="clear"></div>
			</div>
			<div class="line">
				<label class="top3">Адрес доставки<span class="required">*</span></label>
				<input type="text" name="ADDRESS" class="required" value="<?=$_REQUEST["ADDRESS"]?>" />
				<div class="clear"></div>
			</div>
			<div class="line">
				<label class="top3">Модель техники</label>
				<input type="text" name="MODEL" class="required" value="<?=$_REQUEST["MODEL"]?>" />
				<div class="clear"></div>
			</div>
			<div class="line">
				<label class="message">Комментарий</label>				
				<textarea name="MESSAGE" placeholder="Ваши комментарии"><?=$_REQUEST["MESSAGE"]?></textarea>
				<div class="clear"></div>
			</div>
			<p class="hint">* - поля, обязательные для заполнения.</p>
			<div class="line">
				<button type="submit" name="send">Отправить</button>
				<div class="clear"></div>
			</div>
		</form>
	</div>
</div>