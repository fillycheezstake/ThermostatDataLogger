# ThermostatDataLogger

Linux Cron runs a python script every 5 minutes.
This script (Tstat.py) places data into a mysql database.

Using Google Charts API (index.html) the data is graphed. In order to get the data out of the array and into the format Google Charts API wants it, a php file converts and passes the data. 
