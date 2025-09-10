import requests
from bs4 import BeautifulSoup
import json
import sys

URL = "https://www.bbc.com"

# Funkcija, ki naredi request za pridobivanje linkov do člankov
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

    return response.content

# Funkcija za delanje requestov za članke
def req_article(url):
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
def scrape_BBC_world():
    result = []
    for index in range(0, 10):
        url = f"https://web-cdn.api.bbci.co.uk/xd/content-collection/07cedf01-f642-4b92-821f-d7b324b8ba73?country=si&page={index}&size=9&path=%2Fnews%2Fworld"
        soup = json.loads(req(url))['data']
        links = []

        for link in soup:
            links.append(URL + link['path'])

        result.append(singleScrape(links))

        # Izpiši zbrane podatke v JSON formatu na standardni izhod (stdout).
    res = json.dumps(result, indent=4, ensure_ascii=False)

    return res

# Postrga en članek
def singleScrape(scraped_articles: list):
    articles = []
    for link in scraped_articles:
        print(link)
        soup = req_article(link)
        summary = soup.find('h1').text # type: ignore

        txt = ""
        if link[25] != 'v':
            body = soup.find_all('div', attrs={'data-component': 'text-block'})
            for index, item in enumerate(body):
                body[index] = item.text
            deli = ''
            txt = deli.join(body) # type: ignore
        else:
            body = soup.find_all('p', class_='sc-9a00e533-0 hxuGS')
            for index, item in enumerate(body):
                body[index] = item.text
            deli = ''
            txt = deli.join(body) # type: ignore

        article_data = {
            'link': link, # type: ignore
            'summary': str(summary),
            'text': txt
        }
        articles.append(article_data)
    return articles


if __name__ == '__main__':
    scrape_BBC_world()
