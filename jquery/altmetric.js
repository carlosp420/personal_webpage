function altmetric() {
	$('a[title="DOI"]').each(function() {
		var doi_url = $(this).attr("href");
		doi = doi_url.replace("http://dx.doi.org/", "");

		var url = "http://api.altmetric.com/v1/doi/";
		url += doi + "?callback=?";

		window.setTimeout(function() {
			$.getJSON(url, function(data) {
				if( data.images.small != "" ) {
					var output = "";
					output += "<a href='";
					output += data.details_url;
					output += "' title='Altmetric'>";
					output += "<img src='";
					output += data.images.small;
					output += "' alt='Altmetric' height='38px' />";
					output += "</a>";


					$('a[href="' + doi_url + '"]').after(output);
					$('img[alt="Altmetric"]').fadeIn('slow');
				}
				});
		}, 1000);

	});
}
