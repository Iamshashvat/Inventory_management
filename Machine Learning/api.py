from flask import Flask, request, jsonify
import json
import csv

app = Flask(__name__)

@app.route('/', methods=['GET', 'POST'])
def getJSON():
    if request.method == 'GET':
       csvfile = open('Book1.csv', 'r')
       fieldnames = ("rdate","Open","High","Low","Volume")
       reader = csv.DictReader( csvfile, fieldnames)
       out = json.dumps( [ row for row in reader ] )
       return out

@app.route('/keyboard/', methods=['GET', 'POST'])
def getKeyboardJSON():
    if request.method == 'GET':
       csvfile = open('Keyboard.csv', 'r')
       fieldnames = ("rdate","pname","price","discount","quantity")
       reader = csv.DictReader( csvfile, fieldnames)
       out = json.dumps( [ row for row in reader ] )
       return out

@app.route('/mouse/', methods=['GET', 'POST'])
def getMouseJSON():
    if request.method == 'GET':
       csvfile = open('Mouse.csv', 'r')
       fieldnames = ("rdate","pname","price","discount","quantity")
       reader = csv.DictReader( csvfile, fieldnames)
       out = json.dumps( [ row for row in reader ] )
       return out

@app.route('/harddisk/', methods=['GET', 'POST'])
def getHDDJSON():
    if request.method == 'GET':
       csvfile = open('Hardisk.csv', 'r')
       fieldnames = ("rdate","pname","price","discount","quantity")
       reader = csv.DictReader( csvfile, fieldnames)
       out = json.dumps( [ row for row in reader ] )
       return out

@app.route('/monitor/', methods=['GET', 'POST'])
def getMonitorJSON():
    if request.method == 'GET':
       csvfile = open('Monitor.csv', 'r')
       fieldnames = ("rdate","pname","price","discount","quantity")
       reader = csv.DictReader( csvfile, fieldnames)
       out = json.dumps( [ row for row in reader ] )
       return out        

@app.route('/laptop/', methods=['GET', 'POST'])
def getLaptopJSON():
    if request.method == 'GET':
       csvfile = open('Laptop.csv', 'r')
       fieldnames = ("rdate","pname","price","discount","quantity")
       reader = csv.DictReader( csvfile, fieldnames)
       out = json.dumps( [ row for row in reader ] )
       return out  


if __name__ == '__main__':
    app.run(debug=True)
