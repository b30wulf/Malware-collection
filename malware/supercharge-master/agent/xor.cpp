#include "xor.hpp"

std::string XOR(std::string data) {
    // Encryption KEY BELOW
    char key[] = {'M','Y','K','E','Y'};
    // DONT FORGET TO SET !
    std::string output = data;
    
    for (int i = 0; i < data.size(); i++){
        output[i] = data[i] ^ key[i % (sizeof(key) / sizeof(char))];
    }
    return output;
}
