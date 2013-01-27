#!/usr/bin/env python
# -*- coding: utf-8 -*-

import string;
import re;
import codecs;
import sys;
import urllib2;
import urllib;
import pycurl;
import json;
import re;
from StringIO import StringIO;
import couchdb;


## ---------------------------------------------------------------------------
def get_from_couchdb(database):
	all_references = [];
	couch = couchdb.Server("http://localhost:5984");
	db = couch[database];
	for id in db:
		reference = db[id];
		ref = reference_to_citation_string(reference);
		all_references.append(ref);

	return all_references;
	


## ---------------------------------------------------------------------------
## from Rod Page's biostor-cloud
def reference_to_citation_string(reference):
	citation = "<b>";
	
	year = "";
	if 'author' in reference:
		authors = []
		for author in reference['author']:
			if 'given' in author:
				authors.append(author['family'] + " " + author['given']);
			else:
				tmp = author['name'];
				tmp = tmp.replace(",", "");
				authors.append(tmp);
				
		citation += ", ".join(authors);

	if len(authors) == 0:
		authors.append(reference['author']);
		citation += ", ".join(authors);
				
	if 'year' in reference:
		year = reference['year'];
	elif 'date-parts' in reference:
		year = reference['date-parts'][0];
		year = year[0];
	elif 'issued' in reference:
		date_parts = reference['issued'];
		year = date_parts['date-parts'][0][0]

	if year != "":
		year = str(year);
		year = year.strip();
		match = re.search("\d{4}", year);
		if match:
			year = match.group();
			citation += ' (' + str(year) + ')</b>';
		

	if 'title' in reference:
		citation += " " + reference['title'] + ".";

	if 'container-title' in reference:
		citation += " <i>" + reference['container-title'] + "</i>";
	if 'journal' in reference:
		citation += " <i>" + reference['journal'] + "</i>";
	if 'volume' in reference:
		citation += ", " + reference['volume'];
	if 'issue' in reference:
		citation += "(" + reference['issue'] + ")";
	if 'number' in reference:
		citation += "(" + reference['number'] + ")";
	if 'page' in reference:
		citation += ": " + reference['page'] + ".";
	if 'start page' in reference:
		citation += ": " + reference['start page'];
	if 'end page' in reference:
		citation += "-" + reference['end page'] + ".";

	if 'doi' in reference:
		doi = reference['doi']
		citation += " <a href='http://dx.doi.org/" + doi;
		citation += "'>doi:" + doi + "</a>";
	elif 'DOI' in reference:
		doi = reference['DOI']
		citation += " <a href='http://dx.doi.org/" + doi;
		citation += "'>doi:" + doi + "</a>";

	citation = citation.replace("///", "");

	citation = clean_citation(citation);
	return citation;



## ---------------------------------------------------------------------------
def clean_citation(citation):
	citation = re.sub("\u00e9", "é", citation);
	return citation;



storage = StringIO();

"""
c = pycurl.Curl();
url = "https://carlosp420:borisco2@carlosp420.cloudant.com/euptychiina/_all_docs";
c.setopt(pycurl.URL, url);
c.setopt(pycurl.WRITEFUNCTION, storage.write);
c.perform();
c.close();
content = storage.getvalue();
content = json.loads(content);
"""


all_references = get_from_couchdb("euptychiina");
all_references.sort();

output =  "###Literature on Euptychiina\n";

j = 1;
for ref in all_references:
	output += str(j) + ". " + ref + "\n";
	j = j + 1;


print output.encode('utf8');
