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
        'EDIT' => 'Edit',
        'LOG_IN' => 'Login',
        'SIGN_UP' => 'Sign Up',
    ];
    return $lang[$phrase];
}
