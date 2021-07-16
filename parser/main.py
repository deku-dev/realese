import json, requests, bs4, re, mysqlconn, pymysql, sys, traceback
from listgame import ListGame as LG
from gameparse import GameParse as GP
from senddata import SendData as SD
from var_dump import var_dump
from threading import Thread
import timing
import analyzer
import common
import logging


from colorama import init, Fore, Back, Style

init(autoreset=True) 

connect = mysqlconn.getConnection()
try:
  with connect.cursor() as cursor:
    class ControlParser(Thread):
      global connect, cursor
      def __init__(self, pageCat, numThread):
        Thread.__init__(self)
        self.catlink = pageCat
        self.numTh = numThread

      def run(self):
        """Запуск потока"""
        print(Back.GREEN+Fore.BLACK+"Thread #"+str(self.numTh)+" started")
        listPage = LG(self.catlink)
        listPage.getAllGame()
        for linkGame in listPage.listAllGame:
          sendGameData = SD(connect, cursor, linkGame)
          sendGameData.setCategory(self.catlink)
        print(Back.RED+Fore.BLACK+"Ended work thread #"+str(self.numTh))

    def createThread(pagelist):
      
      threads = []
      numThread = 0
      for catLink in pagelist:
        threads.append(ControlParser(catLink, numThread))
        numThread += 1
      for t in threads:
        t.start()
      for t in threads:
        t.join()
      print(Back.RED+Fore.BLACK+"End all thread in main")

    def main():
      try:
        logging.config.fileConfig('/path/to/logging.ini',disable_existing_loggers=False)
        logger = logging.getLogger(__name__)
        global connect, cursor
        print("Started primary script")
        connToBase = SD(connect, cursor)
        connToBase.formatDatabase()
        analyzer.main()
        allCateg = common.getCategory('https://s5.torents-igruha.org/')
        # allCateg = ["https://s5.torents-igruha.org/game-open-world/"]
        createThread(allCateg)

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










