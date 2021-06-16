import pymysql, mysqlconn
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
