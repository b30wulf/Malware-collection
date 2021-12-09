// ListenDlg.cpp : 实现文件
//

#include "stdafx.h"
#include "Trochilus.h"
#include "ListenDlg.h"
#include "afxdialogex.h"
#include "CommNames.h"


// CListenDlg 对话框

IMPLEMENT_DYNAMIC(CListenDlg, CDialogEx)

CListenDlg::CListenDlg(CWnd* pParent /*=NULL*/)
	: CDialogEx(CListenDlg::IDD, pParent)
{

}

CListenDlg::~CListenDlg()
{
}

void CListenDlg::DoDataExchange(CDataExchange* pDX)
{
	CDialogEx::DoDataExchange(pDX);
	DDX_Control(pDX, IDC_LIST_LISTEN, m_listenList);
	DDX_Control(pDX, IDC_COMBO_PROTO, m_protoList);
}

void CListenDlg::InitView()
{
	m_Imagelist.Create(40,40,ILC_COLOR32|ILC_MASK,2,2);

	m_listenList.SetExtendedStyle(LVS_EX_GRIDLINES | LVS_EX_FULLROWSELECT);	
	m_listenList.InsertColumn(0,_T("Protocol"),LVCFMT_CENTER,250,-1);
	m_listenList.InsertColumn(1,_T("Port"),LVCFMT_CENTER,170,-1);
// 	m_listenList.InsertColumn(2,_T("Status"),LVCFMT_CENTER,115,-1);
// 	m_listenList.InsertColumn(3,_T("Error"),LVCFMT_CENTER,200,-1);
	
	m_Imagelist.Add(AfxGetApp()->LoadIcon(IDI_ICON_LIS));
	m_listenList.SetImageList(&m_Imagelist);

	m_protoList.InsertString(0,_T("HTTP"));
	m_protoList.SetItemData(0,COMMNAME_HTTP);

	m_protoList.InsertString(1,_T("HTTPS"));
	m_protoList.SetItemData(1,COMMNAME_HTTPS);

	m_protoList.InsertString(2,_T("TCP"));
	m_protoList.SetItemData(2,COMMNAME_TCP);

// 	m_protoList.InsertString(3,_T("TCPS"));
// 	m_protoList.SetItemData(3,COMMNAME_TCPS);

	m_protoList.InsertString(3,_T("UDP"));
	m_protoList.SetItemData(3,COMMNAME_UDP);

// 	m_protoList.InsertString(5,_T("UDPS"));
// 	m_protoList.SetItemData(5,COMMNAME_UDPS);

	m_protoList.SetCurSel(0);

	SetDlgItemText(IDC_EDIT_PORT,_T("80"));
}


BEGIN_MESSAGE_MAP(CListenDlg, CDialogEx)
	ON_BN_CLICKED(IDC_BUTTON_START, &CListenDlg::OnBnClickedButtonStart)
	ON_BN_CLICKED(IDC_BUTTON_STOP, &CListenDlg::OnBnClickedButtonStop)
	ON_NOTIFY(NM_RCLICK, IDC_LIST_LISTEN, &CListenDlg::OnNMRClickListListen)
	ON_CBN_SELENDCANCEL(IDC_COMBO_PROTO, &CListenDlg::OnCbnSelendcancelComboProto)
	ON_CBN_SELCHANGE(IDC_COMBO_PROTO, &CListenDlg::OnCbnSelchangeComboProto)
	ON_WM_SIZE()
END_MESSAGE_MAP()


// CListenDlg 消息处理程序


BOOL CListenDlg::OnInitDialog()
{
	CDialogEx::OnInitDialog();


	InitView();
	InitResize();

	return TRUE; 
}

void CListenDlg::InitResize()
{
	static CResizer::CBorderInfo s_bi[] = {
		{IDC_LIST_LISTEN,    
		{CResizer::eFixed, IDC_MAIN, CResizer::eLeft},
		{CResizer::eFixed, IDC_MAIN, CResizer::eTop},
		{CResizer::eFixed, IDC_MAIN, CResizer::eRight},
		{CResizer::eFixed, IDC_MAIN, CResizer::eBottom}},
		{IDC_STATIC1,
		{CResizer::eFixed, IDC_MAIN, CResizer::eLeft},
		{CResizer::eFixed, IDC_MAIN, CResizer::eLeft},
		{CResizer::eFixed, IDC_MAIN, CResizer::eRight},
		{CResizer::eFixed, IDC_MAIN, CResizer::eRight}}
	};

	const int nSize = sizeof(s_bi)/sizeof(s_bi[0]);
	m_resizer.Init(m_hWnd, NULL, s_bi, nSize);
}

void CListenDlg::OnBnClickedButtonStart()
{
	int sel = m_protoList.GetCurSel();

	int port = GetDlgItemInt(IDC_EDIT_PORT);

	int nCount = m_listenList.GetItemCount();
	
	CString strPort;

	strPort.Format(_T("%d"),port);

	CString strProtocol;

	int data = m_protoList.GetItemData(sel);

	if (data == COMMNAME_HTTP)
	{
		strProtocol = _T("HTTP");
	}
	else if (data == COMMNAME_HTTPS)
	{
		strProtocol = _T("HTTPS");
	}
	else if (data == COMMNAME_TCP)
	{
		strProtocol = _T("TCP");
	}
	else if (data == COMMNAME_TCPS)
	{
		strProtocol = _T("TCPS");
	}
	else if (data == COMMNAME_UDP)
	{
		strProtocol = _T("UDP");
	}
	else if (data == COMMNAME_UDPS)
	{
		strProtocol = _T("UDPS");
	}

	m_listenList.InsertItem(nCount,strProtocol,0);
	m_listenList.SetItemText(nCount,1,strPort);

	int serial = AddCommService(port,m_protoList.GetItemData(sel));

	if (serial)
	{
		m_listenList.SetItemData(nCount,serial);
// 		m_listenList.SetItemText(nCount,2,_T("Running"));
// 		m_listenList.SetItemText(nCount,3,_T("None"));
		m_listenList.SetItemColor(serial,RGB(255,0,0));
	}
	else
	{
// 		m_listenList.SetItemData(nCount,serial);
// 		m_listenList.SetItemText(nCount,2,_T("Stop"));
// 		m_listenList.SetItemText(nCount,3,_T("Port was used!"));
		m_listenList.DeleteItem(nCount);
		AfxMessageBox(_T("Listen to port faild!"));
	}
	
}


void CListenDlg::OnBnClickedButtonStop()
{
	CString str;
	POSITION pos=m_listenList.GetFirstSelectedItemPosition();
	if(pos == NULL)
		AfxMessageBox(_T("No item has selected!"));
	else
	{
		int nIdx = -1;
		nIdx = m_listenList.GetNextSelectedItem(pos);

		DWORD serial = m_listenList.GetItemData(nIdx);
		DeleteCommService(serial);
		m_listenList.DeleteItem(nIdx);
	}

}
void CListenDlg::OnNMRClickListListen(NMHDR *pNMHDR, LRESULT *pResult)
{
// 	LPNMITEMACTIVATE pNMItemActivate = reinterpret_cast<LPNMITEMACTIVATE>(pNMHDR);
// 
// 	POSITION pos=m_listenList.GetFirstSelectedItemPosition();
// 
// 	if (!pos)
// 		return;
// 
// 	CPoint point;
// 
// 	::GetCursorPos(&point);
// 
// 	CMenu menu;
// 
// 	menu.LoadMenu(IDR_MENU_LIS);
// 
// 	CMenu* pMenu = menu.GetSubMenu(0);
// 
// 	pMenu->TrackPopupMenu(TPM_LEFTALIGN|TPM_RIGHTBUTTON, point.x, point.y, this );
// 
// 	*pResult = 0;
}


void CListenDlg::OnCbnSelendcancelComboProto()
{

	// TODO: 在此添加控件通知处理程序代码
}


void CListenDlg::OnCbnSelchangeComboProto()
{
	int nSel = m_protoList.GetCurSel();
	int data = m_protoList.GetItemData(nSel);

	if(data == COMMNAME_HTTP)
	{
		SetDlgItemText(IDC_EDIT_PORT,_T("80"));
	}
	else if(data == COMMNAME_HTTPS)
	{
		SetDlgItemText(IDC_EDIT_PORT,_T("443"));
	}
	else if(data == COMMNAME_TCP)
	{
		SetDlgItemText(IDC_EDIT_PORT,_T("8082"));
	}
	else if(data == COMMNAME_UDP)
	{
		SetDlgItemText(IDC_EDIT_PORT,_T("8082"));
	}
	else
	{
		SetDlgItemText(IDC_EDIT_PORT,_T("8082"));
	}
}


void CListenDlg::OnSize(UINT nType, int cx, int cy)
{
	CDialogEx::OnSize(nType, cx, cy);
	m_resizer.Move();
	// TODO: 在此处添加消息处理程序代码
}
