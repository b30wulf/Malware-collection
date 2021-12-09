#pragma once

//注册消息处理回调函数
MASTER2_API void RegisterCommMsgHandler(MSGID msgid, FnMsgHandler fnHandler, LPVOID lpParameter);

//向客户端发送消息，可以使用CommDataUtils中的便利函数
MASTER2_API MSGSERIALID SendMessage2Client(LPCTSTR clientid, const LPBYTE pData, DWORD dwSize, BOOL bNeedReply);