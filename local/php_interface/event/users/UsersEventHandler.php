<?php

namespace Event\Users;

use Bitrix\Main\Loader;
use CUser;
use CIBlockElement;
use \Bitrix\Main\Mail\Event;

class UsersEventHandler
{
    private static $oldAuthorStatus;

    public static function onBeforeUserUpdateHandler(&$arFields)
    {
        if (Loader::includeModule("iblock") && Loader::includeModule("main"))
        {
            $oldUserRes = CUser::GetById($arFields["ID"]);
            $oldAuthorStatusId = $oldUserRes->Fetch()["UF_AUTHOR_STATUS"];
    
            $oldAuthorStatusRes = CIBlockElement::GetByID($oldAuthorStatusId);
            static::$oldAuthorStatus = $oldAuthorStatusRes->Fetch()["NAME"];
        }
    }

    public static function onAfterUserUpdateHandler(&$arFields)
    {
        if (Loader::includeModule("iblock") && Loader::includeModule("main"))
        {
            $oldAuthorStatusRes = CIBlockElement::GetByID($arFields["UF_AUTHOR_STATUS"]);
            $newAuthorStatus = $oldAuthorStatusRes->Fetch()["NAME"];
            if (!isset($arFields["RESULT_MESSAGE"]) && static::$oldAuthorStatus !== $newAuthorStatus)
            {
                Event::send([
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