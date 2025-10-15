<?php

namespace Event\Users;

class UsersEventHandler
{
    private static $oldAuthorStatus;

    public static function onBeforeUserUpdateHandler(&$arFields)
    {
        if (\Bitrix\Main\Loader::includeModule("iblock") && \Bitrix\Main\Loader::includeModule("main"))
        {
            $oldUserRes = \CUser::GetById($arFields["ID"]);
            $oldAuthorStatusId = $oldUserRes->Fetch()["UF_AUTHOR_STATUS"];
    
            $oldAuthorStatusRes = \CIBlockElement::GetByID($oldAuthorStatusId);
            static::$oldAuthorStatus = $oldAuthorStatusRes->Fetch()["NAME"];
        }
    }

    public static function onAfterUserUpdateHandler(&$arFields)
    {
        if (\Bitrix\Main\Loader::includeModule("iblock") && \Bitrix\Main\Loader::includeModule("main"))
        {
            $oldAuthorStatusRes = \CIBlockElement::GetByID($arFields["UF_AUTHOR_STATUS"]);
            $newAuthorStatus = $oldAuthorStatusRes->Fetch()["NAME"];
            if (!isset($arFields["RESULT_MESSAGE"]) && static::$oldAuthorStatus !== $newAuthorStatus)
            {
                \Bitrix\Main\Mail\Event::send([
                    "EVENT_NAME" => "EX2_AUTHOR_INFO", 
                    "LID" => "s1", 
                    "C_FIELDS" => array(
                        "OLD_UF_STATUS" => static::$oldAuthorStatus,
                        "NEW_UF_STATUS" => $newAuthorStatus
                    ),
                ]);
            }
        }
    }
}