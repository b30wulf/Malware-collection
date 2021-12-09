// DownDlg.cpp : 实现文件
//

#include "stdafx.h"
#include "Trochilus.h"
#include "DownDlg.h"
#include "afxdialogex.h"


// CDownDlg 对话框

IMPLEMENT_DYNAMIC(CDownDlg, CDialogEx)

CDownDlg::CDownDlg(LPCTSTR clientid,LPCTSTR path,CWnd* pParent /*=NULL*/)
	: CDialogEx(CDownDlg::IDD, pParent)
{
	m_clientid = clientid;
	m_path = path;
}

CDownDlg::~CDownDlg()
{
}

void CDownDlg::DoDataExchange(CDataExchange* pDX)
{
	CDialogEx::DoDataExchange(pDX);
}


BEGIN_MESSAGE_MAP(CDownDlg, CDialogEx)
	ON_BN_CLICKED(IDC_BUTTON_DOWN, &CDownDlg::OnBnClickedButtonDown)
END_MESSAGE_MAP()


// CDownDlg 消息处理程序


void CDownDlg::OnBnClickedButtonDown()
{
	CString url;

	GetDlgItemText(IDC_EDIT_DOWNURL,url);

	tstring name = url;
	int pos = name.find_last_of('/');

	name = name.substr(pos + 1);

	m_path += name.c_str();

	HttpDownLoad(m_clientid,url,m_path);

	OnOK();
}
