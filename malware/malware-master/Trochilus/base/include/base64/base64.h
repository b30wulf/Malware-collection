#pragma once

#include <string>

std::string base64_encode(unsigned char const* , unsigned int len);
std::string base64_decode(std::string const& s);
std::string url_encode(const std::string &s);
std::string url_decode(const std::string &s);