// ProgressListCtrl.cpp : 实现文件
//

#include "stdafx.h"
#include "ProgressListCtrl.h"


// CProgressListCtrl

IMPLEMENT_DYNAMIC(CProgressListCtrl, CListCtrl)

CProgressListCtrl::CProgressListCtrl()
{

}

CProgressListCtrl::~CProgressListCtrl()
{
}


BEGIN_MESSAGE_MAP(CProgressListCtrl, CListCtrl)
	ON_WM_PAINT()
END_MESSAGE_MAP()



// CProgressListCtrl 消息处理程序



void CProgressListCtrl::OnPaint()
{
	//CPaintDC dc(this); // device context for painting
	// TODO: 在此处添加消息处理程序代码
	// 不为绘图消息调用 CListCtrl::OnPaint()
	
	int Top=GetTopIndex();
	int Total=GetItemCount();
	int PerPage=GetCountPerPage();
	int LastItem=((Top+PerPage)>Total)?Total:Top+PerPage;

	int nItemCount = GetItemCount();
	DeleteProgress();
	CRect rtProgress;
	CRect rtColumn;
	
	CString strPos;
	int nPos;
	for (int nIndex = Top; nIndex < LastItem; ++nIndex)
	{
		GetSubItemRect(nIndex, 2, LVIR_LABEL, rtProgress);

		strPos= GetItemText(nIndex, 2);
		nPos = atoi(t2a(strPos.GetBuffer()));

		CColorProgressCtrl *pColorProgress = new CColorProgressCtrl();
		pColorProgress->Create(NULL,rtProgress,this,IDC_PROGRESS + nIndex);
		pColorProgress->SetPos(nPos);
		pColorProgress->ShowWindow(SW_SHOW);
	}
	CListCtrl::OnPaint();
}


void CProgressListCtrl::DeleteProgress()
{
	for (std::vector<CColorProgressCtrl*>::iterator iter = m_vProgress.begin();
		iter != m_vProgress.end(); ++iter)
	{
		delete *iter;
	}
	m_vProgress.clear();
}