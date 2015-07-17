import logging
import os
import yahoo_finance

logging.basicConfig(filename = os.path.join(os.getcwd(), 'hera.log'), level = logging.WARN, filemode = 'w', format = '%(asctime)s - %(levelname)s: %(message)s')
