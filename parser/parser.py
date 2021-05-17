# INSERT INTO `categorie` (`cat_id`, `cat_name`) VALUES (NULL, 'help'), (NULL, 'hi');
# https://torrent-igruha.org/         #menuigruha li a
import requests, bs4, random, pymysql, re
import parsel
from OpenSSL import rand
from urllib.parse import urlparse
from random import randrange
from datetime import date, timedelta
from datetime import datetime
import json
import mysqlconn



# Рандомная дата между start и end
def random_date(start, end):
  delta = end - start
  int_delta = (delta.days * 24 * 60 * 60) + delta.seconds
  random_second = randrange(int_delta)
  return start + timedelta(seconds=random_second)

d1 = datetime.strptime('2000/1/1', '%Y/%m/%d')
d2 = datetime.strptime('2020/09/15', '%Y/%m/%d')

# print(random_date(d1, d2).strftime('%Y-%m-%d'))

def listParse(soup):
  item = str(soup)
  item = item.strip()
  return list(filter(None, item.split('<br/>')))
def dictparse(listv):
  return dict(s.split(':',1) for s in searchDot(listv))
def searchDot(string):
  listProp = ['Год выпуска', 'Жанр', 'Разработчик', 'Издательство', 'Платформа', 'Язык интерфейса', 'Язык озвучки', 'Таблетка', 'ОС', 'Процессор', 'Оперативная память', 'Видеокарта']
  worss = []
  for listr in string:
    if listr.find(':') == -1:
      for words in listProp:
        if listr.find(words) != -1:
          worss.append(listr.replace(words, words+':'))
    else:
      worss.append(listr)
  return worss

  
"""
@author Anima
func: Парсер елементов на одной странице
"""
def parserText(url, selector, sql):
  select = sql
  site = requests.get(url)
  htmlSet = bs4.BeautifulSoup(site.text, "html.parser")
  link = htmlSet.select(selector)
  x = 1
  for item in link:
    select += "("+str(x)+", '"+ item.getText()+"'),"
    x += 1
  return select
 
# print(parserText("https://torrent-igruha.org/", "#menuigruha li a", "INSERT INTO `category` (`cat_id`, `cat_name`) VALUES "))
"""
@author Anima
func: Выбор элементов из массива
"""
# (NULL, 'gurich', 'wade', 'dekanip', 'wader', current_timestamp(), 'gyrycvadym@gmail.com');"
def randomSQL(list, selector):
  x= 0
  while x<6:
    selector += "(NULL, '"+random.choice(rand)+"', '"+random.choice(rand)+"', 'dekanip"+random.choice(rand)+"', '"+random.choice(rand)+"', current_timestamp(), '"+random.choice(rand)+"@gmail.com'),"
    x+=1
  print(selector)
# rand = ['one','two','three','four','five','six','seven','eight','ten']
# selector =  "INSERT INTO `users` (`user_id`, `firstname`, `lastname`, `password`, `nickname`, `date`, `email`) VALUES "
# randomSQL(rand, selector)

"""
@author Anima
func: Многостраничный парсер сайта
INSERT INTO `game` (`game_id`, `name`, `pubdate`, `date`, `cat_id`, `views`, `language`) VALUES (NULL, 'онпоп', '2020-07-22 04:18:19', '2020-07-14', '5', '65'); вставка данных в game

INSERT INTO `fulldescip` (`game_id`, `description`, `specification_json`, `media`, `torrent_file`) VALUES ('6', 'help', '{"ОС":"Windows 10","видео": "GTX1060"}', '{"screenshot":["https://s1.torrents-igruha.org/uploads/posts/2019-06/thumbs/1560266984_714f241933bcc041_1920xh.jpg","https://s1.torrents-igruha.org/uploads/posts/2019-06/thumbs/1560266995_96855c0a3f96fd70_1920xh.jpg"],"video":["PGdfhZve-HI","8PApf-ZOyos"]}', 'file');
"""
def searchSize(tag):
  return tag["id"] == 'navbartor' and tag.contents['id'] != 'tes_pict'

def parserSite(siteurl, game_id, connection):
  # siteurl = "https://torrent-igruha.org/zombie-games/"
  pattern = r'(?<=blockinfo">).+(?=<div class="clr")'
  games = "INSERT INTO `game` (`game_id`, `name`, `pubdate`, `date`, `views`, `image`, `language`) VALUES "
  fulldesc = "INSERT INTO `fulldescip` (`game_id`, `description`, `specification_json`, `media`, `torrent_file`) VALUES "
  code = requests.get(siteurl)
  htmlSet = bs4.BeautifulSoup(code.text, "html.parser")
  game = htmlSet.select(".article-film")
  for item in game:
    img = item.img["src"] # Картинка +
    name_center = item.select("center a")[0].getText() # Название +
    print(name_center+'\t'+str(game_id)+'\n')
    gameHtml = requests.get(item.a["href"])
    htmlGame = bs4.BeautifulSoup(gameHtml.text, "html5lib")
    
    screensh = htmlGame.select(".item-screenstop img")
    screenshot = [item['src'] for item in screensh] # Скриншоты игры +
    video = htmlGame.select(".youtube")
    video_com = [item['id'] for item in video] # Видео +
    mediaDump = {
      "video": video_com,
      "screenshot": screenshot
    }
    mediaJson = json.dumps(mediaDump) # Media json complete
    desc = str(htmlGame.find_all("div", "blockinfo")[0])
    descstring = desc.replace("\n","")
    description = re.findall(pattern, descstring)  # Описание +
    sizelist = []
    filelist = []
    if htmlGame.find(class_="online"):
      with open('bag_img.txt', 'a') as file:
        fileDump = ""
        file.write(item.a["href"]+"Online game\n")
    else:
      fileurl = htmlGame.select('.torrent')
      size = htmlGame.select('center span[style="font-size:14pt;"] span[style="color: #89c80e;"]')
      idn = 0
      for item in size:
        sizelist.append(item.text)
      filesize = list(filter(None, sizelist))
      for file in fileurl:
        filelist.append("https://s1.torrents-igruha.org/engine/download.php?id="+file['href'].split('=')[-1])
      fileDump = json.dumps(dict(zip(filelist,sizelist)), ensure_ascii=False)
    spec = htmlGame.select("#dle-content div")[7]
    spec.select(".exampleone")[0].extract()
    if spec.find('img'):
      spec_compl = ""
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
      spec_compl = json.dumps(dictparse(listParse(html_spec)), ensure_ascii=False) # Системные требования json + 
    # fulldesc += "(\""+str(game_id)+"\",\""+description[0]+"\",\""+spec_compl+"\",\""+mediaJson+"\",\""+fileDump+"\"), "
    # fulldesc = "INSERT INTO `fulldescip` (`game_id`, `description`, `specification_json`, `media`, `torrent_file`) VALUES ('6', 'help', '{\"ОС\":\"Windows 10\",\"видео\": \"GTX1060\"}', '{\"screenshot\":[\"https://s1.torrents-igruha.org/uploads/posts/2019-06/thumbs/1560266984_714f241933bcc041_1920xh.jpg\",\"https://s1.torrents-igruha.org/uploads/posts/2019-06/thumbs/1560266995_96855c0a3f96fd70_1920xh.jpg\"],\"video\":[\"PGdfhZve-HI\",\"8PApf-ZOyos\"]}', 'file');"
    # games += '('+str(game_id)+', "'+name_center+'", current_timestamp(), "'+str(random_date(d1, d2).strftime('%Y-%m-%d'))+'", "1", "'+ img +'", "ru"), '
    with connection.cursor() as cursor:
      game = "INSERT INTO `game` (`game_id`, `name`, `pubdate`, `date`, `views`, `image`) VALUES (%s, %s, current_timestamp(), %s, %s, %s)"
      cursor.execute(game, (game_id, name_center, str(random_date(d1, d2).strftime('%Y-%m-%d')), random.randint(2, 200), img))
      fulldesc = "INSERT INTO `fulldescip` (`game_id`, `description`, `specification_json`, `media`, `torrent_file`) VALUES (%s, %s, %s, %s, %s)"
      cursor.execute(fulldesc, (game_id, description, spec_compl, mediaJson, fileDump))
      game = "INSERT INTO `lang` (`game_id`, `lang_id`) VALUES (%s, %s)"
      x = random.randint(1,2)
      catt = "lang="
      while x <= 2:
        y = random.randint(1,3)
        cursor.execute(game, (game_id, y))
        catt += str(game_id)+"-"+str(y)+"="
        x += 1
      print(catt)
      y = 0
      ins = "INSERT INTO `cat_game` (`game_id`, `cat_id`) VALUES "
      catt = "cat_game="
      while y < 5:
        cat_id = str(random.randint(1, 30))
        ins += "('"+str(x)+"', '"+str(cat_id)+"'),"
        game = "INSERT INTO `cat_game` (`game_id`, `cat_id`) VALUES (%s, %s)"
        cursor.execute(game, (game_id, cat_id))
        catt += str(game_id)+"-"+str(cat_id)+"="
        y += 1
      print(catt)
      connection.commit()
    game_id += 1

  return games, game_id, fulldesc


# parserSite("https://s1.torrents-igruha.org/newgames/page/67/", 1)
connection = mysqlconn.getConnection()
x = 1
ids = 1
try:
  with connection.cursor() as cursor:
    game = "SET foreign_key_checks = 0"
    cursor.execute(game)
    game = "DELETE FROM `fulldescip` WHERE 1"
    cursor.execute(game)
    game = "DELETE FROM `game` WHERE 1"
    cursor.execute(game)
    game = "DELETE FROM `cat_game` WHERE 1"
    cursor.execute(game)
    game = "DELETE FROM `lang` WHERE 1"
    cursor.execute(game)
    game = "SET foreign_key_checks = 1"
    cursor.execute(game)
    connection.commit()
  while x<=103:
    game, ids, full = parserSite("https://s1.torrents-igruha.org/newgames/page/"+str(x)+"/", ids, connection)
    print('Pages'+str(x))
    x += 1
finally:
  connection.close()
  # with open('allgame.txt', 'a', encoding='utf-8') as file:
  #   file.write(game+"\n")
  # if x <30:
  #   with open('full.txt', 'a', encoding='utf-8') as target:
  #     target.write(full+'\n')
  # if x > 30 and x < 60:
  #   with open('full2.txt', 'a', encoding='utf-8') as target:
  #     target.write(full+'\n')
  # if x > 60:
  #   with open('full3.txt', 'a', encoding='utf-8') as target:
  #     target.write(full+'\n')




# INSERT INTO `fulldescip` (`game_id`, `description`, `specification_json`, `media`, `torrent_file`) VALUES ('6', 'help', '{"ОС":"Windows 10","видео": "GTX1060"}', '{"screenshot":["https://s1.torrents-igruha.org/uploads/posts/2019-06/thumbs/1560266984_714f241933bcc041_1920xh.jpg","https://s1.torrents-igruha.org/uploads/posts/2019-06/thumbs/1560266995_96855c0a3f96fd70_1920xh.jpg"],"video":["PGdfhZve-HI","8PApf-ZOyos"]}', 'file');



def parseCat():
  connection = mysqlconn.getConnection()
  try:
    with connection.cursor() as cursor:
      game = "DELETE FROM `cat_game` WHERE 1"
      cursor.execute(game)
      connection.commit()
  finally:
    connection.close()
  ins = "INSERT INTO `cat_game` (`game_id`, `cat_id`) VALUES "
  stat = "('6', '17')"
  x = 1
  z = 1
  while x<3540:
    y = 0
    while y < 5:
      cat_id = str(random.randint(1, 30))
      ins += "('"+str(x)+"', '"+str(cat_id)+"'),"
      connection = mysqlconn.getConnection()
      try:
        with connection.cursor() as cursor:
          game = "INSERT INTO `cat_game` (`game_id`, `cat_id`) VALUES (%s, %s)"
          cursor.execute(game, (x, cat_id))
          connection.commit()
      finally:
        connection.close()
      z += 1
      y += 1
    print('x = '+str(x)+'\tcat_id = '+cat_id+'\t'+str(z))
    x += 1
def langSet():
  connection = mysqlconn.getConnection()
  try:
    with connection.cursor() as cursor:
      game = "DELETE FROM `lang` WHERE 1"
      cursor.execute(game)
      connection.commit()
  finally:
    connection.close()
  ins = "INSERT INTO `lang` (`game_id`, `cat_id`) VALUES "
  stat = "('6', '17')"
  x = 1
  z = 1
  while x<3540:
    y = 0
    while y < 1:
      cat_id = str(random.randint(1, 3))
      ins += "('"+str(x)+"', '"+str(cat_id)+"'),"
      connection = mysqlconn.getConnection()
      try:
        with connection.cursor() as cursor:
          game = "INSERT INTO `cat_game` (`game_id`, `cat_id`) VALUES (%s, %s)"
          cursor.execute(game, (x, cat_id))
          connection.commit()
      finally:
        connection.close()
      z += 1
      y += 1
    print('x = '+str(x)+'\tcat_id = '+cat_id+'\t'+str(z))
    x += 1
# langSet()
# parseCat()
