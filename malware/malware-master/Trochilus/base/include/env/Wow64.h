#pragma once

class Wow64FsRedirectionDisabler
{
public:
	typedef BOOL (WINAPI *FnWow64DisableWow64FsRedirection)(__out PVOID *OldValue);
	typedef BOOL (WINAPI *FnWow64RevertWow64FsRedirection)(__in PVOID OlValue);

	Wow64FsRedirectionDisabler()
		: m_hMod(NULL)
		, m_bDisabled(FALSE)
		, m_lpOldValue(NULL)
	{
		
	}

	~Wow64FsRedirectionDisabler()
	{
		if (NULL != m_hMod) ::FreeLibrary(m_hMod);
	}
	
	BOOL Disable()
	{
		if (m_bDisabled) return TRUE;

		if (! Init()) return FALSE;

		m_bDisabled = m_fnDisable(&m_lpOldValue);

		return m_bDisabled;
	}

	BOOL Revert()
	{
		if (! m_bDisabled) return TRUE;

		if (! Init()) return FALSE;

		m_bDisabled = ! m_fnRevert(m_lpOldValue);

		return ! m_bDisabled;
	}

private:
	BOOL Init()
	{
		if (NULL == m_hMod)
		{
			m_hMod = ::LoadLibrary(_T("Kernel32.dll"));  
			if (NULL == m_hMod)
			{
				return FALSE;
			}

			m_fnDisable = (FnWow64DisableWow64FsRedirection) ::GetProcAddress(m_hMod, "Wow64DisableWow64FsRedirection");
			m_fnRevert = (FnWow64RevertWow64FsRedirection) ::GetProcAddress(m_hMod, "Wow64RevertWow64FsRedirection");
			if (NULL == m_fnDisable || NULL == m_fnRevert)
			{
				::FreeLibrary(m_hMod);
				m_hMod = NULL;

				return FALSE;
			}
		}

		return TRUE;
	}

private:
	HMODULE	m_hMod;
	FnWow64DisableWow64FsRedirection	m_fnDisable;
	FnWow64RevertWow64FsRedirection		m_fnRevert;
	LPVOID	m_lpOldValue;
	BOOL	m_bDisabled;
};
