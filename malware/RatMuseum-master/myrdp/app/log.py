import logging
logger = logging.getLogger('myrdp')

logger.propagate = False
formatter = logging.Formatter('[%(asctime)s][%(levelname)s] %(message)s')
ch = logging.StreamHandler()
ch.setFormatter(formatter)
logger.addHandler(ch)
