<?php

function lang($phrase)
{
    $lang = [
        'HOME' => 'الرئيسية',
        'LOGS' => 'Logs',
        'ITEMS' => 'مواد',
        'MEMBERS' => 'أعضاء',
        'COMMENTS' => 'نعليقات',
        'STATISTICS' => 'احصائيات',
        'CATEGORIES' => 'تصنيفات',
        'SHOP_VIEW' => 'عرض المحل',
        'EDIT' => 'تعديل',
        'SIGN_OUT' => 'خروج'
    ];
    return $lang[$phrase];
}
