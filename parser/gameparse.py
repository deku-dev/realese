import re, bs4, requests, json
class GameParse: # Page game with all data
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

