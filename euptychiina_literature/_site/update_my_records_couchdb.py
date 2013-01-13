#!/usr/bin/env python

import couchdb;
import urllib2;
import json;
import string;


print "\nUpdate records by looking DOI numbers in Crossref\n";

couch = couchdb.Server("http://localhost:5984/");
db = couch["euptychiina"];

## ---------------------------------------------------------------------------
## get metadata for doi from crossref
def get_metadata(doi):
	# get metadata from crossrefs
	request = urllib2.Request("http://dx.doi.org/" + doi, headers={"Accept" : "Accept: application/rdf+xml;q=0.5, application/vnd.citationstyles.csl+json;q=1.0"});
	contents = urllib2.urlopen(request).read();
	return contents;


for i in db:
	id = i;
	doc = db[i];
	doi = db[id]['doi'];
	metadata = get_metadata(doi);
	metadata = json.loads(metadata);
	for j, key in enumerate(metadata):
		val = metadata[key];
		key = key.lower();
		doc[key] = val;
	db.save(doc);
	print "Uploading doi: ", doi;
