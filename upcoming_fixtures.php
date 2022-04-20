<!DOCTYPE html>
<?php
require("include.php");

use Carbon\Carbon;

if (!isset($_GET['team']) || !is_numeric($_GET['team'])) {
    die("No Team Specified");
}
$team = $_GET['team'];

$season = date("Y");
$url = "http://play-cricket.com/api/v2/matches.json?&site_id=1477&season=" . $season . "&team_id=" . $team . "&api_token=" . $apiToken;

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

?>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">
    <link href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
    <title><?php echo $team; ?></title>
    <style>
    .collapsing {
        -webkit-transition: none;
        transition: none;
        display: none;
    }
    </style>
</head>

<body>

    <div class="table-responsive">
        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th></th>
                    <th>Match Time</th>
                    <th>Match Type</th>
                    <th>Home Team</th>
                    <th>Away Team</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if (count($matches) > 0) {
                    foreach ($matches as $match) {
                        ?>
                        <tr data-toggle="collapse" data-target="#details_<?php echo $match['id']; ?>">
                            <td><i class="fa fa-plus" aria-hidden="true"></i></td>
                            <td><?php echo $match['match_date']; ?> <?php echo $match['match_time']; ?></td>
                            <td><?php echo $match['competition_type']; ?></td>
                            <td><?php echo $match['home_club_name']; ?></td>
                            <td><?php echo $match['away_club_name']; ?></td>
                        </tr>
                        <tr class="collapse" id="details_<?php echo $match['id']; ?>">
                            <td colspan="5" style="text-align: center;">
                                <p>
                                    Ground: <?php echo $match['ground_name']; ?><br />
                                    Start Time: <?php echo $match['match_time']; ?><br />
                                    <a class="text-blue-500" target="_blank" href="https://bronze.play-cricket.com/match_details?id=<?= $match['id'] ?>">
                                        View on Play Cricket
                                    </a>
                                </p>
                            </td>
                        </tr>
                    <?php
                    }
                } else {
                    ?>
                    <tr>
                        <td colspan="5" style="text-align: center;">No Fixtures to Display</td>
                    </tr>
                <?php
                }
                ?>
            </tbody>
        </table>
    </div>

    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/iframe-resizer/4.1.1/iframeResizer.contentWindow.min.js"></script>
    <script>
        jQuery('tr').click(function(e) {
            jQuery('.collapse').collapse('hide');
        });
    </script>
</body>

</html>
