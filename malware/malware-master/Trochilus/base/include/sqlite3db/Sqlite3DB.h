#pragma once
#include <string>
#include "sqlite3.h"
#include "utils/QueueWorker.h"

class Sqlite3QueryResult
{
public:
	Sqlite3QueryResult();
	~Sqlite3QueryResult();

	void Init(char** pResult, int rows, int columns);
	int Rows() const;
	int Columns() const;

	LPCSTR GetFieldName(int column) const;
	LPCSTR GetStrData(int row, int column) const;
	LONG GetLongData(int row, int column) const;
	UINT64 GetUint64Data(int row, int column) const;
	
private:
	char**	m_pResult;
	int		m_rows;
	int		m_columns;
};

class Sqlite3DB
{
public:
	Sqlite3DB();
	~Sqlite3DB();

	static BOOL Unicode2Utf8(LPCWSTR pUnicode, std::string& utf8str);
	static BOOL Utf82Unicode(LPCSTR pUtf8, CString& unicode);

	BOOL Open(LPCTSTR filepath);
	void Close();

	BOOL StartQueueWorker();
	BOOL IsQueueWorkerBusy();
	void AddSql2Queue(LPCSTR sql);
	BOOL AddSql2Queue(LPCWSTR sql);

	BOOL TableExists(LPCTSTR tableName);
	BOOL ExecuteSQL(LPCSTR sql, int* pAffectedRows = NULL);
	BOOL ExecuteSQL(LPCWSTR unicodeSql, int* pAffectedRows = NULL);
	BOOL QuerySQL(LPCSTR sql, Sqlite3QueryResult& result);

private:
	static void QueueSqlHandler(std::string item, Sqlite3DB* pOwner);

private:
	sqlite3*							m_pDB;
	QueueWorker<Sqlite3DB, std::string>	m_queueWorker;
};
