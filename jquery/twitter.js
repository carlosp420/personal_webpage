// get last two twitts
function twitter() {
var url = "http://api.twitter.com/1/statuses/user_timeline.json?callback=?";

$.getJSON(url,
		{
		screen_name: 'carlosp420',
		count: '3'
		},
		function(data) {
			$.each(data, function(i,item) {
				var id_str = item.id_str;
				var output = "<div class='item'>";
				output += "<span class='position'>4</span>";
				output += "<div class='body'>";
				output += "<img src='images/twitter.png' alt='Twitter' height='48px' />";
				output += item.text;
				output += "</div>";
				output += "<div class='foot'>";
				output += "<a href='http://twitter.com/carlosp420/status/" + id_str + "'>";
				output += "view</a> | ";
				output += " <a href='http://twitter.com/intent/tweet?in_reply_to=";
				output += id_str + "'>reply</a> | ";
				output += " <a href='http://twitter.com/intent/retweet?tweet_id=";
				output += id_str + "'>retweet</a> | ";
				output += " <a href='http://twitter.com/intent/favorite?tweet_id=";
				output += id_str + "'>favorite</a>";
				output += "</div>";
				output += "</div>";

				$('#container').append(output);
			});

		}
	);
}
