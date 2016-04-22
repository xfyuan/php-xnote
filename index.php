<?php
/**
  * Loading all packagies
  *
  **/
require_once __DIR__ . '/vendor/autoload.php';


// ----------------------------
// read source data
// ----------------------------
$source = './data/source.txt';
$data = file_get_contents($source);

// ----------------------------
// init conference
// ----------------------------
$conference = new App\Conference($data);

// ----------------------------
// generate scheduled tracks
// ----------------------------
$conference->scheduleTracksWithTalks();
$conference->outputScheduledTracks();
