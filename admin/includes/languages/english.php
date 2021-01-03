<?php

function lang($phrase)
{
    $lang = [
        'HOME' => 'Home',
        'ITEMS' => 'Items',
        'MEMBERS' => 'Members',
        'COMMENTS' => 'Comments',
        'STATISTICS' => 'Statistics',
        'LOGS' => 'Logs',
        'CATEGORIES' => 'Categories',
        'SHOP_VIEW' => 'Shop View',
        'EDIT' => 'Edit',
        'SIGN_OUT' => 'Sign out'
    ];
    return $lang[$phrase];
}
