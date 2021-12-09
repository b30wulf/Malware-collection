#pragma once
#include <set>
#include "MyDropTarget.h"

#ifdef _WIN32_WCE
#error "Windows CE 不支持 CDHtmlDialog。"
#endif 

// CHTMLContainerDlg 对话框

class CHTMLContainerDlg : public CDHtmlDialog
{
	DECLARE_DYNCREATE(CHTMLContainerDlg)
private:
	CComPtr<IDispatch>	m_spComDisp;
	CComPtr<IHTMLDocument2> m_spDoc;
	CString				m_strURL;
	RECT				m_titleRect;
	BOOL				m_bTitleRectAvailable;
	int					m_iWidth;
	int					m_iHeight;
	CMyDropTarget		m_droptarget;
	CString				m_dialogTitle;
// 	CString				m_lastURL;
// 	CString				m_curURL;
	__time64_t			m_backspaceTime;

	typedef std::set<CHTMLContainerDlg*> DialogSet;
	static CCriticalSection	s_mapSection;
	static DialogSet s_dialogSet;

public:
	CHTMLContainerDlg(CWnd* pParent = NULL);   // 标准构造函数
	CHTMLContainerDlg(UINT nIDTemplate, UINT nHtmlResID = 0, CWnd *pParentWnd = NULL, int iWidth = 0, int iHeight = 0);
	virtual ~CHTMLContainerDlg();

	// 暂时这样,以后读取HTML文件里面的Title多态设置标题
	void SetHtmlAndCom(CString strURL, CString strProg);
	void SetDialogTitle(LPCTSTR title);
	void SetTitleRect(LONG x, LONG y, LONG width, LONG height);
	void SetWindowSize(int iWidth, int iHeight);

	void SetWindowEllispeFrame(int nWidthEllipse = 5, int nHeightEllipse = 5);
	void SetDropCallback(FnDropFilesCallback fnCallback, LPVOID lpParameter);

	bool GetJScript(CComPtr<IDispatch>& spDisp);
	CComVariant CallJScript(const CString strFunc, const CStringArray& paramArray);
	bool CallJScript(const CString strFunc);

	BOOL SetWindowTop();
	BOOL CancelWindowTop();

	static void MakeAllOnCancel();
	
// 重写
	HRESULT OnButtonOK(IHTMLElement *pElement);
	HRESULT OnButtonCancel(IHTMLElement *pElement);

	virtual BOOL HandleAltF4() {return FALSE;};

	BOOL AllowDragAndDropForVistaAbove(BOOL bAllow = TRUE);
	BOOL ChangeWindowMessageFilterList(const UINT* pMsgList, int iCount, BOOL bAllow);

// 对话框数据
	//enum { IDD = IDD_HTMLCONTAINERDLG, IDH = 0 };

protected:
	virtual void DoDataExchange(CDataExchange* pDX);    // DDX/DDV 支持
	virtual BOOL OnInitDialog();
	virtual BOOL PreTranslateMessage(MSG* pMsg);

	static void RegisterDialog(CHTMLContainerDlg* pDlg);
	static void UnregisterDialog(CHTMLContainerDlg* pDlg);

	DECLARE_MESSAGE_MAP()
	DECLARE_DHTML_EVENT_MAP()
	DECLARE_EVENTSINK_MAP()
public:
	STDMETHOD(GetHostInfo)(DOCHOSTUIINFO* pInfo);
	STDMETHOD(GetDropTarget)(IDropTarget *pDropTarget, IDropTarget **ppDropTarget);

	virtual	BOOL CanAccessExternal();
	virtual void OnDocumentComplete(LPDISPATCH pDisp, LPCTSTR szUrl);
	void _OnBeforeNavigate2(LPDISPATCH pDisp, VARIANT FAR* URL, VARIANT FAR* Flags, VARIANT FAR* TargetFrameName, VARIANT FAR* PostData, VARIANT FAR* Headers, BOOL FAR* Cancel);
};
