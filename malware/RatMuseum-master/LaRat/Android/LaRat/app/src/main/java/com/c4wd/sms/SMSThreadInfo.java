package com.c4wd.sms;

/**
 * Created by cory on 10/15/15.
 */
public class SMSThreadInfo {

    private String number;
    private String contact_id;
    private long threadId;

    public String getNumber() {
        return number;
    }

    public void setNumber(String number) {
        this.number = number;
    }

    public long getThreadId() {
        return threadId;
    }

    public void setThreadId(long threadId) {
        this.threadId = threadId;
    }

    public String getContactId() {
        return contact_id;
    }

    public void setContactId(String contact_id) {
        this.contact_id = contact_id;
    }
}
