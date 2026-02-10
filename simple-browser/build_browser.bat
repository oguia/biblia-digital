@echo off
echo ==========================================
echo      Construindo seu Navegador Seguro
echo ==========================================
echo.
echo 1. Instalando dependencias...
call npm install
if %errorlevel% neq 0 (
    echo Erro ao instalar dependencias. Verifique se o Node.js esta instalado.
    pause
    exit /b
)

echo.
echo 2. Compilando o codigo...
call npm run build
if %errorlevel% neq 0 (
    echo Erro ao compilar.
    pause
    exit /b
)

echo.
echo ==========================================
echo      SUCESSO!
echo ==========================================
echo.
echo O instalador foi criado na pasta "release".
echo Pode fechar esta janela.
pause
