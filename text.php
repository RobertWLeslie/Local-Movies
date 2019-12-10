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
    //decode url and call robs shit
    require_once('send_sms_function.php'); // This should be in the same directory as this file

    //?movieName=Black Christmas&theaterName=Regal UA Washington Township&theaterAddress=121 Tuckahoe Rd Sewell NJ&showTime=Fri, 2019-12-13 @ 00:30:00

    $movieName = $_GET['movieName'];
    $theaterName = $_GET['theaterName'];
    $theaterAddress = $_GET['theaterAddress'];
    $showTime = $_GET['showTime'];
	
	if (isset($_POST['phonNum'])){
		send_sms($_POST['phonNum'], $movieName, $theaterName, $theaterAddress, $showTime);
	}
	
    echo '<br>';
    echo '<br>';
    echo '<br>';
    echo $movieName;
    echo '<br>';
    echo $theaterName;
    echo '<br>';
    echo $theaterAddress;
    echo '<br>';
    echo $showTime;
	
	echo '<form action="" method="post">';
    echo 'Number: <input type="text" name="phonNum"><br><input type="submit">';
	echo '</form>';
	
   
?>
