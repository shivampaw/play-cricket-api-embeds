<!DOCTYPE html>
<?php
require("include.php");

use Carbon\Carbon;

$season = date("Y");
$url = "http://play-cricket.com/api/v2/matches.json?&site_id=1477&season=" . $season . "&api_token=" . $apiToken;

$data = file_get_contents($url);
$data = json_decode($data, true)['matches'];

$matches = array_filter($data, function ($match) {
    if ($match['status'] == 'Deleted') {
        return false;
    }

    $date = $match['match_date'];
    if (!Carbon::createFromFormat('d/m/Y', $date)->addDay()->isFuture()) {
        return false;
    }

    return true;
});

$matches = array_slice($matches, 0, 5);

?>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
    <title>All Upcoming Fixtures</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.11/tailwind.min.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/iframe-resizer/4.1.1/iframeResizer.contentWindow.min.js"></script>
</head>

<body>
<?php
if (count($matches) > 0):
    foreach ($matches as $match): ?>
        <div class="mb-2">
            <div class="p-2 bg-gray-200 text-center text-gray-600 font-bold w-full">
                <?php if ($match['competition_type'] == 'Friendly'): ?>
                    Friendly
                <?php else: ?>
                    <?= $match['league_name']; ?> - <?= $match['competition_name']; ?>
                <?php endif; ?>
            </div>
            <div class="bg-gray-100 w-full px-2 py-5">
                <div class="flex items-center flex-wrap">
                    <div class="w-2/5 text-center p-4 text-gray-800">
                        <h3 class="text-md md:text-lg lg:text-xl text-green-600 font-bold uppercase"><?= $match['home_club_name'] ?></h3>
                        <p class="text-sm md:text-md lg:text-lg font-bold uppercase text-gray-400"><?= $match['home_team_name'] ?></p>
                    </div>
                    <div class="w-1/5 text-center text-gray-800 p-4">
                        <img class="mx-auto" src="https://img.icons8.com/ios/50/000000/head-to-head.png" />
                    </div>
                    <div class="w-2/5 text-center p-4 text-gray-800">
                        <h3 class="text-md md:text-lg lg:text-xl text-green-600 font-bold uppercase"><?= $match['away_club_name'] ?></h3>
                        <p class="text-sm md:text-md lg:text-lg font-bold uppercase text-gray-400"><?= $match['away_team_name'] ?></p>
                    </div>
                </div>
            </div>
            <div class="w-full p-2 text-center bg-gray-200 text-gray-600 w-full">
                <a class="text-blue-500" target="_blank" href="https://bronze.play-cricket.com/grounds/<?= $match['ground_id'] ?>">
                    <i class="fa fa-map-marker mr-2" aria-hidden="true"></i>
                    <?= $match['ground_name'] ?>
                </a>
                <p class="mt-2">
                    <i class="fa fa-clock-o mr-2" aria-hidden="true"></i>
                    <?= Carbon::createFromFormat('d/m/Y', $match['match_date'])->format('D j M Y') . ' - ' . $match['match_time']; ?>
                </p>
                <a class="text-blue-500" target="_blank" href="https://bronze.play-cricket.com/match_details?id=<?= $match['id'] ?>">
                    <i class="fa fa-external-link mr-2" aria-hidden="true"></i>
                </a>
            </div>
        </div>
    <?php endforeach;
else: ?>
    <div class="w-full bg-gray-200 text-gray-600 uppercase font-bold p-5 text-center">
        No Upcoming Fixtures
    </div>
<?php endif ?>

</body>

</html>
