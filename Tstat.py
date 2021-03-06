#!usr/bin/env python

import radiotherm
# much thanks to Michael Hrivnak <mhrivnak@hrivnak.org> for his simple library!
#i wrote a similar thing in java when i did this project version 1, this is way nicer
#plus I learned mySQL, Python, php, and other stuff during this project
#by Philip Ahlers, http://philipahlers.com

#this script is run using cron - I've been running it every 5 minutes
import MySQLdb
import time
import urllib2
import json  #jaaaaaaaaason


#this small script grabs the temp for where the thermostats are located:raleigh, nc
#wunderground gives nice json! examples can be found on their website.  most of this script is directly from their website.
f = urllib2.urlopen('http://api.wunderground.com/api/***********/geolookup/conditions/q/27606.json')
json_string = f.read()
parsed_json = json.loads(json_string)
temp_f = parsed_json['current_observation']['temp_f']
f.close()



#logging into the mysql server
db = MySQLdb.connect(host="localhost",
                     user="Monitor",
                     passwd="***********",
                     db="Monitor")

cur = db.cursor()  #setting the cursor for the sql database



#start to use this nice library.
def getinfo(ip):
    tstat = radiotherm.get_thermostat(ip)
    #tstat.temp & dicts use dicts as output.

    temp = tstat.temp['raw']  #this grabs the raw value of temp from the therm and puts into a float
    FanState = tstat.fstate['raw']  #this grabs the raw value of fan on or off
    ThermState = tstat.tstate['raw']  #this grabs raw value of thermostat state -if it is commanding cool, heat,or off
    tname = tstat.name['raw']  #name of thermostat given by the radio thermostat company
    ThermMode = tstat.tmode['raw'] #current mode: cool, heat or off.
	

    if (ThermMode == 2):
        comm = tstat.t_cool['raw']  #commanded temp, not sure the difference b/w t_cool and it_cool
    else:
        comm = tstat.t_heat['raw']  #commanded temp for heat

    return {'temp': temp, 'FanState': FanState, 'ThermState': ThermState, 'ThermName': tname, 'comm': comm}


def writeinfo(dict):
    if (dict['temp'] > 0):
		try:
			cur.execute("INSERT INTO tempdat VALUES (CURDATE(),NOW(),%s,%s,%s,%s,%s,%s)",
						(dict['ThermName'], dict['temp'], dict['ThermState'], dict['FanState'], temp_f, dict['comm']))

			db.commit()
			print "\nData Committed!", dict['ThermName']

		except:
			print "Error in committing, rolling back!"
			db.rollback()





#these IPs are internal LAN addresses that won't change.  I've set them up as static in my router.  
writeinfo(getinfo('192.168.0.13'))
writeinfo(getinfo('192.168.0.9'))


db.close()
