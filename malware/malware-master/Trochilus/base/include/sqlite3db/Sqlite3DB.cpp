#include "stdafx.h"
#include "Sqlite3DB.h"

#pragma comment(lib, "sqlite3.lib")

//---------------------CSQLite3Exception------------------------------
class CSQLite3Exception
{
public:
	CSQLite3Exception(const int nErrCode,
		char* szErrMess,
		bool bDeleteMsg=true)
	{
		m_pErrMess = sqlite3_mprintf("%s[%d]: %s",
			errorCodeAsString(nErrCode),
			nErrCode,
			szErrMess ? szErrMess : "");

		if (bDeleteMsg && szErrMess)
		{
			sqlite3_free(szErrMess);
		}
	}
	CSQLite3Exception(const CSQLite3Exception&  e)
	{
		m_pErrMess = 0;
		if (e.m_pErrMess)
		{
			m_pErrMess = sqlite3_mprintf("%s", e.m_pErrMess);
		}
	}
	virtual ~CSQLite3Exception()
	{
		if (m_pErrMess)
		{
			sqlite3_free(m_pErrMess);
			m_pErrMess = 0;
		}
	}
	const int getErrorCode() { return m_errCode; }
	const char* getErrorMessage() { return m_pErrMess; }
	const char* what() { return m_pErrMess; }
	static const char* errorCodeAsString(int errCode)
	{
		switch (errCode)
		{
		case SQLITE_OK          : return "SQLITE_OK";
		case SQLITE_ERROR       : return "SQLITE_ERROR";
		case SQLITE_INTERNAL    : return "SQLITE_INTERNAL";
		case SQLITE_PERM        : return "SQLITE_PERM";
		case SQLITE_ABORT       : return "SQLITE_ABORT";
		case SQLITE_BUSY        : return "SQLITE_BUSY";
		case SQLITE_LOCKED      : return "SQLITE_LOCKED";
		case SQLITE_NOMEM       : return "SQLITE_NOMEM";
		case SQLITE_READONLY    : return "SQLITE_READONLY";
		case SQLITE_INTERRUPT   : return "SQLITE_INTERRUPT";
		case SQLITE_IOERR       : return "SQLITE_IOERR";
		case SQLITE_CORRUPT     : return "SQLITE_CORRUPT";
		case SQLITE_NOTFOUND    : return "SQLITE_NOTFOUND";
		case SQLITE_FULL        : return "SQLITE_FULL";
		case SQLITE_CANTOPEN    : return "SQLITE_CANTOPEN";
		case SQLITE_PROTOCOL    : return "SQLITE_PROTOCOL";
		case SQLITE_EMPTY       : return "SQLITE_EMPTY";
		case SQLITE_SCHEMA      : return "SQLITE_SCHEMA";
		case SQLITE_TOOBIG      : return "SQLITE_TOOBIG";
		case SQLITE_CONSTRAINT  : return "SQLITE_CONSTRAINT";
		case SQLITE_MISMATCH    : return "SQLITE_MISMATCH";
		case SQLITE_MISUSE      : return "SQLITE_MISUSE";
		case SQLITE_NOLFS       : return "SQLITE_NOLFS";
		case SQLITE_AUTH        : return "SQLITE_AUTH";
		case SQLITE_FORMAT      : return "SQLITE_FORMAT";
		case SQLITE_RANGE       : return "SQLITE_RANGE";
		case SQLITE_ROW         : return "SQLITE_ROW";
		case SQLITE_DONE        : return "SQLITE_DONE";
		default: return "UNKNOWN_ERROR";
		}
	}

private:
	int m_errCode;
	char* m_pErrMess;
};
#define SQLTRY() try {
#define SQLCACHE(memo)									\
	}													\
	catch (CSQLite3Exception& e)						\
	{													\
		errorLog(_T("%s failed. errorcode:%u [%s]"),	\
			memo,										\
			e.getErrorCode(),							\
			CString(e.getErrorMessage())				\
			);											\
	}	
#define SQLCACHE_RETFALSE(memo)							\
	}													\
	catch (CSQLite3Exception& e)						\
	{													\
		errorLog(_T("%s failed. errorcode:%u [%s]"),	\
			memo,										\
			e.getErrorCode(),							\
			CString(e.getErrorMessage())				\
			);											\
		return FALSE;									\
	}
//---------------------CSQLite3Exception------------------------------




Sqlite3DB::Sqlite3DB()
	: m_pDB(NULL)
{

}

Sqlite3DB::~Sqlite3DB()
{
	Close();
}

BOOL Sqlite3DB::Unicode2Utf8(LPCWSTR pUnicode, std::string& utf8str)
{
	if (NULL == pUnicode) return FALSE;

	BOOL bRet = FALSE;

	int nBytesNeeded = ::WideCharToMultiByte(CP_UTF8, 0, pUnicode, -1, NULL, 0, NULL, NULL);
	LPSTR utf8 = new CHAR[nBytesNeeded];
	ZeroMemory(utf8, nBytesNeeded);
	if (WideCharToMultiByte(CP_UTF8, 0, pUnicode, -1, utf8, nBytesNeeded, NULL, NULL) > 0)
	{
		utf8str = utf8;
		bRet = TRUE;
	}

	delete[] utf8;

	return bRet;
}

BOOL Sqlite3DB::Utf82Unicode(LPCSTR pUtf8, CString& unicode)
{
	if (NULL == pUtf8) return FALSE;

	DWORD dwUnicodeLen = ::MultiByteToWideChar(CP_UTF8, 0, pUtf8, -1, NULL, 0);
	TCHAR *pText = new TCHAR[dwUnicodeLen];
	if (NULL == pText)
	{
		return FALSE;
	}

	::MultiByteToWideChar(CP_UTF8, 0, pUtf8, -1, pText, dwUnicodeLen);

	unicode = pText;

	delete[] pText;

	return TRUE;
}

BOOL Sqlite3DB::Open( LPCTSTR filepath )
{
	Close();

	SQLTRY();

	std::string utf8Filepath;
	if (! Unicode2Utf8(filepath, utf8Filepath))
	{
		errorLog(_T("can not transfer [%s] to utf8"), filepath);
		return FALSE;
	}
	int iRet = sqlite3_open(utf8Filepath.c_str(), &m_pDB);
	if(iRet != SQLITE_OK)
	{
		errorLogE(_T("Cannot open db[%s]: %s."), filepath, CString(sqlite3_errmsg(m_pDB)));
		return FALSE;
	}

	SQLCACHE_RETFALSE(_T("open db"));

	return TRUE;
}

void Sqlite3DB::Close()
{
	if (NULL == m_pDB) return;

	m_queueWorker.Stop();
	sqlite3_close(m_pDB);
	m_pDB = NULL;
}

BOOL Sqlite3DB::StartQueueWorker()
{
	if (! m_queueWorker.Init(this, QueueSqlHandler, _T("sqlite3db queueworker")))
	{
		return FALSE;
	}

	return m_queueWorker.Start();
}

void Sqlite3DB::AddSql2Queue( LPCSTR sql )
{
	m_queueWorker.AddWorkItem(sql);
}

BOOL Sqlite3DB::AddSql2Queue( LPCWSTR sql )
{
	std::string utf8Sql;
	if (! Unicode2Utf8(sql, utf8Sql))
	{
		return FALSE;
	}

	AddSql2Queue(utf8Sql.c_str());

	return TRUE;
}

BOOL Sqlite3DB::TableExists( LPCTSTR tableName )
{
	if (NULL == m_pDB) return FALSE;

	BOOL bExists = FALSE;

	SQLTRY();

	CStringA sql;
	sql.Format("select name from sqlite_master where type='table' and name='%s'", CStringA(tableName));

	char** dbresult = NULL;
	char* errmsg = NULL;
	int nrow = 0;
	int ncolumn = 0; 
	int ret = sqlite3_get_table(m_pDB, sql, &dbresult, &nrow, &ncolumn, &errmsg);
	if(ret == SQLITE_OK && nrow > 0)
	{
		bExists = TRUE;
	}

	sqlite3_free_table(dbresult);

	SQLCACHE_RETFALSE(_T("table exists"));

	return bExists;
}

BOOL Sqlite3DB::ExecuteSQL( LPCSTR sql, int* pAffectedRows )
{
	if (NULL == m_pDB) return FALSE;
	BOOL bRet = FALSE;

	SQLTRY();

	char* errmsg = NULL;
	int ret = sqlite3_exec(m_pDB, sql, NULL, NULL, &errmsg);
	if(ret == SQLITE_OK)
	{
		if (NULL != pAffectedRows) *pAffectedRows = sqlite3_changes(m_pDB);
		bRet = TRUE;
	}
	else
	{
		errorLog(_T("execute sql [%s] failed: %s"), CString(sql), CString(errmsg));
	}
	sqlite3_free(errmsg);

	SQLCACHE_RETFALSE(_T("ExecuteSQL"));

	return bRet;
}

BOOL Sqlite3DB::ExecuteSQL( LPCWSTR unicodeSql, int* pAffectedRows /*= NULL*/ )
{
	std::string utf8Sql;
	if (! Unicode2Utf8(unicodeSql, utf8Sql))
	{
		return FALSE;
	}

	return ExecuteSQL(utf8Sql.c_str(), pAffectedRows);
}

BOOL Sqlite3DB::QuerySQL( LPCSTR sql, Sqlite3QueryResult& result )
{
	if (NULL == m_pDB) return FALSE;
	BOOL bRet = FALSE;

	SQLTRY();

	char** dbresult = NULL;
	char* errmsg = NULL;
	int nrow = 0;
	int ncolumn = 0; 
	int ret = sqlite3_get_table(m_pDB, sql, &dbresult, &nrow, &ncolumn, &errmsg);
	if(ret == SQLITE_OK)
	{
		bRet = TRUE;
		result.Init(dbresult, nrow, ncolumn);
	}

	SQLCACHE_RETFALSE(_T("querysql"));
	
	return bRet;
}

void Sqlite3DB::QueueSqlHandler( std::string item, Sqlite3DB* pOwner )
{
	pOwner->ExecuteSQL(item.c_str());
}

BOOL Sqlite3DB::IsQueueWorkerBusy()
{
	if (m_queueWorker.QueueLength() > 0 || m_queueWorker.IsWorkerBusy())
	{
		return TRUE;
	}
	else
	{
		return FALSE;
	}
}

//---------------------Sqlite3QueryResult---------------------
Sqlite3QueryResult::Sqlite3QueryResult()
	: m_pResult(NULL)
	, m_rows(0)
	, m_columns(0)
{

}

Sqlite3QueryResult::~Sqlite3QueryResult()
{
	if (NULL != m_pResult)
	{
		sqlite3_free_table(m_pResult);
		m_pResult = NULL;
	}
}

void Sqlite3QueryResult::Init( char** pResult, int rows, int columns )
{
	m_pResult = pResult;
	m_rows = rows;
	m_columns = columns;
}

int Sqlite3QueryResult::Rows() const
{
	return m_rows;
}

int Sqlite3QueryResult::Columns() const
{
	return m_columns;
}

LPCSTR Sqlite3QueryResult::GetStrData( int row, int column ) const
{
	if (NULL == m_pResult || row >= m_rows || row < 0 || column > m_columns || column < 0) return NULL;

	return m_pResult[m_columns + row * m_columns + column];
}

LPCSTR Sqlite3QueryResult::GetFieldName( int column ) const
{
	if (NULL == m_pResult || column < 0 || column >= m_columns) return FALSE;

	return m_pResult[column];;
}

LONG Sqlite3QueryResult::GetLongData( int row, int column ) const
{
	LPCSTR pStr = GetStrData(row, column);
	if (NULL == pStr) return 0;

	return atol(pStr);
}

UINT64 Sqlite3QueryResult::GetUint64Data( int row, int column ) const
{
	LPCSTR pStr = GetStrData(row, column);
	if (NULL == pStr) return 0;

	UINT64 ret = 0;
	sscanf_s(pStr, "%I64u", &ret);

	return ret;
}
