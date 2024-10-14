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
import nmap

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


# Recommendations based on vulnerability results
def generate_recommendations(results):
    recommendations = []

    # Check for missing security headers
    if isinstance(results["security_headers"], list) and results["security_headers"]:
        recommendations.append(
            f"Consider adding the following security headers: {', '.join(results['security_headers'])}."
        )

    # SQL Injection recommendations
    if "Potential SQL Injection" in results["sql_injection"]:
        recommendations.append(
            "Implement parameterized queries or prepared statements to prevent SQL Injection vulnerabilities."
        )

    # XSS recommendations
    if "XSS vulnerability detected" in results["xss"]:
        recommendations.append(
            "Sanitize user inputs and use content security policies to mitigate XSS vulnerabilities."
        )

    # SSL recommendations
    if results["ssl_result"] == "SSL Expired":
        recommendations.append(
            "Update your SSL certificate to ensure secure connections."
        )

    # Open ports recommendations
    if isinstance(results["open_ports"], list) and results["open_ports"]:
        recommendations.append(
            f"Close unnecessary open ports: {', '.join(map(str, results['open_ports']))}."
        )

    if not recommendations:
        recommendations.append("No improvements necessary; your site appears secure.")

    return recommendations

# Function to check for missing security headers
def check_security_headers(url):
    """
    Checks the presence of important security headers in the HTTP response.
    """
    try:
        response = requests.get(url)
        headers = response.headers
        missing_headers = []

        # Check for each security header
        if 'X-Frame-Options' not in headers:
            missing_headers.append("X-Frame-Options")
        if 'X-XSS-Protection' not in headers:
            missing_headers.append("X-XSS-Protection")
        if 'Strict-Transport-Security' not in headers:
            missing_headers.append("Strict-Transport-Security")
        if 'Content-Security-Policy' not in headers:
            missing_headers.append("Content-Security-Policy")

        if missing_headers:
            return missing_headers
        return "All Headers Present"
    except Exception as e:
        return f"Error checking headers: {e}"

# Function to perform basic SQL injection testing
def check_sql_injection(url):
    """
    Tests for potential SQL injection vulnerabilities using common payloads.
    """
    test_payloads = ["' OR 1=1--", "' AND 'a'='a", "'; DROP TABLE users;--"]
    for payload in test_payloads:
        target = f"{url}?id={payload}"
        try:
            response = requests.get(target)
            if "error" in response.text or response.status_code == 500:
                return f"Potential SQL Injection with payload: {payload}"
        except Exception as e:
            return f"Error testing SQL Injection: {e}"
    return "No SQL Injection detected"

# Function to check for XSS vulnerabilities
def check_xss(url):
    """
    Tests for Cross-Site Scripting (XSS) vulnerabilities using a sample payload.
    """
    xss_payload = "<script>alert(1)</script>"
    try:
        response = requests.get(f"{url}?query={xss_payload}")
        if xss_payload in response.text:
            return "XSS vulnerability detected"
    except Exception as e:
        return f"Error testing XSS: {e}"
    return "No XSS vulnerability detected"

# Function to check SSL certificate validity
def check_ssl(url):
    """
    Checks the SSL certificate for validity and expiration.
    """
    hostname = url.replace('https://', '').replace('http://', '').split('/')[0]
    context = ssl.create_default_context()
    try:
        with socket.create_connection((hostname, 443)) as sock:
            with context.wrap_socket(sock, server_hostname=hostname) as ssock:
                cert = ssock.getpeercert()
                ssl_expiry_date = cert['notAfter']
                current_time = datetime.utcnow()
                expiry_date = datetime.strptime(ssl_expiry_date, '%b %d %H:%M:%S %Y %Z')
                return "SSL Valid" if expiry_date > current_time else "SSL Expired"
    except Exception as e:
        return f"SSL Error ({e})"

# Function to check for open ports
def check_open_ports(url):
    """
    Scans the target website for open ports using nmap.
    """
    hostname = url.replace('https://', '').replace('http://', '').split('/')[0]
    try:
        nm = nmap.PortScanner()
        nm.scan(hostname, '1-1024')  # Scan ports 1-1024
        open_ports = []
        for host in nm.all_hosts():
            for proto in nm[host].all_protocols():
                lport = nm[host][proto].keys()
                for port in lport:
                    state = nm[host][proto][port]['state']
                    if state == 'open':
                        open_ports.append(port)
        return open_ports if open_ports else "No open ports detected"
    except Exception as e:
        return f"Port scanning error: {e}"

# New function for comprehensive vulnerability scanning
def vulnerability_scan(url):
    """
    Performs a comprehensive scan for vulnerabilities on the given URL.
    """
    ssl_result = check_ssl(url)
    security_headers_result = check_security_headers(url)
    sql_injection_result = check_sql_injection(url)
    xss_result = check_xss(url)
    open_ports_result = check_open_ports(url)

    results = {
        "ssl_result": ssl_result,
        "security_headers": security_headers_result,
        "sql_injection": sql_injection_result,
        "xss": xss_result,
        "open_ports": open_ports_result
    }

    # Generate recommendations based on scan results
    recommendations = generate_recommendations(results)

    # Include recommendations in the results
    results["recommendations"] = recommendations

    return results

@app.route('/vulnerability_scan', methods=['POST'])
def run_vulnerability_scan():
    """
    API endpoint to run the vulnerability scan on a given website.
    """
    data = request.get_json()
    website = data.get('website')

    # Validate input
    if not website:
        return jsonify({"error": "Website URL is required."}), 400

    # Proceed with scanning
    try:
        scan_results = vulnerability_scan(website)
        return jsonify(scan_results)
    except Exception as e:
        return jsonify({"error": str(e)}), 500

if __name__ == '__main__':
    app.run(debug=True)
