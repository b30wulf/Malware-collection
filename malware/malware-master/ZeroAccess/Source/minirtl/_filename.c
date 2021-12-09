#include <Windows.h>
#include "minirtl.h"

char *_filename_a(const char *f)
{
	char *p = (char *)f;

	if (f == 0)
		return 0;

	while (*f != (char)0) {
		if (*f == '\\')
			p = (char *)f + 1;
		f++;
	}
	return p;
}

wchar_t *_filename_w(const wchar_t *f)
{
	wchar_t *p = (wchar_t *)f;

	if (f == 0)
		return 0;

	while (*f != (wchar_t)0) {
		if (*f == (wchar_t)'\\')
			p = (wchar_t *)f + 1;
		f++;
	}
	return p;
}

char *_fileext_a(const char *f)
{
	char *p = 0;

	if (f == 0)
		return 0;

	while (*f != (char)0) {
		if (*f == '.')
			p = (char *)f;
		f++;
	}

	if (p == 0)
		p = (char *)f;

	return p;
}

wchar_t *_fileext_w(const wchar_t *f)
{
	wchar_t *p = 0;

	if (f == 0)
		return 0;

	while (*f != (wchar_t)0) {
		if (*f == '.')
			p = (wchar_t *)f;
		f++;
	}

	if (p == 0)
		p = (wchar_t *)f;

	return p;
}

char *_filename_noext_a(char *dest, const char *f)
{
	char *p, *l, *dot;

	if ((f == 0) || (dest == 0))
		return 0;

	p = _filename_a(f);
	dot = _strend_a(p);
	l = p;

	while (*l != (char)0)
	{
		if (*l == '.')
			dot = l;
		l++;
	}

	while (p<dot)
	{
		*dest = *p;
		p++;
		dest++;
	}

	*dest = 0;
	return dest;
}

wchar_t *_filename_noext_w(wchar_t *dest, const wchar_t *f)
{
	wchar_t *p, *l, *dot;

	if ((f == 0) || (dest == 0))
		return 0;

	p = _filename_w(f);
	dot = _strend_w(p);
	l = p;

	while (*l != (wchar_t)0)
	{
		if (*l == (wchar_t)'.')
			dot = l;
		l++;
	}

	while (p<dot)
	{
		*dest = *p;
		p++;
		dest++;
	}

	*dest = 0;
	return dest;
}
