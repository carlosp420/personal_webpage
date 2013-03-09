#!/usr/bin/env python
# -*- coding: utf-8 -*-

import string;
import re;
import argparse;
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
		if 'title' in reference:
			ref = reference_to_citation_string(reference);
			all_references.append(ref);

	return all_references;
	


## ---------------------------------------------------------------------------
## from Rod Page's biostor-cloud
## input should be a valid bibjson object
def reference_to_citation_string(reference):
	citation = "<b>";
	
	year = "";
	if 'author' in reference:
		authors = []
		for author in reference['author']:
			if 'given' in author:
				authors.append(author['family'] + " " + author['given']);
			else:
				try:
					tmp  = author['lastname'];
					tmp += ", ";
					tmp += author['firstname'];
					tmp = re.sub("^,\s*", "", tmp);
					authors.append(tmp);
				except:
					tmp = author['name'];
					tmp = tmp.replace(",", "");
					authors.append(tmp);
				
		citation += "; ".join(authors);

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

	 # journal
	if 'journal' in reference:
		if 'name' in reference['journal']:
			citation += " <i>" + reference['journal']['name'].strip() + "</i>";

		if 'volume' in reference['journal']:
			citation += ", " + reference['journal']['volume'];

		if 'issue' in reference['journal']:
			if len(reference['journal']['issue']) > 0:
				citation += "(" + reference['journal']['issue'] + ")";

		if 'number' in reference['journal']:
			citation += "(" + reference['journal']['number'] + ")";

		if 'page' in reference['journal']:
			citation += ": " + reference['journal']['page'] + ".";
		if 'pages' in reference['journal']:
			citation += ": " + reference['journal']['pages'].replace("--", "-") + ".";
		if 'start page' in reference['journal']:
			citation += ": " + reference['journal']['start page'];
		if 'end page' in reference['journal']:
			citation += "-" + reference['journal']['end page'] + ".";

	if 'doi' in reference:
		doi = reference['doi']
		citation += " <a href='http://dx.doi.org/" + doi;
		citation += "'>doi:" + doi + "</a>";
	elif 'DOI' in reference:
		doi = reference['DOI']
		citation += " <a href='http://dx.doi.org/" + doi;
		citation += "'>doi:" + doi + "</a>";
	elif 'identifier' in reference:
		if 'type' in reference['identifier']:
			if reference['identifier']['type'] == 'DOI':
				doi = reference['identifier']['id'];
				citation += " <a href='http://dx.doi.org/" + doi;
				citation += "'>doi:" + doi + "</a>";
			

	citation = citation.replace("///", "");

	citation = clean_citation(citation);
	return citation;



## ---------------------------------------------------------------------------
def clean_citation(citation):
	citation = re.sub("\u00e9", "é", citation);
	return citation;


## ---------------------------------------------------------------------------
def main():
	description = """Pulls references from local couchdb.
	Enter database name as argument.""";

	parser = argparse.ArgumentParser(description=description);
	parser.add_argument('-db', '--database', action='store', metavar='db',
			nargs=1,
			required=True, dest='db',
			help='a couchdb');
	args = parser.parse_args();

	db = args.db[0];

	storage = StringIO();
	
	all_references = get_from_couchdb(db);
	all_references.sort();

	if db == "euptychiina":
		output =  "###Literature on Euptychiina\n";
	elif db == "lamas":
		output =  "###Literature from Lamas (2013)  \n\n";
		output += "I am parsing Gerardo Lamas' list of bibliographic literature on Neotropical butterflies from 2013. ";
		output += "All parsed references are hosted in a local **couchdb** in **bibjson";
		output += " format** for creating mashups in the near future.  \n\n";
		output += "####Lamas, G. (2013). An Annotated Bibliography of the Neotropical butterflies and skippers (Lepidoptera";
		output += ": Papilionoidea and Hesperioidea). <a href='Lamas_2013_Annotated_Bibliography.pdf'>";
		output += "<img src='images/kpdf.png' /></a>  \n\n";
	
	j = 1;
	for ref in all_references:
		output += str(j) + ". " + ref + "\n";
		j = j + 1;
	
	
	print output.encode('utf8');



if __name__ == "__main__":
	main()
