<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
class CCommentUFs
{
	var $component = null;

	function __construct(&$component)
	{
		global $APPLICATION;
		$this->component = &$component;
		$arResult =& $component->arResult;
		$arParams =& $component->arParams;

		AddEventHandler("forum", "OnCommentsInit", Array(&$this, "OnCommentsInit"));
		AddEventHandler("forum", "OnPrepareComments", Array(&$this, "OnPrepareComments"));
	}

	function OnCommentsInit()
	{
		$arResult =& $this->component->arResult;
		$arParams =& $this->component->arParams;
		$arResult["UF_PROPERIES"] = $GLOBALS["USER_FIELD_MANAGER"]->GetUserFields("FORUM_MESSAGE", 0, LANGUAGE_ID);
		$arResult['UFS'] = array();
	}

	function OnPrepareComments()
	{
		$arResult =& $this->component->arResult;
		$arParams =& $this->component->arParams;

		$arMessages = &$arResult['MESSAGES'];
		$arResult['UFS'] = array();
		if (!empty($arMessages) && !empty($arResult["UF_PROPERIES"]))
		{
			$res = array_keys($arMessages);
			$arFilter = array(
				"FORUM_ID" => $arParams["FORUM_ID"],
				"TOPIC_ID" => $arResult["FORUM_TOPIC_ID"],
				"APPROVED_AND_MINE" => $GLOBALS["USER"]->GetId(),
				">ID" => intVal(min($res)) - 1,
				"<ID" => intVal(max($res)) + 1);
			if ($arResult["USER"]["RIGHTS"]["MODERATE"] == "Y")
				unset($arFilter["APPROVED_AND_MINE"]);

			$db_res = CForumMessage::GetList(array("ID" => "ASC"), $arFilter, false, 0, array("SELECT" => array_keys($arResult["UF_PROPERIES"])));
			if ($db_res && ($res = $db_res->Fetch()))
			{
				do {
					$arResult['UFS'][$res["ID"]] = array_intersect_key($res, $arResult["UF_PROPERIES"]);
				} while ($res = $db_res->Fetch());
			}
		}
	}

	function OnCommentPreviewDisplay()
	{
		$arResult =& $this->component->arResult;
		$arParams =& $this->component->arParams;
		if (empty($arResult["UF_PROPERIES"]))
			return null;

		ob_start();
		foreach ($arResult["UF_PROPERIES"] as $k => $arPostField)
		{
			if(!empty($_REQUEST[$k]))
			{
				$GLOBALS["APPLICATION"]->IncludeComponent(
					"bitrix:system.field.view",
					$arPostField["USER_TYPE"]["USER_TYPE_ID"],
					array("arUserField" => array_merge($arPostField, array("VALUE" => $_REQUEST[$k]))),
					null,
					array("HIDE_ICONS"=>"Y")
				);
			}
		}
		return array(array('DISPLAY' => 'AFTER', 'SORT' => '50', 'TEXT' => ob_get_clean()));
	}

	function OnCommentDisplay($arComment)
	{
		$arResult =& $this->component->arResult;
		$arParams =& $this->component->arParams;

		if (empty($arComment["PROPS"]))
			return null;

		ob_start();
		if (is_array($arComment["PROPS"])) {
			foreach ($arComment["PROPS"] as $arPostField)
			{
				if(!empty($arPostField["VALUE"]))
				{
					$GLOBALS["APPLICATION"]->IncludeComponent("bitrix:system.field.view", $arPostField["USER_TYPE"]["USER_TYPE_ID"],
						array("arUserField" => $arPostField), null, array("HIDE_ICONS"=>"Y"));
				}
			}
		}
		return array(array('DISPLAY' => 'AFTER', 'SORT' => '50', 'TEXT' => ob_get_clean()));
	}

	function OnCommentFormDisplay()
	{
		$arResult =& $this->component->arResult;
		$arParams =& $this->component->arParams;
		if (empty($arResult["UF_PROPERIES"]))
			return null;

		ob_start();
		foreach ($arResult["UF_PROPERIES"] as $k => $v)
		{
			if ($k != "UF_FORUM_MESSAGE_DOC")
			{
				$v["VALUE"] = (!empty($_REQUEST[$k]) ? $_REQUEST[$k] : $v["VALUE"]);

				?><dt><?=$v["EDIT_FORM_LABEL"]?></dt><dd><?
					$GLOBALS["APPLICATION"]->IncludeComponent(
					"bitrix:system.field.edit",
					$v["USER_TYPE"]["USER_TYPE_ID"],
					array("arUserField" => $v, "bVarsFromForm" => true),
					null,
					array("HIDE_ICONS" => "Y")
				);?></dd><?
			}
		}
		$res = ob_get_clean();
		if (!empty($res))
			$res = "<dl>".$res."</dl>";
		return array(array('DISPLAY' => 'AFTER', 'SORT' => '50', 'TEXT' => $res));
	}
}
?>
