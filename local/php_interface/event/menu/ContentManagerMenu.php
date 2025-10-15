<?php

namespace Event\Menu;

use \Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);

class ContentManagerMenu
{
    public static function onBuildGlobalMenuHandler(&$aGlobalMenu, &$aModuleMenu)
    {
        global $USER;
        if (in_array(5, $USER->GetUserGroupArray()))
        {
            $contentMenu = $aGlobalMenu["global_menu_content"];
            $aGlobalMenu = [];
            $aGlobalMenu["global_menu_content"] = $contentMenu;

            
            $aModuleMenu = array_filter($aModuleMenu, function($item) {
                return $item["parent_menu"] == "global_menu_content";
            });

            $aMenu = array(
                "parent_menu" => "global_menu_content",
                "sort" => 150,
                "text" => Loc::GetMessage("CONTENT_MANAGER_MENU_FAST_TEXT"),

                "items_id" => "menu_fast",
                "items" => array(
                    array(
                        "text" => Loc::GetMessage("CONTENT_MANAGER_MENU_FIRST_LINK_TEXT"),
                        "url" => "https://test1"
                    ),
                    array(
                        "text" => Loc::GetMessage("CONTENT_MANAGER_MENU_SECOND_LINK_TEXT"),
                        "url" => "https://test2"
                    ),
                )
            );
            $aModuleMenu[] = $aMenu;
        }
    }
}