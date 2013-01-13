#! /usr/bin/env python

# This script is to create sitemaps
# you need a file called lista with all the html files you want to add to the sitemap
# you will be asked for the http:// path that should go before the .html filenames
# Carlos Pena - 2006-09-23
#

import string, datetime

today = str(datetime.date.today())

path = raw_input("Enter path Example: http://myexample.com/: ")
	
# open and read list of files to proccess
f_lista = open("lista", "r");
lista = f_lista.readlines()

f_sitemap = open("sitemap.xml", "w");

#write xml header
f_sitemap.write('<?xml version="1.0" encoding="UTF-8"?>\n<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">\n');

#write url sitemaps
for line in lista:
	file_name = line.rstrip()
	f_sitemap.write('\n\t<url>\n\t\t<loc>')
	f_sitemap.write(path + file_name)
	f_sitemap.write('</loc>\n\t\t<lastmod>')
	f_sitemap.write(today)
	f_sitemap.write('</lastmod>\n\t\t<changefreq>weekly</changefreq>\n\t\t<priority>0.5</priority>\n\t</url>\n\n')
	
f_sitemap.write('</urlset>')

f_lista.close()
f_sitemap.close()
