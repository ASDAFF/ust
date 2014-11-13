<?
/*

Don't forgot to send a FORM_NAME parameter fom your new form !

You can look to
\bitrix\components\areal\form.feedback.new\component.php
for view an example

*/

class MailHandler
{

    function OnBeforeEventAddHandler(&$event, &$lid, &$arFields, &$duplicate, &$message_id)
    {

        if ($event == "WEB_FORM" && isset($_SESSION['id_user_web']))
        {

            $id_user_web=$_SESSION['id_user_web'];

            $name="Данные формы пользователя#".$id_user_web;

            $el = new CIBlockElement;

            $fields=array();

            $formName=($arFields['FORM_NAME'])? $arFields['FORM_NAME'] : '';

            unset($arFields['FORM_NAME']);

            foreach ($arFields as $key => $value)
            {
                $fields[$key]=$value;
            }

            $data=json_encode($fields);

            $PROP = array();
            $PROP["ID_USER"] = $id_user_web;
            $PROP["ID_FORM"] = $formName;
            $PROP["DATA"] = $data;

            $arLoadProductArray = Array(
                "EXTERNAL_ID" => $id_user_web,
                "IBLOCK_SECTION_ID" => false, // элемент лежит в корне раздела
                "IBLOCK_ID" => 146,
                "PROPERTY_VALUES" => $PROP,
                "ACTIVE" => "Y", // активен
                "NAME" => $name,
            );
            unset($PROP);

            $el->Add($arLoadProductArray);

        }
    }


}

?>