set anio=%date:~6,4%
set mes=%date:~3,2%
set dia=%date:~0,2%
set disp=5
cd C:\Users\Libramiento Sur\Desktop\NO BORRAR
rename %anio%%mes%%dia%.txt %anio%%mes%%dia%_%disp%.txt
@echo off
echo user matrix2017> ftpcmd.dat
echo M@tr1x2017>> ftpcmd.dat
echo bin>> ftpcmd.dat
echo cd nomina>> ftpcmd.dat
echo put %anio%%mes%%dia%_%disp%.txt>> ftpcmd.dat
echo quit>> ftpcmd.dat
ftp -n -s:ftpcmd.dat servermatrixxxb.ddns.net
del ftpcmd.dat