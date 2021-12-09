#include "StdAfx.h"
#include "FileTransfer.h"
#include "CommManager.h"


CFileTransfer::CFileTransfer()
{

}
CFileTransfer::~CFileTransfer()
{

}

BOOL CFileTransfer::Init()
{
	CommManager::GetInstanceRef().RegisterMsgHandler(MSGID_GET_FILE, MsgHandler_GetFile, this);
	CommManager::GetInstanceRef().RegisterMsgHandler(MSGID_PUT_FILE, MsgHandler_PutFile, this);

	return TRUE;
}

BOOL CFileTransfer::MsgHandler_GetFile( MSGID msgid, const CommData& commData, LPVOID lpParameter )
{
	return CFileTransfer::GetInstanceRef().MsgHandler_GetFile_Proc(msgid,commData);
}

BOOL CFileTransfer::MsgHandler_GetFile_Proc(MSGID msgid, const CommData& commData)
{
	DECLARE_STR_PARAM(serverpath)
	DECLARE_STR_PARAM(clientpath)
	DECLARE_STR_PARAM(md5)
	DECLARE_UINT64_PARAM(size)
	DECLARE_UINT64_PARAM(offset)
	DECLARE_UINT64_PARAM(total)
	
	MyFile file;
	BOOL ret = file.Open(serverpath.c_str(),GENERIC_READ);
	
	if (!ret)
		return FALSE;

	int FileSize = GetFileSize(file,0);
	
	file.Close();

	do 
	{
		ret = IsHasStop(serverpath.c_str());

		if ( ret )
		{
			break;
		}

		TRANS_STATUS status;

		ByteBuffer buffer;
		int nReaded = 0xffffffff;

		if (FileSize != offset)
			nReaded = CFileParser::GetInstanceRef().Read(serverpath.c_str(),offset,size,md5,buffer);

		if (!nReaded)
			break;

		status.isDown = FALSE;
		status.nCurPos = offset;
		status.nTotal = FileSize;
		lstrcpy(status.strSPath,serverpath.c_str());
		lstrcpy(status.strCPath,clientpath.c_str());

		UpdateTransferList(commData.GetClientID(),status);

		//完成则停止请求
		if ( offset == FileSize )
			break;

		CommData sendData;
		sendData.SetMsgID(MSGID_PUT_FILE);
		sendData.SetData(_T("serverpath"), serverpath.c_str());
		sendData.SetData(_T("clientpath"), clientpath.c_str());
		sendData.SetData(_T("size"), nReaded);
		sendData.SetData(_T("total"),FileSize);
		sendData.SetData(_T("md5"), md5.c_str());
		sendData.SetByteData(buffer,buffer.Size());

		DWORD serialID = CommManager::GetInstanceRef().AddToSendMessage(commData.GetClientID(), sendData);

		if (INVALID_MSGSERIALID == serialID)
		{
			errorLog(_T("add to send msg failed"));
			break;
		}


	} while (FALSE);

	return TRUE;
}

BOOL CFileTransfer::MsgHandler_PutFile( MSGID msgid, const CommData& commData, LPVOID lpParameter )
{
	return CFileTransfer::GetInstanceRef().MsgHandler_PutFile_Proc(msgid,commData);
}

BOOL CFileTransfer::MsgHandler_PutFile_Proc(MSGID msgid, const CommData& commData)
{
	DECLARE_STR_PARAM(serverpath)
	DECLARE_STR_PARAM(clientpath)
	DECLARE_STR_PARAM(md5)
	DECLARE_UINT64_PARAM(size)
	DECLARE_UINT64_PARAM(total)

	ByteBuffer buffer = commData.GetByteData();
	
	do 
	{
		CFileParser& parser = CFileParser::GetInstanceRef();

		if (!parser.IsFileExist(CString(serverpath.c_str())+OPTIONS_EXT))
		{
			DeleteFile(serverpath.c_str());
			parser.CreateFileStatus(serverpath.c_str(),md5.c_str(),total);
		}
		
		BOOL ret = CFileParser::GetInstanceRef().Write(serverpath.c_str(),size,md5.c_str(),buffer);

		buffer.Free();

		FILE_OPTIONS options;
		TRANS_STATUS status;
		
		options.nTotalSize = total;
		lstrcpyA(options.szMD5,t2a(md5.c_str()));

		ret = CFileParser::GetInstanceRef().GetFileCurStatus(serverpath.c_str(),options);
		
		if (!ret)
			break;

		status.isDown = TRUE;
		status.nCurPos = options.nCurSel;
		status.nTotal = options.nTotalSize;
		lstrcpy(status.strSPath,serverpath.c_str());
		lstrcpy(status.strCPath,clientpath.c_str());

 		UpdateTransferList(commData.GetClientID(),status);

		ret = IsHasStop(serverpath.c_str());

		if (!ret && (status.nCurPos != status.nTotal))
			RequestGetFile(commData.GetClientID(),clientpath.c_str(),serverpath.c_str());

		if ((status.nCurPos == status.nTotal))
		{
			DeleteFile(CString(status.strSPath)+OPTIONS_EXT);
		}

	} while (FALSE);

	return TRUE;
}

BOOL CFileTransfer::RequestPutFile( LPCTSTR clientid,LPCTSTR clientpath,LPCTSTR serverpath )
{
	CommData sendData;

	sendData.SetMsgID(MSGID_REQUESTPUT_FILE);
	sendData.SetData(_T("serverpath"), serverpath);
	sendData.SetData(_T("clientpath"), clientpath);

	DWORD serialID = CommManager::GetInstanceRef().AddToSendMessage(clientid,sendData);

	if (INVALID_MSGSERIALID == serialID)
	{
		errorLog(_T("add to send msg failed"));
		return FALSE;
	}

	return TRUE;
}

BOOL CFileTransfer::RequestGetFile( LPCTSTR clientid,LPCTSTR clientpath,LPCTSTR serverpath )
{
	FILE_OPTIONS options;
	BOOL ret = CFileParser::GetInstanceRef().GetFileCurStatus(serverpath,options);

	if (!ret)
		return FALSE;

	CommData sendData;
	sendData.SetMsgID(MSGID_GET_FILE);
	sendData.SetData(_T("serverpath"), serverpath);
	sendData.SetData(_T("clientpath"), clientpath);
	sendData.SetData(_T("size"), MAX_BLOCK_SIZE);
	sendData.SetData(_T("offset"), options.nCurSel);

	DWORD serialID = CommManager::GetInstanceRef().AddToSendMessage(clientid,sendData);

	if (INVALID_MSGSERIALID == serialID)
	{
		errorLog(_T("add to send msg failed"));
		return FALSE;
	}

	return TRUE;
}

BOOL CFileTransfer::AddStopList( LPCTSTR serverpath )
{
	DeleteStopList(serverpath);
	m_csStopMap.Enter();
	{
		m_stopList.push_back(serverpath);
	}
	m_csStopMap.Leave();
	return TRUE;
}

BOOL CFileTransfer::DeleteStopList( LPCTSTR serverpath )
{
	m_csStopMap.Enter();
	{
		TransStopList::iterator it = m_stopList.begin();
		for (; it != m_stopList.end(); it++)
		{
			if (*it == serverpath)
			{
				m_stopList.erase(it);
				break;
			}
		}
	}
	m_csStopMap.Leave();
	return TRUE;
}
BOOL CFileTransfer::DeleteTransferInfo(LPCTSTR clientid, TRANS_STATUS& status )
{
	m_csProcessMap.Enter();
	{
		ProcessMap::iterator it = m_processMap.find(clientid);
		if ( it != m_processMap.end() )
		{
			TransStatusVector::iterator it2 = it->second.begin();

			for ( ; it2 != it->second.end(); it2++ )
			{
				if (lstrcmp(it2->second.strSPath , status.strSPath) == 0)
				{
					DeleteStopList(status.strSPath);
					m_processMap[clientid].erase(it2);
					break;
				}
			}

		}
	}
	m_csProcessMap.Leave();
	return TRUE;
}

BOOL CFileTransfer::IsHasStop(LPCTSTR serverpath)
{
	BOOL ret = FALSE;

	m_csStopMap.Enter();
	{
		TransStopList::iterator it = m_stopList.begin();
		for (; it != m_stopList.end(); it++)
		{
			if (*it == serverpath)
			{
				ret = TRUE;
				break;
			}
		}
	}
	m_csStopMap.Leave();

	return ret;
}

void CFileTransfer::GetTransferList( LPCTSTR clientid,TransStatusVector& list )
{
	m_csProcessMap.Enter();
	{
		ProcessMap::iterator it = m_processMap.find(clientid);
		if (it != m_processMap.end())
		{
			TransStatusVector::iterator it2 = it->second.begin();
			for (; it2 != it->second.end();it2++)
			{
				list[it2->first] = it2->second;
			}
		}
	}
	m_csProcessMap.Leave();
}

BOOL CFileTransfer::GetStatusByPath(LPCTSTR clientid,CString strSPath,TRANS_STATUS& status)
{

	TransStatusVector list;
	GetTransferList(clientid,list);;
	
	TransStatusVector::iterator it = list.begin();

	for(; it != list.end(); it++)
	{
		if (CString(it->second.strSPath) == strSPath)
		{
			status = it->second;
			return TRUE;
		}
	}
	return FALSE;
}

void CFileTransfer::UpdateTransferList( LPCTSTR clientid,TRANS_STATUS& status )
{
	m_csProcessMap.Enter();
	{
		do 
		{
			ProcessMap::iterator it1 = m_processMap.find(clientid);

			//查找是否存在对应clientid的list
			if (it1 != m_processMap.end())
			{
				TransStatusVector &list = m_processMap[clientid];

				//迭代查找符合条件的
				TransStatusVector::iterator it2 = list.begin();

				for (; it2 != list.end(); it2++)
				{
					if (CString(it2->second.strSPath) == CString(status.strSPath))
					{
						list[it2->first] = status;
						break;
					}
				}

				//没有符合条件的就添加
				if (it2 == list.end())
				{
					for(int i = 0 ; i <= 10000 ; i++)
					{
						if (list.find(i) == list.end())
						{
							list[i] = status;
							break;
						}
					}
				}
			}
			//如果list不存在，则添加 
			else
			{
				TransStatusVector newlist;

				newlist[0] = status;
				m_processMap[clientid] = newlist;
			}

		} while (FALSE);
	}
	m_csProcessMap.Leave();
}