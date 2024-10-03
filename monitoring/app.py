from flask import Flask, render_template, request, send_file, jsonify
import requests
import pandas as pd
from io import BytesIO
from datetime import datetime
from flask_cors import CORS
import socket
import ssl
import time
from bs4 import BeautifulSoup
import logging

logging.basicConfig(level=logging.DEBUG)
app = Flask(__name__)
CORS(app)  # Allow all domains to access the API
# Store the last checked status list globally
last_status_list = []

def check_website_status(url):
    try:
        start_time = time.time()
        response = requests.get(url, timeout=50)
        end_time = time.time()
        
        status = "Up" if response.status_code == 200 else f"Down (Status code: {response.status_code})"
        response_time = end_time - start_time
    except requests.ConnectionError:
        status = "Down (Connection Error)"
        response_time = None
    except requests.Timeout:
        status = "Down (Timeout)"
        response_time = None
    except requests.RequestException as e:
        status = f"Down (Error: {e})"
        response_time = None
    
    try:
        ip_address = socket.gethostbyname(url.split('//')[-1].split('/')[0])
    except socket.gaierror:
        ip_address = "Unavailable"
    
    ssl_status = "Not Checked"
    ssl_expiry_date = "N/A"
    
    if url.startswith("https://"):
        hostname = url.split('//')[-1].split('/')[0]
        context = ssl.create_default_context()
        try:
            with socket.create_connection((hostname, 443)) as sock:
                with context.wrap_socket(sock, server_hostname=hostname) as ssock:
                    cert = ssock.getpeercert()
                    ssl_expiry_date = cert['notAfter']
                    current_time = datetime.utcnow()
                    expiry_date = datetime.strptime(ssl_expiry_date, '%b %d %H:%M:%S %Y %Z')
                    ssl_status = "Valid" if expiry_date > current_time else "Expired"
        except Exception as e:
            ssl_status = f"Error ({e})"
    
    return status, ip_address, ssl_status, ssl_expiry_date, response_time



@app.route('/')
def home():
    return render_template('report.html')

@app.route('/check', methods=['POST'])
def check():
    websites = request.form.get('websites')
    website_list = websites.split()
    status_list = []
    current_time = datetime.now().strftime('%Y-%m-%d %H:%M:%S')
    
    for website in website_list:
        status, ip_address, ssl_status, ssl_expiry_date, response_time = check_website_status(website)
        status_list.append({
            'url': website, 
            'status': status, 
            'ip_address': ip_address, 
            'ssl_status': ssl_status, 
            'ssl_expiry_date': ssl_expiry_date, 
            'response_time': f"{response_time:.4f} seconds" if response_time else "N/A",
            'checked_at': current_time
        })

    global last_status_list
    last_status_list = status_list  # Update the global status list

    return jsonify(status_list)

API_KEY = 'AIzaSyAphYiaA9yAtC-FA2DcBgGcR8iFFnx1LNw'  # Replace with your API Key
CX = '2269e3f7b84cd4e41'  # Replace with your Custom Search Engine ID

def google_search(query):
    results = []
    for start in range(1, 51, 10):  # Fetching first 50 results (5 pages of 10)
        url = f"https://www.googleapis.com/customsearch/v1?key={API_KEY}&cx={CX}&q={query}&start={start}"
        response = requests.get(url)

        if response.status_code == 200:
            data = response.json()
            if 'items' in data:
                for item in data['items']:
                    results.append(item['link'])
        else:
            print(f"Failed to access Google. Status code: {response.status_code}")

    return results

# Call the function
google_results = google_search('site:ciamiskab.go.id intext:"slot"')
print(google_results)
@app.route('/search', methods=['POST'])
def search():
    global last_status_list  # Declare global variable to store status
    status_list = []
    current_time = datetime.now().strftime('%Y-%m-%d %H:%M:%S')
    
    # Get the search query from the POST request
    google_query = request.form.get('query', f'site:ciamiskab.go.id intext:"slot" after:{current_time}')
    google_results = google_search(google_query)

    # Update the status list with search results and timestamp
    for result in google_results:
        status_list.append({
            'url': result,
            'checked_at': current_time
        })

    # Update the global status list with new results
    last_status_list.extend(status_list)
    
    # Return the status list as a JSON response
    return jsonify(status_list)

@app.route('/export', methods=['GET'])
def export():
    sorted_status_list = sorted(last_status_list, key=lambda x: x['status'])

    df = pd.DataFrame(sorted_status_list)
    output = BytesIO()
    with pd.ExcelWriter(output, engine='openpyxl') as writer:
        df.to_excel(writer, index=False, sheet_name='Status')
    output.seek(0)
    return send_file(output, download_name='website_status_report.xlsx', as_attachment=True)

if __name__ == '__main__':
    app.run(debug=True)
