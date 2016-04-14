<?php
require_once __DIR__ . '/vendor/autoload.php';

$source = './data/source.txt';

$data = file_get_contents($source);

$conference = new App\Conference($data);

$conference->groupedTalks();

$conference->scheduleTracksWithTalks();

$conference->outputScheduledTracks();
