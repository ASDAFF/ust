<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<div class="tooltip">
	<form name="callback_contacts" method="post" action="<?=$APPLICATION->GetCurPage(false)?>" class="protection">
		<?=bitrix_sessid_post()?>
		<div class="title">Форма обратной связи</div>
		<div class="description">Вы можете задать вопрос о характеристиках товара, условиях заказа либо оставить отзыв о сотрудничестве с компанией.</div>
		<?if($_REQUEST["success"] == "Y"):?>
			<?echo ShowNote($arResult["SUCCESS_MESSAGE"])?>
		<?endif;?>
        <?foreach($arResult["PROPS"] as $arProps):?>
            <?switch($arProps["PROPERTY_TYPE"]){
                case 'S': 
                    if($arProps["ROW_COUNT"] > 1){
                    ?>            
                    <div class="line">
                        <label class="message">Комментарий</label>                
                        <textarea name="PROPS_<?=$arProps["CODE"]?>" placeholder="Ваши комментарии"><?=$_REQUEST[$arProps["CODE"]]?></textarea>
                        <div class="clear"></div>
                    </div>      
                    <?
                    }
                    else{
                    ?>
                    <div class="line <?if($arProps["CODE"] == "TOWN"):?>autocomplete small<?endif?>">
                        <label class="<?if($arProps["CODE"] == "NAME" || $arProps["CODE"] == "ADDRESS" || $arProps["CODE"] == "MODEL"):?>top3 <?endif?>"><?=$arProps["NAME"]?><?if($arProps["IS_REQUIRED"] == "Y"):?><span class="required">*</span><?endif?></label>
                        <input type="text" name="PROPS_<?=$arProps["CODE"]?>" class="<?if($arProps["IS_REQUIRED"] == "Y"):?>required <?endif?><?if($arProps["CODE"] == "TOWN"):?>autocomplete town <?endif?><?if($arProps["CODE"] == "EMAIL"):?>email<?endif?><?if($arProps["CODE"] == "PHONE"):?>phone<?endif?>" value="<?=$_REQUEST[$arProps["CODE"]]?><?if($arProps["CODE"] == "TOWN"):?>Москва<?endif?>" />
                        <?if($arProps["CODE"] == "TOWN"):?><div class="b-places"></div><?endif?>
                        <div class="clear"></div>
                    </div>
                    <?   
                    }
                break;
                case 'L':
                    ?>
                    <div class="line">
                        <label><?=$arProps["NAME"]?><?if($arProps["IS_REQUIRED"] == "Y"):?><span class="required">*</span><?endif?></label>
                        <select name="PLIST_<?=$arProps["CODE"]?>">
                            <?foreach($arProps["VAL_LIST"] as $arVal):?>
                                <option value="<?=$arVal["ID"]?>" <?if($arVal["DEF"] == "Y"):?>selected="selected"<?endif;?>><?=$arVal["VALUE"]?></option>
                            <?endforeach;?>
                        </select>
                        <div class="clear"></div>
                    </div>
                    <?
                break;
                case 'E':
                    //echo '<pre>'; print_r($arProps); echo '</pre>';
                break;
            }
            ?>
        <?endforeach?>
		<?/*<div class="line">
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
		<div class="line autocomplete">
			<label>Город</label>				
			<input type="text" class="autocomplete town" name="TOWN" value="<?if(!empty($_SESSION["SELECTED_TOWN"])) echo trim($_SESSION["SELECTED_TOWN"])?>" />
			<div class="b-places"></div>
			<div class="clear"></div>
		</div>
		<?if(!empty($arResult["THEMES"])):?>
			<div class="line">
				<label>Тема</label>
				<select name="THEME">
					<?foreach($arResult["THEMES"] as $key => $theme):?>
						<option value="<?=$key?>"><?=$theme["NAME"]?></option>
					<?endforeach;?>
				</select>
				<div class="clear"></div>
			</div>
		<?endif;?>
		<div class="line">
			<label>Комментарий<span class="required">*</span></label>
			<textarea class="required" type="text" name="COMMENT" /></textarea>
			<div class="clear"></div>
		</div>       */?>
		<p class="hint">* - поля, обязательные для заполнения.</p>
		<div class="line">
			<button type="submit" name="send">Отправить</button>
			<div class="clear"></div>
		</div>
	</form>
</div>