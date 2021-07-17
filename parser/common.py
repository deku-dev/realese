import bs4, requests
import pickle

def getCategory(page):
  catPage = bs4.BeautifulSoup(requests.get(page).text, "html5lib")
  catList = catPage.select("#menuigruha li a")
  return { item["href"]:item.text for item in catList}

def saveObj(obj, name ):
  with open('obj/'+ name + '.pkl', 'wb') as f:
    pickle.dump(obj, f, pickle.HIGHEST_PROTOCOL)

def loadObj(name):
  with open('obj/' + name + '.pkl', 'rb') as f:
    return pickle.load(f)