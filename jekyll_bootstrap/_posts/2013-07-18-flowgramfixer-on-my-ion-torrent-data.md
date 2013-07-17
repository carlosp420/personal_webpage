---
layout: post
title: "FlowgramFixer on my Ion Torrent data"
description: "Testing flowgramFixer on Ion Torrent data files"
excerpt: "I tried to run flowgramFixer on my data. I downloaded
the SFF file from our Ion Torrent server and run ``flower``."
category: 
tags: [Ion Torrent, Next Generation Sequencing]
---
{% include JB/setup %}

I tried to run flowgramFixer [@golan2013] on my data. I downloaded
the SFF file from our Ion Torrent server and run ``flower``.

I couldn't compile `flowgramfixer.c` and its author David Colan
was very kind to point out that some libraries might need to be 
specified in the compiling command:

```shell
gcc flowgramfixer.c -o flowgramFixer -O3 -lmath
```

As specified in [**FlowgramFixer** website](https://sites.google.com/site/davidgolanshomepage/flowgramfixer),
I run flower on the 
SFF file to get the flowgrams for each read (float numbers that
represent incorportion values of nucleotides during the sequencing
process).

```shell
flower ionfile.sff | awk 'NR%6==3' | cut -f2 > ionfile.flowgram
```

This how the first lines of the file look like:

```shell
0.94 0.00 0.85 0.00 0.03 0.85 0.07 0.79 0.56 0.01 0.03 1.97 0.04 0.21
0.88 0.00 0.00 0.00 0.99 0.01 2.01 0.18 0.25 1.14 0.64 2.04 0.03 1.05
```

The authors recommend running **flowgramFixer** on 1% of the data to 
get maximum likelihood values for the parameters (specially noise). Then
we can use take the average values and apply them to the rest of the 
flowgrams of the data (I also got little help from David Golan for this):

```shell
# get 1% of the flowgrams
cat ionfile.flowgram | perl -n -e 'print if (rand() < .01)' > 1_per_cent.flowgram
flowgramFixer 1_per_cent.flowgram1_per_cent normal greedy

# get average of parameter values from .lik file
cat 1_per_cent.lik | awk '{a += $1; b += $2} END {print a/NR,b/NR}'
> 0.061942 0.000429938

# use these two numbers to run flowgramFixer on the entire data
flowgramFixer ionfile.flowgram output normal 0.061942 0.000429938
```

After some time I got the DNA sequences for all the reads as recovered
by the base-calling method of **FlowgramFixer**. After a quick look, it seems that
it produced better results than the base-calling from the Ion Torrent.
In many cases the Ion Torrent had produced many insertions, specially after 
repetitions of the same nucleotide, but flowgramFixer was able to get the
sequence without the insertions.

But now the problem I have is that the output file doesn't contain the
quality values.
I was thinking on pulling the quality values from the Ion Torrent file
but this would be problematic because it would have additional quality
values per read in the insertions that flowgramfixer was able to avoid.

I asked David Golan whether it is possible to get the reads along with
quality values from flowgramfixer and he said that this feature might
be implemented in the future.

So it might be interesting to keep an eye on **flowgramFixer**. It also
has a [github page](https://github.com/golandavid/flowgramFixer).



### References
