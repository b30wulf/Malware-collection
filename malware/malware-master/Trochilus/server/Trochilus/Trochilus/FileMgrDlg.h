#pragma once
#include "afxcmn.h"
#include "IModule.h"
#include "resource.h"
#include "mfcresize/Resizer.h"

class CFileMgrDlg : public CDialogEx, public IModule
{
	DECLARE_DYNAMIC(CFileMgrDlg)

public:
	CFileMgrDlg(CWnd* pParent = NULL);
	virtual ~CFileMgrDlg();

	enum { IDD = IDD_DIALOG_FILE };

	//处理模块消息
	static void CALLBACK HandleModuleMsg(LPCTSTR clientid,UINT nMsg, LPVOID lpContext, LPVOID lpParameter);
	void HandleModuleMsgProc(UINT nMsg, LPVOID lpContext, LPVOID lpParameter);

protected:
	virtual void DoDataExchange(CDataExchange* pDX);


	//初始化界面控件和数据
	virtual BOOL OnInitDialog();
	void InitView();
	void InitData();
	void InitResize();

	//设置对话框字段名
	void SetColumn(CListCtrl* list,LPTSTR text,int nCol,int nWidth);

	//将反馈内容插入文件列表
	void InsertFileList( char* lpJson,BOOL isRemote);

	//得到远程文件列表
	void GetRemoteList(CString strPath);

	//得到本地文件列表
	void GetLocalList(CString strPath);

	//得到文件图标句柄
	HICON GetFileIcon(CString strPath);

	//处理本地文件列表
	void HandleLocalFile(UINT nMsg, LPVOID lpContext, LPVOID lpParameter);

	//处理远程文件列表
	void HandleRemoteFile(UINT nMsg, LPVOID lpContext, LPVOID lpParameter);

	BOOL IsEquPath(LPCTSTR filename,BOOL isRemote);
	DECLARE_MESSAGE_MAP()
public:

	CListCtrl m_rDicList;
	CListCtrl m_rFileList;
	CListCtrl m_lDicList;
	CListCtrl m_lFileList;

	CString m_rCurDir;
	CString m_lCurDir;

	CriticalSection m_csList;
	CImageList m_ImageList;

	CResizer m_resizer;

	LPVOID m_panel;
private:
	//远程目录列表双击
	afx_msg void OnNMDblclkListRdic(NMHDR *pNMHDR, LRESULT *pResult);
	//本地目录列表双击
	afx_msg void OnNMDblclkListLdic(NMHDR *pNMHDR, LRESULT *pResult);
	//远程上翻目录按钮
	afx_msg void OnBnClickedButtonRupdic();
	//本地上翻目录按钮
	afx_msg void OnBnClickedButtonLupdic();
	//远程目录刷新
	afx_msg void OnBnClickedButtonRrefresh();
	//本地目录刷新
	afx_msg void OnBnClickedButtonLrefresh();
	//上传文件按钮
	afx_msg void OnBnClickedButtonUpload();
	//下载文件按钮
	afx_msg void OnBnClickedButtonDown();
public:
	afx_msg void OnBnClickedButtonRun();
	afx_msg void OnBnClickedButtonDelete();
	afx_msg void OnNMRClickListRfile(NMHDR *pNMHDR, LRESULT *pResult);
	afx_msg void OnClickDownFile();
	afx_msg void OnSize(UINT nType, int cx, int cy);
	afx_msg void OnSysCommand(UINT nID, LPARAM lParam);
};
