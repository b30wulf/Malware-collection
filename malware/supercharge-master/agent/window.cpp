#include "window.hpp"

std::string current_window()
{
    char window[256];
    memset(window, '\0', 256);
    HWND f = GetForegroundWindow();
    GetWindowText(f, window, 256);
    return std::string(window);
}