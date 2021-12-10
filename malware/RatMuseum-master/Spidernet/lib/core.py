#!usr/bin/env python
################################################
# SpiderNet 
#  By Wandering-Nomad (@wand3ringn0mad)
################################################
#
# This tool for educational uses only.  This  
#  code should not be used for systems that one
#  does not have authorization for.  All 
#  modification / rebranding / public use 
#  requires the authors concent 
#                                               
################################################


import os
import sys

from lib.server import *

active_hosts = []

def start_spidernet():
	while True:
		menu()
	

def read_host_file():
	try:
		if (  os.path.exists('hostlist' ) ): 
			with open( 'hostlist' ) as handle_host_file:
			    return handle_host_file.readlines()
		else:
			sys.exit(" [X] Put Host List in hostlist")
	except IOError:
		sys.exit(" [X] Put Host List in hostlist")

	
def parse_host_file(host_file_handle):
	for host_row in host_file_handle:
		if host_row[0] != "#":
			hostname, port, user, password = host_row.rstrip().split(":")
			connect_hosts(hostname, port, user, password)


def connect_hosts(hostname, port, user, password):
	tmp_server = server(hostname, port, user, password)
	result_status = tmp_server.connect_host()

	if (result_status != 1):
		active_hosts.append(tmp_server)

def menu():
	for num, desc in enumerate(["Connect Hosts", "Update Hosts", "List Hosts", "Host Details", "Run Command", "Open Shell", "Exit"]):
		print "[" + str(num) + "] " + desc

	while True:
		raw_choice = raw_input("#> ")
		if raw_choice.isdigit():
			choice = int(raw_choice)
			break
	print "-----------------------------------"

	if choice == 0:
		parse_host_file(read_host_file())
		print "----------Active Hosts-------------"
		for host in active_hosts:
			host.details()
		print "-----------------------------------"
	elif choice == 1:
		print "----------Clearing Hosts-----------"
		active_hosts[:] = []
		parse_host_file(read_host_file())
		print "----------Active Hosts-------------"
		for host in active_hosts:
			host.details()
		print "-----------------------------------"
	elif choice == 2:
		print "----------Active Hosts-------------"
		for host in active_hosts:
			host.details()		
		print "-----------------------------------"
	elif choice == 3:
		print "----------Hosts Details------------"
		for host in active_hosts:
			host.fulldetails()		
		print "-----------------------------------"
						
	elif choice == 4:
		cmd = raw_input("Command: ")
		for host in active_hosts:
			print "%s : %s" % (host.hostname, host.execute_command(cmd) )

		print "-----------------------------------"	
		
	elif choice == 5:
		for num, host in enumerate(active_hosts):
			print "[%s] %s (%s)" % ( str(num), host.hostname, host.execute_command("ifconfig eth0 | grep 'inet addr' | awk '{ print $2 }' | sed 's/addr://'" ) )

		while True:
			raw_cmd = raw_input("Host: ")
			if raw_cmd.isdigit():
				cmd = int(raw_cmd)
				break
				
		active_hosts[int(cmd)].shell()

		print "-----------------------------------"	
				
	elif choice == 6:
		for host in active_hosts:
			host.shutdown()

		sys.exit(0)	
		
		
		
			
	return;		