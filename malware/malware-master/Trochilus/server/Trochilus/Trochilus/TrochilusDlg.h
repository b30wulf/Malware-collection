#pragma once
#include "afxcmn.h"
#include "thread/RepeatTask.h"
#include "HostDlg.h"
#include "ListenDlg.h"
#include "mfcresize/Resizer.h"

class CTrochilusDlg : public CDialogEx
{

public:

	CTrochilusDlg(CWnd* pParent = NULL);
	enum { IDD = IDD_TROCHILUS_DIALOG };

protected:

	virtual void DoDataExchange(CDataExchange* pDX);

protected:

	afx_msg void OnPaint();
	afx_msg HCURSOR OnQueryDragIcon();
	afx_msg void OnSysCommand(UINT nID, LPARAM lParam);
	
	DECLARE_MESSAGE_MAP()

private:

	//初始化界面控件和数据
	virtual BOOL OnInitDialog();
	virtual BOOL InitView();
	void		InitResize();
public:

	HICON m_hIcon;
	CTabCtrl m_TabCtrl;
	RepeatTask m_timeRepeat;
	CStatusBarCtrl m_wndStatusBar;

	CHostDlg m_HostDlg;
	CListenDlg m_LisDlg;

	CResizer m_resizer;
	//定时更新时间
	static DWORD WINAPI DateRepeatProc(LPVOID lpParameter);

	afx_msg void OnTcnSelchangeTabMain(NMHDR *pNMHDR, LRESULT *pResult);
	afx_msg void OnSize(UINT nType, int cx, int cy);

};
