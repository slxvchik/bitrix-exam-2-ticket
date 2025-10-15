<?php

CModule::AddAutoloadClasses(
	'',
	[
        'Event\\Reviews\\ReviewsEventHandler' => '/local/php_interface/event/reviews/ReviewsEventHandler.php',
        'Event\\Users\\UsersEventHandler' => '/local/php_interface/event/users/UsersEventHandler.php',
        'Agents\\Reviews\\ReviewAgent' => '/local/php_interface/agents/reviews/ReviewAgent.php',
    ]
);

AddEventHandler("iblock", "OnBeforeIBlockElementAdd", Array("Event\\Reviews\\ReviewsEventHandler", "onBeforeReviewAdd"));
AddEventHandler("iblock", "OnBeforeIBlockElementUpdate", Array("Event\\Reviews\\ReviewsEventHandler", "onBeforeReviewUpdate"));
AddEventHandler("iblock", "OnAfterIBlockElementUpdate", Array("Event\\Reviews\\ReviewsEventHandler", "onAfterReviewUpdate"));

AddEventHandler("main", "OnBeforeUserUpdate", Array("Event\\Users\\UsersEventHandler", "onBeforeUserUpdateHandler"));
AddEventHandler("main", "OnAfterUserUpdate", Array("Event\\Users\\UsersEventHandler", "onAfterUserUpdateHandler"));