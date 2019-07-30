<!DOCTYPE html>
<?php
require("include.php");

use Carbon\Carbon;

if (!isset($_GET['team']) || !is_numeric($_GET['team'])) {
    die("No Team Specified");
}
$team = $_GET['team'];

$url = "http://play-cricket.com/api/v2/result_summary.json?&site_id=1477&season=" . date("Y") . "&team_id=" . $team . "&api_token=" . $apiToken;

$data = file_get_contents($url);

$matches = json_decode($data, true)['result_summary'];
?>
<!DOCTYPE html>
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
                    <th>Match Date</th>
                    <th>Match Type</th>
                    <th>Home Team</th>
                    <th>Away Team</th>
                    <th>Result</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if (count($matches) > 0) {
                    foreach ($matches as $match) {
                        // No need to show match in progress results
                        $NAMES = [];
                        $NAMES[$match['home_team_id']] = $match['home_club_name'] . " - " . $match['home_team_name'];
                        $NAMES[$match['away_team_id']] = $match['away_club_name'] . " - " . $match['away_team_name'];

                        if ($match['result_description'] == "Match In Progress") {
                            continue;
                        }
                        ?>
                        <tr data-toggle="collapse" class="toggle" data-target="#details_<?php echo $match['id']; ?>">
                            <td><i class="fa fa-plus" aria-hidden="true"></i></td>
                            <td><?php echo $match['match_date']; ?></td>
                            <td><?php echo $match['competition_type']; ?></td>
                            <td><?php echo $match['home_club_name']; ?></td>
                            <td><?php echo $match['away_club_name']; ?></td>
                            <td><?php echo $match['result_description']; ?></td>
                        </tr>
                        <tr class="collapse details" id="details_<?php echo $match['id']; ?>">
                            <td colspan="6" style="text-align: center;">
                                <p>
                                    <!-- Toss -->
                                    <?php echo $match['toss']; ?><br /><br />

                                    <!-- Innings -->
                                    <?php foreach ($match['innings'] as $innings) { ?>
                                        <?php echo $NAMES[$innings['team_batting_id']] . " scored " . $innings['runs'] . ' for ' . $innings['wickets'] . ' in ' . $innings['overs'] . ' overs.'; ?><br />
                                    <?php } ?>

                                    <br />

                                    <!-- Points Received -->
                                    <?php foreach ($match['points'] as $points) { ?>
                                        <?php echo $NAMES[$points['team_id']] . " received " . $points['game_points'] . ' points.'; ?><br />
                                    <?php } ?>

                                    <br />

                                    <!-- Scorecard Link -->
                                    <a href="https://bronze.play-cricket.com/website/results/<?php echo $match['id']; ?>" target="_blank">Full Scorecard</a>
                                </p>
                            </td>
                        </tr>
                    <?php
                    }
                } else {
                    ?>
                    <tr>
                        <td colspan="6" style="text-align: center;">No Results to Display</td>
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

</html
