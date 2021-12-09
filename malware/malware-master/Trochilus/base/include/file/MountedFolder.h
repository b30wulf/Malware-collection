#pragma once

namespace MountedFolder
{
	BOOL IsMountedFolder(LPCTSTR dirpath);

	BOOL GetRealDirpath(LPCTSTR dirpath, tstring& realDirpath);
}
