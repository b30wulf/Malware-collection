// PanelDlg.cpp : 实现文件
//

#include "stdafx.h"
#include "mfcresize/Resizer.h"
#include "Trochilus.h"
#include "FilePanelDlg.h"
#include "afxdialogex.h"

// CPanelDlg 对话框

IMPLEMENT_DYNAMIC(CFilePanelDlg, CDialogEx)

CFilePanelDlg::CFilePanelDlg(CWnd* pParent /*=NULL*/)
	: CDialogEx(CFilePanelDlg::IDD, pParent)
{

}

CFilePanelDlg::~CFilePanelDlg()
{
}

void CFilePanelDlg::DoDataExchange(CDataExchange* pDX)
{
	CDialogEx::DoDataExchange(pDX);
	DDX_Control(pDX, IDC_TAB_PANEL, m_TabCtrl);
}


BEGIN_MESSAGE_MAP(CFilePanelDlg, CDialogEx)
	ON_NOTIFY(TCN_SELCHANGE, IDC_TAB_PANEL, &CFilePanelDlg::OnTcnSelchangeTabPanel)
	ON_WM_CLOSE()
	ON_WM_SIZE()
END_MESSAGE_MAP()


// CPanelDlg 消息处理程序
void CFilePanelDlg::InitTab()
{
	m_TabCtrl.InsertItem(0,_T("File Manager"));
	m_TabCtrl.InsertItem(1,_T("Transfer"));

	CRect rs;
	m_TabCtrl.GetClientRect(&rs);
	rs.top += 21;

	CLIENT_INFO info;
	GetClientInfo(m_clientid,&info);

	m_FileMgr.m_panel = this;

	m_FileMgr.Create(IDD_DIALOG_FILE,&m_TabCtrl);
	m_FileMgr.MoveWindow(&rs);

	m_TransInfo.Create(IDD_DIALOG_TRANSFER,&m_TabCtrl);
	m_TransInfo.MoveWindow(&rs);

	m_FileMgr.ShowWindow(TRUE);
}

void CFilePanelDlg::InitResize()
{
	static CResizer::CBorderInfo s_bi[] = {
		{IDC_TAB_PANEL,    
		{CResizer::eFixed, IDC_MAIN, CResizer::eLeft},
		{CResizer::eFixed, IDC_MAIN, CResizer::eTop},
		{CResizer::eFixed, IDC_MAIN, CResizer::eRight},
		{CResizer::eFixed, IDC_MAIN, CResizer::eBottom}}
	};

	const int nSize = sizeof(s_bi)/sizeof(s_bi[0]);
	m_resizer.Init(m_hWnd, NULL, s_bi, nSize);
}

void CFilePanelDlg::InitView()
{	
	InitTab();

	CString strTitle;
	CLIENT_INFO info;

	BOOL ret = GetClientInfo(m_clientid,&info);

	if (ret )
	{
		IN_ADDR connectIP;
		connectIP.S_un.S_addr = info.connectIP;

		strTitle.Format(_T("Control Panel [%s][%s]"),info.computerName,CString(inet_ntoa(connectIP)).GetBuffer());

		SetWindowText(strTitle);
	}
}

BOOL CFilePanelDlg::OnInitDialog()
{
	CDialogEx::OnInitDialog();
	InitView();
	InitResize();

	return TRUE;
}

void CFilePanelDlg::OnTcnSelchangeTabPanel(NMHDR *pNMHDR, LRESULT *pResult)
{
	int nSel = m_TabCtrl.GetCurSel();

	if (0 == nSel)
	{
		m_FileMgr.ShowWindow(TRUE);
		m_TransInfo.ShowWindow(FALSE);
	}
	else
	{
		m_FileMgr.ShowWindow(FALSE);
		m_TransInfo.ShowWindow(TRUE);
	}
	*pResult = 0;
}

void CFilePanelDlg::OnClose()
{
	this->ShowWindow(FALSE);
}
void CFilePanelDlg::OnSize(UINT nType, int cx, int cy)
{
	CDialogEx::OnSize(nType, cx, cy);

	CRect rs;

	m_resizer.Move();

	if (m_TabCtrl.m_hWnd)
	{
		m_TabCtrl.GetClientRect(&rs);
		rs.top += 21;

		m_FileMgr.MoveWindow(rs);
		m_TransInfo.MoveWindow(rs);
	}

}