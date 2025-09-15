import requests
from bs4 import BeautifulSoup
import json
import sys
import socket
from pathlib import Path

# Get the absolute path to the directory of the current script
script_dir = Path(__file__).parent

# Define a relative path to a file (e.g., a data file in a 'data' folder)
data_file_path = script_dir / 'data' / 'last24urnews.txt'

socket.setdefaulttimeout(10)


URL = "https://www.24ur.com"

def send_to_backend(article: dict):
    headers = {
        "Authorization": f"Bearer EbEDMRb748ahv2tgLci4R09eK0S9ekv18qdrspIynMcbTjybk2SfMYQhfrYQ3Oji",
        "Content-Type": "application/json"
    }

    payload = {
        "link": article["link"],
        "summary": article["summary"],
        "text": article["text"]
    }

    response = requests.post("http://localhost:8000/api/articles/analyze", headers=headers, json=payload)
    response.raise_for_status()


# Funkcija, ki naredi request
def req(url):
    try:
        # Pošlji HTTP GET zahtevo na URL
        response = requests.get(url, timeout=10)
        # Preveri, če je bila zahteva uspešna (status code 200)
        response.raise_for_status() 
    except requests.exceptions.RequestException as e:
        # V primeru napake (ni povezave, timeout, ...) izpiši napako na stderr
        # in končaj izvajanje.
        print(json.dumps({"error": f"Napaka pri povezovanju: {e}"}), file=sys.stderr)
        quit()

    return BeautifulSoup(response.content, 'html.parser')

# Postrga osnovno stran z tujimi novicami
def scrape_24ur_tujina():
    result = []
    storage = open(data_file_path, 'r')
    last_news = storage.readline()
    storage.close()
    stop = False
    first_news = True

    # 10 strani povezav pobere
    for ind in range(1, 11):
        url = f"https://www.24ur.com/arhiv/novice/tujina/?p={ind}"
        soup = req(url)
        scraped_articles = []
        main = soup.find('main')
        articles: list(str) = main.find_all('a', class_='flex flex-col lg:flex-row wrap overflow-visible lg:overflow-hidden card-overlay pb-16 group') # type: ignore
        for index, article in enumerate(articles):
            # Preverimo, ali obstajata naslov in href atribut.
            if 'href' in article.attrs: # type: ignore
                #Preverimo katera je zadnja novica
                if URL + article['href'] != last_news:
                    # Izpiše samo novico najbolj na vrhu, torej najnovejšo
                    if first_news:
                        storage = open(data_file_path, 'w')
                        storage.write(URL + article['href'])
                        storage.close()
                        first_news = False
                    scraped_articles.append(URL + article['href'])
                else:
                    stop = True
                    break
        result += singleScrape(scraped_articles)
        if stop:
            break
    print(json.dumps(result, indent=4, ensure_ascii=False))

# Fukncija, ki postrga članke iz interneta
def singleScrape(scraped_articles: list):
    articles = []
    for link in scraped_articles:
        soup = req(link)
        summary = soup.find('p', class_='text-article-summary').text # type: ignore

        body = soup.find('div', class_='article__body') # type: ignore
        text: list = body.find_all('p') # type: ignore

        for index, item in enumerate(text):
            text[index] = item.text
        deli = '  '
        txt = deli.join(text) # type: ignore

        article_data = {
            'link': link, # type: ignore
            'summary': str(summary),
            'text': txt
        }
        articles.append(article_data)
    return(articles)


scrape_24ur_tujina()
