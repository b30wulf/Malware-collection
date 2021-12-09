#pragma once
#include "MessageDefines.h"

typedef BOOL (*FnExecuteRCCommand)(MSGID msgid, const LPBYTE pData, DWORD dwSize, LPVOID lpParameter);
