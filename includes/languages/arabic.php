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
        'EDIT' => 'تعديل',
        'LOG_IN' => 'دخول',
        'SIGN_UP' => 'تسجيل',
    ];
    return $lang[$phrase];
}
