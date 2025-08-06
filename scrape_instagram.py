
import requests
from bs4 import BeautifulSoup
import re

def scrape_instagram_hashtag(hashtag, max_posts=5):
    headers = {
        'User-Agent': 'Mozilla/5.0',
    }
    url = f"https://www.instagram.com/explore/tags/{hashtag}/"
    resp = requests.get(url, headers=headers)
    if resp.status_code != 200:
        print(f"Failed to fetch page: {resp.status_code}")
        return []

    shared_data = re.search(r"window\._sharedData = (.*?);</script>", resp.text)
    if not shared_data:
        print("Could not find shared data.")
        return []

    # Real scraping from Instagram is limited due to dynamic JS and bot protection
    # This is a placeholder that simulates extracting post URLs
    print(f"Simulated scrape for #{hashtag} complete. Return dummy posts.")
    return [f"https://www.instagram.com/p/POST{i}/" for i in range(1, max_posts+1)]

if __name__ == "__main__":
    posts = scrape_instagram_hashtag("muggshot", max_posts=3)
    for post in posts:
        print("Post:", post)
