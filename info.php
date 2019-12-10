<head>
<title>LocalMovies</title>
<meta charset="utf-8" />
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
      <a href="movies.php" class="w3-bar-item w3-button">Movies</a>
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
      <center><br><br><br>

<?php
	#echo "<p>" . $_GET["movieID"] . "</p><br>";


	$movie_query = urlencode($_GET["movieID"]);

	#echo "<p>". $movie_query ."</p><br>";

	$curl = curl_init();

	$movie_db_key = urlencode("KEY_GOES_HERE");

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
	
	$response_array = json_decode($response, true);

	curl_close($curl);

	if($err){
		echo "cURL Error #:". $err;
	} else {
		#var_dump($response_array["results"][0]);
		#echo "<h1>" . $_SERVER['REMOTE_ADDR'] . "<h1><br>";
		$movie_info = $response_array["results"][0];
		echo "<h1>" .$movie_info["original_title"]."</h1><br>";
		echo "<p>" . $movie_info["overview"] . "</p><br>";
		echo "<p> Released: " . $movie_info["release_date"] . "</p><br>";

		echo "<img src=\"https://image.tmdb.org/t/p/w500".$movie_info["poster_path"]."\" alt=\"".$movie_info["original_title"]. "poster \">";
	}

?>
