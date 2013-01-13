<?php

$key = "40f181d15c560e9082b6f342672cdd85";
$secret = "1522b3e873a919d3";
$token = "";
$error_msg = array();

if( isset($_GET) && $_GET['frob'] != "") {
	include_once("Phlickr/Api.php");
	set_time_limit(0);

	$api = new Phlickr_Api($key, $secret);

	$new_frob = clean_string($_GET['frob']);
	$new_frob = $new_frob[0];

	// convert new frob to a token
	try {
		$token = $api->setAuthTokenFromFrob($new_frob);
	}
	catch(Exception $e) {
		//echo $e;
	}
}

if( $_POST['submit'] == "Get Token" ) {
	include_once("Phlickr/Api.php");
	set_time_limit(0);

	$api = new Phlickr_Api($key, $secret);

	try {
		$frob = $api->requestFrob();
	}
	catch(Exception $e) {
		if( $frob == "" ) {
			$error_msg[] = "There was an unexpected error, pleast try again";
		}
	}
	
	if( $frob != "" && $new_frob == "") {
		// got a frob
		// set permissions to write
		$perms = "write";
		$url = $api->buildAuthUrl($perms, $frob);

		header("location: $url");
	}
}



function clean_string($string) {
	$i = 0;
	if( (isset($string) && trim($string) != '') ) {
		$user_strings = array();
		$symbols = array(",",'"',"'","&","/","\\",";","=");
		#is number? then dont filter by symbols
		if( is_numeric($string) ) {
			array_push($user_strings, $string);
		}
		else { #not number, then clean by filtering symbols
			$id_subject = trim(str_replace($symbols, "", $string));
			$subject = explode(" ", $id_subject);
			foreach( $subject as $val ) {
				if( trim($val) != "" ) {
					$pattern = '/[a-öA-Ö0-9_\.\-]+/';
					preg_match($pattern, $val, $match);
					if( $i < 3 ) {
						array_push($user_strings, $match[0]);
					}
					$i++;
				}
			}
		}
		return $user_strings;
	}
}


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<title>VoSeq - Integration with Flickr	</title>
	
	<link rel="stylesheet" href="1.css" type="text/css" />

	<script type="text/javascript" src="http://localhost/VoSeq/includes/jquery.js"></script>
	
	<link rel="SHORTCUT ICON" href="http://localhost/VoSeq/favicon.ico" />
	<meta content="Gvim" name="GENERATOR" />
	<meta content="Carlos Pe&ntilde;a" name="author" />
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />


</head>
<body>

<div id="menu">
</div>

<div id="content_narrow"><table border="0" width="850px">	<tr>
	<td valign='top'>
		<h1>VoSeq - Hosting pictures in Flickr</h1>			

		<p>VoSeq hosts all the specimen photos in Flickr. If you have a free account you can host up to 200 photos. The Pro account allows you hosting unlimited number of photos for a yearly fee (25 USD).</p>
		<p>By having the voucher photos in Flickr, VoSeq makes it very easy to submit your photos to the <a href="http://www.eol.org">Encyclopedia of Life (EOL)</a>.</p>
		<p>This page helps you getting a <b>Token</b> key needed for integrating your VoSeq installation and Flickr.</p>
<?php
	if( $token != "" ) {
		echo "<img src='images/success.png' /> <h1>Success! </h1>";

		echo "In your VoSeq installation, you need you edit your file <b><code>conf.php</code></b> with a text editor software and write your Flickr keys where indicated:";
		
		echo "<ul>
				<li><code>\$flickr_api_key = \"$key\";</code></li>
				<li><code>\$flickr_api_secret = \"$secret\";</code></li>
				<li><code>\$flickr_api_token = \"$token\";</code></li>
			  </ul>";

		echo "<p>Thus, you will be able to upload pictures to your account in Flickr from your VoSeq installation.</p>"; 

		echo "<p>Note! You can share your voucher photos with the Encyclopedia or Life. <a href=\"http://nymphalidae.utu.fi/cpena/VoSeq_docu.html\">See here</a></p>";
	}
	else {
?>

		<p>First of all, you need to create and account in Flickr: <a href="http://www.flickr.com/" target="_blank">Flickr.com</a></p>

		<p>Then click on "Get Token" to start the process. You will be redirected to Flickr and <b>you should authorize</b> VoSeq to upload your photos.</p>

		<form action='index.php' method='post'>
			<input type='submit' name='submit' value='Get Token' />
		</form>

<?php 
		}
?>
			</td>		<td class='sidebar' valign='top'><img src="images/logo-small.jpg" alt="VoSeq database" class="logo" />

		 <h1>Powered by:</h1>
		 <div class="submenu">
			<a href="http://httpd.apache.org"><img src="images/apache.png" alt="Apache" class="link" /></a>
			<a href="http://www.php.net"><img src="images/php.png" alt="PHP" class="link" /></a>
			<a href="http://www.mysql.com"><img src="images/mysql.png" alt="MySQL" class="link" /></a>
			<a href="http://www.ubuntu.com"><img src="images/ubuntu.png" alt="Ubuntu" class="link"></a>
			<a href="http://dojotoolkit.org"><img src="images/dojo.png" alt="Dojo toolkit" class="link"></a>
		 </div>		</td>
		</tr>
	</table>
</div><!-- standard page footer begins -->

<div id="footer_admin">2012 VoSeq </div>
</body>
</html>
