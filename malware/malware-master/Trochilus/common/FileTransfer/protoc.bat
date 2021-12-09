%~dp0protoc -I=%~dp0 --cpp_out=%~dp0 %~dp0FileTransfer.proto
move /Y %~dp0FileTransfer.pb.h %1
move /Y %~dp0FileTransfer.pb.cc %1