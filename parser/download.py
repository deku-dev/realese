import urllib, asyncio, aiohttp
from listgame import ListGame as LG
import common
import os.path
from listgame import ListGame as LG
from gameparse import GameParse as GP
from senddata import SendData as SD

def listGames(listPages=None):
  if listPages is None:
    listAll = LG("https://s8.torents-igruha.org/newgames/")
    if os.path.exists('obj/listAllPage.pkl'):
      listPage = common.loadObj("listAllPage")
    else:
      listUnique = listAll.uniqueListGame()
      common.saveObj(listUnique, "listAllPage")
      listPage = listUnique
  return listPage
  
async def dowloader(listPage):
  numGame = 1
  for gameItem in listPage:
  # for gameItem in ["https://s8.torents-igruha.org/778-world-of-tanks.html"]:
    async with aiohttp.ClientSession() as session:
      async with session.get(gameItem) as resp:
        gameHtml = gameItem.split("/")[-1]
        if not os.path.exists('html/'+gameHtml):
          with open("html/"+gameHtml, "w") as file:
            file.write(await resp.text())
            print(str(numGame)+" Game "+gameHtml+"saved to local")
        else:
          print(str(numGame)+" Game + "+gameHtml+" saved to local")
        numGame += 1


def main():
  loop = asyncio.get_event_loop()
  loop.run_until_complete(dowloader(listGames()))