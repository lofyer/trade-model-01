#!/usr/bin/env python
import logging
import os
import sys
import yahoo_finance
import ConfigParser

#
# This is a functional draft.
#

class URL_Collector:
    URL_Collector_Num = 0
    def __init__(self):
        URL_Collector_Num += 1

# Read configuration
config = ConfigParser.RawConfigParser()
config.read("athena.conf")
NEWS_FILES = config.get("News Lists","news_list_files")

# DEBUG INFO WARN ERROR
logging.basicConfig(filename = os.path.join(os.getcwd(), 'athena.log'), level = logging.INFO, filemode = 'aw', format = '%(asctime)s - %(levelname)s: %(message)s')

lists = dict()

# Collect URLs from list files
for i in NEWS_FILES.split(","):
    f_name = i.strip()
    logging.info("Opening news files... %s" % f_name )
    try:
        f = open(f_name, 'r')
        this_list = [ item.strip() for item in f.readlines() if item.startswith('#') == False and item != '' ]
        print this_list
    except Exception as e:
        logging.error(e)
        sys.exit(1)
    logging.info("Closing news files... %s" % i)
    f.close()

for i in lists:
    print i
