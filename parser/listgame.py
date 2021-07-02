import bs4, requests, asyncio
from var_dump import var_dump
class ListGame: # page with list game
  def __init__(self, page):
    self.listHtml = bs4.BeautifulSoup(requests.get(page).text, "html5lib")
    self.listGame = []
    self.listAllGame = []

  def getlistlink(self, htmlToList):
    listgame = htmlToList.select(".article-film > center > .article-film-title a")
    self.listGame = [item['href'] for item in listgame]
    return self.listGame # list game for one page
  
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
    print(self.listAllGame)
    return self.listAllGame # list all game for category
