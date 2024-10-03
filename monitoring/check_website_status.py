import requests

def check_website_status(url):
    try:
        response = requests.get(url, timeout=5)
        # Consider a website down if status code is not in 200 range
        if response.status_code == 200:
            return "Up"
        else:
            return "Down"
    except requests.ConnectionError:
        return "Down"
    except requests.Timeout:
        return "Down"
    except requests.RequestException as e:
        return f"Error: {e}"

if __name__ == "__main__":
    websites = [
        "https://www.google.com",
        "https://www.nonexistentwebsite.com",
        "https://www.github.com"
    ]

    for website in websites:
        status = check_website_status(website)
        print(f"The website {website} is {status}")
