# ThermostatDataLogger

Linux Cron runs a python script every 5 minutes.
This script (Tstat.py) places data into a mysql database.

Index.html uses the Google Charts API to graph and navigate through the data. A php script converts and passes the data from the mySQL server to the Google Charts API (in the JSON format it needs).
