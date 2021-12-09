#pragma once


// CColorProgressCtrl
/*
	作者：ToTo
	QQ:27459870
	Web:http://www.ecjtu.org/forum
	日期:2008/12/31
*/

class CColorProgressCtrl : public CProgressCtrl
{
	DECLARE_DYNAMIC(CColorProgressCtrl)

public:
	CColorProgressCtrl();
	virtual ~CColorProgressCtrl();

protected:
	DECLARE_MESSAGE_MAP()
public:
	//设置范围
	void SetRange(short nLower, short nUpper);
	//设置位置
	void SetPos(int nPos);
	//设置颜色
	void SetColor(COLORREF roundRectColor, COLORREF progressColor);
	//设置弧度
	void SetRadin(CPoint radin);
private:
	COLORREF m_roundRectColor;	//边框色
	COLORREF m_progressColor;	//进度色
	short m_nLower;				//下限
	short m_nUpper;				//上限
	CRect m_wndRect;			//进度条大小
	short m_nPos;				//当前位置
	CPoint m_radin;				//弧度
	afx_msg void OnPaint();
};


