---
layout: post
title: "FlowgramFixer on my Ion Torrent data"
description: ""
category: 
tags: []
---
{% include JB/setup %}

I tried to run flowgramFixer on my data. I downloaded
the SFF file from our Ion Torrent server and run ``flower``.

I couldn't compile `flowgramfixer.c` and its author David Colan
was very kind to point out that some libraries might need to be 
specified in the compiling command:

    gcc flowgramfixer.c -o flowgramFixer -O3 -lmath


