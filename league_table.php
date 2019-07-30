<!DOCTYPE html>
<?php
require("include.php");

if (!isset($_GET['league']) || !is_numeric($_GET['league'])) {
    die("No League Specified");
}
$team = $_GET['league'];

$url = "http://play-cricket.com/api/v2/league_table.json?&division_id=" . $_GET['league'] . "&api_token=" . $apiToken;

$data = file_get_contents($url);
$table = json_decode($data, true)['league_table'];
?>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">
    <title><?php echo $table[0]["name"]; ?></title>
</head>
<body>

<div class="table-responsive">
    <table class="table table-striped table-bordered">
        <thead>
        <tr>
            <th></th>
            <?php foreach ($table[0]['headings'] as $heading) { ?>
                <th><?php echo $heading; ?></th>
            <?php } ?>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($table[0]['values'] as $team_values) { ?>
            <?php unset($team_values['team_id']); ?>
            <tr>
                <?php foreach ($team_values as $val) { ?>
                    <td><?php echo $val; ?></td>
                <?php } ?>
            </tr>
        <?php } ?>
        </tbody>
    </table>
    <p class="small"><?php echo $table[0]['key']; ?></p>
</div>

<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/iframe-resizer/4.1.1/iframeResizer.contentWindow.min.js"></script>
</body>
</html
