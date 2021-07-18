import bs4, requests, asyncio
import common
from var_dump import var_dump
import logging
class ListGame: # page with list game
  def __init__(self, page):
    self.pageLink = page
    self.listHtml = bs4.BeautifulSoup(requests.get(self.pageLink).text, "html5lib")
    self.listGame = []
    self.listAllGame = []

  def getlistlink(self, htmlToList):
    listgame = htmlToList.select(".article-film > center > .article-film-title a")
    self.listGame = [item['href'] for item in listgame]
    return self.listGame # list game for one page

  def uniqueListGame(self):
    listCat = common.getCategory(self.pageLink)
    uniqueList = set()
    for catLink in listCat:
      if catLink == "https://repack-igruha.org/":
        continue
      self.listHtml = bs4.BeautifulSoup(requests.get(catLink).text, "html5lib")
      self.getAllGame()
    for gameItem in self.listAllGame:
      uniqueList.add(gameItem)
    print("Unique item "+str(len(uniqueList)))
    common.saveObj(uniqueList, "listAllPage")
    return list(uniqueList)


  def getAllGame(self):
    self.listAllGame.extend(self.getlistlink(self.listHtml))
    linkNewPage = self.listHtml.find('span', class_="page-next")
    if(linkNewPage):
      linkNewPage = linkNewPage.parent.get('href')
    else:
      return self.listAllGame
    while(linkNewPage):
      nextPageHtml = bs4.BeautifulSoup(requests.get(linkNewPage).text, "html5lib")
      self.listAllGame.extend(self.getlistlink(nextPageHtml))
      linkNewPage = nextPageHtml.find('span', class_="page-next").parent.get('href')
    print("List game for "+self.pageLink+" "+str(len(self.listAllGame)) )
    return self.listAllGame # list all game for category
