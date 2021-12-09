#pragma once
#include "afxcmn.h"
#include "thread/RepeatTask.h"
#include "ProgressListCtrl.h"
#include "mfcresize/Resizer.h"

class CTransferDlg : public CDialogEx,public IModule
{
	DECLARE_DYNAMIC(CTransferDlg)

public:

	CTransferDlg(CWnd* pParent = NULL);
	virtual ~CTransferDlg();

	enum { IDD = IDD_DIALOG_TRANSFER };

protected:

	virtual void DoDataExchange(CDataExchange* pDX);
	DECLARE_MESSAGE_MAP()

public:

	//初始化界面控件和数据
	void InitView();
	void InitData();
	void InitResize();
	virtual BOOL OnInitDialog();

	//定时检查文件传输状态
	static DWORD WINAPI CheckTaskList(LPVOID lpParameter);
	DWORD CheckTaskListProc();

	static void ModifyStatus(LPCTSTR clientid,TRANS_STATUS status,LPVOID lpParameter);
	void ModifyStatusProc(LPCTSTR clientid,TRANS_STATUS status,LPVOID lpParameter);

	//处理模块消息
	static void HandleModuleMsg(LPCTSTR clientid,UINT nMsg, LPVOID lpContext, LPVOID lpParameter)
	{
		return;
	}

	RepeatTask m_checkTask;
	CListCtrl m_transList;
	CImageList m_ImageList;
	CResizer m_resizer;

	//响应窗口销毁消息
	afx_msg void OnDestroy();
	afx_msg void OnBnClickedButtonStart();
	afx_msg void OnBnClickedButtonStop();
	afx_msg void OnBnClickedButtonDelete();
	afx_msg void OnSize(UINT nType, int cx, int cy);
};
