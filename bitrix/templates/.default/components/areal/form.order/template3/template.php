<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<div class="right-side">
	<div class="tooltip">
		<form name="order_form_question" method="post" action="<?=$APPLICATION->GetCurPage(false)?>" class="protection">
			<?=bitrix_sessid_post()?>
			<div class="description">
				Вы можете связаться с нашими специалистами по телефону:
				<div class="phone"><?$APPLICATION->IncludeComponent("bitrix:main.include", "template2", Array(
	"AREA_FILE_SHOW" => "file",	// Показывать включаемую область
	"PATH" => SITE_DIR."include/phone.php",	// Путь к файлу области
	),
	false,
	array(
	"ACTIVE_COMPONENT" => "Y"
	)
);?></div>
				Или заполнить форму:
			</div>		
			<?if($_REQUEST["success"] == "Y"):?>
				<?echo ShowNote($arResult["SUCCESS_MESSAGE"])?>
			<?endif;?>
			<div class="line">
				<label class="top3">ФИО и Название компании<span class="required">*</span></label>
				<input type="text" name="NAME" class="required" value="" />
				<div class="clear"></div>
			</div>
			<div class="line">
				<label>Телефон</label>
				<input type="text" name="PHONE" class="phone" value="" />
				<div class="clear"></div>
			</div>
			<div class="line">
				<label>E-mail<span class="required">*</span></label>
				<input type="text" name="EMAIL" class="email required" value="" />
				<div class="clear"></div>
			</div>
			<div class="line autocomplete small">
				<label>Город</label>				
				<input type="text" class="autocomplete town" name="TOWN" value="<?=$_REQUEST["TOWN"] ? $_REQUEST["TOWN"] : $_SESSION["SELECTED_TOWN"]?>" />
				<div class="b-places"></div>
				<div class="clear"></div>
			</div>
			<?if(count($arResult["THEME"]) > 1):?>
				<div class="line">
					<label>Тема <span class="required">*</span></label>
					<select name="THEME">
						<?foreach($arResult["THEME"] as $theme):?>
							<option value="<?=$theme?>" <?if($_REQUEST["THEME"] == $theme):?>selected="selected"<?endif;?>><?=$theme?></option>
						<?endforeach;?>
					</select>
					<div class="clear"></div>
				</div>
			<?else:?>
				<input type="hidden" name="THEME" value="<?=$arResult["THEME"][0]?>" />
			<?endif;?>
			<input type="hidden" name="DESCRIPTION" value="" />
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