#pragma once
#include "MyString.h"

//数组模板定义
template <class ITEM, class ctrlUninit>
class ItemList
{
public:
	ItemList()
	: m_pArray(NULL)
	, m_dwCount(0)
	{
	}

	~ItemList()
	{
		Clean();
	}

	void Clean()
	{
		if (NULL != this->m_pArray)
		{
			if (sizeof(ctrlUninit) > 1)
			{
				for (DWORD i = 0; i < this->m_dwCount; i++)
				{
					this->m_pArray[i].~ITEM();
				}
			}
			
			free(this->m_pArray);
			this->m_pArray = NULL;
		}

		this->m_dwCount = 0;
	}

	void Alloc(DWORD dwSize)
	{
		Clean();
		if (dwSize == 0)
		{
			this->m_pArray = NULL;
			this->m_dwCount = 0;
		}
		else
		{
			this->m_pArray = (ITEM*) malloc(sizeof(ITEM) * dwSize);
			this->m_dwCount = dwSize;
		}
	}

	DWORD Count() const
	{
		return m_dwCount;
	}

	operator ITEM*()
	{
		return m_pArray;
	}

	ITEM* GetPointer() const
	{
		return m_pArray;
	}

	ITEM& At(DWORD dwIndex) const
	{
		if (dwIndex >= m_dwCount) dwIndex = m_dwCount - 1;
		return m_pArray[dwIndex];
	}

private:
	ITEM*	m_pArray;
	DWORD	m_dwCount;
};

#define NEED_UNINIT DWORD
#define NO_UNINIT	BYTE

typedef ItemList<MyString, NEED_UNINIT> MyStringList;
typedef ItemList<BYTE, NO_UNINIT> ByteList;