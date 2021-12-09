#pragma once
#include "afxcmn.h"
#include "afxwin.h"
#include "ReportCtrl.h"
#include "mfcresize/Resizer.h"


// CListenDlg 对话框

class CListenDlg : public CDialogEx
{
	DECLARE_DYNAMIC(CListenDlg)

public:
	CListenDlg(CWnd* pParent = NULL);   // 标准构造函数
	virtual ~CListenDlg();

// 对话框数据
	enum { IDD = IDD_DIALOG_LIS };

protected:
	virtual void DoDataExchange(CDataExchange* pDX);    // DDX/DDV 支持

	void InitView();
	void InitResize();
	DECLARE_MESSAGE_MAP()
private:
	virtual BOOL OnInitDialog();
	CReportCtrl m_listenList;
	CComboBox m_protoList;

	CImageList m_Imagelist;
	CResizer m_resizer;
public:
	afx_msg void OnBnClickedButtonStart();
	afx_msg void OnBnClickedButtonStop();
	afx_msg void OnNMRClickListListen(NMHDR *pNMHDR, LRESULT *pResult);
	afx_msg void OnCbnSelendcancelComboProto();
	afx_msg void OnCbnSelchangeComboProto();
	afx_msg void OnSize(UINT nType, int cx, int cy);
};
