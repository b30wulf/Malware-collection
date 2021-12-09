
#ifndef VTCP_H
#define VTCP_H

#ifdef WIN32

#include <WinSock2.h>

#ifndef int64
typedef __int64 int64;	
#endif

#ifndef socklen_t
#define socklen_t int
#endif

#else

#include <sys/types.h>
#include <sys/socket.h>

#include <netinet/in.h>

#ifndef int64
typedef __int64_t int64;	
#endif

#endif


#define VTCP_OK				int(0)
#define VTCP_ERROR			int(-1)
#define VTCP_INVALID_SOCKET	int(-1)

typedef int		VTCP_SOCKET;
typedef void *	VTCP_QIO_HANDLE;


struct SVTcpKeepAlive 
{
	unsigned long onoff;
	unsigned long keepalivetime;
	unsigned long keepaliveinterval;
};

struct SVTcpLinger 
{
	unsigned short l_onoff;
	unsigned short l_linger;
};

struct SVTcpRSVP
{
	unsigned long rsvp_min;	//KB/s
	unsigned long rsvp_max;	//KB/s

};

#define VTCP_MAX_FD_SET_COUNT 128

typedef struct SVTcpFdSet
{
	int fd_count;

	VTCP_SOCKET fd_array[VTCP_MAX_FD_SET_COUNT];

}S_VTCP_FD_SET,*PS_VTCP_FD_SET;


typedef struct SVTcpStatCount					//状态统计信息
{	
	int sizeOfStruct;							//结构大小

	unsigned long version;						//版本
	
	unsigned long time;							//时间间隔
	
	int64	count_recv_bytes;					//接收总字节
	int64	count_recv_bytes_cur;				//接收总字节（当前速度）
	int64	count_send_bytes;					//发送总字节
	int64	count_send_bytes_cur;				//发送总字节（当前速度）

	int64	count_do_send_data_rep;				//发出数据流包重复个数
	int64	count_do_send_data;					//发出数据流包个数
	int64	count_do_send_data_ack;				//发出数据流包应答个数
	int64	count_do_send_sync;					//发出同步包个数
	int64	count_do_send_sync_ack;				//发出同步包应答个数

	int64	count_on_recv_data_rep;				//收到数据流包重复个数
	int64	count_on_recv_data;					//收到数据流包个数
	int64	count_on_recv_data_ack;				//收到数据流包应答个数
	int64	count_on_recv_sync;					//收到同步包个数
	int64	count_on_recv_sync_ack;				//收到同步包应答个数

	int		tcp_rtt;							//往返周期
	int		tcp_cwnd;							//发送窗口
	int		tcp_cwnd_ssthresh;					//发送窗口阀值

	int		count_async_opt;					//应用层异步队列个数
	int		count_pm_r;							//内核包接收队列
	int		count_pm_s;							//内核包发送队列

	unsigned char grap_r[128];					//包发送状态位图
	unsigned char grap_s[128];					//包接收状态位图


}S_VTCP_STAT_COUNT,*PS_VTCP_STAT_COUNT;


enum EVTcpErrorCode
{
	VTCP_ERROR_NOT_ENOUGH_MEMORY		= 1,	//内存不足
	VTCP_ERROR_INVALID_PARAMETER		= 2,	//参数错误
	VTCP_ERROR_INVALID_STATE			= 3,	//句柄状态错误
	VTCP_ERROR_INVALID_SOCKET			= 4,	//句柄错误
	VTCP_ERROR_TIMEOUT					= 5,	//操作超时
	VTCP_ERROR_WOULDBLOCK				= 7,	//异步阻挡
	VTCP_ERROR_CONNRESET				= 8,	//远程关闭了连接
	VTCP_ERROR_CONNABORTED				= 9,	//本地关闭了连接
	VTCP_ERROR_CONNREFUSED				= 10,	//连接被积极拒绝
	VTCP_ERROR_MSG_SIZE					= 11,	//消息尺寸错误
	VTCP_ERROR_CANCELLED				= 12,	//操作被取消
	VTCP_ERROR_NOT_FOUND				= 13,	//对象未发现
	VTCP_ERROR_BUSY						= 14,	//系统繁忙
	VTCP_ERROR_SYSTEM_FAIL				= 15,	//系统调用失败
	VTCP_ERROR_NETRESET					= 16,	//因保活检测失败而断线

};

enum EVTcpSoOpt
{
	VTCP_SO_CONNECT_TIMEO				= 1,	//连接超时(unsigned long)(毫秒）
	VTCP_SO_RECV_TIMEO					= 2,	//接收超时(unsigned long)(毫秒）
	VTCP_SO_RECVFROM_TIMEO				= 3,	//接收超时(unsigned long)(毫秒）
	VTCP_SO_SEND_TIMEO					= 4,	//发送超时(unsigned long)(毫秒）
	VTCP_SO_SENDTO_TIMEO				= 5,	//发送超时(unsigned long)(毫秒）
	VTCP_SO_LINGER						= 6,	//逗留时间(SVTcpLinger)
	VTCP_SO_KEEPALIVE					= 7,	//保活设置(SVTcpKeepAlive)
	VTCP_SO_RSVP						= 8,	//资源预留(SVTcpRSVP)(视频传输)
	VTCP_SO_STAT_COUNT					= 9,	//统计信息(SVTcpStatCount)
	VTCP_SO_LOSS_PERCENT				= 10,	//丢包容忍(int)(百分比)(0~100)

};

enum EVTcpQIOEvent
{
	VTCP_QIO_EVENT_ACCEPT		= 1, //兼容VTCP_QIO_EVENT_RECV
	VTCP_QIO_EVENT_CONNECT		= 2, //兼容VTCP_QIO_EVENT_SEND
	VTCP_QIO_EVENT_RECV			= 3,
	VTCP_QIO_EVENT_RECVFROM		= 4,
	VTCP_QIO_EVENT_SEND			= 5,
	VTCP_QIO_EVENT_SENDTO		= 6,

};


enum EVTcpCtl
{
	VTCP_CTL_GET_NONBIO			= 1,
	VTCP_CTL_SET_NONBIO			= 2,

};


#endif

#ifdef WIN32
#define VTCP_EXPORT_API(t) t WINAPI		
#else
#define VTCP_EXPORT_API(t) extern "C" t
#endif

//API使用帮助 WWW.VTCP123.COM  

VTCP_EXPORT_API(VTCP_SOCKET		)vtcp_accept		(VTCP_SOCKET s, sockaddr * sai, socklen_t * sailen);
VTCP_EXPORT_API(int				)vtcp_bind			(VTCP_SOCKET s, const struct sockaddr * sai, socklen_t sailen);
VTCP_EXPORT_API(int				)vtcp_cleanup		();
VTCP_EXPORT_API(int				)vtcp_close			(VTCP_SOCKET s);
VTCP_EXPORT_API(int				)vtcp_connect		(VTCP_SOCKET s, const struct sockaddr * sai, socklen_t sailen);
VTCP_EXPORT_API(int				)vtcp_ctl			(VTCP_SOCKET s, EVTcpCtl ctl,unsigned long * p);
VTCP_EXPORT_API(int				)vtcp_geterror		();
VTCP_EXPORT_API(int				)vtcp_getsockname	(VTCP_SOCKET s, struct sockaddr * sai, socklen_t * sailen);
VTCP_EXPORT_API(int				)vtcp_getpeername	(VTCP_SOCKET s, struct sockaddr * sai, socklen_t * sailen);
VTCP_EXPORT_API(int				)vtcp_listen		(VTCP_SOCKET s, int backlog);
VTCP_EXPORT_API(int				)vtcp_recv			(VTCP_SOCKET s, char * buffer, int cb, int flag);
VTCP_EXPORT_API(int				)vtcp_recvfrom		(VTCP_SOCKET s, char * buffer, int cb, int flag, struct sockaddr * from, socklen_t * fromlen);
VTCP_EXPORT_API(int				)vtcp_send			(VTCP_SOCKET s, char * buffer, int cb, int flag);
VTCP_EXPORT_API(int				)vtcp_sendto		(VTCP_SOCKET s, char * buffer, int cb, int flag, const struct sockaddr * to, socklen_t tolen);
VTCP_EXPORT_API(int				)vtcp_getsockopt	(VTCP_SOCKET s, int level, int optname, char * optval, int * optlen);
VTCP_EXPORT_API(int				)vtcp_setsockopt	(VTCP_SOCKET s, int level, int optname, char * optval, int   optlen);
VTCP_EXPORT_API(int				)vtcp_select		(SVTcpFdSet * fdr, SVTcpFdSet * fdw, SVTcpFdSet * fde, const struct timeval * to);
VTCP_EXPORT_API(VTCP_SOCKET		)vtcp_socket		(int af, int itype, int protocol);
VTCP_EXPORT_API(VTCP_SOCKET		)vtcp_socketshare	(VTCP_SOCKET s);
VTCP_EXPORT_API(int				)vtcp_startup		();

VTCP_EXPORT_API(VTCP_QIO_HANDLE	)vtcp_qio_create	();
VTCP_EXPORT_API(int				)vtcp_qio_post		(VTCP_QIO_HANDLE qio, VTCP_SOCKET   s, EVTcpQIOEvent   qio_event, void *  qio_data, int   qio_error);
VTCP_EXPORT_API(int				)vtcp_qio_get		(VTCP_QIO_HANDLE qio, VTCP_SOCKET * s, EVTcpQIOEvent * qio_event, void ** qio_data, int * qio_error, unsigned long timeout);
VTCP_EXPORT_API(int				)vtcp_qio_set		(VTCP_QIO_HANDLE qio, VTCP_SOCKET   s, EVTcpQIOEvent   qio_event, void *  qio_data);
VTCP_EXPORT_API(int				)vtcp_qio_close		(VTCP_QIO_HANDLE qio);

//VTCP.H


