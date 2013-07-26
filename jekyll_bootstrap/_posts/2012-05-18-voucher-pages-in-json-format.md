---
layout: post
title: "Voucher pages in JSON format"
description: "VoSeq dabase API for JSON format"
excerpt: "I made a quick addition to our public [NSG database](http://nymphalidae.utu.fi/db.php). Voucher pages will output the specimen's data in **JSON format** for easy and automated harvesting of our data."
category: 
tags: [API, JSON, JSONP, nymphalidae, voucher database]
---
{% include JB/setup %}

I made a quick addition to our public [NSG database](http://nymphalidae.utu.fi/db.php).
Voucher pages will output the specimen's data in **JSON format** for easy and automated
harvesting of our data.

[JSON](http://en.wikipedia.org/wiki/JSON) is becoming a commonly used format for transfer
of data over the Internet because it can be easily integrated into Javascript. Nowadays,
there are even database systems that keep all data in JSON format (e.g. 
[couchdb](http://couchdb.apache.org/), [mongodb](http://www.mongodb.org/), etc).

This is how it works in our database:

If you go to this voucher page:
[http://nymphalidae.utu.fi/story.php?code=NW85-8](http://nymphalidae.utu.fi/story.php?code=NW85-8)
you will see an interface created for humans:

![VoSeq figure](/cpena/blog/assets/figs/nw71-1.png)

If you add to the URL the option **&format=json** [http://nymphalidae.utu.fi/story.php?code=NW85-8&format=json](http://nymphalidae.utu.fi/story.php?code=NW85-8&format=json),
you will get all the data in JSON format:

{% highlight json %}
{
  "institutionCode": "NSG",
  "catalogNumber": "NW85-8",
  "voucher_code": "NW85-8",
  "recordNumber": "NW85-8",
  "family": "Hesperiidae",
  "subfamily": "",
  "tribe": "",
  "subtribe": "",
  "genus": "Achlyodes",
  "specificEpithet": "busiris",
  "infraspecificEpithet": "",
  "country": "PERU",
  "locality": "Km 28, road to Yurimaguas",
  "decimalLatitude": "-6.412590",
  "decimalLongitude": "-76.315900",
  "verbatimElevation": "750m",
  "collector": "St\u00e9phanie Gallusser",
  "eventDate": "2001-11-02",
  "voucherLocality": "NSG coll.",
  "sex": "",
  "voucherImage": "http:\/\/flickr.com\/photos\/37256239@N03\/3429238255\/",
  "associatedSequences": "GQ864726;GQ864820;GQ864414;GQ865378;GQ865050;GQ864915;GQ864593;GQ864507;GQ865158;GQ865279"
}
{% endhighlight json %}
`

If you use the function getJSON of jQuery to call this web service, you will 
need to use the field **jsoncallback=?** or **callback=?** and, to avoid 
confusions, the field **format=jsonp**.

Example: Calls the NSG database for data about specimen code **NW85-8** using
jQuery's function [getJSON](http://api.jquery.com/jQuery.getJSON/).

{% highlight html %}
<!DOCTYPE html>
<html lang='en' xml:lang='en' xmlns='http://www.w3.org/1999/xhtml'>
<head>
<title>test
</title>
<script src="http://code.jquery.com/jquery-latest.js"></script>


</head>
<body>

<script>
$(document).ready(function() {
       $.getJSON("http://nymphalidae.utu.fi/story.php?callback=?",
            {
                code: "NW85-8",
                format: "jsonp"
            },
        function(data) {
            var output = "";
            output += "voucher code: " + data.voucher_code + "<br />";
            output += "genus: " + data.genus + "<br />";
            output += "species: " + data.specificEpithet + "<br />";

            $("div").html(output);
        });
});

</script>

<div></div>
</body>
</html>
{% endhighlight %}

And the output will be:

![NW85-8](/cpena/blog/assets/figs/nw85-8.png)

