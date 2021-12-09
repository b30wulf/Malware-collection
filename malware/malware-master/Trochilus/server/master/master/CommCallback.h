#pragma once
#include "MessageDefines.h"
#include "CommData.h"

typedef BOOL (*FnMsgHandler)(MSGID msgid, const CommData& commData, LPVOID lpParameter);
