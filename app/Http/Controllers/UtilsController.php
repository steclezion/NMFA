<?php

namespace App\Http\Controllers;

use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

class UtilsController extends Controller
{
    /**
     * Display directory contents recursively
     * @param $path : path starting from dossier/dir_of_the_dossier
     * @return array: all files
     */
    public static function getDirContents($path)
    {
        $iterator = new RecursiveIteratorIterator (new RecursiveDirectoryIterator ($path));
        $files = array();
        foreach ($iterator as $file)
            if (!$file->isDir())
                // find all files inside this dir
                $files[] = $file->getPathname();
        return $files;
    }


    /**
     * Display bytes in KB, MB ..etc
     * @param $bytes :
     * @param $precision : default 2,
     * @return string
     */
    public static function human_readable_filesize($bytes, $precision = 2)
    {
        $size = array('B', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB');
        $factor = floor((strlen($bytes) - 1) / 3);

        return sprintf("%.{$precision}f", $bytes / pow(1024, $factor)) . @$size[$factor];
    }

    /**
     * Static Values of Progress Status
     *
     */
    public static function eval_progress_status()
    {

        $progress = array(
            "UNASSIGNED" => "unassigned",
            "ASSIGNED" => "assigned",
            "PENDING" => "pending",
            "PAUSE" => "pause",
            "INPROGRESS" => "Inprogress",
            "COMPLETED" => "completed",
            "QUEUED" => "queued",

        );
        return $progress;

    }


    /**
     * Split report title to main_title and report_sequence
     * Eg. Full Assessment Report (Final), $main_title=Full Assessment Report, $report_sequence=Final
     * @param $report_title
     * @return array
     */
    public static function split_report_title($report_title)
    {

        $pattern = "/(.*)(\(\w+\))/"; // group1 = any word .*  , group2= word inside ()

        preg_match_all($pattern,
            $report_title,
            $matches
            );

        #print_r($matches[1][0]);  //$main_title

        #print_r($matches[2][0]);  //$report_sequence

        return array($matches[1][0], $matches[2][0]);

    }



}

