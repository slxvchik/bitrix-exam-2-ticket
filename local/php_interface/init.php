<?php

CModule::AddAutoloadClasses(
	'',
	[
        'Event\\Reviews\\ReviewsEventHandler' => '/local/php_interface/event/reviews/ReviewsEventHandler.php',
    ]
);

AddEventHandler("iblock", "OnBeforeIBlockElementAdd", Array("Event\\Reviews\\ReviewsEventHandler", "onBeforeReviewAdd"));
AddEventHandler("iblock", "OnBeforeIBlockElementUpdate", Array("Event\\Reviews\\ReviewsEventHandler", "onBeforeReviewUpdate"));
AddEventHandler("iblock", "OnAfterIBlockElementUpdate", Array("Event\\Reviews\\ReviewsEventHandler", "onAfterReviewUpdate"));
