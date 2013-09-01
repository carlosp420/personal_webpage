---
layout: post
title: "Integration with EOL"
excerpt: "Our database now asks [EOL](http://eol.org/) for author and year of description for species names. It is using [EOL's search API](http://eol.org/info/technology) to pull the authority and"
description: "Integration of VoSeq with EOL"
category: 
tags: [API, Encyclopedia of life, EOL, web services]
---
{% include JB/setup %}

Our database now asks [EOL](http://eol.org/) for author and year of description
for species names. It is using 
[EOL's search API](http://eol.org/info/technology) to pull the authority and
link to the corresponding species page in EOL. If there is a positive response
from EOL the authority and link will appear under the 
[voucher code](http://nymphalidae.utu.fi/story.php?code=BB28):


![ ](/cpena/blog/assets/figs/authority_from_eol.png)

EOL gets the authority information from several sources (including Ubio and GenBank). However their taxonomy is far from complete and needs urgent updates.
