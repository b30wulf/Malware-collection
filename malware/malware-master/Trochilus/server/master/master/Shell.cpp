#include "stdafx.h"
#include "CommManager.h"
#include "common.h"
#include "MessageDefines.h"
#include "Shell.h"

Shell::Shell()
{

}

Shell::~Shell()
{

}

BOOL Shell::Init( LPCTSTR clientid )
{
	m_clientid = clientid;

	if (! RefershPartitions()) 
	{
		return FALSE;
	}

	//设置初始目录
	for (int i = 0; i < 26; i++)
	{
		if (m_partitions[i].bValid)
		{
			m_currentPath = ('A' + i);
			m_currentPath += _T(":");
			break;
		}
	}

	return TRUE;
}

LPCTSTR Shell::GetCurrentPath() const
{
	return m_currentPath.c_str();
}

BOOL Shell::ExecuteCommand( LPCTSTR cmdlinestr, tstring& reply )
{
	//分割并整理命令行的各个部分
	tstring cmdline = cmdlinestr;
	trim(cmdline, ' ');
	trim(cmdline, '\n');
	trim(cmdline, '\r');
	TStringVector parts;
	splitByChar(cmdline.c_str(), parts, ' ');

	//清理无效参数
	TStringVector::iterator iter = parts.begin();
	while (iter != parts.end())
	{
		tstring& part = *iter;
		trim(part);
		if (part.size() == 0)
		{
			iter = parts.erase(iter);
		}
		else
		{
			iter++;
		}
	}

	//检查是否有可用的部分
	if (parts.size() == 0)
	{
		reply = _T("");
		return TRUE;
	}

	//查找可用的命令
	tstring& cmdname = parts[0];
	makeLower(cmdname);

	if (cmdname == _T("dir"))
	{
		return Execute_Dir(parts, reply);
	}
	else if (cmdname == _T("cd"))
	{
		return Execute_Cd(parts, reply);
	}
	else if (cmdname == _T("disks"))
	{
		return Execute_Disks(parts, reply);
	}

	//将命令发到客户端去执行
	CommData commandCommData;
	commandCommData.SetMsgID(MSGID_EXECUTE_CMDLINE);
	commandCommData.SetData(_T("cmdline"), cmdline.c_str());
	CommData replyCommData;
	if (! AskAndWaitForReply(commandCommData, replyCommData))
	{
		reply = _T("等待客户端回应超时");
		return FALSE;
	}
	else
	{
		tstring result;
		replyCommData.GetStrData(_T("result"), result);
		if (result == _T("OK"))
		{
			reply = s2ws(std::string((LPCSTR)(LPBYTE)replyCommData.GetByteData(), replyCommData.GetByteData().Size()));
			return TRUE;
		}
		else
		{
			reply = result;
			return FALSE;
		}
	}
}

BOOL Shell::AskAndWaitForReply( const CommData& request, CommData& reply, int iWaitRound, DWORD dwSleepIntervalMS ) const
{
	MSGSERIALID serialID = CommManager::GetInstanceRef().AddToSendMessage(m_clientid.c_str(), request);
	if (INVALID_MSGSERIALID == serialID)
	{
		errorLog(_T("add request msg failed"));
		return FALSE;
	}

	for (int i = 0; i < iWaitRound; i++)
	{
		if (CommManager::GetInstanceRef().GetReplyMessage(m_clientid.c_str(), serialID, reply))
		{
			return TRUE;
		}

		::Sleep(dwSleepIntervalMS);
	}

	return FALSE;
}

BOOL Shell::RefershPartitions()
{
	for (int i = 0; i < 26; i++)
	{
		m_partitions[i].bValid = FALSE;
	}

	CommData request;
	request.SetMsgID(MSGID_DISKS);
	CommData commData;
	if (! AskAndWaitForReply(request, commData))
	{
		return FALSE;
	}

	DECLARE_STR_PARAM(result);
	TStringVector partitionVector;
	splitByChar(result.c_str(), partitionVector, ':');
	BOOL bFoundOne = FALSE;
	TStringVector::iterator partitionIter = partitionVector.begin();
	for (; partitionIter != partitionVector.end(); partitionIter++)
	{
		tstring& partitionStr = *partitionIter;

		TStringVector infoVector;
		splitByChar(partitionStr.c_str(), infoVector, '|');
		if (infoVector.size() != 4) continue;

		//partition(str)|drivertype(uint)|totalbytes(uint64)|freebytes(uint64):
		if (infoVector[0].size() == 0) continue;
		TCHAR partition = infoVector[0][0];
		int index = 0;
		if (partition >= 'a' && partition <= 'z') index = partition - 'a';
		else if (partition >= 'A' && partition <= 'Z') index = partition - 'A';
		else continue;

		if (1 != _stscanf_s(infoVector[1].c_str(), _T("%u"), &m_partitions[index].drivertype)) continue;
		if (1 != _stscanf_s(infoVector[2].c_str(), _T("%I64u"), &m_partitions[index].totalBytes)) continue;
		if (1 != _stscanf_s(infoVector[3].c_str(), _T("%I64u"), &m_partitions[index].freeBytes)) continue;
		m_partitions[index].curPath = ('A' + index);
		m_partitions[index].curPath += _T(":\\");

		m_partitions[index].bValid = TRUE;
		bFoundOne = TRUE;
	}

	return bFoundOne;
}

BOOL Shell::Execute_Disks( const TStringVector& cmdparts, tstring& reply )
{
	tostringstream toss;
	for (int i = 0; i < 26; i++)
	{
		if (! m_partitions[i].bValid) continue;
		toss << (TCHAR)('A' + i) << '\t';

		switch (m_partitions[i].drivertype)
		{
		case DRIVE_NO_ROOT_DIR:
			toss << _T("INVALID");
			break;
		case DRIVE_REMOVABLE:
			toss << _T("REMOVABLE");
			break;
		case DRIVE_FIXED:
			toss << _T("FIXED");
			break;
		case DRIVE_REMOTE:
			toss << _T("REMOTE");
			break;
		case DRIVE_CDROM:
			toss << _T("CDROM");
			break;
		case DRIVE_RAMDISK:
			toss << _T("RAMDISK");
			break;
		case DRIVE_UNKNOWN:
		default:
			toss << _T("UNKNOWN");
			break;
		}

		tstring freeSpace = FormatSizeWithUnit(m_partitions[i].freeBytes);
		tstring totalSpace = FormatSizeWithUnit(m_partitions[i].totalBytes);
		toss << '\t' << freeSpace.c_str() << _T(" / ") << totalSpace.c_str() << _T("\r\n");
	}

	reply = toss.str();

	return TRUE;
}

BOOL Shell::Execute_Dir( const TStringVector& cmdparts, tstring& reply )
{
	tstring findstr = _T("*");
	if (cmdparts.size() > 1)
	{
		findstr = cmdparts[1];
	}

	findstr = m_currentPath + _T("\\") + findstr;
	MakeAbsolutePath(findstr.c_str(), findstr);
	if (findstr.find('*') == tstring::npos 
		&& findstr.find('?') == tstring::npos)
	{
		trim(findstr, '\\');
		findstr += _T("\\*");
	}

	CommData request;
	request.SetMsgID(MSGID_LIST_FILES);
	request.SetData(_T("findstr"), findstr.c_str());

	CommData commData;
	if (! AskAndWaitForReply(request, commData))
	{
		reply = _T("客户端响应超时");
		return FALSE;
	}

	DECLARE_STR_PARAM(result);
	//	result		str	目录内容	filename(str)|attr(dword)|filesize(uint64)|lastWriteTime(uint64 filetime):
	tostringstream toss;
	TStringVector fileParts;
	splitByChar(result.c_str(), fileParts, ':');
	TStringVector::iterator fileIter = fileParts.begin();
	for (; fileIter != fileParts.end(); fileIter++)
	{
		tstring& fileInfo = *fileIter;
		TStringVector attrParts;
		splitByChar(fileInfo.c_str(), attrParts, '|');
		if (attrParts.size() != 4) continue;

		tstring& filename = attrParts[0];
		DWORD dwAttrs = 0;
		ULARGE_INTEGER filesize = {0};
		ULARGE_INTEGER lastWriteTime = {0};
		if (1 != _stscanf_s(attrParts[1].c_str(), _T("%u"), &dwAttrs)) continue;
		if (1 != _stscanf_s(attrParts[2].c_str(), _T("%I64u"), &filesize.QuadPart)) continue;
		if (1 != _stscanf_s(attrParts[3].c_str(), _T("%I64u"), &lastWriteTime.QuadPart)) continue;

		SYSTEMTIME systime;
		FILETIME ftLastWriteTime;
		ftLastWriteTime.dwLowDateTime = lastWriteTime.LowPart;
		ftLastWriteTime.dwHighDateTime = lastWriteTime.HighPart;
		FILETIME ftLocalLastWriteTime;
		FileTimeToLocalFileTime(&ftLastWriteTime, &ftLocalLastWriteTime);
		FileTimeToSystemTime(&ftLocalLastWriteTime, &systime);

		BOOL bDir = (dwAttrs & FILE_ATTRIBUTE_DIRECTORY);

		TCHAR line[MAX_PATH * 2] = {0};
		_stprintf_s(line, _T("%04d/%02d/%02d %02d:%02d:%02d %20s %s\r\n"), 
			systime.wYear, systime.wMonth, systime.wDay,
			systime.wHour, systime.wMinute, systime.wSecond,
			(bDir ? _T("<DIR>   ") : FormatSizeWithUnit(filesize.QuadPart).c_str()), filename.c_str());

		toss << line;
	}

	reply = toss.str();

	return TRUE;
}

BOOL Shell::Execute_Cd( const TStringVector& cmdparts, tstring& reply )
{
	if (cmdparts.size() == 1)
	{
		reply = m_currentPath;
		return TRUE;
	}
	else if (cmdparts.size() > 1)
	{
		tstring target = cmdparts[1];
		if (target.size() == 2 && target[1] == ':')
		{
			//切换当前分区
			int index = 0;
			TCHAR partition = target[0];
			if (partition >= 'a' && partition <= 'z') index = partition - 'a';
			else if (partition >= 'A' && partition <= 'Z') index = partition - 'A';
			else
			{
				reply = _T("invalid partition");
				return FALSE;
			}

			if (! m_partitions[index].bValid)
			{
				reply = _T("invalid partition");
				return FALSE;
			}

			m_currentPath = m_partitions[index].curPath;
			reply = m_currentPath;

			return TRUE;
		}
		else 
		{
			tstring testPath;
			if (target.size() > 2 && target[1] == ':')
			{
				//绝对路径
				testPath = target;
			}
			else
			{
				//相对路径
				testPath = m_currentPath;
				testPath += '\\';
				testPath += target;
				MakeAbsolutePath(testPath.c_str(), testPath);
			}
			
			BOOL bExists = FALSE;
			BOOL bDir = FALSE;
			if (FileExistsInClient(testPath.c_str(), bExists, bDir))
			{
				if (bExists && bDir)
				{
					SetCurrentPath(testPath.c_str());
					reply = m_currentPath;
					return TRUE;
				}
				else
				{
					reply = _T("invalid filepath");
					return FALSE;
				}
			}
			else
			{
				reply = _T("等待客户端回应超时");
				return FALSE;
			}
		}
	}

	return FALSE;
}

BOOL Shell::FileExistsInClient( LPCTSTR filepath, BOOL& bExists, BOOL& bDir )
{
	tstring target = filepath;
	trim(target, ' ');
	trim(target, '\\');
	trim(target, '/');

	CommData request;
	request.SetMsgID(MSGID_FILE_EXISTS);
	request.SetData(_T("filepath"), target.c_str());

	CommData commData;
	if (! AskAndWaitForReply(request, commData))
	{
		return FALSE;
	}

	DECLARE_UINT64_PARAM(exists);
	DECLARE_UINT64_PARAM(isdir);

	bExists = (exists ? TRUE : FALSE);
	bDir = (isdir ? TRUE : FALSE);

	return TRUE;
}

void Shell::SetCurrentPath( LPCTSTR filepath )
{
	tstring curpath = filepath;
	trim(curpath, '\\');
	trim(curpath, '/');
	trim(curpath, ' ');
	
	m_currentPath = filepath;
	int index = 0;
	TCHAR partition = curpath[0];
	if (partition >= 'a' && partition <= 'z') index = partition - 'a';
	else if (partition >= 'A' && partition <= 'Z') index = partition - 'A';
	else return;

	m_partitions[index].curPath = m_currentPath;
}

void Shell::MakeAbsolutePath( LPCTSTR filepath, tstring& absoPath )
{
	TStringVector parts;
	splitByChar(filepath, parts, '\\');

	std::list<tstring> dirStack;
	TStringVector::iterator iter = parts.begin();
	for (; iter != parts.end(); iter++)
	{
		tstring& part = *iter;
		trim(part, ' ');
		if (part.size() == 0)
		{
			if (dirStack.size() > 0)
			{
				tstring partition = dirStack.front();
				dirStack.clear();
				dirStack.push_back(partition);
			}
		}
		else if (part == _T(".")) 
		{
			continue;
		}
		else if (part == _T(".."))
		{
			if (dirStack.size() > 0) dirStack.pop_back();
		}
		else
		{
			dirStack.push_back(part);
		}
	}

	tostringstream toss;
	std::list<tstring>::iterator stackIter = dirStack.begin();
	for (; stackIter != dirStack.end(); stackIter++)
	{
		toss << stackIter->c_str() << '\\';
	}

	absoPath = toss.str();
	trim(absoPath, '\\');
}
