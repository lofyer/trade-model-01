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

logging.info("Opening csv files...")
lists = []
for i in CSV_FILES.split(","):
    f = open(i,"r")
    lists = lists + f.readlines()
    f.close()

for i in lists:
    print i
