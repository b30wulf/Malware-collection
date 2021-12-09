/*******************************************************************************
*
*  (C) COPYRIGHT AUTHORS, 2016
*
*  TITLE:       P2P.C
*
*  VERSION:     1.01
*
*  DATE:        22 Jan 2016
*
*  Yuudachi poi2poi.
*
* THIS CODE AND INFORMATION IS PROVIDED "AS IS" WITHOUT WARRANTY OF
* ANY KIND, EITHER EXPRESSED OR IMPLIED, INCLUDING BUT NOT LIMITED
* TO THE IMPLIED WARRANTIES OF MERCHANTABILITY AND/OR FITNESS FOR A
* PARTICULAR PURPOSE.
*
*******************************************************************************/
#include "p2p.h"
#include "gui.h"
#include "..\shared\za_crypto.h"
#include "..\shared\ea.h"

typedef void (__cdecl *pfnqsort)(
	_Inout_updates_bytes_(_NumOfElements * _SizeOfElements) void*  _Base,
	_In_                                                    size_t _NumOfElements,
	_In_                                                    size_t _SizeOfElements,
	_In_ int(__cdecl* _PtFuncCompare)(void const*, void const*)
	);

static ZA_SCANCTX     g_zascan;
pfnqsort _qsort;

/*
* SfAvlCompareCallback
*
* Purpose:
*
* AVL table compare callback.
*
*/
RTL_GENERIC_COMPARE_RESULTS NTAPI SfAvlCompareCallback(
	_In_ struct _RTL_AVL_TABLE *Table,
	_In_ PVOID FirstStruct,
	_In_ PVOID SecondStruct
	)
{
	RTL_GENERIC_COMPARE_RESULTS res;
	ZA_PEERINFO *Peer1 = (ZA_PEERINFO*)FirstStruct;
	ZA_PEERINFO *Peer2 = (ZA_PEERINFO*)SecondStruct;

	UNREFERENCED_PARAMETER(Table);

	if ((Peer1->IP == Peer2->IP) && (Peer1->Port == Peer2->Port))
		return GenericEqual;

	if (Peer1->IP > Peer2->IP)
		res = GenericGreaterThan;
	else
		res = GenericLessThan;

	return res;
}

/*
* SfAvlAllocateCallback
*
* Purpose:
*
* AVL table allocate memory callback.
*
*/
PVOID NTAPI SfAvlAllocateCallback(
	_In_ struct _RTL_AVL_TABLE *Table,
	_In_ ULONG ByteSize
	)
{
	UNREFERENCED_PARAMETER(Table);
	return RtlAllocateHeap(NtCurrentPeb()->ProcessHeap, HEAP_ZERO_MEMORY, ByteSize);
}

/*
* SfAvlFreeCallback
*
* Purpose:
*
* AVL table free memory callback.
*
*/
VOID NTAPI SfAvlFreeCallback(
	_In_  _RTL_AVL_TABLE *Table,
	_In_ _Post_invalid_ PVOID Buffer
	)
{
	UNREFERENCED_PARAMETER(Table);
	RtlFreeHeap(NtCurrentPeb()->ProcessHeap, 0, Buffer);
}

/*
* SfQSortCompare
*
* Purpose:
*
* qsort callback.
*
*/
int __cdecl SfQSortCompare(
	void const* first,
	void const* second
	)
{
	int i;
	ZA_PEERINFO *Peer1 = (ZA_PEERINFO*)first;
	ZA_PEERINFO *Peer2 = (ZA_PEERINFO*)second;

	if (Peer1->TimeStamp <= Peer2->TimeStamp)
		i = (Peer1->TimeStamp < Peer2->TimeStamp);
	else
		i = -1;
	return i;
}

/*
* SfNStoreFile
*
* Purpose:
*
* Save file in U directory and add EA for Harusame.
*
*/
BOOL SfNStoreFile(
	_In_ ZA_SCANCTX *ScanContext,
	_In_ LPWSTR FileName,
	_In_ PVOID FileBuffer,
	_In_ ULONG FileSize,
	_In_ ZA_FILEHEADER *FileHeader
	)
{
	BOOL              bResult = FALSE;
	HANDLE            hFile;
	NTSTATUS          status;
	OBJECT_ATTRIBUTES ObjectAttributes;
	IO_STATUS_BLOCK   IoStatusBlock;
	UNICODE_STRING    usName;

	RtlSecureZeroMemory(&usName, sizeof(usName));
	RtlInitUnicodeString(&usName, FileName);
	InitializeObjectAttributes(&ObjectAttributes, &usName, OBJ_CASE_INSENSITIVE, 
		ScanContext->RootDirectoryHandle, NULL);

	status = NtCreateFile(&hFile, FILE_GENERIC_WRITE, &ObjectAttributes,
		&IoStatusBlock, NULL, FILE_ATTRIBUTE_NORMAL, 0, FILE_OVERWRITE_IF,
		FILE_SYNCHRONOUS_IO_NONALERT | FILE_NON_DIRECTORY_FILE, NULL, 0);

	if (NT_SUCCESS(status)) {
		if (NT_SUCCESS(NtWriteFile(hFile, NULL, NULL, NULL,
			&IoStatusBlock, FileBuffer, FileSize, NULL, NULL)))
		{
			bResult = SfNtfsSetFileHeaderToEa(hFile, FileHeader);
		}
		NtClose(hFile);
	}
	return bResult;
}

/*
* SfNDownloadFile
*
* Purpose:
*
* Download file from p2p network.
*
*/
BOOL SfNDownloadFile(
	_In_ ZA_SCANCTX *ScanContext,
	_In_ ZA_FILEHEADER *FileHeader,
	_In_ ZA_PEERINFO *in_peer
	)
{
	BOOL                cond = FALSE, bResult = FALSE;
	SOCKET              st = INVALID_SOCKET;
	struct sockaddr_in  io_addr;
	MD5_CTX             ctx;
	rc4_state           rc4ctx;
	PBYTE               recvbuffer = NULL;
	int                 recv_size;
	SIZE_T              sz;
	WCHAR               szText[MAX_PATH];


	do {
		st = socket(AF_INET, SOCK_STREAM, IPPROTO_TCP);
		if (st == INVALID_SOCKET)
			break;

		RtlSecureZeroMemory(&io_addr, sizeof(io_addr));
		io_addr.sin_family = AF_INET;
		io_addr.sin_port = htons(TCP_PORT);
		if (bind(st, (struct sockaddr *)&io_addr, sizeof(io_addr)) != 0)
			break;

		RtlSecureZeroMemory(&io_addr, sizeof(io_addr));
		io_addr.sin_family = AF_INET;
		io_addr.sin_port = htons((u_short)(P2P_UDP_PORT_ADJUST + in_peer->Port));
		io_addr.sin_addr.S_un.S_addr = in_peer->IP;

		_strcpy(szText, TEXT(">>> trying connect to -> "));
		RtlIpv4AddressToStringW((const struct in_addr*)&io_addr.sin_addr, _strend(szText));
		_strcat(szText, TEXT(":"));
		ultostr(ntohs(io_addr.sin_port), _strend(szText));
		SfUIAddEvent(ScanContext, GUI_EVENT_DOWNLOAD_FILE, szText);

		if (connect(st, (struct sockaddr *)&io_addr, sizeof(io_addr)) != 0) {
			_strcpy(szText, TEXT(">>> "));
			RtlIpv4AddressToStringW((const struct in_addr*)&io_addr.sin_addr, _strend(szText));
			_strcat(szText, TEXT(":"));
			ultostr(ntohs(io_addr.sin_port), _strend(szText));
			_strcat(szText, TEXT(" <- connection attempt timed out"));
			SfUIAddEvent(ScanContext, GUI_EVENT_DOWNLOAD_FILE, szText);
			break;
		}

		SfUIAddEvent(ScanContext, GUI_EVENT_DOWNLOAD_FILE, TEXT(">>> <- connected OK"));

		sz = RECV_BUFFER_SIZE * 4;
		recvbuffer = NULL;
		NtAllocateVirtualMemory(NtCurrentProcess(), &recvbuffer, 0, &sz, MEM_RESERVE | MEM_COMMIT, PAGE_READWRITE);
		if (recvbuffer == NULL)
			break;

		send(st, (const char *)FileHeader, 12, 0);
		recv_size = recv(st, (char *)recvbuffer, RECV_BUFFER_SIZE, 0);

		if (recv_size <= 0)
			break;

		if ((ULONG)recv_size < FileHeader->Size) {
			SfUIAddEvent(ScanContext, GUI_EVENT_DOWNLOAD_FILE, TEXT(">>> received size is not equal to the header"));
			break;
		}

		MD5Init(&ctx);
		MD5Update(&ctx, (const unsigned char *)FileHeader, 12);
		MD5Final(&ctx);
		rc4_init(&rc4ctx, (const unsigned char *)&ctx.digest, sizeof(ctx.digest));
		rc4_crypt(&rc4ctx, recvbuffer, recvbuffer, recv_size);

		_strcpy(szText, TEXT("U\\ip-"));
		RtlIpv4AddressToStringW((const struct in_addr*)&io_addr.sin_addr, _strend(szText));
		_strcat(szText, TEXT("-port-"));
		ultostr(ntohs(io_addr.sin_port), _strend(szText));
		_strcat(szText, TEXT("-id-"));
		ultohex(FileHeader->Name, _strend(szText));
#ifdef _WIN64
		_strcat(szText, TEXT("-64"));
#else
		_strcat(szText, TEXT("-32"));
#endif
		_strcat(szText, TEXT(".bin"));

		bResult = SfNStoreFile(ScanContext, szText, recvbuffer, recv_size, FileHeader);

		if (bResult) {
			_strcat(szText, TEXT(" file saved OK"));
			SfUIAddEvent(ScanContext, GUI_EVENT_DOWNLOAD_FILE, szText);
		}
		else {
			SfUIAddEvent(ScanContext, GUI_EVENT_ERROR, TEXT(">>> error saving file"));
		}

	} while (cond);

	if (recvbuffer != NULL) {
		sz = 0;
		NtFreeVirtualMemory(NtCurrentProcess(), &recvbuffer, &sz, MEM_RELEASE);
	}

	if (st != INVALID_SOCKET) {
		shutdown(st, SD_BOTH);
		closesocket(st);
	}

	return bResult;
}

/*
* SfNAddFileHeader
*
* Purpose:
*
* Process file header, validate and download.
*
*/
VOID SfNAddFileHeader(
	_In_ ZA_SCANCTX *ScanContext,
	_In_ ZA_FILEHEADER *hdr,
	_In_ ZA_PEERINFO *in_peer
	)
{
	ULONG	       c;
	WCHAR          text[MAX_PATH];   
	LARGE_INTEGER  ftime;
	SYSTEMTIME     st1;

	if (ScanContext->NumberOfFiles >= MAXIMUM_FILES)
		return;

	for (c = 0; c < ScanContext->NumberOfFiles; c++) {
		if (memcmp(&ScanContext->FileHeaders[c], hdr, sizeof(ZA_FILEHEADER)) == 0) {
#ifdef _DEBUG		
			OutputDebugString(TEXT("Received file header already in the list\r\n"));
#endif			
			return;
		}
	}

	_strcpy(text, TEXT(">> new file header received ->Name: "));
	ultohex(hdr->Name, _strend(text));
	_strcat(text, TEXT(", TimeStamp: "));

	RtlSecondsSince1980ToTime(hdr->Time, &ftime);
	if (FileTimeToSystemTime((PFILETIME)&ftime, &st1)) {
		ultostr(st1.wDay, _strend(text));
		_strcat(text, TEXT("/"));
		ultostr(st1.wMonth, _strend(text));
		_strcat(text, TEXT("/"));
		ultostr(st1.wYear, _strend(text));
		_strcat(text, TEXT(" "));
		ultostr(st1.wHour, _strend(text));
		_strcat(text, TEXT(":"));
		ultostr(st1.wMinute, _strend(text));
		_strcat(text, TEXT(":"));
		ultostr(st1.wSecond, _strend(text));
	}
	else {
		ultohex(hdr->Time, _strend(text));
	}
	_strcat(text, TEXT(", Size: "));
	ultostr(hdr->Size, _strend(text));
	SfUIAddEvent(ScanContext, GUI_EVENT_FILE_HEADER, text);

	_strcpy(text, TEXT(">> checking file header signature "));
	if (SfcValidateFileHeader(ScanContext->CryptoProv, ScanContext->CryptoKey, hdr)) {
		_strcat(text, TEXT(" -> verified OK, processing download"));
		if (SfNDownloadFile(ScanContext, hdr, in_peer)) {
			RtlCopyMemory(&ScanContext->FileHeaders[ScanContext->NumberOfFiles], hdr, sizeof(ZA_FILEHEADER));
			ScanContext->NumberOfFiles++;
		}
	}
	else {
		_strcat(text, TEXT(" -> verification FAILED, file header tampered"));
	}
	SfUIAddEvent(ScanContext, GUI_EVENT_FILE_HEADER, text);
}

/*
* SfNFormatPrintPeer
*
* Purpose:
*
* Output peer info to listview.
*
*/
void SfNFormatPrintPeer(
	ZA_SCANCTX *ScanContext, 
	ZA_PEERINFO *peer
	)
{
	TCHAR			text[128];
	LARGE_INTEGER	ftime;
	SYSTEMTIME		st1;

	RtlSecureZeroMemory(text, sizeof(text));
	_strcpy(text, TEXT(">> peer record received ->"));
	RtlIpv4AddressToStringW((const struct in_addr *)&peer->IP, _strend(text));
	_strcat(text, TEXT(":"));
	
	ultostr(P2P_UDP_PORT_ADJUST + peer->Port, _strend(text));
	_strcat(text, TEXT(" "));

	RtlSecondsSince1980ToTime((peer->TimeStamp * 3600) - 0xbf000000, &ftime);
	RtlSecureZeroMemory(&st1, sizeof(st1));
	if (FileTimeToSystemTime((PFILETIME)&ftime, &st1)) {
		ultostr(st1.wDay, _strend(text));
		_strcat(text, TEXT("/"));
		ultostr(st1.wMonth, _strend(text));
		_strcat(text, TEXT("/"));
		ultostr(st1.wYear, _strend(text));
		_strcat(text, TEXT(" "));
		ultostr(st1.wHour, _strend(text));
		_strcat(text, TEXT(":"));
		ultostr(st1.wMinute, _strend(text));
		_strcat(text, TEXT(":"));
		ultostr(st1.wSecond, _strend(text));
	}
	SfUIAddEvent(ScanContext, GUI_EVENT_PEER_HEADER, text);
}

/*
* SfNAddToTable
*
* Purpose:
*
* Insert new peer element to AVL tables.
*
*/
VOID SfNAddToTable(
	ZA_SCANCTX *ScanContext,
	ZA_PEERINFO *peer
	)
{
	IO_STATUS_BLOCK  IoStatusBlock;
	LARGE_INTEGER    Position;
	ZA_PEERINFO     *LookupElement;
	BOOLEAN          NewElement = FALSE;

	RtlEnterCriticalSection(&ScanContext->csTableLock);

	//add new element to table, check before if it already in
	LookupElement = RtlLookupElementGenericTableAvl(&ScanContext->PeersTable, (PVOID)peer);
	if (LookupElement == NULL) {
		RtlInsertElementGenericTableAvl(&ScanContext->PeersTable, peer, sizeof(ZA_PEERINFO), &NewElement);
	}

#ifdef _DEBUG
	else {
		OutputDebugString(TEXT("Duplicate peer entry found\r\n"));
	}
#endif
	RtlLeaveCriticalSection(&ScanContext->csTableLock);

	//this is new element, collect it and send to listview
	RtlEnterCriticalSection(&ScanContext->csTableDumpLock);
	if (LookupElement == NULL) {

		LookupElement = RtlLookupElementGenericTableAvl(&ScanContext->PeersTableDump, (PVOID)peer);
		if (LookupElement == NULL) {
			RtlInsertElementGenericTableAvl(&ScanContext->PeersTableDump, peer, sizeof(ZA_PEERINFO), &NewElement);

			Position.LowPart = FILE_WRITE_TO_END_OF_FILE;
			Position.HighPart = -1;
			if (NT_SUCCESS(NtWriteFile(ScanContext->DumpFileHandle, 0, NULL, NULL,
				&IoStatusBlock, peer, sizeof(ZA_PEERINFO), &Position, NULL)))
			{
				NtFlushBuffersFile(ScanContext->DumpFileHandle, &IoStatusBlock);
			}
			SfNFormatPrintPeer(ScanContext, peer);
		}
#ifdef _DEBUG
		else {
			OutputDebugString(TEXT("Duplicate peer entry in dump found\r\n"));
		}
#endif
	}
	RtlLeaveCriticalSection(&ScanContext->csTableDumpLock);
}

/*
* SfNgetLSender
*
* Purpose:
*
* getL processing thread.
*
*/
DWORD WINAPI SfNgetLSender(
	_In_ PZA_SCANCTX ScanContext
	)
{
	TCHAR				textbuf[256];
	struct sockaddr_in	io_addr;
	ULONG				c = 0, n = 0;
	ZA_PACKETHEADER		packet;
	USHORT              port;
	ZA_PEERINFO        *TableEntry;
	ZA_PEERINFO        *CurrentState;
	SIZE_T              memIO;

	RtlSecureZeroMemory(&textbuf, sizeof(textbuf));
	_strcpy(textbuf, TEXT("> getL thread started, sid=0x"));
	ultohex(ScanContext->SessionId, _strend(textbuf));
	SfUIAddEvent(ScanContext, GUI_EVENT_THREAD_STARTED, textbuf);

	RtlEnterCriticalSection(&ScanContext->csTableLock);

	n = RtlNumberGenericTableElementsAvl(&ScanContext->PeersTable);

	memIO = n * sizeof(ZA_PEERINFO);
	CurrentState = NULL;
	NtAllocateVirtualMemory(NtCurrentProcess(), &CurrentState, 0, &memIO, MEM_RESERVE | MEM_COMMIT, PAGE_READWRITE);
	if (CurrentState) {

		RtlSecureZeroMemory(CurrentState, memIO);

		for (
			TableEntry = RtlEnumerateGenericTableAvl(&ScanContext->PeersTable, TRUE), c = 0;
			TableEntry != NULL;
			TableEntry = RtlEnumerateGenericTableAvl(&ScanContext->PeersTable, FALSE), c += 1)
		{
			RtlCopyMemory(&CurrentState[c], TableEntry, sizeof(ZA_PEERINFO));
		}

	}
	RtlLeaveCriticalSection(&ScanContext->csTableLock);

	//memory error
	if (CurrentState == NULL)
		return (DWORD)-1;

	c = 0;
	_qsort(CurrentState, n, sizeof(ZA_PEERINFO), &SfQSortCompare);

	while (!g_guictx.bShutdown) {

		RtlSecureZeroMemory(&io_addr, sizeof(io_addr));
		io_addr.sin_family = AF_INET;

		port = (USHORT)(P2P_UDP_PORT_ADJUST + CurrentState[c].Port);
		
		io_addr.sin_port = htons((u_short)port);
		io_addr.sin_addr.S_un.S_addr = CurrentState[c].IP;

		packet.CRC = 0;
		packet.Command = 'getL';
		packet.SessionID = ScanContext->SessionId;
		packet.Opt1 = 0x0000;
		packet.Opt2 = c & 0x3ff;
		packet.CRC = RtlComputeCrc32(0, (PUCHAR)&packet, sizeof(packet));
		SfuDecodeStream((PBYTE)&packet, sizeof(packet), '1234');

		_strcpy(textbuf, TEXT("> sending getL -> "));
		RtlIpv4AddressToStringW((const struct in_addr*)&io_addr.sin_addr, _strend(textbuf));
		_strcat(textbuf, TEXT(":"));
		ultostr(ntohs(io_addr.sin_port), _strend(textbuf));
		SfUIAddEvent(ScanContext, GUI_EVENT_PACKET_SEND, textbuf);

		sendto(ScanContext->su, (const char *)&packet, sizeof(packet), 0, (struct sockaddr *)&io_addr, sizeof(io_addr));

		c += 1;
		if (c >= n) {
			SfUIAddEvent(ScanContext, GUI_EVENT_NEWROUND, TEXT("New round!"));
			
			memIO = 0;
			NtFreeVirtualMemory(NtCurrentProcess(), &CurrentState, &memIO, MEM_RELEASE);
			CurrentState = NULL;

			RtlEnterCriticalSection(&ScanContext->csTableLock);

			n = RtlNumberGenericTableElementsAvl(&ScanContext->PeersTable);
			memIO = n * sizeof(ZA_PEERINFO);
			NtAllocateVirtualMemory(NtCurrentProcess(), &CurrentState, 0, &memIO, MEM_RESERVE | MEM_COMMIT, PAGE_READWRITE);
			if (CurrentState) {
				RtlSecureZeroMemory(CurrentState, memIO);
				for (
					TableEntry = RtlEnumerateGenericTableAvl(&ScanContext->PeersTable, TRUE), c = 0;
					TableEntry != NULL;
					TableEntry = RtlEnumerateGenericTableAvl(&ScanContext->PeersTable, FALSE), c += 1)
				{
					RtlCopyMemory(&CurrentState[c], TableEntry, sizeof(ZA_PEERINFO));
				}
			}

			RtlLeaveCriticalSection(&ScanContext->csTableLock);

			//memory error
			if (CurrentState == NULL)
				break;

			c = 0;
			_qsort(CurrentState, n, sizeof(ZA_PEERINFO), &SfQSortCompare);

			Sleep(1000);
			continue;
		}
		Sleep(1000);
	}

	SfUIAddEvent(ScanContext, GUI_EVENT_THREAD_TERMINATED, TEXT("getL thread terminated."));
	return 0;
}

/*
* SfNP2PListener
*
* Purpose:
*
* Listener thread.
*
*/
DWORD WINAPI SfNP2PListener(
	_In_ PZA_SCANCTX ScanContext
	)
{
	WCHAR               textbuf[MAX_PATH];
	struct sockaddr_in  io_addr;
	int                 addr_len, recv_bytes;
	char                *recvbuffer = NULL, *sendbuffer = NULL;
	PZA_PACKET          recvpacket, sendpacket;
	ULONG               crc, k, l;
	USHORT              Port;
	BOOL                cond = FALSE;
	SIZE_T              memIO;
	ZA_PEERINFO         in_peer;

	RtlSecureZeroMemory(&textbuf, sizeof(textbuf));
	_strcpy(textbuf, TEXT("> p2p listener thread started, sid=0x"));
	ultohex(ScanContext->SessionId, _strend(textbuf));
	SfUIAddEvent(ScanContext, GUI_EVENT_THREAD_STARTED, textbuf);

	do {

		memIO = UDP_BUFFER_SIZE;
		NtAllocateVirtualMemory(NtCurrentProcess(), &recvbuffer, 0, &memIO, MEM_COMMIT | MEM_RESERVE, PAGE_READWRITE);
		if (recvbuffer == NULL)
			break;

		memIO = UDP_BUFFER_SIZE;
		NtAllocateVirtualMemory(NtCurrentProcess(), &sendbuffer, 0, &memIO, MEM_COMMIT | MEM_RESERVE, PAGE_READWRITE);
		if (sendbuffer == NULL)
			break;

		recvpacket = (PZA_PACKET)recvbuffer;
		sendpacket = (PZA_PACKET)sendbuffer;

		do {

			RtlSecureZeroMemory(&io_addr, sizeof(io_addr));
			addr_len = sizeof(io_addr);
			recv_bytes = recvfrom(ScanContext->su, recvbuffer, UDP_BUFFER_SIZE, 0, (struct sockaddr *)&io_addr, &addr_len);
			if (recv_bytes <= 0)
				continue;

			Port = ntohs(io_addr.sin_port);
			_strcpy(textbuf, TEXT("> received packet <- "));
			RtlIpv4AddressToStringW((const struct in_addr*)&io_addr.sin_addr, _strend(textbuf));
			_strcat(textbuf, TEXT(":"));
			ultostr(Port, _strend(textbuf));
			SfUIAddEvent(ScanContext, GUI_EVENT_PACKET_RECV, textbuf);

			SfuDecodeStream((PBYTE)recvbuffer, recv_bytes, '1234');
			crc = recvpacket->Header.CRC;
			recvpacket->Header.CRC = 0;

			if (RtlComputeCrc32(0, (PUCHAR)recvbuffer, recv_bytes) == crc) {

				_strcpy(textbuf, TEXT(">> CRC-ok, cmd="));
				switch (recvpacket->Header.Command) {

				case 'getL':
					_strcat(textbuf, TEXT("getL"));
					break;
				case 'retL':
					_strcat(textbuf, TEXT("retL"));
					break;
				default:
					_strcat(textbuf, TEXT("UnknownCmd"));
					break;
				}
				_strcat(textbuf, TEXT(" size="));
				ultostr(recv_bytes, _strend(textbuf));
				_strcat(textbuf, TEXT(" sid=0x"));
				ultohex(recvpacket->Header.SessionID, _strend(textbuf));
				_strcat(textbuf, TEXT(" opts="));
				ultohex(recvpacket->Header.Opt1, _strend(textbuf));
				_strcat(textbuf, TEXT(":"));
				ultohex(recvpacket->Header.Opt2, _strend(textbuf));

				if ((Port >= P2P_WIN32_PORT_RANGE_BEGIN) && (Port <= P2P_WIN32_PORT_RANGE_END)) {
					_strcat(textbuf, TEXT(" (Win32 bot)"));
				}
				else
					if ((Port >= P2P_WIN64_PORT_RANGE_BEGIN) && (Port <= P2P_WIN64_PORT_RANGE_END)) {
						_strcat(textbuf, TEXT(" (Win64 bot)"));
					}
					else {
						_strcat(textbuf, TEXT(" (Unknown bot port range)"));
					}

					SfUIAddEvent(ScanContext, GUI_EVENT_PACKET_HEADER, textbuf);

					switch (recvpacket->Header.Command) {

					case 'getL':

						if ((recvpacket->Header.Opt2 & P2P_GETFILELIST) == 0) {

							sendpacket->Header.CRC = 0;
							sendpacket->Header.Command = 'retL';
							sendpacket->Header.SessionID = ScanContext->SessionId;
							sendpacket->Header.Opt1 = 0x0000;
							sendpacket->Header.Opt2 = recvpacket->Header.Opt2 & P2P_SESSION_MASK;
							RtlCopyMemory(&sendpacket->PeerList, ScanContext->LastPeerList, sizeof(sendpacket->PeerList));
							sendpacket->Header.CRC = RtlComputeCrc32(0, (PUCHAR)sendbuffer, sizeof(ZA_PACKET));
							SfuDecodeStream((PBYTE)sendbuffer, sizeof(ZA_PACKET), '1234');
							sendto(ScanContext->su, (const char *)sendbuffer, sizeof(ZA_PACKET), 0, (struct sockaddr *)&io_addr, addr_len);
						}
						break;

					case 'retL':

						RtlCopyMemory(ScanContext->LastPeerList, recvpacket->PeerList, sizeof(ScanContext->LastPeerList));

						in_peer.IP = io_addr.sin_addr.S_un.S_addr;
						in_peer.Port = Port;
						in_peer.TimeStamp = 0;

						for (k = 0; k < recvpacket->Header.Opt1; k++) {
							l = sizeof(ZA_PACKET) + (k + 1)*sizeof(ZA_FILEHEADER);
							if (l <= (ULONG)recv_bytes)
								SfNAddFileHeader(
									ScanContext, 
									(PZA_FILEHEADER)(recvbuffer + sizeof(ZA_PACKET) + k*sizeof(ZA_FILEHEADER)),
									&in_peer
									);
						}

						for (k = 0; k < 16; k++)
							SfNAddToTable(ScanContext, &recvpacket->PeerList[k]);

						break;

					default:
						break;
					}
			}
			else {
				SfUIAddEvent(ScanContext, GUI_EVENT_ERROR, TEXT(">> received CRC mismatch, corrupted packet header"));
			}

		} while (!g_guictx.bShutdown);

	} while (cond);

	if (recvbuffer != NULL) {
		memIO = 0;
		NtFreeVirtualMemory(NtCurrentProcess(), &recvbuffer, &memIO, MEM_RELEASE);
	}

	if (sendbuffer != NULL) {
		memIO = 0;
		NtFreeVirtualMemory(NtCurrentProcess(), &sendbuffer, &memIO, MEM_RELEASE);
	}
	SfUIAddEvent(ScanContext, GUI_EVENT_THREAD_TERMINATED, TEXT("Listener thread terminated."));
	return 0;
}

/*
* SfNWorkerThread
*
* Purpose:
*
* Scan worker thread.
*
*/
VOID WINAPI SfNWorkerThread(
	_In_ PZA_SCANCTX ScanContext
	)
{
	BOOL                        cond = FALSE;
	SIZE_T                      sz;
	SOCKET                      su = INVALID_SOCKET;
	HANDLE                      hThread = NULL, hFile = NULL;
	ULONG                       nBootstrap = 0, k;
	NTSTATUS                    status;
	PVOID                       Wow64 = NULL;
	PZA_PEERINFO                Bootstrap = NULL;
	struct sockaddr_in          io_addr;
	UNICODE_STRING              usName;
	OBJECT_ATTRIBUTES           ObjectAttributes;
	IO_STATUS_BLOCK             IoStatusBlock;
	FILE_STANDARD_INFORMATION   fsi;
	WCHAR                       szText[MAX_PATH];
	BOOLEAN                     NewElement = FALSE;

	RtlInitializeCriticalSection(&ScanContext->csTableLock);
	RtlInitializeCriticalSection(&ScanContext->csTableDumpLock);

	do {

		if (!CryptAcquireContext(&ScanContext->CryptoProv, NULL, MS_DEF_PROV, PROV_RSA_FULL, CRYPT_VERIFYCONTEXT))
			break;

		k = ~GetTickCount();
		ScanContext->SessionId = RtlRandomEx(&k);
		if (!CryptGenRandom(ScanContext->CryptoProv, (DWORD)sizeof(ULONG), (BYTE*)&ScanContext->SessionId))
			break;

		if (!CryptImportKey(ScanContext->CryptoProv, (const BYTE *)RSA_KEY, sizeof(RSA_KEY), 0, 0, &ScanContext->CryptoKey))
			break;


		RtlInitializeGenericTableAvl(&ScanContext->PeersTable,
			(PRTL_AVL_COMPARE_ROUTINE)&SfAvlCompareCallback,
			(PRTL_AVL_ALLOCATE_ROUTINE)&SfAvlAllocateCallback,
			(PRTL_AVL_FREE_ROUTINE)&SfAvlFreeCallback,
			(PVOID)ScanContext);

		RtlInitializeGenericTableAvl(&ScanContext->PeersTableDump,
			(PRTL_AVL_COMPARE_ROUTINE)&SfAvlCompareCallback,
			(PRTL_AVL_ALLOCATE_ROUTINE)&SfAvlAllocateCallback,
			(PRTL_AVL_FREE_ROUTINE)&SfAvlFreeCallback,
			(PVOID)ScanContext);

		_strcpy(szText, TEXT("Loading bootstrap list "));
		_strcat(szText, P2P_BOOTSTRAP_NAME);
#ifdef _WIN64
		_strcat(szText, TEXT(", running in x86-64 mode"));
#else
		_strcat(szText, TEXT(", running in x86-32 mode"));
#endif
		SfUIAddEvent(NULL, GUI_EVENT_INFORMATION, szText);
		
		usName.Buffer = P2P_BOOTSTRAP_NAME;
		usName.Length = sizeof(P2P_BOOTSTRAP_NAME) - sizeof(WCHAR);
		usName.MaximumLength = usName.Length + sizeof(UNICODE_NULL);
		InitializeObjectAttributes(&ObjectAttributes, &usName, OBJ_CASE_INSENSITIVE, ScanContext->RootDirectoryHandle, NULL);
		if (!NT_SUCCESS(SfuLoadPeerList(&ObjectAttributes, &Bootstrap, &nBootstrap))) {
			SfUIAddEvent(NULL, GUI_EVENT_ERROR, TEXT("Could not read bootstrap peer list."));
			break;
		}

		_strcpy(szText, TEXT("Bootstrap loaded OK, peers count: "));
		ultostr(nBootstrap, _strend(szText));
		SfUIAddEvent(NULL, GUI_EVENT_INFORMATION, szText);

		_qsort(Bootstrap, nBootstrap, sizeof(ZA_PEERINFO), SfQSortCompare);
		//SfuWriteBufferToFile(L"test64.bin", Bootstrap, nBootstrap * sizeof(ZA_PEERINFO), FALSE, FALSE);
		for (k = 0; k < nBootstrap; k++) {
			NewElement = FALSE;
			if (!RtlInsertElementGenericTableAvl(&ScanContext->PeersTable, &Bootstrap[k], sizeof(ZA_PEERINFO), &NewElement))
				break;
		}

		sz = 0;
		NtFreeVirtualMemory(NtCurrentProcess(), &Bootstrap, &sz, MEM_RELEASE);
		Bootstrap = NULL;

		_strcpy(szText, TEXT("Loading dumped bootstrap list "));
		_strcat(szText, P2P_BOOTSTRAP_SAVE_NAME);
		SfUIAddEvent(NULL, GUI_EVENT_INFORMATION, szText);

		usName.Buffer = P2P_BOOTSTRAP_SAVE_NAME;
		usName.Length = sizeof(P2P_BOOTSTRAP_SAVE_NAME) - sizeof(WCHAR);
		usName.MaximumLength = usName.Length + sizeof(UNICODE_NULL);
		status = NtCreateFile(&hFile, FILE_READ_ACCESS | FILE_WRITE_ACCESS | SYNCHRONIZE, &ObjectAttributes,
			&IoStatusBlock, NULL, FILE_ATTRIBUTE_NORMAL, 0, FILE_OPEN_IF,
			FILE_SYNCHRONOUS_IO_NONALERT | FILE_NON_DIRECTORY_FILE, NULL, 0);
		if (!NT_SUCCESS(status)) {
			SfUIAddEvent(NULL, GUI_EVENT_ERROR, TEXT("Could not create output peer list."));
			break;
		}
		ScanContext->DumpFileHandle = hFile;

		RtlSecureZeroMemory(&fsi, sizeof(fsi));
		if (NT_SUCCESS(NtQueryInformationFile(hFile, &IoStatusBlock, &fsi, sizeof(fsi), FileStandardInformation))) {

			sz = fsi.EndOfFile.LowPart;
			if ((sz % sizeof(ZA_PEERINFO)) == 0) {

				Bootstrap = NULL;
				NtAllocateVirtualMemory(NtCurrentProcess(), &Bootstrap, 0, &sz, MEM_RESERVE | MEM_COMMIT, PAGE_READWRITE);
				if (Bootstrap) {
					if (NT_SUCCESS(NtReadFile(hFile, NULL, NULL, NULL, &IoStatusBlock, Bootstrap, fsi.EndOfFile.LowPart, NULL, NULL))) {
						nBootstrap = fsi.EndOfFile.LowPart / sizeof(ZA_PEERINFO);
						
						_strcpy(szText, TEXT("Dump bootstrap loaded OK, peers count: "));
						ultostr(nBootstrap, _strend(szText));
						SfUIAddEvent(NULL, GUI_EVENT_INFORMATION, szText);

						for (k = 0; k < nBootstrap; k++) {
							NewElement = FALSE;
							if (!RtlInsertElementGenericTableAvl(&ScanContext->PeersTableDump, &Bootstrap[k], sizeof(ZA_PEERINFO), &NewElement))
								break;
						}
					}
					sz = 0;
					NtFreeVirtualMemory(NtCurrentProcess(), &Bootstrap, &sz, MEM_RELEASE);
					Bootstrap = NULL;
				}
			}
		}

		su = socket(AF_INET, SOCK_DGRAM, IPPROTO_UDP);
		if (su == INVALID_SOCKET)
			break;

		ScanContext->su = su;

		RtlSecureZeroMemory(&io_addr, sizeof(io_addr));
		io_addr.sin_family = AF_INET;
		io_addr.sin_port = htons((u_short)UDP_PORT);
		if (bind(su, (struct sockaddr *)&io_addr, sizeof(io_addr)) != 0)
			break;

		NtQueryInformationProcess(NtCurrentProcess(), ProcessWow64Information, &Wow64, sizeof(PVOID), NULL);

		_strcpy(szText, TEXT("ZeroAccess monitor, mode="));
		ultostr((Wow64 != NULL) ? 32 : 64, _strend(szText));
		_strcat(szText, TEXT(", port: "));
		ultostr(UDP_PORT, _strend(szText));
		_strcat(szText, TEXT(", sid=0x"));
		ultohex(ScanContext->SessionId, _strend(szText));
		
		SetWindowText(g_guictx.MainWindow, szText);

		hThread = CreateThread(NULL, 0, (LPTHREAD_START_ROUTINE)&SfNgetLSender, (LPVOID)ScanContext, 0, NULL);
		if (hThread != NULL) {
			CloseHandle(hThread);
		}

		hThread = CreateThread(NULL, 0, (LPTHREAD_START_ROUTINE)&SfNP2PListener, (LPVOID)ScanContext, 0, NULL);
		if (hThread != NULL) {
			CloseHandle(hThread);
		}

		while (g_guictx.bShutdown == FALSE) {
			Sleep(1000);
		}

	} while (cond);

	//cleanup

	if (su != INVALID_SOCKET) {
		shutdown(su, SD_BOTH);
		closesocket(su);
	}

	if (ScanContext->RootDirectoryHandle != NULL) {
		NtClose(ScanContext->RootDirectoryHandle);
	}

	if (ScanContext->DumpFileHandle != NULL) {
		NtClose(ScanContext->DumpFileHandle);
	}

	if (Bootstrap != NULL) {
		sz = 0;
		NtFreeVirtualMemory(NtCurrentProcess(), &Bootstrap, &sz, MEM_RELEASE);
	}

	if (ScanContext->CryptoKey) {
		CryptDestroyKey(ScanContext->CryptoKey);
	}

	if (ScanContext->CryptoProv) {
		CryptReleaseContext(ScanContext->CryptoProv, 0);
	}

	RtlDeleteCriticalSection(&ScanContext->csTableLock);
	RtlDeleteCriticalSection(&ScanContext->csTableDumpLock);
}

/*
* SfNStartup
*
* Purpose:
*
* Create/Open directories and start worker thread.
*
*/
BOOL SfNStartup(
	_In_ ZA_SCANCTX *ScanContext
	)
{
	UNICODE_STRING     usName;
	ANSI_STRING        str;
	NTSTATUS           status;
	HANDLE             RootDirectoryHandle = NULL;
	IO_STATUS_BLOCK    IoStatusBlock;
	OBJECT_ATTRIBUTES  ObjectAttributes;
	PVOID              DllImageBase = NULL;
	BOOL               bResult = FALSE, cond = FALSE;

	RtlSecureZeroMemory(&usName, sizeof(usName));

	do {

		RtlInitUnicodeString(&usName, L"ntdll.dll");
		if (NT_SUCCESS(LdrGetDllHandle(NULL, NULL, &usName, &DllImageBase))) {
			RtlInitString(&str, "qsort");
			LdrGetProcedureAddress(DllImageBase, &str, 0, (PVOID)&_qsort);
			if (_qsort == NULL) {
				break;
			}
		}

		bResult = RtlDosPathNameToNtPathName_U(
			RtlGetCurrentPeb()->ProcessParameters->CurrentDirectory.DosPath.Buffer,
			&usName, NULL, NULL
			);
		if (bResult == FALSE)
			break;

		InitializeObjectAttributes(&ObjectAttributes,
			&usName,
			OBJ_CASE_INSENSITIVE, 0, NULL);

		status = NtCreateFile(&RootDirectoryHandle,
			FILE_GENERIC_READ | FILE_GENERIC_WRITE,
			&ObjectAttributes,
			&IoStatusBlock,
			NULL,
			FILE_ATTRIBUTE_READONLY,
			FILE_SHARE_READ | FILE_SHARE_WRITE | FILE_SHARE_DELETE,
			FILE_OPEN,
			FILE_DIRECTORY_FILE,
			NULL,
			0
			);

		RtlFreeUnicodeString(&usName);

		if (!NT_SUCCESS(status))
			break;
	
		usName.Buffer = L"U";
		usName.Length = 2;
		usName.MaximumLength = 4;
		ObjectAttributes.RootDirectory = RootDirectoryHandle;
		bResult = SfuCreateDirectory(&ObjectAttributes);
		if (bResult) {
			/*we dont use*/
			usName.Buffer = L"L";
			ObjectAttributes.RootDirectory = RootDirectoryHandle;
			bResult = SfuCreateDirectory(&ObjectAttributes);
			if (bResult == FALSE) {
				SfUIAddEvent(NULL, GUI_EVENT_ERROR, TEXT("Could not create working L directory."));
				break;
			}
		}
		else {
			SfUIAddEvent(NULL, GUI_EVENT_ERROR, TEXT("Could not create working U directory."));
			break;
		}

	} while (cond);

	if (!bResult) {
		if (RootDirectoryHandle)
			NtClose(RootDirectoryHandle);
	}
	else {
		if (RootDirectoryHandle) {
			ScanContext->RootDirectoryHandle = RootDirectoryHandle;
		}
	}

	return bResult;
}

/*
* SfNMain
*
* Purpose:
*
* Scan entry point.
*
*/
VOID SfNMain(
	VOID
	)
{
	HANDLE hThread;

	RtlSecureZeroMemory(&g_zascan, sizeof(g_zascan));
	SfNStartup(&g_zascan);
	if (SfInitMD5()) {
		hThread = CreateThread(NULL, 0, (LPTHREAD_START_ROUTINE)&SfNWorkerThread, &g_zascan, 0, NULL);
		if (hThread) {
			CloseHandle(hThread);
		}
	}
}
