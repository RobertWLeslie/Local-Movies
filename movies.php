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
    </head>


<body bgcolor="#eceff1">

      <center>   
		  
	  <?php

	$curl = curl_init();
	
	$movie_db_key = urlencode("KEY_GOES_HERE");
	$movie_query = urlencode($_POST['query']);
	echo $movie_query . "\n";
	curl_setopt_array(
		$curl,
		array(
		  CURLOPT_URL => "https://api.themoviedb.org/3/search/movie?page=1&query=".$movie_query."&api_key=".$movie_db_key,
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_ENCODING => "",
		  CURLOPT_MAXREDIRS => 10,
		  CURLOPT_TIMEOUT => 30,
		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  CURLOPT_CUSTOMREQUEST => "GET",
		  CURLOPT_POSTFIELDS => "{}",
		)
	);

	$response = curl_exec($curl);
	$err = curl_error($curl);
	$response_array = json_decode($response,true);

	curl_close($curl);

	//Here we need to submit the desired movie to the DB and then go to the info page that pulls info from db about the movie
	foreach ($response_array['results'] as $movie) {
        
		echo "<br>";
		foreach ( $movie as $key => $value ) {
			if($key == 'title'){

				echo "<a href = \"./info.php?movieID=$value\" > $value</a> \n";

				//echo "$value";
			}
		  }

		echo "<br>";
		echo "<hr>";
	  
	  }

	if ($err) {
	  echo "cURL Error #:" . $err;
	} else {
	  //echo "<pre>";
	  //echo var_dump($response_array["results"]);
	}
?>


</center> 
</table>
</tr>
</body>




    
</td>

</html>