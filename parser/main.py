import json, requests, bs4, re, mysqlconn, pymysql, sys, traceback
from listgame import ListGame as LG
from gameparse import GameParse as GP
from senddata import SendData as SD
from var_dump import var_dump
from threading import Thread, Lock

import timing
import analyzer
import common
import logging, asyncio
import logging.config

from colorama import init, Fore, Back, Style

init(autoreset=True) 

connect = mysqlconn.getConnection()
try:
  lock = Lock()
  with connect.cursor() as cursor:
    class ControlParser(Thread):
      global connect, cursor
      def __init__(self, pageCat, numThread):
        Thread.__init__(self)
        self.catlink = pageCat
        self.numTh = numThread

      def run(self):
        """Запуск потока"""
        logging.info("Thread #"+str(self.numTh)+" started")
        listPage = LG(self.catlink)
        listPage.getAllGame()
        for linkGame in listPage.listAllGame:
          with lock:
            sendGameData = SD(connect, cursor, linkGame, self.catlink)
            sendGameData.setCategory()
        logging.info("Ended work thread #"+str(self.numTh))

    def createThread(pagelist):
      threads = []
      numThread = 0
      for catLink in pagelist:
        if catLink == "https://repack-igruha.org/":
          continue
        contrThread = ControlParser(catLink, numThread)
        contrThread.name = "Thread-"+catLink.split("/")[-2]
        contrThread.daemon = True
        print("Thread - %s started" % (contrThread.name))
        threads.append(contrThread)
        numThread += 1
      for t in threads:
        t.start()
      for t in threads:
        t.join()
      logging.info("End all thread in main")

    def main():
      logging.config.fileConfig('logging.ini',disable_existing_loggers=False)
      logger = logging.getLogger(__name__)
      try:
        global connect, cursor
        logger.debug("Started primary script")
        # connToBase = SD(connect, cursor)
        # print(Back.GREEN+Fore.BLACK+"Format database")
        # connToBase.formatDatabase()
        # print(Back.GREEN+Fore.BLACK+"Start analyzer")
        # analyzer.main()
        allCateg = common.getCategory('https://s5.torents-igruha.org/')
        # allCateg = ["https://s5.torents-igruha.org/newgames/"]
        print(Back.GREEN+Fore.BLACK+"Started ")
        createThread(allCateg)
        print(Back.GREEN+Fore.BLACK+"End all script")

        # print(Back.GREEN+Fore.BLACK+"TESTING ...")
      except OSError as e:
        logger.error(e, exc_info=True)
      except:
        logger.error("uncaught exception: %s", traceback.format_exc())
        return False

    if __name__ == '__main__':
      main()

finally:
  connect.close()










