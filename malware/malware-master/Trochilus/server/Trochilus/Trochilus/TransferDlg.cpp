// TransferDlg.cpp : 实现文件
//

#include "stdafx.h"
#include "Trochilus.h"
#include "TransferDlg.h"
#include "afxdialogex.h"
#include "IconLoader.h"
#include "thread/RepeatTask.h"

// CTransferDlg 对话框

IMPLEMENT_DYNAMIC(CTransferDlg, CDialogEx)

CTransferDlg::CTransferDlg(CWnd* pParent /*=NULL*/)
	: CDialogEx(CTransferDlg::IDD, pParent)
{
	m_modname = _T("ModTransfer");
}

CTransferDlg::~CTransferDlg()
{
}

void CTransferDlg::DoDataExchange(CDataExchange* pDX)
{
	CDialogEx::DoDataExchange(pDX);
	DDX_Control(pDX, IDC_LIST_TRANSFER, m_transList);
}


BEGIN_MESSAGE_MAP(CTransferDlg, CDialogEx)
	ON_WM_DESTROY()
	ON_BN_CLICKED(IDC_BUTTON_STARTTRANS, &CTransferDlg::OnBnClickedButtonStart)
	ON_BN_CLICKED(IDC_BUTTON_STOP, &CTransferDlg::OnBnClickedButtonStop)
	ON_BN_CLICKED(IDC_BUTTON_DELETE, &CTransferDlg::OnBnClickedButtonDelete)
	ON_WM_SIZE()
END_MESSAGE_MAP()

void CTransferDlg::InitView()
{
	m_ImageList.Create(20,20,ILC_COLOR8|ILC_MASK,2,1);

	m_transList.SetExtendedStyle(LVS_EX_GRIDLINES | LVS_EX_FULLROWSELECT);
	m_transList.InsertColumn(0,_T("Local Location"),LVCFMT_CENTER,200,-1);
	m_transList.InsertColumn(1,_T("Remote Location"),LVCFMT_CENTER,200,-1);
	m_transList.InsertColumn(2,_T("Processer"),LVCFMT_CENTER,150,-1);
	m_transList.InsertColumn(3,_T("Progress"),LVCFMT_CENTER,100,-1);
	m_transList.InsertColumn(4,_T("Status"),LVCFMT_CENTER,75,-1);

	m_transList.SetImageList(&m_ImageList,LVSIL_SMALL);
	m_ImageList.Add(AfxGetApp()->LoadIcon(IDI_ICON_PROCESS));

}
DWORD WINAPI CTransferDlg::CheckTaskList(LPVOID lpParameter)
{
	CTransferDlg* lpDlg = (CTransferDlg*)lpParameter;
	return lpDlg->CheckTaskListProc();
}
int GetItemIdByString(CListCtrl& list,LPCTSTR str,int nSubItem)
{
	for (int n = 0; n < list.GetItemCount() ;n++)
	{
		CString tmp;
		tmp = list.GetItemText(n,0);
		if (tmp == str)
		{
			return n;
		}
	}
	return 0xFFFFFFFF;
}

void CTransferDlg::ModifyStatus(LPCTSTR clientid,TRANS_STATUS status,LPVOID lpParameter)
{
	CTransferDlg* dlg = (CTransferDlg*)lpParameter;

	dlg->ModifyStatusProc(clientid,status,lpParameter);
}

void CTransferDlg::ModifyStatusProc(LPCTSTR clientid,TRANS_STATUS status,LPVOID lpParameter)
{
	TRANS_STATUS& info = status;

	DWORD dwDoneBytes = info.nCurPos;
	DWORD dwTotalBytes = info.nTotal;


	//字节进度
	CString donebytes;
	donebytes.Format(_T("%u MB"), dwDoneBytes);
	CString totalbytes;
	totalbytes.Format(_T("%u MB"), dwTotalBytes);

	float fProcess = ((float)dwDoneBytes)/((float)dwTotalBytes);
	int nProcess = (int)(fProcess * (float)100);


	//百分之进度
	CString process;
	process.Format(_T("%d / %d"),dwDoneBytes,dwTotalBytes);

	CString sstatus = !info.isDown ? _T("Uploading") : _T("Downloading");

	if (IsHasStop(m_clientid,status))
	{
		sstatus = _T("Stopped");
	}
	
	CString progress;
	progress.Format(_T("%d%%"),nProcess);
	if (nProcess == 100)
	{
		sstatus = _T("Finish");
	}

	int nTmp = GetItemIdByString(m_transList,info.strSPath,0);

	if (nTmp == 0xffffffff)
	{
		nTmp = 0;
		int nImage = m_ImageList.Add(CIconLoader::GetInstanceRef().LoadIcon(info.strSPath));
		m_transList.InsertItem(nTmp,info.strSPath,nImage);

		TRANS_STATUS *myStatus = new TRANS_STATUS;

		memcpy(myStatus,&status,sizeof(TRANS_STATUS));

		m_transList.SetItemData(nTmp,(DWORD_PTR)myStatus);
		m_transList.SetItemText(nTmp,1,info.strCPath);
	}
	m_transList.SetItemText(nTmp,2,process);
	m_transList.SetItemText(nTmp,3,progress);
	m_transList.SetItemText(nTmp,4,sstatus);
}

DWORD CTransferDlg::CheckTaskListProc()
{
	QueryTransferStatus(m_clientid,ModifyStatus,this);
	return 0;
}

void CTransferDlg::InitData()
{
	m_checkTask.Init(CheckTaskList,this,_T("CheckTransTask"),0,1);

	m_checkTask.Start();
}
BOOL CTransferDlg::OnInitDialog()
{
	CDialogEx::OnInitDialog();

	InitView();
	InitResize();
	InitData();

	return TRUE;
}


void CTransferDlg::OnDestroy()
{
	__super::OnDestroy();
	m_checkTask.Stop();
}


void CTransferDlg::OnBnClickedButtonStart()
{
	POSITION pos =  m_transList.GetFirstSelectedItemPosition();   

	if (pos == 0)
	{
		AfxMessageBox(_T("No selected!"));
		return;
	}

	int index = m_transList.GetNextSelectedItem(pos);

	TRANS_STATUS* pData = (TRANS_STATUS*)m_transList.GetItemData(index);

	StartFileTransfer(m_clientid,*pData);

}


void CTransferDlg::OnBnClickedButtonStop()
{
	POSITION pos =  m_transList.GetFirstSelectedItemPosition();  

	if (pos == 0)
	{
		AfxMessageBox(_T("No selected!"));
		return;
	}

	int index = m_transList.GetNextSelectedItem(pos);

	TRANS_STATUS* pData = (TRANS_STATUS*)m_transList.GetItemData(index);

	StopFileTransfer(m_clientid,*pData);
}


void CTransferDlg::OnBnClickedButtonDelete()
{
	POSITION pos =  m_transList.GetFirstSelectedItemPosition();   

	if (pos == 0)
	{
		AfxMessageBox(_T("No selected!"));
		return;
	}

	int index = m_transList.GetNextSelectedItem(pos);

	TRANS_STATUS* pData = (TRANS_STATUS*)m_transList.GetItemData(index);

	DeleteFileTransfer(m_clientid,*pData);

	m_transList.DeleteItem(index);

	delete pData;
}
void CTransferDlg::InitResize()
{

	static CResizer::CBorderInfo s_bi[] = {
		{IDC_LIST_TRANSFER,    
		{CResizer::eFixed, IDC_MAIN, CResizer::eLeft},
		{CResizer::eFixed, IDC_MAIN, CResizer::eTop},
		{CResizer::eFixed, IDC_MAIN, CResizer::eRight},
		{CResizer::eFixed, IDC_MAIN, CResizer::eBottom}}
	};

	const int nSize = sizeof(s_bi)/sizeof(s_bi[0]);
	m_resizer.Init(m_hWnd, NULL, s_bi, nSize);
}

void CTransferDlg::OnSize(UINT nType, int cx, int cy)
{
	CDialogEx::OnSize(nType, cx, cy);
	m_resizer.Move();
}
