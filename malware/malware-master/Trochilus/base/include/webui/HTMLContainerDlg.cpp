#include "stdafx.h"
#include "HTMLContainerDlg.h"
// HTMLContainerDlg.cpp : 实现文件
//

CCriticalSection	CHTMLContainerDlg::s_mapSection;
CHTMLContainerDlg::DialogSet CHTMLContainerDlg::s_dialogSet;

// CHTMLContainerDlg 对话框

IMPLEMENT_DYNCREATE(CHTMLContainerDlg, CDHtmlDialog)

CHTMLContainerDlg::CHTMLContainerDlg(CWnd* pParent /*=NULL*/)
	: CDHtmlDialog((UINT)0, 0, pParent)
	, m_bTitleRectAvailable(FALSE)
	, m_backspaceTime(0)
{
	RegisterDialog(this);
}

CHTMLContainerDlg::CHTMLContainerDlg(UINT nIDTemplate, UINT nHtmlResID /*= 0*/, CWnd *pParentWnd /*= NULL*/, int iWidth /*= 0*/, int iHeight /*= 0*/ )
	: CDHtmlDialog(nIDTemplate, nHtmlResID, pParentWnd)
	, m_bTitleRectAvailable(FALSE)
	, m_iWidth(iWidth)
	, m_iHeight(iHeight)
{
	RegisterDialog(this);
}

CHTMLContainerDlg::~CHTMLContainerDlg()
{
	UnregisterDialog(this);
}

void CHTMLContainerDlg::SetHtmlAndCom(CString strURL, CString strProg)
{
	HRESULT		hr		= NOERROR;
	m_strURL = strURL;
	hr = m_spComDisp.CoCreateInstance(strProg);
	if(FAILED(hr))
	{
		TRACE(_T("Some error when create com object...\n"));
	}
	SetExternalDispatch(m_spComDisp);
}

void CHTMLContainerDlg::SetTitleRect(LONG x, LONG y, LONG width, LONG height)
{
	m_titleRect.left = x;
	m_titleRect.top = y;
	m_titleRect.right = x + width;
	m_titleRect.bottom = y + height;

	m_bTitleRectAvailable = TRUE;
}

void CHTMLContainerDlg::DoDataExchange(CDataExchange* pDX)
{
	CDHtmlDialog::DoDataExchange(pDX);
}

BOOL CHTMLContainerDlg::OnInitDialog()
{
	AfxEnableControlContainer();

	CDialog::OnInitDialog();

	RECT rectClient;
	GetClientRect(&rectClient);

	// if we've been created from the dynamic template
	// set the caption
	if(!m_lpszTemplateName)
		SetWindowText(m_strDlgCaption);

	// check if there is a browser control on the dialog
	// already
	CWnd *pCtrl = GetDlgItem(AFX_IDC_BROWSER);
	LPUNKNOWN lpUnk;
	if(pCtrl)
	{
		lpUnk = pCtrl->GetControlUnknown();
		if (lpUnk && SUCCEEDED(lpUnk->QueryInterface(IID_IWebBrowser2, (void **) &m_pBrowserApp)))
		{
			m_wndBrowser.Attach(pCtrl->m_hWnd);
			m_bAttachedControl = TRUE;
		}
	}
	if(m_pBrowserApp == NULL)
	{
		// create the control window
		m_wndBrowser.CreateControl(CLSID_WebBrowser, NULL,
			WS_VISIBLE | WS_CHILD, rectClient, this, AFX_IDC_BROWSER);
		lpUnk = m_wndBrowser.GetControlUnknown();
		if(FAILED(lpUnk->QueryInterface(IID_IWebBrowser2, (void**) &m_pBrowserApp)))
		{
			m_wndBrowser.DestroyWindow();
			DestroyWindow();
			return TRUE;
		}
	}
	Navigate(m_strURL);

	m_pBrowserApp->put_Silent(VARIANT_TRUE);//禁止脚本错误提示  
	
	//在不同dpi的情况下，使用相同大小的显示
	if (m_iWidth > 0 && m_iHeight > 0)
	{
		SetWindowPos(NULL, 0, 0, m_iWidth, m_iHeight, SWP_NOMOVE);
	}

	if (m_dialogTitle.GetLength() > 0) SetWindowText(m_dialogTitle);

	return TRUE;  // 除非将焦点设置到控件，否则返回 TRUE
}

BEGIN_MESSAGE_MAP(CHTMLContainerDlg, CDHtmlDialog)
END_MESSAGE_MAP()

BEGIN_DHTML_EVENT_MAP(CHTMLContainerDlg)
	DHTML_EVENT_ONCLICK(_T("ButtonOK"), OnButtonOK)
	DHTML_EVENT_ONCLICK(_T("ButtonCancel"), OnButtonCancel)
END_DHTML_EVENT_MAP()



// CHTMLContainerDlg 消息处理程序

HRESULT CHTMLContainerDlg::OnButtonOK(IHTMLElement* /*pElement*/)
{
	OnOK();
	return S_OK;
}

HRESULT CHTMLContainerDlg::OnButtonCancel(IHTMLElement* /*pElement*/)
{
	OnCancel();
	return S_OK;
}

void CHTMLContainerDlg::OnDocumentComplete(LPDISPATCH pDisp, LPCTSTR szUrl)
{
	CDHtmlDialog::OnDocumentComplete(pDisp, szUrl);

	// TODO: 在此添加专用代码和/或调用基类
	//AfxMessageBox(_T("DLL加载文档成功..."));
}

STDMETHODIMP CHTMLContainerDlg::GetHostInfo(DOCHOSTUIINFO* pInfo)
{
	//pInfo->dwFlags = DOCHOSTUIFLAG_THEME;// | DOCHOSTUIFLAG_DIALOG;
	pInfo->cbSize = sizeof(DOCHOSTUIINFO);
	pInfo->dwFlags = //DOCHOSTUIFLAG_DIALOG | 
		DOCHOSTUIFLAG_THEME  | 
		DOCHOSTUIFLAG_NO3DBORDER | 
		DOCHOSTUIFLAG_SCROLL_NO;
	pInfo->dwDoubleClick = DOCHOSTUIDBLCLK_DEFAULT;

	return S_OK;
}

STDMETHODIMP CHTMLContainerDlg::GetDropTarget(IDropTarget *pDropTarget, IDropTarget **ppDropTarget)
{
	*ppDropTarget = (IDropTarget*)&m_droptarget;
 
 	return S_OK; // 你之前返回E_NOTIMPL，将导致你的IDropTarget接口在首次被调用后不再调用，因为以后调用默认实现了
}

BOOL CHTMLContainerDlg::CanAccessExternal()
{
	// we trust all com object (haha, you can make virus)
	return TRUE;
}

BOOL CHTMLContainerDlg::PreTranslateMessage(MSG* pMsg)  
{
	// TODO: 在此添加专用代码和/或调用基类
	if ((pMsg->message == WM_RBUTTONDOWN) || (pMsg->message == WM_RBUTTONDBLCLK))
	{
		/*CPoint point(pMsg->pt);
		ScreenToClient(&point);
		IHTMLDocument2* pdoc2 = NULL;
		IHTMLElement* pElement = NULL;
		IDispatch* pDisp=NULL;
		pDisp = GetHtmlDocument();
		pDisp->QueryInterface(IID_IHTMLDocument2,(void**)&pdoc2);
		pDisp->Release();
		pdoc2->elementFromPoint(point.x,point.y,&pElement);
		pdoc2->Release();
		if(pElement)
		{
			BSTR ID;
			pElement->get_id(&ID);
			pElement->Release();
			CString str=(LPCTSTR)(_bstr_t)ID;
			if(str == "Layer1")
			{
				//CMenu menu;
				//menu.LoadMenu(IDR_MENU1 );
				//CMenu* pmenu=menu.GetSubMenu(0);
				//pmenu->TrackPopupMenu(0,pMsg->pt.x,pMsg->pt.y,this);
			}
		}*/
		return TRUE;//如果想完全屏蔽掉,不显示任何菜单,直接返回TRUE就行,上面这些代码演示了怎么对html中特定ID的元素弹出自己想要显示的菜单 
	}
 	else if (pMsg->message == WM_LBUTTONDOWN && m_bTitleRectAvailable)
 	{
		RECT windowRect;
		GetWindowRect(&windowRect);

		RECT titleRect;
		titleRect.left = windowRect.left + m_titleRect.left;
		titleRect.right = windowRect.left + m_titleRect.right;
		titleRect.top = windowRect.top + m_titleRect.top;
		titleRect.bottom = windowRect.top + m_titleRect.bottom;
		
 		POINT htPoint;
 		GetCursorPos(&htPoint);
		if (htPoint.x > titleRect.left && htPoint.x < titleRect.right
			&& htPoint.y > titleRect.top && htPoint.y < titleRect.bottom)
		{
			PostMessage(WM_NCLBUTTONDOWN, HTCAPTION, MAKEWPARAM(htPoint.x, htPoint.y));
		}
 	}
	else if ( pMsg->message == WM_KEYDOWN )
	{
		switch(pMsg->wParam)
		{
		case VK_ESCAPE:
			return TRUE; //直接返回TRUE
			break;
 		case VK_RETURN:
			TranslateMessage(pMsg);
			DispatchMessage(pMsg);
			return TRUE;
		case VK_BACK:
			_time64(&m_backspaceTime);
			break;
#ifndef _DEBUG
		case VK_F5:
			return TRUE;
			break;
#endif
		}
	}
	else if (pMsg->message == WM_SYSKEYDOWN && pMsg->wParam == VK_F4)
	{
		if (HandleAltF4()) return TRUE;
	}
	
	return CDHtmlDialog::PreTranslateMessage(pMsg);
} 

void CHTMLContainerDlg::SetWindowEllispeFrame(int nWidthEllipse, int nHeightEllipse)
{
	HDC hdc = ::GetDC(m_hWnd);
	HDC hdcMem = CreateCompatibleDC(hdc);
	::ReleaseDC(m_hWnd, hdc);

	RECT rect;
	GetWindowRect(&rect);

	// 画一个圆角矩形。
	BeginPath(hdcMem);
	RoundRect(hdcMem, 0, 0, rect.right - rect.left, rect.bottom - rect.top, nWidthEllipse, nHeightEllipse); 
	EndPath(hdcMem);

	HRGN hRgn = PathToRegion(hdcMem); // 最后把路径转换为区域。

	SetWindowRgn(hRgn, TRUE);
}

void CHTMLContainerDlg::SetDropCallback( FnDropFilesCallback fnCallback, LPVOID lpParameter )
{
	m_droptarget.SetDropCallback(fnCallback, lpParameter);
}

//获取JS脚本
bool CHTMLContainerDlg::GetJScript(CComPtr<IDispatch>& spDisp)
{
	HRESULT hr = S_OK;
	if (!m_spDoc) {
		hr = GetDHtmlDocument(&m_spDoc);
		ATLASSERT(SUCCEEDED(hr));
	}
	if (SUCCEEDED(hr)) {
		hr = m_spDoc->get_Script(&spDisp);
		ATLASSERT(SUCCEEDED(hr));
	}

	return SUCCEEDED(hr);
}

bool CHTMLContainerDlg::CallJScript(const CString strFunc)
{
	CComPtr<IHTMLWindow2> spWindow;
	HRESULT hr = m_spDoc->get_parentWindow(&spWindow);
	if (FAILED(hr)) {
		return false;
	}
	VARIANT vaResult;
	vaResult.vt = VT_EMPTY;
	hr = spWindow->execScript(strFunc.AllocSysString(), _T("JavaScript"), &vaResult);
	return SUCCEEDED(hr);
}

//调用JS函数
CComVariant CHTMLContainerDlg::CallJScript(const CString strFunc, const CStringArray& paramArray)
{
	//Getting IDispatch for Java Script objects
	CComPtr<IDispatch> spScript;
	if(!GetJScript(spScript))
	{
		::OutputDebugString(_T("Cannot GetScript"));
		return false;
	}
	//Find dispid for given function in the object
	CComBSTR bstrMember(strFunc);
	DISPID dispid = NULL;
	HRESULT hr = spScript->GetIDsOfNames(IID_NULL,&bstrMember,1,
		LOCALE_SYSTEM_DEFAULT,&dispid);
	if(FAILED(hr)) {
		return false;
	}

	const int arraySize = paramArray.GetSize();
	//Putting parameters  
	DISPPARAMS dispparams;
	memset(&dispparams, 0, sizeof dispparams);
	dispparams.cArgs      = arraySize;
	dispparams.rgvarg     = new VARIANT[dispparams.cArgs];
	dispparams.cNamedArgs = 0;

	for( int i = 0; i < arraySize; i++)
	{
		CComBSTR bstr = paramArray.GetAt(arraySize - 1 - i); // back reading
		bstr.CopyTo(&dispparams.rgvarg[i].bstrVal);
		dispparams.rgvarg[i].vt = VT_BSTR;
	}
	EXCEPINFO excepInfo;
	memset(&excepInfo, 0, sizeof excepInfo);
	CComVariant vaResult;
	UINT nArgErr = (UINT)-1;  // initialize to invalid arg
	//Call JavaScript function         
	hr = spScript->Invoke(dispid,IID_NULL,0,
		DISPATCH_METHOD,&dispparams,
		&vaResult,&excepInfo,&nArgErr);
	delete [] dispparams.rgvarg;
	if(FAILED(hr)) {
		return false;
	}
	return vaResult;
}


BOOL CHTMLContainerDlg::SetWindowTop()
{
	if(GetExStyle() & WS_EX_TOPMOST)
	{
		return TRUE;
	}
	else
	{
		return SetWindowPos(&CWnd::wndTopMost, 0, 0, 0, 0, SWP_NOMOVE | SWP_NOSIZE);
	}
}

//取消窗口置顶
BOOL CHTMLContainerDlg::CancelWindowTop()
{
	return SetWindowPos(&CWnd::wndNoTopMost, 0, 0, 0, 0, SWP_NOMOVE | SWP_NOSIZE);
}

//注意：还需要调整 链接器->清单文件->允许隔离 = 否
BOOL CHTMLContainerDlg::AllowDragAndDropForVistaAbove( BOOL bAllow /*= TRUE*/ )
{
	static UINT MSGID_ARRAY[] = {
		0x0049,
		WM_DROPFILES
	};

	return ChangeWindowMessageFilterList(MSGID_ARRAY, 2, bAllow);
}

BOOL CHTMLContainerDlg::ChangeWindowMessageFilterList(const UINT* pMsgList, int iCount, BOOL bAllow)
{
	HMODULE hUserMod = LoadLibrary(_T("user32.dll"));
	if( NULL == hUserMod )
	{
		return FALSE;
	}

	typedef BOOL (WINAPI *FnChangeWindowMessageFilter)(UINT message, DWORD dwFlag);
	FnChangeWindowMessageFilter pChangeWindowMessageFilter =
		(FnChangeWindowMessageFilter)GetProcAddress( hUserMod, "ChangeWindowMessageFilter");
	if( NULL == pChangeWindowMessageFilter )
	{
		::FreeLibrary(hUserMod);
		return FALSE;
	}
	
	BOOL bAllSuccess = TRUE;
	for (int i = 0; i < iCount; i++)
	{
		BOOL bResult = pChangeWindowMessageFilter( pMsgList[i], bAllow ? MSGFLT_ADD : MSGFLT_REMOVE );
		if (! bResult)
		{
			bAllSuccess = FALSE;
		}
	}

	if( NULL != hUserMod )
	{
		FreeLibrary( hUserMod );
	}

	return bAllSuccess;
}

void CHTMLContainerDlg::RegisterDialog( CHTMLContainerDlg* pDlg )
{
	s_mapSection.Lock();
	{
		s_dialogSet.insert(pDlg);
	}
	s_mapSection.Unlock();
}

void CHTMLContainerDlg::UnregisterDialog( CHTMLContainerDlg* pDlg )
{
	s_mapSection.Lock();
	{
		s_dialogSet.erase(pDlg);
	}
	s_mapSection.Unlock();
}

void CHTMLContainerDlg::MakeAllOnCancel()
{
	DialogSet dialogSet;
	s_mapSection.Lock();
	{
		dialogSet = s_dialogSet; 
	}
	s_mapSection.Unlock();

	DialogSet::iterator iter = dialogSet.begin();
	for (; iter != dialogSet.end(); iter++)
	{
		CHTMLContainerDlg* pDlg = *iter;
		pDlg->OnCancel();
	}
}

void CHTMLContainerDlg::SetWindowSize( int iWidth, int iHeight )
{
	m_iWidth = iWidth;
	m_iHeight = iHeight;
}

void CHTMLContainerDlg::SetDialogTitle( LPCTSTR title )
{
	m_dialogTitle = title;
}

BEGIN_EVENTSINK_MAP(CHTMLContainerDlg, CDHtmlDialog)
	ON_EVENT(CHTMLContainerDlg, AFX_IDC_BROWSER, 250 /* BeforeNavigate2 */, _OnBeforeNavigate2, VTS_DISPATCH VTS_PVARIANT VTS_PVARIANT VTS_PVARIANT VTS_PVARIANT VTS_PVARIANT VTS_PBOOL)
END_EVENTSINK_MAP()

void CHTMLContainerDlg::_OnBeforeNavigate2(LPDISPATCH pDisp, VARIANT FAR* URL, VARIANT FAR* Flags, VARIANT FAR* TargetFrameName, VARIANT FAR* PostData, VARIANT FAR* Headers, BOOL FAR* Cancel)
{
	CString targetURL(V_BSTR(URL));

	__time64_t now;
	_time64(&now);
	if (max(now, m_backspaceTime) - min(now, m_backspaceTime) <= 1) *Cancel = TRUE;
// 	if (m_lastURL.CompareNoCase(targetURL) == 0)
// 	{
// 		*Cancel = TRUE;
// 	}
// 	else
// 	{
// 		m_lastURL = m_curURL;
// 		m_curURL = targetURL;
// 	}
	
	OnBeforeNavigate(pDisp, targetURL);
}
