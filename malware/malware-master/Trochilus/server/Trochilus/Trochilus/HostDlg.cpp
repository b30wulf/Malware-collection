// HostDlg.cpp : 实现文件
//

#include "stdafx.h"
#include "Trochilus.h"
#include "HostDlg.h"
#include "afxdialogex.h"
#include "TrochilusDlg.h"
#include "FilePanelDlg.h"
#include "CmdDlg.h"

// CHostDlg 对话框

IMPLEMENT_DYNAMIC(CHostDlg, CDialogEx)

CHostDlg::CHostDlg(CWnd* pParent /*=NULL*/)
	: CDialogEx(CHostDlg::IDD, pParent)
{
}

CHostDlg::~CHostDlg()
{
}

void CHostDlg::DoDataExchange(CDataExchange* pDX)
{
	CDialogEx::DoDataExchange(pDX);
	DDX_Control(pDX, IDC_LIST_CLIENT, m_Clientlist);
}

void CHostDlg::OnSysCommand(UINT nID, LPARAM lParam)
{
	CDialog::OnSysCommand(nID, lParam);
}

BEGIN_MESSAGE_MAP(CHostDlg, CDialogEx)
	ON_WM_SIZE()
	ON_WM_SYSCOMMAND()
END_MESSAGE_MAP()


// CHostDlg 消息处理程序

void CHostDlg::StartPanel(CLIENT_INFO& info,LPVOID lpParameter)
{
	//msgManager::GetInstanceRef().OpenControlPanel(info.clientid);
}
void CHostDlg::ClientInfoNotify(UINT nMsg, LPVOID lpContext, LPVOID lpParameter)
{
	CLIENT_INFO* pInfo = (CLIENT_INFO*) lpContext;
	CHostDlg* lpDlg = (CHostDlg*)lpParameter;

	return lpDlg->ClientInfoNotifyProc(nMsg,pInfo);
}

static int g_online = 0;

void CALLBACK CHostDlg::ClientInfoNotifyProc( UINT nMsg,CLIENT_INFO* pInfo )
{
	CTrochilusDlg* lpMainDlg = (CTrochilusDlg*)AfxGetApp()->GetMainWnd();
	CString strHost = lpMainDlg->m_wndStatusBar.GetText(2);

	m_csClient.Enter();

	ClientMap::iterator it = m_clients.find(pInfo->clientid);

	if (nMsg == WM_ADD_CLIENT)
	{
		if (it == m_clients.end())
		{
			m_clients[pInfo->clientid] = *pInfo;
			g_online++;
			int i = m_Clientlist.AddClientInfo(&m_clients[pInfo->clientid]);

			m_Clientlist.SetItemColor((int)&m_clients[pInfo->clientid],RGB(255,0,0));
			m_Clientlist.Update(i);
		}
		else
		{
			m_Clientlist.SetItemColor((int)&m_clients[pInfo->clientid],RGB(255,0,0));
			int i = m_Clientlist.GetIdByData((int)&m_clients[pInfo->clientid]);
			m_Clientlist.Update(i);
		}

		m_Clientlist.SetAlive(pInfo->clientid,TRUE);
	}
	else if(nMsg == WM_DEL_CLIENT)
	{
		if (it != m_clients.end())
		{
			m_Clientlist.SetAlive(pInfo->clientid,FALSE);
			m_Clientlist.DeleteClientInfo(&m_clients[pInfo->clientid]);
		}
	}

	m_csClient.Leave();
	strHost.Format(_T("Host: %d"),g_online);
	lpMainDlg->m_wndStatusBar.SetText(strHost,2,0);
}

void CHostDlg::InitResize()
{

	static CResizer::CBorderInfo s_bi[] = {
		{IDC_LIST_CLIENT,    
		{CResizer::eFixed, IDC_MAIN, CResizer::eLeft},
		{CResizer::eFixed, IDC_MAIN, CResizer::eTop},
		{CResizer::eFixed, IDC_MAIN, CResizer::eRight},
		{CResizer::eFixed, IDC_MAIN, CResizer::eBottom}}
	};

	const int nSize = sizeof(s_bi)/sizeof(s_bi[0]);
	m_resizer.Init(m_hWnd, NULL, s_bi, nSize);
}

void CHostDlg::InitView()
{
	//初始化在线主机列表
	m_Clientlist.SetExtendedStyle(LVS_EX_GRIDLINES | LVS_EX_FULLROWSELECT);	
	m_Clientlist.InsertColumn(0,_T("Computer Name"),LVCFMT_CENTER,130,-1);
	m_Clientlist.InsertColumn(1,_T("Lan"),LVCFMT_CENTER,120,-1);
	m_Clientlist.InsertColumn(2,_T("W-Lan"),LVCFMT_CENTER,115,-1);
	m_Clientlist.InsertColumn(3,_T("OS"),LVCFMT_CENTER,130,-1);
	m_Clientlist.InsertColumn(4,_T("CPU Frep"),LVCFMT_CENTER,140,-1);
	m_Clientlist.InsertColumn(5,_T("Memory(MB)"),LVCFMT_CENTER,140,-1);
	m_Clientlist.InsertColumn(6,_T("Languge"),LVCFMT_CENTER,140,-1);
	m_Clientlist.InsertColumn(7,_T("User"),LVCFMT_CENTER,110,-1);
	m_Clientlist.InsertColumn(8,_T("Protocol"),LVCFMT_CENTER,110,-1);
	m_Clientlist.InsertColumn(9,_T("Install Time"),LVCFMT_CENTER,140,-1);

	m_Imagelist.Create(32,32,ILC_COLOR32|ILC_MASK,2,2);
	HICON hIcon0 = ::LoadIcon (AfxGetResourceHandle(), MAKEINTRESOURCE(IDI_ICON_ONLINE));
	m_Imagelist.Add(hIcon0);
	HICON hIcon1 = ::LoadIcon (AfxGetResourceHandle(), MAKEINTRESOURCE(IDI_ICON2));
	m_Imagelist.Add(hIcon1);
	HICON hIcon2 = ::LoadIcon (AfxGetResourceHandle(), MAKEINTRESOURCE(IDI_ICON3));
	m_Imagelist.Add(hIcon2);

	m_Clientlist.SetImageList(&m_Imagelist/*,LVSIL_SMALL*/);
}

BOOL CHostDlg::OnInitDialog()
{
	CDialogEx::OnInitDialog();

	InitView();
	InitResize();
	if (!StartMasterWorking())
	{
		::MessageBox(NULL, _T("初始化工作线程失败！"), _T("Error"), MB_OK | MB_ICONERROR);
		ExitProcess(0);
	}
	SetClientInfoNotifies(ClientInfoNotify,this);

	return TRUE;  // return TRUE unless you set the focus to a control
	// 异常: OCX 属性页应返回 FALSE
}


void CHostDlg::OnSize(UINT nType, int cx, int cy)
{
	CDialogEx::OnSize(nType, cx, cy);
	m_resizer.Move();
}