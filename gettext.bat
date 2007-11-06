dir /a /b /-p /o:gen *.php > sources.txt
cd admin
dir /a /b /-p /o:gen *.php > sources_admin.txt
cd ..
 
PATH C:\Archivos de programa\poEdit\bin
 
for /f %%a in ('dir /b languages') do call :add_strings "%%a"
 
cd admin
del sources_admin.txt
cd ..
del sources.txt
 
goto :eof
 
:add_strings
xgettext --keyword=__ --language=PHP --files-from=sources.txt -j --from-code=UTF-8 -d languages/%1/messages
cd admin
xgettext --keyword=__ --language=PHP --files-from=sources_admin.txt -j --from-code=UTF-8 -d ../languages/%1/messages
cd ..
cd languages/%1
msgfmt messages.po
cd ../..