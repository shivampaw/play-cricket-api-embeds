# Play Cricket API Browser

This app provides read-only web pages to the following Play Cricket stats:

* League tables
* Upcoming fixtures for a team
* Recent matches for a team

## Install
* Place the entire project in your root directory.
* Rename .env.example to .env
* Edit the .env file and add in your Play Cricket API Key

# Endpoints
* `league_table.php?league=LEAGUE_ID`
* `upcoming_fixtures.php?team=TEAM_ID`
* `recent_matches.php?team=TEAM_ID`

You can get the leage & team IDs from the Play Cricket website by looking at the URL.

## Screenshots
### Recent Matches
![](https://i.imgur.com/sHF3DpW.png)
### Upcoming Matches
![](https://i.imgur.com/tQZHFHV.png)
### League Table
![](https://i.imgur.com/l9fJ6Nw.png)
