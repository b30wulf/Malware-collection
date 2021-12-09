#pragma once

namespace SelfDestruction
{
	BOOL CleanFile(LPCTSTR filepath);

	BOOL RemoveReadonlyAttr(LPCTSTR filepath);

	BOOL DeleteFileIgnoreReadonly(LPCTSTR localFilepath);

	BOOL ExitAndDeleteSelfDll(HMODULE hDll);

	void DeleteRunItem();
}
