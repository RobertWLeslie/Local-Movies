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

$addrInfo =  unserialize(file_get_contents('http://www.geoplugin.net/php.gp?ip='.$_SERVER['REMOTE_ADDR']));


// Get cURL resource
$ch = curl_init();

// Set url
//distance should be a user input
curl_setopt($ch, CURLOPT_URL, 'https://api.internationalshowtimes.com/v4/cinemas/?location=' . $addrInfo['geoplugin_latitude'] . ',' . $addrInfo['geoplugin_longitude'] .
 '&distance=30');

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
  #echo "Response HTTP Status Code : " . curl_getinfo($ch, CURLINFO_HTTP_CODE);
  #echo "\nResponse HTTP Body : " . $resp;
  //var_dump($resp);
  echo '<br>';

  $cinemaData = json_decode($resp,true);
  echo "<pre>";


  //i should be user selectable
    for($i = 0;$i < 3;$i++){
        //var_dump($cinemaData{'cinemas'}[$i]['id']);
        //var_dump($cinemaData{'cinemas'}[$i]['name']);
      
        $cinemaID[$i] = $cinemaData{'cinemas'}[$i]['id'];
        $cinemaName[$i] = $cinemaData{'cinemas'}[$i]['name'];

        $tableID = "cinema_" . $cinemaID[$i];

        //Insert into theaters table to allow translate between ID and location

        require_once('./DBfuncs.php');
        require_once('./Connect.php');

        $dbh = ConnectDB();

        try{

          $query = "INSERT INTO theaters(theater_ID, theater_Name, street, address, ZIP,  city, state, country, latitude, longitude) 
          VALUES (
            '".$cinemaData{'cinemas'}[$i]['id']."',
           '".$cinemaData{'cinemas'}[$i]['name']."',
            '".$cinemaData{'cinemas'}[$i]['location']['address']['street']."', 
          '".$cinemaData{'cinemas'}[$i]['location']['address']['house']."',
          '".$cinemaData{'cinemas'}[$i]['location']['address']['zipcode']."', 
         '".$cinemaData{'cinemas'}[$i]['location']['address']['city']."',
          '".$cinemaData{'cinemas'}[$i]['location']['address']['state_abbr']."', 
          '".$cinemaData{'cinemas'}[$i]['location']['address']['country_code']."',
           '".$cinemaData{'cinemas'}[$i]['location']['lat']."',
          '".$cinemaData{'cinemas'}[$i]['location']['lon']."');";

          

          $queryTwo = "CREATE TABLE IF NOT EXISTS $tableID (movie_ID varchar(8) primary key not null, movie_Title varchar(50) not null,
          movie_Syn varchar(500), movie_Rating varchar(4));";

  
  $stmt = $dbh->prepare($query);  
  $stmt->execute();
  $stmtTwo = $dbh->prepare($queryTwo);
  $stmtTwo->execute();
  
}catch(PDOException $e){
	die('PDO error inserting: ' . $e->getMessage());
}
    }


}
// Close request to clear up some resources
curl_close($ch);
echo "<br>";

////////////////
$counter = 0; //used to print cinemas names

foreach($cinemaID as $datum){
// Get cURL resource
$ch = curl_init();
// Set url
curl_setopt($ch, CURLOPT_URL, 'https://api.internationalshowtimes.com/v4/movies/?cinema_id=' . $datum);
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
  //var_dump($resp);
  echo '<br>';
  echo "<a href = \"./showtimes.php?cinemaID=$cinemaID[$counter]\" > $cinemaName[$counter]</a> \n";
  $movieData = json_decode($resp,true);
  //echo $cinemaName[$counter];
  echo "<br>";
  //echo $movieData{'meta_info'}['total_count'];
  
  $tableIDTwo = "cinema_" . $cinemaID[$counter];
  for($j = 0; $j < $movieData{'meta_info'}['total_count']; $j++){
    if($movieData{'movies'}[$j]['title'] !== NULL){

      //TODO
      //get additional info

 $movieNameForURL = $movieData{'movies'}[$j]['id'];
      

// Get cURL resource
$chInfo = curl_init();

// Set url
curl_setopt($chInfo, CURLOPT_URL, 'https://api.internationalshowtimes.com/v4/movies/' . $movieNameForURL  . '?fields=poster_image_thumbnail,synopsis,ratings');

// Set method
curl_setopt($chInfo, CURLOPT_CUSTOMREQUEST, 'GET');

// Set options
curl_setopt($chInfo, CURLOPT_RETURNTRANSFER, 1);

// Set headers
curl_setopt($chInfo, CURLOPT_HTTPHEADER, [
  "X-API-Key: KEY_GOES_HERE",
 ]
);


// Send the request & save response to $resp
$info = curl_exec($chInfo);

if(!$info) {
  die('Error: "' . curl_error($chInfo) . '" - Code: ' . curl_errno($chInfo));
} else {
  $info = json_decode($info, true);
  
}

// Close request to clear up some resources
curl_close($chInfo);

      /////////////////////////////////////////////////////////////////////////////////////////

    echo $movieData{'movies'}[$j]['title'];
    echo "<br>";

        try{

          //$query = "ALTER TABLE $cinemaID[$counter] ADD COLUMN '".$movieData{'movies'}[$j]['title']."' VARCHAR(50) NOT NULL";

          //echo $info['movie']['poster_image_thubnail'];
          //echo $info['movie']['synopsis'];
         //echo $info['movie']['ratings']['imdb']['value'];

          $query = "INSERT INTO $tableIDTwo(movie_ID, movie_Title, movie_Syn, movie_Rating) VALUES ('".$movieData{'movies'}[$j]['id']."', '".$movieData{'movies'}[$j]['title']."',
           '".$info['movie']['synopsis']."', '".$info['movie']['ratings']['imdb']['value']."');";

  $stmt = $dbh->prepare($query);  
  $stmt->execute();
  
}catch(PDOException $e){
	die('PDO error inserting: ' . $e->getMessage());
}
}


    }
    echo '<br>';
    $counter++;

}

// Close request to clear up some resources
curl_close($ch);
    

}//end of foreach




 ?>

 


</center> 
</table>
</tr>
</body>




    
</td>

</html>
