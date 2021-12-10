package me.jershdervis.monitorj.ui;

import javax.swing.table.DefaultTableModel;

/**
 * Created by Josh on 27/06/2015.
 */
public class ClientTableModel extends DefaultTableModel {

    private final Object[] columns = {
            "Country", "HWID", "PC Name", "User Name", "OS", "IP Address", "Port", "Ping"
    };

    private final Class[] columnClass = new Class [] {
            javax.swing.JLabel.class, java.lang.String.class, java.lang.String.class, java.lang.String.class, java.lang.String.class, java.lang.String.class, java.lang.String.class, java.lang.String.class
    };

    private final boolean[] canEdit = new boolean [] {
            false, false, false, false, false, false, false, false
    };

    public ClientTableModel() {
        for(Object column : columns)
            this.addColumn(column);
    }

    @Override
    public Class getColumnClass(int columnIndex) {
        return columnClass [columnIndex];
    }

    @Override
    public boolean isCellEditable(int rowIndex, int columnIndex) {
        return canEdit [columnIndex];
    }
}
