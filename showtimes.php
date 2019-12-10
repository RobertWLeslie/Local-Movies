<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" lang="en">

<head>
<title>LocalMovies</title>
<meta charset="utf-8" />
<meta name="Author" content="Carl Koenig" />
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<link rel='stylesheet' href='https://use.fontawesome.com/releases/v5.7.0/css/all.css' integrity='sha384-lZN37f5QGtY3VHgisS14W3ExzMWZxybE1SJSEsQp9S+oqd12jhcu+A56Ebc1zFSJ' crossorigin='anonymous'>
<style>
body {font-family: "Times New Roman", Georgia, Serif;}
h1, h2, h3, h4, h5, h6 {
  font-family: "Playfair Display";
  letter-spacing: 5px;
}
</style>
<body>

<!-- Navbar (sit on top) -->
<div class="w3-top">
  <div class="w3-bar w3-white w3-padding w3-card" style="letter-spacing:4px;">
    <a href="homepage.php" class="w3-bar-item w3-button">LocalMovies</a>
    <!-- Right-sided navbar links. Hide them on small screens -->
    <div class="w3-right w3-hide-small">
      <a href="form.php" class="w3-bar-item w3-button">Movies</a>
      <a href="theaters.php" class="w3-bar-item w3-button">Theaters</a>
      <a href="homepage.php#contact" class="w3-bar-item w3-button">Help</a>
    </div>
  </div>
</div>
   
<!-- TABLE FOR MENU AND MAIN BODY -->
<table cellspacing=20 cellpadding=20 width="100%">
    
  <colgroup>
    <col width="10%">
  </colgroup>

                  <td valign=100px class=main-text>
<center>
<?php
//Get user location, not GDPR compliant
//$addrInfo['geoplugin_latitude']
//$addrInfo['geoplugin_longitude']
$addrInfo =  unserialize(file_get_contents('http://www.geoplugin.net/php.gp?ip='.$_SERVER['REMOTE_ADDR']));

echo "<br>";
echo "<br>";

require_once('./DBfuncs.php');
require_once('./Connect.php');

$dbh = ConnectDB();

$cinemaID = $_REQUEST['cinemaID'];
$cinemaTableName = 'cinema_' . $cinemaID;


///////

try {
    $query = "SELECT * FROM theaters where theater_ID = $cinemaID;";

    $queryTwo = "Select * From $cinemaTableName";
        
    $stmt = $dbh->prepare($query);
    $stmt->execute();
    $cinemaInfo = $stmt->fetchAll(PDO::FETCH_OBJ);
    $stmt = null;

    $stmtTwo = $dbh->prepare($queryTwo);
    $stmtTwo->execute();
    $movieInfo = $stmtTwo->fetchAll(PDO::FETCH_OBJ);
    $stmtTwo = null;

    
}
catch (PDOException $e)
{
    die("PDO Error: $e->getMessage()");
}

//decode the returned arrays for easier use
$cinemaInfo = json_decode(json_encode($cinemaInfo[0]), true);
$movieInfo = json_decode(json_encode($movieInfo), true);


$title = $cinemaInfo['theater_Name'];
echo "<h1><b>$title</b></h1>";
$cinemaInfo['city'] = str_replace(' Township', '', $cinemaInfo['city']);
echo 'Address: ' . $cinemaInfo['address'] . ' ' . $cinemaInfo['street'] . ' ' . $cinemaInfo['city'] . ' ' . $cinemaInfo['state'];
echo "<br>";
 

$theaterAddress = $cinemaInfo['address'] . '%20' . $cinemaInfo['street'] . '%20' . $cinemaInfo['city'] . '%20' . $cinemaInfo['state'];

foreach($movieInfo as $movie){
    $x = $movie['movie_Title'];
    $summary = $movie['movie_Syn'];
    $rating = $movie['movie_Rating'];
    echo "<h3><b>$x</b></h3>";
    echo "<br>"; 
    echo "<p>$summary</p>";
    echo "<br>";
    echo 'IMDB Rating: ' . $rating;
    echo "<br>";

    //Get show times
    
// Get cURL resource
$ch = curl_init();

// Set url
curl_setopt($ch, CURLOPT_URL, 'https://api.internationalshowtimes.com/v4/showtimes?movie_id=' . $movie['movie_ID'] .'&cinema_id=' . $cinemaID);

// Set method
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');

// Set options
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

// Set headers
curl_setopt($ch, CURLOPT_HTTPHEADER, [
  "X-API-Key: KEY_GOES_HERE",
 ]
);


// Send the request & save response to $resp
$resp = curl_exec($ch);

if(!$resp) {
  die('Error: "' . curl_error($ch) . '" - Code: ' . curl_errno($ch));
} else {
$showTime = json_decode($resp,true);
foreach($showTime as $dateTime){
    foreach($dateTime as $x){


        //echo $x['start_at'];
       // $temp = explode("T",$x['start_at']);
        //echo $temp[0] . ' ' . $temp[1];

        $dateString = explode("T", $x['start_at']);
        $date_String = implode(" ", $dateString);
        $timeString = strtotime($date_String);
        $time_String = date('D, Y-m-d @ H:i:s', $timeString);


        $name = $movie['movie_Title'];
        echo "<a href = \"./text.php?movieName=$name&theaterName=$title&theaterAddress=$theaterAddress&showTime=$time_String\" > $time_String</a> \n";


        //echo $time_String;
        echo '<br>';
   
    }
}

}

// Close request to clear up some resources
curl_close($ch);
}





?>
</center> 
</table>
</tr>
</body>




    
</td>

</html>
