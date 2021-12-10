# -*- mode: python -*-

block_cipher = None


a = Analysis(['main.py'],
             pathex=['.'],
             binaries=[],
             datas=[
                ('app/database', 'app/database'),
                ('/usr/lib/kde4/plugins/styles', 'qt4_plugins/styles')
             ],
             hiddenimports=['six', 'packaging', 'packaging.version', 'packaging.specifiers', 'packaging.requirements',
                            'appdirs'],
             hookspath=[],
             runtime_hooks=[],
             excludes=[],
             win_no_prefer_redirects=False,
             win_private_assemblies=False,
             cipher=block_cipher)
pyz = PYZ(a.pure, a.zipped_data,
             cipher=block_cipher)
exe = EXE(pyz,
          a.scripts,
          exclude_binaries=True,
          name='myrdp',
          debug=False,
          strip=False,
          upx=True,
          console=True )
coll = COLLECT(exe,
               a.binaries,
               a.zipfiles,
               a.datas,
               strip=False,
               upx=True,
               name='myrdp')
