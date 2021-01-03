<?php

/*
**
**   Get any field Function:
**  
**
*/

function getAllFrom($field, $table, $where = NULL, $andsQuery = NULL, $orderBy = NULL, $order = 'DESC')
{
    global $db;
    $stm = $db->prepare("SELECT $field FROM $table $where $andsQuery ORDER BY $orderBy $order");
    $stm->execute();
    return $stm->fetchAll();
}



/*
** Title Name Function v1.0
** Title Function That Echos The page's Title In Case The page Has 
** variable $pageTitle And Echos default For Other pages
*/

function setTitle()
{
    global $pageTitle;
    echo $pageTitle = isset($pageTitle) ? $pageTitle : 'Default';
}


/*
** Redirect Function v2.0
** Home Redirect Function accepts(message, seconds);
**                                --> message: the message to be displayed
**                                --> seconds: number of seconds takes to redirect default=3
**                                --> url:     URL to redirect to default = index.php
*/

function redirect($message, $url = NULL, $seconds = 3)
{
    if ($url === NULL) {
        $url = 'index.php';
    } elseif ($url === 'back') {
        $url = (isset($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER'] !== '') ? $_SERVER['HTTP_REFERER'] : 'index.php';
    }
    echo $message;
    header("refresh:$seconds ; url=$url");
    exit();
}


/*
** Check For Item Function v1.0
** This Function Checks If The Item Exists On Database To Avoid Repeating Same Request Everytime We Need A check 
** Its Parameters: -> $select: field Name
                   -> $table:  Table To Check In
                   -> $value:  Item Value To Be Checked
                   returns integer
*/

function checkForItem($select, $table, $value)
{
    global $db; // to look for database connection variable globally
    $STM1 = $db->prepare("SELECT $select FROM $table WHERE $select = ?");
    $STM1->execute([$value]);
    $rows = $STM1->rowCount();
    return $rows;
}


/*
** Count Items Function v2.0
** This Function counts the number of members
** Its Parameters: -> $select: field Name
                   -> $table:  Table To count In
                   -> $query: to query a condition
*/

function countItems($select, $table, $query = '')
{
    global $db; // to look for database connection variable globally

    $STM2 = $db->prepare("SELECT COUNT($select) FROM $table $query");
    $STM2->execute();
    $rows = $STM2->fetchColumn(); // fetches all columns
    return $rows;
}



/*
** Get Latest Records Function v1.0
** A function to get the latest records from database
** getLatestRecs($select, $table, $orderByField, $limit= 3);
** Where:   -> $select: field Name
            -> $table:  Table To get from 
            -> $orderByField: field to order by
            -> $limit: limit of records

*/

function getLatestRecs($select, $table, $orderByField, $limit = 3, $query = '')
{
    global $db; // to look for database connection variable globally
    $STM3 = $db->prepare("SELECT $select FROM $table $query ORDER BY $orderByField DESC LIMIT $limit");
    $STM3->execute();
    $rows = $STM3->fetchAll();
    return $rows;
}

/*
**   getCurr($curr) v1.0
**   choose currency from an array
**   
*/

function getCurr($curr)
{
    $currencies = [
        'USD' => '$',
        'CAD' => '$',
        'EUR' => '€',
        'GBP' => '£',
        'DZD' => 'DZD',
    ];
    return $currencies[$curr];
}
