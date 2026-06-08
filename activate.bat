@echo off
cd /d "%~dp0"

wt ^
new-tab --title "Laravel" cmd /k "cd /d %~dp0 && php artisan serve --host=0.0.0.0 --port=8000" ^
; new-tab --title "Queue" cmd /k "cd /d %~dp0 && php artisan queue:work" ^
; new-tab --title "Reverb" cmd /k "cd /d %~dp0 && php artisan reverb:start" ^
; new-tab --title "Livekit" cmd /k "cd /d %~dp0 && .\livekit-server.exe --config docker\livekit.yaml" ^
; new-tab --title "Whisper" cmd /k "cd /d %~dp0 && whisper_server\venv\Scripts\python.exe whisper_server\main.py"