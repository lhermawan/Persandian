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

# Check Security Headers
def check_security_headers(url):
    try:
        response = requests.get(url)
        headers = response.headers
        missing_headers = []
        if 'X-Frame-Options' not in headers:
            missing_headers.append("X-Frame-Options")
        if 'X-XSS-Protection' not in headers:
            missing_headers.append("X-XSS-Protection")
        if 'Strict-Transport-Security' not in headers:
            missing_headers.append("Strict-Transport-Security")
        if 'Content-Security-Policy' not in headers:
            missing_headers.append("Content-Security-Policy")

        return missing_headers if missing_headers else "All Headers Present"
    except Exception as e:
        return f"Error checking headers: {e}"

# Basic SQL Injection Test
def check_sql_injection(url):
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

# XSS Test
def check_xss(url):
    xss_payload = "<script>alert(1)</script>"
    try:
        response = requests.get(f"{url}?query={xss_payload}")
        if xss_payload in response.text:
            return f"XSS vulnerability detected"
    except Exception as e:
        return f"Error testing XSS: {e}"
    return "No XSS vulnerability detected"

# SSL/TLS Check
def check_ssl(url):
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

# Port Scanning (Using Nmap)
def check_open_ports(url):
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

# New function for vulnerability scanning
def vulnerability_scan(url):
    ssl_result = check_ssl(url)
    security_headers_result = check_security_headers(url)
    sql_injection_result = check_sql_injection(url)
    xss_result = check_xss(url)
    open_ports_result = check_open_ports(url)

    return {
        "ssl_result": ssl_result,
        "security_headers": security_headers_result,
        "sql_injection": sql_injection_result,
        "xss": xss_result,
        "open_ports": open_ports_result
    }

@app.route('/vulnerability_scan', methods=['POST'])
def run_vulnerability_scan():
    website = request.form.get('website')
    # websites = [
    #     "https://portal.ciamiskab.go.id"

    # ]
    scan_results = vulnerability_scan(website)
    return jsonify(scan_results)

if __name__ == '__main__':
    app.run(debug=True)
