<?php

CModule::AddAutoloadClasses(
	'',
	[
        'Event\\Reviews\\ReviewsEventHandler' => '/local/php_interface/event/reviews/ReviewsEventHandler.php',
        'Event\\Users\\UsersEventHandler' => '/local/php_interface/event/users/UsersEventHandler.php',
        'Event\\Index\\IndexEventHandler' => '/local/php_interface/event/index/IndexEventHandler.php',
    ]
);

AddEventHandler("iblock", "OnBeforeIBlockElementAdd", Array("Event\\Reviews\\ReviewsEventHandler", "onBeforeReviewAdd"));
AddEventHandler("iblock", "OnBeforeIBlockElementUpdate", Array("Event\\Reviews\\ReviewsEventHandler", "onBeforeReviewUpdate"));
AddEventHandler("iblock", "OnAfterIBlockElementUpdate", Array("Event\\Reviews\\ReviewsEventHandler", "onAfterReviewUpdate"));

AddEventHandler("main", "OnBeforeUserUpdate", Array("Event\\Users\\UsersEventHandler", "onBeforeUserUpdateHandler"));
AddEventHandler("main", "OnAfterUserUpdate", Array("Event\\Users\\UsersEventHandler", "onAfterUserUpdateHandler"));

AddEventHandler("search", "BeforeIndex", Array("Event\\Index\\IndexEventHandler", "BeforeIndexReviewHandler"));