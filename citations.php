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
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<script src="<?php echo $base_url; ?>jquery/jquery.js" type="text/javascript"></script>
	<link href="<?php echo $base_url; ?>minimal.css" rel="stylesheet" type="text/css" media="screen" />
	<link href='http://fonts.googleapis.com/css?family=Open+Sans:400italic,700italic,400,700' rel='stylesheet' type='text/css'>

	<title>Citations for doi <?php echo $doi; ?></title>

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


<div id="content">
<h1>Citations for:</h1>


<div id="my_doi"></div>


<span style="font-size: 0.8em">Taken from Google Scholar using <a href="http://bit.ly/SCegc2">https://github.com/carlosp420/google_scholar_parser <img src="<?php echo $base_url; ?>images/octocat.png" /></a> </span>

<div id="citations"></div>

</div>


<div id="footer">&nbsp;</div>

</body>
</html>
