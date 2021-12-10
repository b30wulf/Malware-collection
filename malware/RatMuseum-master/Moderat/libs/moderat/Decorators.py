def connected_to_server(function):
    '''
    Check If Moderator Connected To Server
    :param function:
    :return:
    '''
    def wrapper(moderat):
        if moderat.connected:
            return function(moderat)
    return wrapper


def is_administrator(function):
    '''
    Check If Moderator Privileges is highest
    :param function:
    :return:
    '''
    def wrapper(moderat):
        if moderat.privs == 1:
            return function(moderat)
    return wrapper


def client_is_selected(function):
    '''
    Check If Client Is Selected
    :param function:
    :return:
    '''
    def wrapper(*args, **kwargs):
        try:
            return function(*args, **kwargs)
        except Exception as e:
            print e
    return wrapper


def update_clients(function):
    '''
    Update Online & Offline Tables
    :param function:
    :return:
    '''
    def wrapper(*args, **kwargs):
        if hasattr(args[0], 'moderat'):
            function(*args, **kwargs)
            args[0].moderat.tables.update_clients()
    return wrapper