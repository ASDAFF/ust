<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<div class="right-side">
	<div class="tooltip">
		<div class="description">
			Вы можете связаться с нашими специалистами по телефону:
			<div class="phone"><?$APPLICATION->IncludeComponent("bitrix:main.include", "", array("AREA_FILE_SHOW" => "file", "PATH" => SITE_DIR."include/phone.php"), false);?></div>
			Или заполнить форму:
		</div>
		<?if($_REQUEST["success"] == "Y"):?>
			<?echo ShowNote($arResult["SUCCESS_MESSAGE"])?>
		<?endif;?>
		<form name="service_form" method="post" action="<?=$APPLICATION->GetCurPage(false)?>" class="protection">
			<?=bitrix_sessid_post()?>
			<div class="line">
				<label class="top3">ФИО и название компании<span class="required">*</span></label>
				<input type="text" name="NAME" class="required" value="<?=$_REQUEST["NAME"]?>" />
				<div class="clear"></div>
			</div>
			<div class="line">
				<label>E-mail</label>
				<input type="text" name="EMAIL" class="email" value="<?=$_REQUEST["EMAIL"]?>" />
				<div class="clear"></div>
			</div>
			<div class="line">
				<label>Телефон<span class="required">*</span></label>
				<input type="text" name="PHONE" class="required phone" value="<?=$_REQUEST["PHONE"]?>" />
				<div class="clear"></div>
			</div>
			<?if(!empty($arResult["TYPE"])):?>
				<div class="line">
					<label>Виды работ<span class="required">*</span></label>
					<select name="TYPE">
						<?foreach($arResult["TYPE"] as $key => $type):?>
							<option value="<?=$key?>" <?if($key == $_REQUEST["TYPE"]):?>selected="selected"<?endif;?>><?=$type["NAME"]?></option>
						<?endforeach;?>
					</select>
					<div class="clear"></div>
				</div>
			<?endif;?>
			<div class="line autocomplete small">
				<label>Город<span class="required">*</span></label>				
				<input type="text" class="autocomplete town required" name="TOWN" value="<?=$_REQUEST["TOWN"] ? $_REQUEST["TOWN"] : $_SESSION["SELECTED_TOWN"]?>" />
				<div class="b-places"></div>
				<div class="clear"></div>
			</div>
			<div class="line">
				<label class="top3">Бренд и модель техники<span class="required">*</span></label>
				<input type="text" name="TEHNIK_BRAND" class="required" value="<?=$_REQUEST["TEHNIK_BRAND"]?>" />
				<div class="clear"></div>
			</div>
			<div class="line">
				<label class="top3">Серийный номер</label>
				<input type="text" name="SERIAL_NUMBER" value="<?=$_REQUEST["SERIAL_NUMBER"]?>" />
				<div class="clear"></div>
			</div>
			<div class="line">
				<label class="top3">Наработка моточасов</label>
				<input type="text" name="MOTO" value="<?=$_REQUEST["MOTO"]?>" />
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