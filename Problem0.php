<?php
require "Problem0/vendor/autoload.php";
require(__DIR__ . '/Problem.php');

/**
 *  Head of Marketing (who's not a technical expert) has asked you to design a system that:
 * - pulls data from a list of JSON sources provided to you
 * - converts each JSON file to CSV and saves it to disk. The CSV should have a header row and the following data (provided below)
 * - regularly checks for updates so that if the JSON source has changed, the CSV file on the disk should be automatically updated
 * - has a relatively easy way to add new JSON sources, ideally without even touching your code
 *
 * Specifically, they ask you to build the initial system around these three URLs:
 * https://data.cityofnewyork.us/api/views/kku6-nxdu/rows.json?accessType=DOWNLOAD
 * https://chronicdata.cdc.gov/api/views/5svk-8bnq/rows.json?accessType=DOWNLOAD
 * https://data.cityofchicago.org/api/views/xzkq-xp2w/rows.json?accessType=DOWNLOAD
 *
 * The output of the initial system should be three CSV files on a disk.
 *
 *
 * Extra (optional):
 * - have the system protect against human errors
 * - have logging so you can debug and let the marketing team know if something breaks
 * - have a notification system if the update fails or the link is broken/inaccessible
 * - have support of JSON files of different structures
 * - have other data formats supported (e.g. XML, CSV, TSV)
 */
class Problem0 implements Problem
{

    public function run(...$params)
    {
        (new app\App())->run();
    }
}


(new Problem0())->run();
