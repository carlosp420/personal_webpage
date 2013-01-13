// this is the webservice file for citations count for articles
// data is taken from local database
// Carlos Peña, June 2012
//

$(document).ready(function(){
	// it looks for the * <a href= * tag with attribute that begins with 
	// href="http://dx.doi.org"

	// for example:  <a href="http://dx.doi.org/my_doi_number></a>
	// 
	// <a href="http://www.mapress.com/zootaxa/2011/f/z02906p051f.pdf"></a>
	
	var dois = $('a[href^="http://dx.doi.org"]');
	
	//var zootaxa = $('a[href^="http://www.mapress.com/zootaxa"]');
	//$('a[href^="http://dx.doi.org"]').after('Index: ' + $('a').index(dois));

	$('a[href^="http://dx.doi.org"]').each(function(i, val) {
			var doi = $(this).attr('href');
			doi = doi.replace("http://dx.doi.org/", "");
			//alert(doi);

			$.getJSON("http://nymphalidae.utu.fi/api/webservice.php?callback=?",
				{
				doi: doi
				},
				function(data) {
					var output = "";
					if( data.records != "0" ) {
						output += " | <a style='font-weight: bold;color: red;' href='http://nymphalidae.utu.fi/cpena/citations/";
						output += doi + "' title='See citations'>Cited by ";
						output += data.records;
						output += "</a>";
					}
					$('a[href^="http://dx.doi.org/' + doi + '"]').after(output);
				});
		}
	);

	
	$('a[href^="http://www.mapress.com/zootaxa"]').each(function(i, val) {
			var url = $(this).attr('href');
			//alert(url);

			$.getJSON("http://nymphalidae.utu.fi/api/webservice.php?callback=?",
				{
				doi: url
				},
				function(data) {
					var output = "";
					if( data.records != "0" ) {
						output += " | <a style='font-weight: bold;color: red;' href='http://nymphalidae.utu.fi/cpena/citations/";
						output += url + "' title='See citations'>Cited by ";
						output += data.records;
						output += "</a>";
					}
					$('a[href^="' + url + '"]').after(output);
				});
		}
	);

	
	$('a[href^="http://nymphalidae.utu.fi"]').each(function(i, val) {
			var url = $(this).attr('href');
			//alert(url);

			$.getJSON("http://nymphalidae.utu.fi/api/webservice.php?callback=?",
				{
				doi: url
				},
				function(data) {
					var output = "";
					if( data.records != "0" ) {
						output += " | <a style='font-weight: bold;color: red;' href='http://nymphalidae.utu.fi/cpena/citations/";
						output += url + "' title='See citations'>Cited by ";
						output += data.records;
						output += "</a>";
					}
					$('a[href^="' + url + '"]').after(output);
				});
		}
	);

	//http://www.eje.cz/pdfarticles/1274/eje_104_4_675_Wahlberg.pdf
	$('a[href^="http://www.eje.cz"]').each(function(i, val) {
			var url = $(this).attr('href');
			//alert(url);

			$.getJSON("http://nymphalidae.utu.fi/api/webservice.php?callback=?",
				{
				doi: url
				},
				function(data) {
					var output = "";
					if( data.records != "0" ) {
						output += " | <a style='font-weight: bold;color: red;' href='http://nymphalidae.utu.fi/cpena/citations/";
						output += url + "' title='See citations'>Cited by ";
						output += data.records;
						output += "</a>";
					}
					$('a[href^="' + url + '"]').after(output);
				});
		}
	);

});
