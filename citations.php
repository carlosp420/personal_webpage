<?php
header('Content-type: text/html; charset=utf8');

if( isset($_GET['doi']) ) {
	$doi = $_GET['doi'];
	$doi = preg_replace("/(http:\/)(\w)/", "\\1/\\2", $doi);
}
else {
	echo "Error: Need to enter doi number as argument";
	exit(0);
}

include_once("conf.php");


// -------------------------------------
// Do stuff


?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <link rel="SHORTCUT ICON" href="favicon.ico" />
<title>Citations for doi <?php echo $doi; ?></title>

    <!-- Le styles -->
    <link rel="stylesheet" href="<?php echo $base_url; ?>css/bootstrap.css">
    <link rel="stylesheet" href="<?php echo $base_url; ?>css/bootstrap-responsive.css">

    <!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
      <script src="jquery/html5shiv.js"></script>
    <![endif]-->

    <!-- Fav and touch icons -->
    <link rel="apple-touch-icon-precomposed" sizes="144x144" href="img/apple-touch-icon-144-precomposed.png">
    <link rel="apple-touch-icon-precomposed" sizes="114x114" href="img/apple-touch-icon-114-precomposed.png">
      <link rel="apple-touch-icon-precomposed" sizes="72x72" href="img/apple-touch-icon-72-precomposed.png">
                    <link rel="apple-touch-icon-precomposed" href="img/apple-touch-icon-57-precomposed.png">
                                   <link rel="shortcut icon" href="img/favicon.ico">

	<link href='http://fonts.googleapis.com/css?family=Open+Sans:400italic,700italic,400,700' rel='stylesheet' type='text/css'>

	<script src="<?php echo $base_url; ?>jquery/jquery.js" type="text/javascript"></script>

	<script>
	$(document).ready(function() {
		$("#footer").hide();
		// this is to get all citations of such doi
		$.getJSON("http://nymphalidae.utu.fi/api/webservice.php?callback=?",
			{
			doi: '<?php echo $doi; ?>'
			},
			function(data) {
				var output = "<ol>";
				if( data.records != "0" ) {
					$.each(data, function(i, value) {
						if( i != "records" ) {
							output += "<li>" + value + "</li>";
						}
					});
				}
				output += "</ol>";

				$("#citations").html(output);

				k = 0;
				$('li').hide().each(function(i){
					$(this).delay(i*100).fadeIn("slow");
					k += 1;
				});
				$("#footer").delay(k*100).fadeIn();
			}
		);


		// this is to metadata for our doi
		$.getJSON("http://nymphalidae.utu.fi/api/return_doi_metadata.php?callback=?",
			{
			doi: '<?php echo $doi; ?>'
			},
			function(data) {
				var output = "";
				if( data.records != "0" ) {
					$.each(data, function(i, value) {
						if( i != "records" ) {
							output += value;
						}
					});
				}

				$("#my_doi").html(output);
			}
		);


		
	});


	</script>


</head>
<body>


<div class="container-fluid">
    <div class="row-fluid">
        <div class="span9">
            <h1>Citations for:</h1>

            <div id="my_doi"></div>


            <span style="font-size: 0.8em">Taken from Google Scholar using <a href="http://bit.ly/SCegc2">https://github.com/carlosp420/google_scholar_parser <img src="<?php echo $base_url; ?>images/octocat.png" /></a> </span>

            <hr>

            <div id="citations"></div>

        </div>
        <div class="span3">
        </div>
    </div>

    <div id="footer">&nbsp;</div>

</div>


    <!-- Le javascript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
</body>
</html>
