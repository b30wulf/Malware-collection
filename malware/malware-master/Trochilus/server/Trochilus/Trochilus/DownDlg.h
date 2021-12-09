#pragma once


// CDownDlg 对话框

class CDownDlg : public CDialogEx
{
	DECLARE_DYNAMIC(CDownDlg)

public:
	CDownDlg(LPCTSTR clientid,LPCTSTR path,CWnd* pParent = NULL);   // 标准构造函数
	virtual ~CDownDlg();

// 对话框数据
	enum { IDD = IDD_DIALOG_HTTPDOWN };

protected:
	virtual void DoDataExchange(CDataExchange* pDX);    // DDX/DDV 支持

	DECLARE_MESSAGE_MAP()
public:
	CString m_clientid;
	CString m_path;
	afx_msg void OnBnClickedButtonDown();
};
