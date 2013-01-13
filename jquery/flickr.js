$.getJSON("http://api.flickr.com/services/feeds/photos_public.gne?id=37256239@N03&lang=en-us&format=json&jsoncallback=?",
	function(data){
		$.each(data.items, function(i,item){
			var output = "";
			output += "<div class='title'><h4>From NSG Flickr account</h4></div>";
			output += "<a href='" + item.link + "' title='See in Flickr'>";
			output += "<img src='" + item.media.m + "' />";
			output += "</a>";
			$("#flickr").append(output);
			if ( i == 0 ) return false;
		});
	});
