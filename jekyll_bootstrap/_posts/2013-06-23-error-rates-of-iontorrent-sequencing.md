---
layout: post
title: "Error rates of IonTorrent sequencing"
description: ""
category: "bioinformatics"
excerpt: "As I process the data sequences from our in-house IonTorrent Next Generation Sequencer, I can't stop noticing quite many sequencing errors in my
    reads. There are a number of gaps, insertions and mismatches in the index 
    region (and primer region as well). I would expect many of these errors in..."
tags: [Next Generation Sequencing, Ion Torrent, bioinformatics]
---
{% include JB/setup %}

As I process the data sequences from our in-house IonTorrent Next 
Generation Sequencer, I can't stop noticing quite many sequencing errors in my
reads. There are a number of gaps, insertions and mismatches in the index 
region (and primer region as well). I would expect many of these errors in the
primer region, but not in the index region. Not so many anyway.
It may be interesting to run our libraries in Illumina to compare the error 
rate in both sequencing technologies.


A recent paper by Golan & Medvedev [-@golan2013] has made discouraging statements
about the error rate of sequences produced by the IonTorrent:

> Despite its advantages, Ion Torrent read accuracy remains a significant concern

According to Golan & Medvedev [-@golan2013], the base-calling process of the IonTorrent 
is very simple. It involves rounding the measurements of changes in electricity as
nucleotides are incorporated during the sequencing cycles. Which is very prone 
to errors as distinguishing electricity changes becomes difficult due to higher
noise levels towards the end of DNA fragments.

They describe a piece of software that can be used to reanalyze
the raw data of the sequencing process (from SFF files). They proposed to 
combine the information on the bases that are incorporated during the sequencing
cycles (flows) to better infer the right nucleotide that gets incorporated 
in the regions close to the 3' end.
They report improvements in the base-calling  results from 4 to 21%.

Now I have to go
back to the IonTorrent server test whether this software improves my reads.



### References

