using System.Collections;
using System.Windows.Forms;

public class ColumnSorter : IComparer
{
    private int _columnIndex;

    public ColumnSorter(int columnIndex)
    {
        _columnIndex = columnIndex;
    }

    #region IComparer Members

    public int Compare(object x, object y)
    {
        ListViewItem lvi1 = x as ListViewItem;
        ListViewItem lvi2 = y as ListViewItem;

        return
        lvi1.SubItems[_columnIndex].Text.CompareTo(lvi2.SubItems[_columnIndex].Text);
    }

    #endregion
}