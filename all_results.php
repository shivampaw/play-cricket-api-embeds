<!DOCTYPE html>
<?php
require("include.php");

use Carbon\Carbon;

$season = date("Y");
$url = "http://play-cricket.com/api/v2/result_summary.json?&site_id=1477&season=" . date("Y") . "&api_token=" . $apiToken;

$data = file_get_contents($url);
$data = json_decode($data, true)['result_summary'];

$matches = array_filter($data, function ($match) {
    return true;
});

$matches = array_slice(array_reverse($matches), 0, 5);

function get_innings($match, $teamId)
{
    return array_values(array_filter($match['innings'], function (array $innings) use ($teamId) {
        return $innings['team_batting_id'] == $teamId;
    }))[0] ?? null;
}

function get_points($match, $teamId)
{
    return array_values(array_filter($match['points'], function (array $points) use ($teamId) {
        return $points['team_id'] == $teamId;
    }))[0] ?? [];
}

?>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
    <title>All Recent Results</title>
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
                        <h3 class="text-base md:text-lg lg:text-xl text-green-600 font-bold uppercase"><?= $match['home_club_name'] ?></h3>
                        <p class="text-sm md:text-base lg:text-lg font-bold uppercase text-gray-400"><?= $match['home_team_name'] ?></p>
                        <?php if (!in_array($match['result'], ['A', 'C', 'CON']) && !empty(get_innings($match, $match['home_team_id']))): ?>
                            <p class="text-sm md:text-base my-4 text-gray-600">
                                <strong><?= get_innings($match, $match['home_team_id'])['runs'] ?></strong> for
                                <strong><?= get_innings($match, $match['home_team_id'])['wickets'] ?></strong> after
                                <strong><?= get_innings($match, $match['home_team_id'])['overs'] ?></strong> overs
                            </p>
                        <?php endif; ?>
                        <div class="my-4 bg-white rounded-full h-12 w-12 font-bold text-lg flex items-center justify-center mx-auto text-white
                            <?= $match['result_applied_to'] === '' ? 'bg-gray-500' : ($match['result_applied_to'] == $match['home_team_id'] ? 'bg-green-600' : 'bg-red-600') ?>"
                        >
                            <?= (get_points($match, $match['home_team_id'])['game_points'] ?? 0) + (get_points($match, $match['home_team_id'])['bonus_points_together'] ?? 0) ?>
                        </div>
                    </div>
                    <div class="w-1/5 text-center text-gray-800 p-4">
                        <img class="mx-auto" src="https://img.icons8.com/ios/50/000000/head-to-head.png" />
                    </div>
                    <div class="w-2/5 text-center p-4 text-gray-800">
                        <h3 class="text-base md:text-lg lg:text-xl text-green-600 font-bold uppercase"><?= $match['away_club_name'] ?></h3>
                        <p class="text-sm md:text-base lg:text-lg font-bold uppercase text-gray-400"><?= $match['away_team_name'] ?></p>
                        <?php if (!in_array($match['result'], ['A', 'C', 'CON']) && !empty(get_innings($match, $match['away_team_id']))): ?>
                            <p class="text-sm md:text-base my-4 text-gray-600">
                                <strong><?= get_innings($match, $match['away_team_id'])['runs'] ?></strong> for
                                <strong><?= get_innings($match, $match['away_team_id'])['wickets'] ?></strong> after
                                <strong><?= get_innings($match, $match['away_team_id'])['overs'] ?></strong> overs
                            </p>
                        <?php endif; ?>
                        <div class="my-4 bg-white rounded-full h-12 w-12 font-bold text-lg flex items-center justify-center mx-auto text-white
                            <?= $match['result_applied_to'] === '' ? 'bg-gray-500' : ($match['result_applied_to'] == $match['away_team_id'] ? 'bg-green-600' : 'bg-red-600') ?>"
                        >
                            <?= (get_points($match, $match['away_team_id'])['game_points'] ?? 0) + (get_points($match, $match['away_team_id'])['bonus_points_together'] ?? 0) ?>
                        </div>
                    </div>
                    <div class="w-full my-2">
                        <p class="text-gray-500 text-lg font-bold text-center">
                            <?= $match['result_description'] ?>
                        </p>
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
                <a class="text-blue-500" target="_blank" href="https://bronze.play-cricket.com/website/results/<?= $match['id'] ?>">
                    <i class="fa fa-external-link mr-2" aria-hidden="true"></i>
                </a>
            </div>
        </div>
    <?php endforeach;
else: ?>
    <div class="w-full bg-gray-200 text-gray-600 uppercase font-bold p-5 text-center">
        No Recent Results
    </div>
<?php endif ?>

</body>

</html>
