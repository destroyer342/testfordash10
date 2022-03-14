<?php

/**
 * Use this file to output reports required for the SQL Query Design test.
 * An example is provided below. You can use the `asTable` method to pass your query result to,
 * to output it as a styled HTML table.
 */

$database = 'nba2019';
require_once('vendor/autoload.php');
require_once('include/utils.php');

/*
 * Example Query
 * -------------
 * Retrieve all team codes & names
 */
echo '<h1>Example Query</h1>';
$teamSql = "SELECT * FROM team";
$teamResult = query($teamSql);
// dd($teamResult);
echo asTable($teamResult);

/*
 * Report 1
 * --------
 * Produce a query that reports on the best 3pt shooters in the database that are older than 30 years old. Only 
 * retrieve data for players who have shot 3-pointers at greater accuracy than 35%.
 * 
 * Retrieve
 *  - Player name
 *  - Full team name
 *  - Age
 *  - Player number
 *  - Position
 *  - 3-pointers made %
 *  - Number of 3-pointers made 
 *
 * Rank the data by the players with the best % accuracy first.
 */
echo '<h1>Report 1 - Best 3pt Shooters</h1>';
// write your query here
$report1Sql = "SELECT r.name player_name , t.name team_name, pt.age, r.number as player_number, r.pos as position, ROUND((pt.3pt/pt.3pt_attempted)*100,2) accuracy, pt.3pt as 3pts_made FROM roster r, team t, player_totals pt WHERE t.code = r.team_code AND r.id = pt.player_id AND pt.age>30 AND (pt.3pt/pt.3pt_attempted)*100>35 ORDER BY accuracy DESC";
$report1Result = query($report1Sql);
// dd($teamResult);
echo asTable($report1Result);

/*
 * Report 2
 * --------
 * Produce a query that reports on the best 3pt shooting teams. Retrieve all teams in the database and list:
 *  - Team name
 *  - 3-pointer accuracy (as 2 decimal place percentage - e.g. 33.53%) for the team as a whole,
 *  - Total 3-pointers made by the team
 *  - # of contributing players - players that scored at least 1 x 3-pointer
 *  - of attempting player - players that attempted at least 1 x 3-point shot
 *  - total # of 3-point attempts made by players who failed to make a single 3-point shot.
 * 
 * You should be able to retrieve all data in a single query, without subqueries.
 * Put the most accurate 3pt teams first.
 */
echo '<h1>Report 2 - Best 3pt Shooting Teams</h1>';
// write your query here
$report2Sql = "SELECT t.name team_name,ROUND((SUM(pt.3pt)/SUM(pt.3pt_attempted))*100,2) total_accuracy, SUM(pt.3pt) total_3pts, sum(pt.3pt>0) total_contributor,sum(pt.3pt_attempted>0) total_attempt_players,(sum(pt.3pt_attempted)-sum(pt.3pt)) failed_3pts_attempt
FROM team t, roster r, player_totals pt
WHERE t.code = r.team_code AND r.id = pt.player_id
GROUP BY t.code
ORDER BY total_accuracy DESC";
$report2Result = query($report2Sql);
// dd($teamResult);
echo asTable($report2Result);
