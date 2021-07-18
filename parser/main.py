import json, requests, bs4, re, mysqlconn, pymysql, sys, traceback
from listgame import ListGame as LG
from gameparse import GameParse as GP
from senddata import SendData as SD
from var_dump import var_dump
from threading import Thread, Lock
import chardet
import download , os
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
    class ControlParser:
      global connect, cursor
      def __init__(self, pageCat):
        self.catlink = pageCat

      def run(self):
        """Запуск потока"""
        listPage = LG(self.catlink)
        for linkGame in listPage.getAllGame():
          sendGameData = SD(connect, cursor, linkGame, self.catlink)
          sendGameData.setCategory()
          print("Set category "+linkGame)

    def createThread(pagelist):
      for catLink in pagelist:
        if catLink == "https://repack-igruha.org/":
          continue
        contrThread = ControlParser(catLink)
        contrThread.run()

    def main():
      logging.config.fileConfig('logging.ini',disable_existing_loggers=False)
      logger = logging.getLogger(__name__)
      try:
        global connect, cursor
        logger.debug("Started primary script")
        # download.main()
        # print("Fix encoding")
        # for file in os.listdir("html"):
          
        #   with open("html/"+file, encoding=common.getFileEncoding("html/"+file)) as fh:
        #     data = fh.read()
        #   with open("html/"+file, 'wb') as fh:
            # fh.write(data.encode('utf-8'))
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










