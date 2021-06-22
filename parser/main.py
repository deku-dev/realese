import json, requests, bs4, re, mysqlconn, pymysql, sys
from listgame import ListGame as LG
from gameparse import GameParse as GP
from senddata import SendData as SD
from var_dump import var_dump
from threading import Thread
import timing
import analyzer

class ControlParser(Thread):
  def __init__(self, pageCat, numThread):
    Thread.__init__(self)
    self.catlink = pageCat
    self.numTh = numThread


  def run(self):
    """Запуск потока"""
    listPage = LG(self.catlink)
    listPage.getAllGame()
    for linkGame in listPage.listAllGame:
      gameParse = GP(linkGame)
      gameParse.getAllData()
      sendGameData = SD(linkGame)
      sendGameData.saveNewGame()

    print("Ended work thread #"+str(self.numTh))


def getCategory(page):
  catPage = bs4.BeautifulSoup(requests.get(page).text, "html5lib")
  catList = catPage.select("#menuigruha li a")
  return [item["href"] for item in catList]

def createThread(pagelist):
  numThread = 0
  for catLink in pagelist:
    numThread += 1
    print("Thread #"+str(numThread)+" started")
    my_thread = ControlParser(catLink, numThread)
    my_thread.start()

def main():
  allCateg = getCategory('https://s5.torents-igruha.org/')
  createThread(allCateg)
  analyzer.main()
















main()