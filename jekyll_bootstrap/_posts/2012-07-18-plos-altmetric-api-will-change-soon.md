---
layout: post
title: "PLoS Altmetric API will change soon"
description: "Altmetric statistics from PLOS publisher"
category: "Altmetrics"
excerpt: "Just got an email from PLoS ALM Team saying that they are updating their API for their Article Level Metrics (ALM; Altmetric) tools."
tags: [Altmetric, API, Application programming interface, PLoS, PLoS ONE, Twitter, VoSeq]
---
{% include JB/setup %}

Just got an email from PLoS ALM Team saying that they are updating their API 
for their Article Level Metrics (ALM; Altmetric) tools.

The Almetric software shows "citation" data on scientific papers harvested from
social networks such as Twitter, Scientific Blogs, Citeulike and Mendeley. They
deliver this content via their very easy API.

You could also visit their website <http://altmetric.com/> and enter a DOI number
for your favorite paper and see how many citations from social network it has.
Also you can see the "hot" papers that have the most number of citations so are
the one that "everybody" is reading right now.

I am using the altmetric API for <a href="http://nymphalidae.utu.fi/cpena/">my website</a>
and noticed that there seems to be a mix up in the data that was harvested for 
one of our recently published papers:


> Peña C, Malm T (2012) VoSeq: A Voucher and DNA Sequence Web Application. PLoS ONE 7: e39071. doi:<a href="http://dx.doi.org/10.1371/journal.pone.0039071">10.1371/journal.pone.0039071</a>


For some reason Altmetric started to collect data from their announcement when
their released Altmetric:

{% image /cpena/blog/assets/figs/altmetric_VoSeq.png %}

You can see the Altmetric API and changes in their [github profile](https://github.com/articlemetrics/alm/wiki/API).
