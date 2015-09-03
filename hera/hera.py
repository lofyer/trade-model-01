#!/usr/bin/env python
import logging
import os
import yahoo_finance
import ConfigParser

# Read configuration
config = ConfigParser.RawConfigParser()
config.read("hera.conf")
CSV_FILES = config.get("Stock Lists","csv_files")

# DEBUG INFO WARN ERROR
logging.basicConfig(filename = os.path.join(os.getcwd(), 'hera.log'), level = logging.INFO, filemode = 'aw', format = '%(asctime)s - %(levelname)s: %(message)s')

lists = []
for i in CSV_FILES.split(","):
    logging.info("Opening csv files... %s" % i )
    f = open(i,"r")
    lists = lists + f.readlines()
    logging.info("Closing csv files... %s" % i)
    f.close()

for i in lists:
    print i
