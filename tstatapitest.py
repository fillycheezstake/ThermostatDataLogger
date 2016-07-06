#!usr/bin/env python

import radiotherm


tstat = radiotherm.get_thermostat('192.168.0.9')

print tstat.temp

print tstat.tstate

print tstat.tmode

print tstat.fstate

print tstat.fmode

print tstat.version

print tstat.time

print tstat.t_cool

print tstat.it_cool

print tstat.t_heat

print tstat.it_heat

print tstat.sys

print tstat.name

#print tstat.services