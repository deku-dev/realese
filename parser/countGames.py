import common
from listgame import ListGame

allCateg = common.getCategory("https://s8.torents-igruha.org/newgames/")
# listGame = ListGame("https://s8.torents-igruha.org/newgames/")
# listGame.getAllGame()
setGames = set()
for catPage in allCateg:
  if catPage == "https://repack-igruha.org/":
    continue
  listG = ListGame(catPage)
  listG.getAllGame()
  for gameItem in listG.listAllGame:
    setGames.add(gameItem)

print("All game for set"+str(len(setGames)))