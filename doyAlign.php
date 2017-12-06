#!/usr/bin/php
<?php

$year = 2017;

$fp = fopen('cancelled-app-counts-' . $year . '40.csv', 'r');

$outFile = fopen('cancelled-app-counts-' . $year . '40-aligned.csv', 'w');

if($fp === false){
    die('Could not open file.');
}

$rows = array();

while(($data = fgetcsv($fp)) !== FALSE){
    print_r($data);
    $rows[] = $data;
}

$indexed = array();
$first = true;
foreach ($rows as $row){
    if($first === true){
        $first = false;
        continue;
    }
    $indexed[$row[3]] = $row;
}

$startingDay = $rows[1][3];
$lastDay = $rows[sizeof($rows) - 1][3];

$newRows = array();
$lastKnownIndex = $startingDay;

for ($i=$startingDay; $i <= $lastDay; $i++){
    if(isset($indexed[$i])){
        fputcsv($outFile, $indexed[$i]);
        $lastKnownIndex = $i;
    } else {
        $myRow = array();
        $timestamp = mktime(0, 0, 0, 1, $i, $year);
        $month = date('n', $timestamp);
        $day = date('j', $timestamp);
        $doy = $i;
        $lastValue = $indexed[$lastKnownIndex][4];

        $myRow = array($timestamp, $month, $day, $doy, $lastValue);

        fputcsv($outFile, $myRow);
    }
}

echo "Done\n\n";
