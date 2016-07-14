#!/usr/bin/php
<?php
/**
 * Scans a directory for calls to checkRight(). Extracts all strings from these
 * calls and prints them out. Use this if you want to start coding first and
 * worry about which permissions you need and where to store them later, or if
 * you want to keep inventory about which rights are still used within your app.
 */

if (empty($argv[1]) || !is_dir($argv[1])) {
    echo "Please specify directory to scan.\n";
    exit(1);
}

$path = $argv[1];
$rights = array();

$dir = new RecursiveDirectoryIterator($path);
$itr = new RecursiveIteratorIterator($dir);
foreach($itr as $file) {
    if ($file == '.' || $file == '..') {
        continue;
    }
    $filecontent = file_get_contents($file);
    $matches = null;
    preg_match_all("/checkRight\(['|\"](.*?)['|\"]\)/i", $filecontent, $matches);
    if (empty($matches[1])) {
        continue;
    }
    foreach($matches[1] as $match) {
        if (!in_array($match, $rights)) {
            $rights[] = $match;
            echo $file.': '.$match."\n";
        }
    }
}
?>