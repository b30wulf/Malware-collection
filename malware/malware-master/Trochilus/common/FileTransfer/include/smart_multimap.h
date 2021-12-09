#pragma once
#include <map>
#include "shared-mutex_lock.h"

namespace smart {
	template<typename KEY, typename VAL> class multimap : public std::multimap<KEY,VAL>
	{
	public:
		void add(const KEY& key, VAL& val)
		{
			m_srwlock.AcquireExclusive();
			std::multimap<KEY,VAL>::insert(std::multimap<KEY,VAL>::value_type(key, val));
			m_srwlock.ReleaseExclusive();
		}
		
		bool findall(const KEY& key, std::vector<VAL> &val)
		{
			std::pair<std::multimap<KEY,VAL>::iterator,std::multimap<KEY,VAL>::iterator> pos;
			bool ret = false;
			m_srwlock.AcquireShared();
			pos = std::multimap<KEY,VAL>::equal_range(key);
			for (std::multimap<KEY,VAL>::iterator i = pos.first; i != pos.second; i++)
			{
				val.push_back(i->second);
			}
			ret = !val.empty();
			m_srwlock.ReleaseShared();
			return ret;
		}

		bool remove(KEY& key, VAL& val)
		{
			std::pair<std::multimap<KEY,VAL>::iterator,std::multimap<KEY,VAL>::iterator> pos;
			bool ret = false;
			m_srwlock.AcquireExclusive();
			pos = std::multimap<KEY,VAL>::equal_range(key);
			for (std::multimap<KEY,VAL>::iterator i = pos.first;
				i != pos.second;
				i++
				)
			{
				if (i->second == val)
				{
					std::multimap<KEY,VAL>::erase(i);
					ret = true;
					break;
				}
			}
			m_srwlock.ReleaseExclusive();
			return ret;
		}

		bool removeall(KEY& key)
		{
			std::pair<std::multimap<KEY,VAL>::iterator,std::multimap<KEY,VAL>::iterator> pos;
			bool ret = false;
			m_srwlock.AcquireExclusive();
			pos = std::multimap<KEY,VAL>::equal_range(key);
			if (pos.first != std::multimap<KEY,VAL>::end() && pos.second != std::multimap<KEY,VAL>::end())
			{
				std::multimap<KEY,VAL>::erase(pos.first, pos.second);
				ret = true;
			}
			m_srwlock.ReleaseExclusive();
			return ret;
		}

		void clear()
		{
			m_srwlock.AcquireExclusive();
			std::multimap<KEY,VAL>::clear();
			m_srwlock.ReleaseExclusive();
		}
		
		size_type size() const
		{
			size_type ret = 0;
			m_srwlock.AcquireShared();
			ret = std::multimap<KEY,VAL>::size();
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