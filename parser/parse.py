import json, requests, bs4, re, mysqlconn, pymysql



class SendData:
  def __init__(self, pageGame):
    self.connect = mysqlconn.getConnection()
    self.pageGame = pageGame # Link game on site
    self.id = self.checkGame() # id game if exist in database

  def sendRequest(self, request, data): # Function send data in database
    try:
      with self.connect.cursor() as cursor:
        cursor.execute(request, data)
        result = cursor.fetchone()
        self.connect.commit()
    finally:
      self.connect.close()
    return result

  def deleteAllGame(self): # Delete all game from database
    request = "DELETE FROM `game` WHERE 1"
    if(input("Confirm delete all game?") == "y"):
      self.sendRequest(request, ())
    

  def saveNewGame(self, name, img, desc, file, media, spec, date, views=0): # Adding new game in database
    reqGame = "INSERT INTO `game`(`game_id`, `name`, `pubdate`, `date`, `views`, `image`, `downloads`) VALUES (NULL,%s,current_timestamp(),%s,%s,%s,%s)"
    reqFull = "INSERT INTO `fulldescip`(`game_id`, `description`, `specification_json`, `media`, `torrent_file`) VALUES (%s,%s,%s,%s,%s)"
    reqLink = "INSERT INTO `torrent_link`(`id`, `link`, `date`, `name`) VALUES (%s,%s,current_timestamp(),%s)"
    self.sendRequest(reqGame, (name, date, views, img, 0))
    self.sendRequest(reqFull, (self.connect.insert_id(), desc, spec, media, file))
    self.sendRequest(reqLink, (self.connect.insert_id(), self.pageGame, name))

  def checkGame(self): # Check game for exist
    reqSearch = "SELECT `id` FROM `torrent_link` WHERE `link`=%s"
    return self.sendRequest(reqSearch, (self.pageGame))['id']
  
  def updateDesc(self, desc): # Update description
    upDesc = "UPDATE `fulldescip` SET `description`=%s WHERE `game_id`=%s"
    self.sendRequest(upDesc, (desc, self.id))
  def updateMedia(self, media):
    upMedia = "UPDATE `fulldescip` SET `media`=%s WHERE `game_id`=%s"
    self.sendRequest(upMedia, (media, self.id))
  def updateFile(self, file):
    upFile = "UPDATE `fulldescip` SET `torrent_file`=%s WHERE `game_id`=%s"
    self.sendRequest(upFile, (file, self.id))
  def updateImage(self, image):
    upImage = "UPDATE `game` SET `image`=%s WHERE `game_id`=%s"
    self.sendRequest(upImage, (image, self.id))

  def addNewUser(self, paswd, nick, mail, about = ""):
    reqUser = "INSERT INTO `users` (`user_id`, `password`, `nickname`, `date`, `email`, `picture`, `banned_user`, `about`) VALUES (NULL, %s, %s, current_timestamp(), %s, 'asset/user.svg', '0', %s);"
    self.sendRequest(reqUser, (paswd, nick, mail, about))
  def setLang(self, lang):
    reqLang = "INSERT INTO `lang`(`game_id`, `lang_id`) VALUES (%s,%s)"
    self.sendRequest(reqLang, (self.id, lang))






class Game: # Page game with all data
  def __init__(self, page):
    self.gameHtml = bs4.BeautifulSoup(requests.get(page).text, "html5lib")

  def searchDot(self, string):
    listProp = ['Год выпуска', 'Жанр', 'Разработчик', 'Издательство', 'Платформа', 'Язык интерфейса', 'Язык озвучки', 'Таблетка', 'ОС', 'Процессор', 'Оперативная память', 'Видеокарта']
    worss = []
    for listr in string:
      if listr.find(':') == -1:
        for words in listProp:
          if listr.find(words) != -1:
            if(words == "Год выпуска"):
              self.dateGame = listr
            worss.append(listr.replace(words, words+':'))
      else:
        worss.append(listr)
    return worss
  def listParse(self, soup):
    item = str(soup)
    item = item.strip()
    return list(filter(None, item.split('<br/>')))
  def dictparse(self, listv):
    return dict(s.split(':',1) for s in self.searchDot([bs4.BeautifulSoup(spec, "html5lib").get_text() for spec in listv]))


  def getImg(self):
    return self.gameHtml.select_one(".article-img-full.entry-image")["src"] # string image game
  
  def getName(self):
    return self.gameHtml.select_one(".module-title:first-child > h1").text # string name game

  def getDate(self):
    return self.dateGame


  def getMedia(self):
    mediaDump = {
      "video": self.getVideo(),
      "screenshot": self.getScreen()
    }
    return json.dumps(mediaDump) # json object video and screenshot
  def getVideo(self):
    video = self.gameHtml.select(".youtube")
    return [item['id'] for item in video] # list string short link video
  def getScreen(self):
    screensh = self.gameHtml.select(".item-screenstop img")
    return [item['src'] for item in screensh] # list link screenshot
  

  def getDescription(self):
    pattern = r'(?<=blockinfo">).+(?=<div class="clr")'
    desc = str(self.gameHtml.find_all("div", "blockinfo")[0])
    descstring = desc.replace("\n","")
    return re.findall(pattern, descstring) # description string

  def getViews(self):
    return self.gameHtml.select_one("#article-film-full-info span:last-child").text


  def getSpecify(self):
    spec = self.gameHtml.select_one("#dle-content > div:nth-child(3)")
    spec.select(".exampleone")[0].extract()
    if spec.find('img'):
      return None
    else:
      specification = ""
      for item in spec.contents:
        specification += str(item)
      html_spec = bs4.BeautifulSoup(specification, "html5lib")
      while html_spec.find('b') != None:
        html_spec.b.unwrap()
      html_spec.body.unwrap()
      html_spec.head.unwrap()
      html_spec.html.unwrap()
      return json.dumps(self.dictparse(self.listParse(html_spec)), ensure_ascii=False) # system requirements json

  def getFile(self):
    sizelist = []
    filelist = []
    if self.gameHtml.find(class_="online"):
      return None
    else:
      fileurl = self.gameHtml.select('.torrent')
      size = self.gameHtml.select('center span[style="font-size:14pt;"] span[style="color: #89c80e;"]')
      for item in size:
        sizelist.append(item.text)
      for file in fileurl:
        filelist.append("https://s1.torrents-igruha.org/engine/download.php?id="+file['href'].split('=')[-1])
      return json.dumps(dict(zip(filelist,sizelist)), ensure_ascii=False) # files size, link json


class ListGame: # page with list game
  def __init__(self, page):
    self.listHtml = bs4.BeautifulSoup(requests.get(page).text, "html5lib")
    self.listGame = []
    self.listAllGame = []

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

class ControlParser: # class for control all code parser
  def __init__(self):
    print("class created")