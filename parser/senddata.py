import pymysql, mysqlconn
import logging
import threading
from colorama import init, Fore, Back, Style
class SendData:
  semaphore = threading.BoundedSemaphore(1)
  def __init__(self, connect, cursor, pageGame=None):
    self.cursor = cursor
    self.connect = connect
    self.pageGame = pageGame # Link game on site
    self.id = None # id game if exist in database, checkGame for the data
    if(pageGame):
      self.id 
    self.idCat = None
    

  def sendRequest(self, request, data): # Function send data in database
    self.cursor.execute(request, data)
    result = self.cursor.fetchone()
    return result
  
  def multiRequest(self, requests): # dict request -> data
    try:
      self.semaphore.acquire()
      for request, data in requests.items():
        self.cursor.execute(request, data)
        result = self.cursor.fetchone()
      self.connect.commit()
    finally:
      self.semaphore.release()
    return result

  def formatDatabase(self):
    reqFormat = {
      "DELETE FROM `game` WHERE 1": None,
      "DELETE FROM `fulldescip` WHERE 1": None,
      "DELETE FROM `cat_game` WHERE 1":None,
      "DELETE FROM `game_tags` WHERE 1":None,
      "DELETE FROM `lang` WHERE 1":None,
      "DELETE FROM `favorites` WHERE 1":None,
      "DELETE FROM `load_list` WHERE 1":None,
      "DELETE FROM `message` WHERE 1":None,
      "DELETE FROM `rating` WHERE 1":None,
      "DELETE FROM `review_like` WHERE 1":None,
      "DELETE FROM `torrent_link` WHERE 1":None,
      "DELETE FROM `users` WHERE 1":None,
      "DELETE FROM `users_session` WHERE 1":None,
      "DELETE FROM `user_hash` WHERE 1":None,
      "DELETE FROM `user_views` WHERE 1":None,
      "DELETE FROM `views` WHERE 1":None,
      "ALTER TABLE `game` AUTO_INCREMENT = 1;":None,
      "ALTER TABLE `game_tags` AUTO_INCREMENT = 1;":None
    }
    self.multiRequest(reqFormat)
    

  def checkCategory(self, linkCategory):
    reqCat = "SELECT `cat_id` FROM `category` WHERE `cat_link`=%s"
    self.idCat = self.sendRequest(reqCat, (linkCategory))["cat_id"]


  def setCategory(self, linkCategory):
    self.checkCategory(linkCategory)
    reqSetcat = "INSERT INTO `cat_game`(`game_id`, `cat_id`) VALUES (%s,%s)"
    self.sendRequest(reqSetcat, (self.id, self.idCat))


  def saveNewGame(self, name, img, desc, file, media, spec, date, views=0, indicator=None): # Adding new game in database
    reqGame = "INSERT INTO `game`(`game_id`, `name`, `pubdate`, `date`, `views`, `image`, `downloads`, `indicator`) VALUES (NULL,%s,current_timestamp(),%s,%s,%s,%s,%s)"
    reqFull = "INSERT INTO `fulldescip`(`game_id`, `description`, `specification_json`, `media`, `torrent_file`) VALUES (%s,%s,%s,%s,%s)"
    reqLink = "INSERT INTO `torrent_link`(`id`, `link`, `date`, `name`) VALUES (%s,%s,current_timestamp(),%s)"

    self.sendRequest(reqGame, (name, date, views, img, 0, indicator))
    self.id = self.connect.insert_id()
    self.sendRequest(reqFull, (self.id, desc, spec, media, file))
    self.sendRequest(reqLink, (self.id, self.pageGame, name))
    


    print(Fore.BLUE+"Game insert %7d %s" % (self.id, name))
    logging.info("Game insert %7d %s" % (self.id, name))

  def checkGame(self): # Check game for exist
    reqSearch = "SELECT `id` FROM `torrent_link` WHERE `link`=%s"
    self.id = self.sendRequest(reqSearch, (self.pageGame))

    if self.id:
      self.id = self.id['id']
      return (self.id, 1)
  
  def updateDesc(self, desc): # Update description
    upDesc = {"UPDATE `fulldescip` SET `description`=%s WHERE `game_id`=%s":(desc, self.id)}
    self.multiRequest(upDesc)

  def updateMedia(self, media):
    upMedia = {"UPDATE `fulldescip` SET `media`=%s WHERE `game_id`=%s":(media, self.id)}
    self.multiRequest(upMedia)

  def updateFile(self, file):
    upFile = {"UPDATE `fulldescip` SET `torrent_file`=%s WHERE `game_id`=%s":(file, self.id)}
    self.multiRequest(upFile)

  def updateImage(self, image):
    upImage = {"UPDATE `game` SET `image`=%s WHERE `game_id`=%s":(image, self.id)}
    self.multiRequest(upImage)

  def addNewUser(self, paswd, nick, mail, about = ""):
    reqUser = {"INSERT INTO `users` (`user_id`, `password`, `nickname`, `date`, `email`, `picture`, `banned_user`, `about`) VALUES (NULL, %s, %s, current_timestamp(), %s, 'asset/user.svg', '0', %s);":(paswd, nick, mail, about)}
    self.multiRequest(reqUser)
  def setLang(self, lang):
    reqLang = {"INSERT INTO `lang`(`game_id`, `lang_id`) VALUES (%s,%s)":(self.id, lang)}
    self.multiRequest(reqLang)

  def setTags(self, tags, name, shortDes):
    reqTags = {"INSERT INTO `game_tags` (`id`, `name`, `meta-desc`, `meta-tags`) VALUES (%s, %s, %s, %s);":(self.id, name, shortDes, tags)}
    self.multiRequest(reqTags)
    print(Fore.GREEN+"Set tags %-8d %-60s"%(self.id,name))
    logging.info("Set tags %-8d %-60s"%(self.id,name))
