dir /a /b /-p /s /o:gen *.php > sources_php.txt
 
PATH C:\Program Files\poEdit\bin
PATH C:\Archivos de programa\poEdit\bin

for /f %%a in ('dir /b languages') do call :add_strings "%%a"
 
del sources_php.txt
 
goto :eof
 
:add_strings
xgettext --keyword=__ --language=PHP --package-name=gelatocms --package-version=1.0 --no-location --no-wrap --files-from=sources_php.txt -j --from-code=UTF-8 -d languages/%1/messages

cd languages/%1
msgfmt messages.po
cd ../..