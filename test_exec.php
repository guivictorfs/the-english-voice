<?php
// Teste se o binário pdftotext.exe está executável
$bin = 'D:/FATEC/English Voice/the-english-voice/public/poppler-24.08.0/Library/bin/pdftotext.exe';
echo file_exists($bin) ? "Arquivo existe\n" : "Arquivo NÃO existe\n";
echo is_executable($bin) ? "Executável\n" : "NÃO executável\n";
