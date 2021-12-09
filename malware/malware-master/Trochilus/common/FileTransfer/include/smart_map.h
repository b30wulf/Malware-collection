#pragma once
#include <map>
#include "shared-mutex_lock.h"

namespace smart {
	template<typename KEY, typename VAL> class map : public std::map<KEY,VAL>
	{
	public:
		bool add(KEY& key, VAL& val)
		{
			bool ret = false;
			m_srwlock.AcquireExclusive();
			std::pair<std::map<KEY,VAL>::iterator,bool> ip = std::map<KEY,VAL>::insert(std::map<KEY,VAL>::value_type(key, val));
			ret = ip.second;
			m_srwlock.ReleaseExclusive();
			return ret;
		}
		
		VAL* find(const KEY& key)
		{
			VAL* ret = NULL;
			m_srwlock.AcquireShared();
			std::map<KEY,VAL>::iterator it = std::map<KEY,VAL>::find(key);
			if (it != std::map<KEY,VAL>::end()) {
				ret = &(it->second);
			}
			m_srwlock.ReleaseShared();
			return ret;
		}

		bool remove(KEY& key)
		{
			bool ret = false;
			m_srwlock.AcquireExclusive();
			std::map<KEY,VAL>::iterator it = std::map<KEY,VAL>::find(key);
			if (it != std::map<KEY,VAL>::end())
			{
				std::map<KEY,VAL>::erase(key);
				ret = true;
			}
			m_srwlock.ReleaseExclusive();
			return ret;
		}

		void clear()
		{
			m_srwlock.AcquireExclusive();
			std::map<KEY,VAL>::clear();
			m_srwlock.ReleaseExclusive();
		}
		
		size_type size() const
		{
			size_type ret = 0;
			m_srwlock.AcquireShared();
			ret = std::map<KEY,VAL>::size();
			m_srwlock.ReleaseShared();
			return ret;
		}
		
		void lock(bool bread = true)
		{
			if (bread)
				m_srwlock.AcquireShared();
			else
				m_srwlock.AcquireExclusive();
		}

		void unlock(bool bread = true)
		{
			if (bread)
				m_srwlock.ReleaseShared();
			else
				m_srwlock.ReleaseExclusive();
		}

	private:
		CSharedMutexLock m_srwlock;
	};
}