<#
.SYNOPSIS
    Mendaftarkan Windows Task Scheduler agar Laravel scheduler (php artisan schedule:run)
    berjalan otomatis setiap menit untuk CMS Romadan.

.DESCRIPTION
    Tanpa task ini, seluruh fitur terjadwal di app/Console/Kernel.php TIDAK AKAN PERNAH
    berjalan otomatis, walaupun sudah terdaftar di kode:
      - backup:run              (backup otomatis harian jam 01:00)
      - backup:cleanup          (hapus backup lebih dari 1 minggu, jam 02:00)
      - media:sync              (sinkronisasi Media Library, tiap jam)
      - publikasi:publish-scheduled (auto-publish publikasi terjadwal, tiap menit)

    Laravel scheduler bekerja dengan prinsip: sesuatu di level OS harus memanggil
    `php artisan schedule:run` setiap menit, lalu Laravel sendiri yang menentukan
    job mana yang sudah waktunya jalan. Skrip ini mendaftarkan panggilan tersebut
    sebagai Windows Scheduled Task.

.NOTES
    WAJIB dijalankan sebagai Administrator (klik kanan PowerShell -> Run as Administrator).
    Jalankan dari folder project ini, atau skrip akan otomatis memakai lokasinya sendiri.

.EXAMPLE
    cd "C:\path\ke\project\CMS-Romadan"
    .\setup-task-scheduler.ps1
#>

$ErrorActionPreference = 'Stop'

$taskName = 'CMS Romadan - Laravel Scheduler'
$projectPath = $PSScriptRoot

if (-not (Test-Path (Join-Path $projectPath 'artisan'))) {
    Write-Error "File 'artisan' tidak ditemukan di $projectPath. Jalankan skrip ini dari root folder project Laravel."
    exit 1
}

$phpCommand = Get-Command php -ErrorAction SilentlyContinue
if (-not $phpCommand) {
    Write-Error "PHP tidak ditemukan di PATH. Edit skrip ini dan set `$phpPath secara manual, mis. 'C:\Program Files\php8540\php.exe'."
    exit 1
}
$phpPath = $phpCommand.Source

$existing = Get-ScheduledTask -TaskName $taskName -ErrorAction SilentlyContinue
if ($existing) {
    Write-Host "Task '$taskName' sudah ada. Menghapus dan mendaftarkan ulang..." -ForegroundColor Yellow
    Unregister-ScheduledTask -TaskName $taskName -Confirm:$false
}

$action = New-ScheduledTaskAction -Execute $phpPath -Argument 'artisan schedule:run' -WorkingDirectory $projectPath
$trigger = New-ScheduledTaskTrigger -Once -At (Get-Date) -RepetitionInterval (New-TimeSpan -Minutes 1) -RepetitionDuration (New-TimeSpan -Days 3650)
$settings = New-ScheduledTaskSettingsSet -MultipleInstances IgnoreNew -StartWhenAvailable -DontStopOnIdleEnd -ExecutionTimeLimit (New-TimeSpan -Minutes 5)

Register-ScheduledTask -TaskName $taskName -Action $action -Trigger $trigger -Settings $settings `
    -Description 'Menjalankan `php artisan schedule:run` setiap menit untuk CMS Romadan (backup otomatis, auto-publish, sinkronisasi media, dll).' `
    -Force | Out-Null

Write-Host "`nTask '$taskName' berhasil didaftarkan." -ForegroundColor Green
Write-Host "PHP   : $phpPath"
Write-Host "Project: $projectPath"
Write-Host "`nVerifikasi kapan saja dengan:"
Write-Host "  Get-ScheduledTask -TaskName '$taskName' | Get-ScheduledTaskInfo"
Write-Host "`nUntuk menghapus task ini nanti:"
Write-Host "  Unregister-ScheduledTask -TaskName '$taskName' -Confirm:`$false"
