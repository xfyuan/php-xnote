<?php
require_once __DIR__ . '/vendor/autoload.php';

$source = './data/source.txt';

$data = file_get_contents($source);

$conference = new App\Conference($data);

$conference->grouped_talks();

$conference->schedule_tracks_with_talks();

$conference->output_scheduled_tracks();
