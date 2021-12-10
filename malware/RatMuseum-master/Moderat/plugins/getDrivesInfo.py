plugin_name = r"""getDrivesInfo"""
plugin_description = r"""Get Info About Logical Drives"""
plugin_type = r"""remote"""
plugin_source = r"""
uppercase = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ'
type_values = {
0: 'The drive type cannot be determined',
1: 'The root path is invalid; for example, there is no volume mounted at the specified path.',
2: 'The drive has removable media; for example, a floppy drive, thumb drive, or flash card reader.',
3: 'The drive has fixed media; for example, a hard disk drive or flash drive.',
4: 'The drive is a remote (network) drive.',
5: 'The drive is a CD-ROM drive.',
6: 'The drive is a RAM disk.',
}
bitmask = Kernel32.GetLogicalDrives()
for letter in uppercase:
    drive = u'{}:\\'.format(letter)
    if bitmask & 1:
        try:
            os.chdir(drive)
        except:
            continue
        volume_name_buffer = ctypes.create_unicode_buffer(1024)
        Kernel32.GetVolumeInformationW(drive, volume_name_buffer,
                                       ctypes.sizeof(volume_name_buffer), None, None, None, None,
                                       None)
        if len(volume_name_buffer.value) == 0:
            lnk_name = 'Removable Disk'
        else:
            lnk_name = volume_name_buffer.value
        mprint += '''
        Drive Letter: %s
        Drive Label: %s
        Drive Type: %s
        <br>''' % (drive, lnk_name, type_values[Kernel32.GetDriveTypeW(drive)])
    bitmask >>= 1

"""