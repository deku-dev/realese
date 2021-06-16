import bs4, requests
class ListGame: # page with list game
  def __init__(self, page):
    self.listHtml = bs4.BeautifulSoup(requests.get(page).text, "html5lib")
    self.listGame = []
    self.listAllGame = []
    print("import class")

  def getlistlink(self):
    listgame = self.listHtml.select(".article-film > center > .article-film-title a")
    self.listGame = [item['href'] for item in listgame]
    return self.listGame # list game for one page
  
  def getAllGame(self):
    linkNewPage = self.listHtml.find('span', class_="page-next").parent
    while(linkNewPage.get('href')):
      linkNewPage = self.listHtml.find('span', class_="page-next").parent['href']
      self.listHtml = bs4.BeautifulSoup(linkNewPage['href'], "html5lib")
      self.listAllGame.extend(self.getlistlink())
    return self.listAllGame # list all game for category
