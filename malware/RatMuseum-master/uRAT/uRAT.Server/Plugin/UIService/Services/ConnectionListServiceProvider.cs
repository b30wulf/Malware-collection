using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Windows.Forms;
using uRAT.Server.Controls;
using uRAT.Server.Forms;
using uRAT.Server.Tools.Extensions;


namespace uRAT.Server.Plugin.UIService.Services
{
    public class ConnectionListService : IUiService
    {
        public string Identifier
        {
            get { return "ConnectionList"; }
        }

        public ConnectionListItem FindItem(Predicate<ConnectionListItem> pred)
        {
            //TODO: FIX!
            MainForm.CheckForIllegalCrossThreadCalls = false;
            foreach (ConnectionListItem itm in Globals.MainForm.connectionListView.Items)
                if (pred(itm))
                    return itm;
            return null;
        }

        public void RemoveItem(ConnectionListItem item)
        {
            Globals.MainForm.connectionListView.FlexibleInvoke(clv => clv.Items.Remove(item));
        }

        public void AddItem(ConnectionListItem item)
        {
            Globals.MainForm.connectionListView.FlexibleInvoke(clv => clv.Items.Add(item));
        }

        public void AddContextMenuItem(ToolStripItem item)
        {
            Globals.MainForm.contextMenuStrip1.FlexibleInvoke(cms => cms.Items.Add(item));
        }

        public List<ConnectionListItem> GetSelectedItems()
        {
            return GetSelectedItemsImpl().ToList();
        }

        private static IEnumerable<ConnectionListItem> GetSelectedItemsImpl()
        {
            return Globals.MainForm.connectionListView.SelectedItems.Cast<ConnectionListItem>();
        }
    }
}
