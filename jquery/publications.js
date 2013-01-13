function publications() {
	$('a[title="DOI"]').each(function() {
		var doi_url = $(this).attr("href");
		doi = doi_url.replace("http://dx.doi.org/", "");
		
		var url = "http://nymphalidae.utu.fi/api/return_doi_metadata.php?";
		url += "format=json";
		url += "&doi=" + doi;
		url += "&callback=?";
		
		$.getJSON(url, function(data) {
			var body = data[1];
			body = body.replace(/doi.+/, "");
			
			var output = "";
			output += "<div class='body'>";
			output += body;
			output += "</div>";
			
			$('a[href="' + doi_url + '"]').parent().before(output);
	
		});
		
	});
}