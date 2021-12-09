#include "bsysinfo.hpp"

std::string basicinfo(int mode){
    SYSTEM_INFO info;
    GetSystemInfo(&info);
    std::ostringstream rbuf;
    switch (mode)
    {
    case PROCESSORS:
        rbuf.str("");
        rbuf.clear();
        rbuf << info.dwNumberOfProcessors;
        return rbuf.str();
        break;

    case PAGESIZE:
        rbuf.str("");
        rbuf.clear();
        rbuf << info.dwPageSize;
        return rbuf.str();
        break;

    case minAppAddr:
        rbuf.str("");
        rbuf.clear();
        rbuf << info.lpMinimumApplicationAddress;
        return rbuf.str();
        break;

    case maxAppAddr:
        rbuf.str("");
        rbuf.clear();
        rbuf << info.lpMaximumApplicationAddress;
        return rbuf.str();
        break;
        
    default:
        break;
    }
}