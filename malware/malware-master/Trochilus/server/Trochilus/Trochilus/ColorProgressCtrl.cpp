// ColorProgressCtrl.cpp : 实现文件
//

#include "stdafx.h"
#include "ColorProgressCtrl.h"


// CColorProgressCtrl

IMPLEMENT_DYNAMIC(CColorProgressCtrl, CProgressCtrl)

CColorProgressCtrl::CColorProgressCtrl()
{
	m_roundRectColor = RGB(116, 158, 210);	//边框色
	m_progressColor = RGB(45, 213,79);		//进度色
	m_nLower = 0;							//下限
	m_nUpper = 100;							//上限
	m_nPos = 0;								//当前位置
	m_radin = CPoint(0, 0);					//弧度
}

CColorProgressCtrl::~CColorProgressCtrl()
{
}


BEGIN_MESSAGE_MAP(CColorProgressCtrl, CProgressCtrl)
	ON_WM_PAINT()
END_MESSAGE_MAP()



// CColorProgressCtrl 消息处理程序



//设置范围
void CColorProgressCtrl::SetRange(short nLower, short nUpper)
{

}
//设置位置
void CColorProgressCtrl::SetPos(int nPos)
{
	if (nPos < 0 || nPos > m_nUpper)
	{
		return;
	}
	m_nPos = nPos;
	InvalidateRect(m_wndRect);
}
//设置颜色
void CColorProgressCtrl::SetColor(COLORREF roundRectColor, COLORREF progressColor)
{
	m_roundRectColor = roundRectColor;
	m_progressColor = progressColor;
}
//设置弧度
void CColorProgressCtrl::SetRadin(CPoint radin)
{
	m_radin = radin;
}


void CColorProgressCtrl::OnPaint()
{
	CPaintDC dc(this); // device context for painting
	// TODO: 在此处添加消息处理程序代码
	GetClientRect(&m_wndRect);
	//画边框
	CPen pen1;
	pen1.CreatePen(PS_SOLID, 2, m_roundRectColor);
	CPen *oldPen = dc.SelectObject(&pen1);
	dc.RoundRect(m_wndRect, m_radin);
	dc.SelectObject(oldPen);
	//画进度
	CRect rectProgress;
	CBrush brush;
	CPen pen2;
	pen2.CreatePen(PS_NULL, 0, m_roundRectColor);
	brush.CreateSolidBrush(m_progressColor);
	rectProgress.bottom = m_wndRect.bottom - 1;
	rectProgress.top = m_wndRect.top + 1;
	rectProgress.left = m_wndRect.left + 1;
	rectProgress.right = m_wndRect.right * (float)m_nPos / (float)m_nUpper - 1;
	dc.SelectObject(pen2);
	dc.SelectObject(brush);
	dc.RoundRect(rectProgress, m_radin);
	// 不为绘图消息调用 CProgressCtrl::OnPaint()
}
