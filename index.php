<?php
include_once("conf.php");
require("lib.php");

// open database connections
$connection = mysql_connect($host, $user, $pass) or die('Unable to connect');
mysql_select_db($db) or die ('Unable to select database');
mysql_query("set names utf8") or die("Error in query: $query. " . mysql_error());

$query = "SELECT * FROM cpena_articles WHERE type='fixed' ORDER BY timestamp ASC LIMIT 0, 9";
$result = mysql_query($query) or die("Error in query: $query. " . mysql_error());
	
$articles = array();

// if records present
if( mysql_num_rows($result) > 0 ) {
	while( $row = mysql_fetch_object($result) ) {
		$articles[] = array(
						"title" => $row->title,
						"body"  => $row->body,
						"link"  => $row->link,
						"image" => $row->image,
						"type"  => $row->type
						);
	}
}

// look for regular articles
$query = "SELECT * FROM cpena_articles WHERE type != 'fixed' ORDER BY timestamp DESC LIMIT 0, 9";
$result = mysql_query($query) or die("Error in query: $query. " . mysql_error());
// if records present
if( mysql_num_rows($result) > 0 ) {
	while( $row = mysql_fetch_object($result) ) {
		$articles[] = array(
						"title" => $row->title,
						"body"  => $row->body,
						"link"  => $row->link,
						"image" => $row->image,
						"type"  => $row->type
						);
	}
}


// look for publications
$query =  "SELECT url FROM publications WHERE ";
$query .= "authors like '%Pena, C%' OR ";
$query .= "authors like '%Peña, C%' ";
$query .= "ORDER by timestamp DESC limit 0, 4";
$result = mysql_query($query) or die("Error in query: $query. " . mysql_error());
// if records present
$dois = array();
if( mysql_num_rows($result) > 0 ) {
	while( $row = mysql_fetch_object($result) ) {
			if( preg_match("/^10\./", $row->url) ) {
				$dois[] = $row->url;
			}
//		$url = "http://nymphalidae.utu.fi/api/return_doi_metadata.php?format=string&amp;doi=" . $doi;

//		$body = get_from_URL($url);

//		$link = "http://dx.doi.org/" . $doi;
//		$body = preg_replace('/doi:.+/', '', $body);

//		$articles[] = array(
//						"title" => "Publication",
//						"body"  => $body,
//						"link"  => $link,
//						"doi"   => $doi,
//						"type"  => "publication"
//						);
	}
}


// call posts from blogger
$url = "http://ajax.googleapis.com/ajax/services/feed/load?v=1.0&num=3&q=http://feeds.feedburner.com/NsgsDatabasesBlog&key=";
$url .= $blogger_key;
$posts = json_decode(get_from_URL($url));

$from_blogger = array();
foreach($posts->responseData->feed->entries as $entry) {
	$dom = new DOMDocument('1.0', 'iso-8859-1');
	$dom->loadHTML($entry->content);
	
	$imgs = $dom->getElementsByTagName("img");
	foreach($imgs as $img) {
		$my_img = array();
		foreach( $img->attributes as $k=>$v) {
			if( $k == "width" ) {
				$my_img["width"] = $v->value;
			}
			if( $k == "height" ) {
				$my_img["height"] = $v->value;
			}
			if( $k == "src" ) {
				$my_img["source"] = $v->value;
			}
		}
		break;
	}
		

	$from_blogger[] = array("title" => $entry->title, 
						    "content" => $entry->contentSnippet,
							"link" => $entry->link,
							"image" => $my_img
							);
}
#print_r($entry);
#print_r($from_blogger);
#print_r($articles);



### calls for twitter
require("tmhOAuth/tmhOAuth.php");
require("tmhOAuth/tmhUtilities.php");
$tmhOAuth = new tmhOAuth(array(
			'consumer_key' => $twitter_consumer_key,
			'consumer_secret' => $twitter_consumer_secret,
			'user_token' => $twitter_access_token,
			'user_secret' => $twitter_access_token_secret
			));
$code_request = $tmhOAuth->request('GET', 'https://api.twitter.com/1.1/statuses/user_timeline.json', array("count" => "3"));
if( $code_request == 200 ) {
	//echo $tmhOAuth->response['response'];
	$twitter = $tmhOAuth->response['response'];
}
else {
	$twitter = "FALSE";
}

$twitter_output = array();
if( $twitter != "FALSE" ) {
	$twitter = json_decode($twitter);
	foreach($twitter as $twit) {
		$text = $twit->text;
		$match = array();
		preg_match("/(http[s|:]\S+)\s?/i", $text, $match);
		if( count($match) > 1 ) {
			$text = str_replace($match[1], "<a href='$match[1]'>$match[1]</a>", $text);
		}

		$output = "";
		$output .= "<div class='item'>";
		$output .= "<span class='position'>4</span>";
		$output .= "<div class='body'>";
		$output .= "<img src='images/twitter.png' alt='Twitter' height='48px' />";
		$output .= $text;
		$output .= "</div>";
		$output .= "<div class='foot'>";
		$output .= "<a href='http://twitter.com/carlosp420/status/" . $twit->id_str . "'>";
		$output .= "view</a> | ";
		$output .= " <a href='http://twitter.com/intent/tweet?in_reply_to=";
		$output .= $twit->id_str . "'>reply</a> | ";
		$output .= " <a href='http://twitter.com/intent/retweet?tweet_id=";
		$output .= $twit->id_str . "'>retweet</a> | ";
		$output .= " <a href='http://twitter.com/intent/favorite?tweet_id=";
		$output .= $twit->id_str . "'>favorite</a>";
		$output .= "</div>";
		$output .= "</div>";
		$twitter_output[] = $output;
	}
}


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta content="gvim" name="GENERATOR" />
    <meta name="keywords" lang="en" content="department of genetics, butterflies,butterfly,evolution,phylogeny,Satyrinae,Neotropics,Nymphalidae,DNA, sequences, database" />
    <meta name="keywords" lang="es" content="mariposas, mariposa, evolucion, filogenia, Satyrinae, Neotropico, Nymphalidae" />
    <meta content="Carlos Pe&ntilde;a's webpage on research on butterflies and moths" name="description" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="Carlos Pe&#241;a Bieberach" name="author" />
    <meta content="global" name="distribution" />
    <meta name="REVISIT-AFTER" content="7 days" />
    <title>Dr. Carlos Peña - Evolutionary history of butterflies</title>

	<link rel="stylesheet" type="text/css" href="css/bootstrap_index.css" />
	<link rel="stylesheet" type="text/css" href="default.css" />
	<link href='http://fonts.googleapis.com/css?family=Open+Sans:400italic,700italic,400,700' rel='stylesheet' type='text/css'>
	
	<link rel="stylesheet" type="text/css" href="css/bootstrap-responsive.css" />
	<script src="jquery/jquery.js"></script>
	<script src="jquery/jquery.isotope.min.js"></script>
	<script src="jquery/altmetric.js"></script>
	<script src="jquery/citations.js"></script>
	<script src="jquery/flickr.js"></script>
	<script src="jquery/publications.js"></script>

<script type="text/javascript">
$(document).ready(function() {
	$("#drop li").hover(
		function() {
			$("ul", this).slideDown(100);
		},
		function() {
			$("ul", this).slideUp(100);
		}
	);
});
</script>

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


<script type="text/javascript">
  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-33283726-1']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();
</script>


</head>
<body>

<div class="navbar navbar-fixed-top">
    <div class="navbar-inner">
        <div class="container">
            <button type="button" class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="brand" href="#">
                <div itemscope itemtype="http://www.schema.org/Person">
                <span itemprop="honorificPrefix">Dr</span>
                <span itemprop="name"><span itemprop="givenName">Carlos</span>
                <span itemprop="familyName">Peña</span></a>
                </div>
            <div class="nav-collapse collapse">
                <ul class="nav">
			        <li class="active"><a href="index.php">Home</a></li>
			        <li><a href="publications.html">Publications</a></li>
                        <li class="dropdown">
                            <a href="software.html" class="dropdown-toggle" 
                                                              data-toggle="dropdown">Software <b class="caret"></b></a>
                            <ul class="dropdown-menu">
                                <li><a href="software.html#google_scholar_parser">Google Scholar citations</a></li>
                                <li><a href="software.html#voseq">Voseq</a></li>
                                <li><a href="software.html#pbs">Partitioned Bremer Support</a></li>
                            </ul>
                        </li>

                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">Research <b class="caret"></b></a>
				        <ul class="dropdown-menu">
				            <li><a href="Satyrinae_phylogeny.html">Satyrinae phylogeny</a></li>
				            <li><a href="Forsterinaria.html"><i>Forsterinaria</i></a></li>
				            <li><a href="euptychiina_references.html">Euptychiina references</a></li>
				            <li><a href="lamas_references.html">Lamas references</a></li>
				        </ul>
			        </li>
			        <li><a href="http://nymphalidae.utu.fi/Vouchers.htm">Voucher's db</a></li>
			        <li><a href="http://www.nymphalidae.net/taxon_db/">Taxon db</a></li>
			        <li><a href="http://nsg-databases.blogspot.com">Blog</a></li>
                </ul>
                <a href='#filter=showAll' id="showAll" style="DISPLAY:none" data-filter='*' class='filters'>show all</a>
            </div>
        </div>
    </div>
</div>



            
            


	
	
	<div id="content">
		<div id="container">

		

<?php

// do tweets
foreach( $twitter_output as $twit ) {
	echo $twit;
}

// do posts from blogger
foreach( $from_blogger as $item) {
	echo '<div class="item blogPost">';

	echo "<span class='position'>5</span>";

	echo '<div class="title">';
	echo '<h4><a href="'. $item['link'] .'">'. $item['title'] . '</a></h4>';
	echo '</div>';

	echo '<div class="body">';
	echo $item['content'];
	echo '<a href="'. $item['link'] .'"><img width="250px" src="' . $item['image']['source'] . '" /></a>';
	echo '</div>';

	echo '<div class="foot">';
	echo "<a href='#filter=blogPost' data-filter='.blogPost' class='filters'><img src='images/blogger.png' alt='Filter blogPost' ";
	echo "title='Filter blog posts' /></a>";
	echo '</div>';

	echo '</div>';
}

foreach( $articles as $article ) {
	echo '<div class="item">';

	if( isset($article['title']) ) {
		if( $article['title'] != "profile_photo" ) {
			echo "\n<div class='title'>";
			echo "<h4>" . $article['title']. "</h4>";
			echo "\n</div>\n";
		}
		elseif( $article['title'] == "profile_photo" ) {
			echo "\n<span class='position'>1</span>";

		}
	}
	if( isset($article['body']) ) {
		echo "\n<div class='body'>";
		echo $article['body'];
		echo "\n</div>\n";
	}
	if( isset($article['image']) ) {
		echo "<img src='". $article['image']. "' width='96px' height='96px' alt='Carlos Pena' />";
	}
	if( isset($article['type']) ) {
		if( $article['type'] == "fixed" && $article['title'] != "profile_photo" ) {
			echo "<span class='position'>2</span>";
		}
	}
	if( isset($article['timestamp']) ) {
		echo $article['timestamp'];
	}
	echo "</div>\n\n";
}

foreach( $dois as $doi ) {
	echo "<div class='item publication'>";

	echo "<span class='position'>3</span>";

	echo "<div class='title'>";
	echo "<h4>Publication</h4>";
	echo "</div>";

	echo "<div class='foot'>";
	echo "<a href='#filter=publication' data-filter='.publication' class='filters'><img src='images/kpdf.png' alt='Filter publications' ";
	echo "title='Filter publications' /></a>";

	echo " <a href='http://dx.doi.org/". $doi . "' title='DOI'><b>doi></b></a> ";
	echo "</div>";

	echo "</div>";
}

## get lastfm RSS
//$lastfm = get_from_URL("http://ws.audioscrobbler.com/1.0/user/carlosp420/recenttracks.rss");
//$xml = simplexml_load_string($lastfm);
//$output = "";
//
//$output .= "<ul>";
//$i = 0;
//foreach($xml->channel->item as $item) {
	//$output .= "<li><a href='". $item->link . "'>" . $item->title . "</a></li>";
	//$i++;
	//if( $i > 4 ) {
		//break;
	//}
//}
//$output .= "</ul>";
//echo '<div class="item">';

//echo "<span class='position'>5</span>";

//echo '<div class="title">';
//echo '<h4>Listening on <a href="http://last.fm/user/carlosp420">last.fm</a></h4>';
//echo '</div>';

//echo '<div class="body">';
//echo $output;
//echo '</div>';

//echo '<div class="foot">';
//echo '<img src="images/lastfm.gif" />';
//echo '</div>';
//echo '</div>';
## end lastfm RSS

?>
          
	
<script>
		altmetric();
		publications();
</script>


			<div class="item">
				<span class="position">5</span>
				<div id="flickr">
				</div>
			</div>
		

			<div class="item">
				<span class="position">5</span>
				<div class="title">
					<h4>Coding activity on GitHub</h4>
				</div>

				<div class="body">
					<?php
					$github_json = json_decode(get_from_URL("https://api.github.com/users/carlosp420/events"));
					$output_gh = "<ul>";
					$i = 0;
					foreach($github_json as $event) {
						$event_type = trim($event->type);
						if($event_type == "PushEvent") {
							$output_gh .= "<li>carlosp420 pushed to ";
						}
						elseif($event_type == "CreateEvent") {
							$output_gh .= "<li>carlosp420 created ";
						}
						elseif($event_type == "DownloadEvent") {
							$output_gh .= "<li>carlosp420 downloaded ";
						}
						elseif($event_type == "DeleteEvent") {
							$output_gh .= "<li>carlosp420 deleted ";
						}
						preg_match("/refs\/heads\/(.+)/", trim($event->payload->ref), $match);
						if( count($match) > 0) {
							if( $match[1] ) {
								$output_gh .= $match[1];
							}
						}
						$output_gh .= " at <i><a href='https://github.com/" . $event->repo->name;
						$output_gh .= "'>" . str_replace("carlosp420/", "", $event->repo->name) . "</a></i>";
						if( property_exists($event, "commits") ) {
							$output_gh .= ": <i>'".  $event->payload->commits[0]->message . "'</i>";
						}
						preg_match("/\d{4}-\d{1,2}-\d{1,2}/", $event->created_at, $match2);
						if( $match2[0] ) {
							$output_gh .= " on ". $match2[0] . ".";
						}
	
	
						$output_gh .= "</li>";
						$i = $i + 1;
						if($i > 3) {
							break;
						}
					}
					$output_gh .= "</ul>";
					echo $output_gh;
					?>
				</div>
					
				<div class="foot">
					<a href="http://github.com/"><img src="images/octocat.png" /></a>
				</div>
			</div>
	
		</div><!-- end container -->
	</div>

	<div id="footer">
			<div class="left">Using the following APIs:  
				<a href="http://altmetric.com/"><img src="images/altmetric.png" height="24px" /></a>
				<a href="http://twitter.com/"><img src="images/twitter2.png" height="24px" /></a>
				<a href="http://www.flickr.com/"><img src="images/flickr.png" height="26px" /></a>
				<a href="http://crossref.org/"><img src="images/crossref.gif" height="23px" /></a>
			</div>
			<div class="right">
				"Inspired by" <a href="http://perlsteinlab.com/">perlsteinlab.com/</a>
			</div>
	</div>
	
</div> <!-- end microdata -->
</div> <!-- end outer -->



<script>
$(document).ready(function() {

	$(window).load(function() {


		$('.filters').click(function() {
			var which_filter = $(this).attr('id');
			if( which_filter != "" && which_filter != "showAll" ) {
				$('#showAll').show()
					// first jump  
					.animate({top:'32px'}, 200).animate({top:'4px'}, 200)
					// second jump
					.animate({top:'16px'}, 100).animate({top:'4px'}, 100)
					// third jump
					.animate({top:'8px'}, 100).animate({top:'4px'}, 100)
					// the last jump
					.animate({top:'4px'}, 100).animate({top:'4px'}, 100);
			}
		});

		$('#showAll').click(function() {
			$('#showAll').slideUp('200');
		});

		//-------	
		var $container = $('#container');

		$container.isotope({
			getSortData: {
				position: function ( $elem ) {
					return parseInt( $elem.find('.position').text(), 10 );
				}
			}
		});

		$container.fadeIn('slow');

		$container.isotope({
			sortBy: 'position',
			sortAsdending: true,
			animationEngine: 'best-available',
			itemSelector: '.item',
			layoutMode: 'masonry'
		});


		//filters
		$('.filters').click(function(){
			var selector = $(this).attr('data-filter');
			$container.isotope({ filter: selector });
			return false;
			});

	});
});
</script>



    <!-- Le javascript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="jquery/google-analytics.js"></script>

    <script src="jquery/bootstrap.min.js"></script>

</body>
</html>
