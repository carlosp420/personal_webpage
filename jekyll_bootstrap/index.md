---
layout: page
title : Blog
tagline: about my research on evolutionary biology
---
{% include JB/setup %}


{% for post in site.posts limit: 5 %}
  <div class="post_info">
	    <h3><a href="{{ BASE_PATH }}{{ post.url }}">{{ post.title }}</a>
	    <small>({{ post.date | date:"%Y-%m-%d" }})</small></h3>
    {{ post.excerpt }} <a href="{{ BASE_PATH }}{{ post.url }}">Read more!</a>
    </div>
  {% endfor %}
