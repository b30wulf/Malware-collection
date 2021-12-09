#pragma once

typedef void (*FnDropFilesCallback)(const CStringArray& filepathList, LPVOID lpParameter);

class CMyDropTarget : public IDropTarget
{
public:
	CMyDropTarget()
		: m_bHandleDrop(FALSE)
		, m_fnCallback(NULL)
		, m_lpParameter(NULL)
	{

	}

	void SetDropCallback(FnDropFilesCallback fnCallback, LPVOID lpParameter)
	{
		m_fnCallback = fnCallback;
		m_lpParameter = lpParameter;
		m_bHandleDrop = TRUE;
	}

	virtual ULONG STDMETHODCALLTYPE AddRef() { return 1; }
	virtual ULONG STDMETHODCALLTYPE Release() { return 1; }
	virtual HRESULT STDMETHODCALLTYPE QueryInterface(REFIID iid, void** ppvObject)
	{
		if (iid==IID_IUnknown || iid==IID_IDropTarget)
		{
			*ppvObject = this;
			return S_OK;
		}
		return E_NOINTERFACE;
	}

	STDMETHOD(DragEnter)(IDataObject* pDataObject, DWORD, POINTL, DWORD * pdwEffect)
	{
		*pdwEffect = DROPEFFECT_NONE;
		return S_OK;
	}

	STDMETHOD(DragOver)(DWORD, POINTL, DWORD * pdwEffect)
	{
		*pdwEffect = m_bHandleDrop ? DROPEFFECT_LINK : DROPEFFECT_NONE;
		return S_OK;
	}

	STDMETHOD(DragLeave)()
	{
		return S_OK;
	}

	STDMETHOD(Drop)(IDataObject* pDataObject, DWORD, POINTL, DWORD * pdwEffect)
	{
		if (m_bHandleDrop)
		{
			*pdwEffect = DROPEFFECT_LINK;

			CStringArray filepathList;
			GetDropFilelist(pDataObject, filepathList);

			if (NULL != m_fnCallback)
			{
				m_fnCallback(filepathList, m_lpParameter);
			}
		}
		else
		{
			*pdwEffect = DROPEFFECT_NONE;
		}

		return S_OK;
	}

private:
	void GetDropFilelist(IDataObject* pDataObject, CStringArray& filepathList)
	{
		FORMATETC cFmt = {(CLIPFORMAT) CF_HDROP, NULL, DVASPECT_CONTENT, -1, TYMED_HGLOBAL};
		STGMEDIUM stgMedium;
		HRESULT hr = pDataObject->GetData(&cFmt, &stgMedium);
		if (SUCCEEDED(hr))
		{
			HDROP hDrop = (HDROP) stgMedium.hGlobal;
			GetFileList(hDrop, filepathList);
		}
	}

	void GetFileList(HDROP hDrop, CStringArray& filepathList)
	{
		TCHAR buffer[MAX_PATH] = {0};
		UINT iFiles = 0xFFFFFFFF;
		int iCount = ::DragQueryFile(hDrop, iFiles, buffer, 0); //Get the Drag _Files Number.

		if(iCount > 0)
		{
			for (int i = 0; i < iCount; i++)
			{
				ZeroMemory(buffer, sizeof(buffer));
				if(::DragQueryFile(hDrop, i, buffer, sizeof(buffer)))
				{
					//Got the FileName in Buffer
					CString filepath(buffer);
					filepath.Trim();

					filepathList.Add(filepath);
				}
			}
		}
	}

private:
	BOOL					m_bHandleDrop;
	FnDropFilesCallback		m_fnCallback;
	LPVOID					m_lpParameter;
};
