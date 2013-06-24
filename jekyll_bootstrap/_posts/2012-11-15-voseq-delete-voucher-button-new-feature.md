---
layout: post
title: "VoSeq: delete voucher button (new feature)"
description: ""
category: "bioinformatics"
excerpt: "Little by little we are doing some progress on VoSeq TODO list. We
    have released a new micro-version of VoSeq: 1.3.1 In this version, we have
    included a feature to delete records. You will find a Delete me button in
    voucher pages under the Administrator interface. If you... "
tags: [VoSeq, database]
---
{% include JB/setup %}

Dear VoSeq users,


Little by little we are doing some progress on VoSeq's {% cite pena2012 %}
TODO list.

We have released a new micro-version of **VoSeq: 1.3.1**

In this version, we have included a feature to delete records. You will find a
**Delete me** button in voucher pages under the **Administrator** interface.
If you
click the button, VoSeq will issue a dialog asking for confirmation to delete
all traces of that voucher record (including its associated sequences, primers
and will remove them from taxon sets).

Use the button with care!

You can download VoSeq from github: 
<https://github.com/carlosp420/VoSeq/tags>


#### References

{% bibliography --cited %}
