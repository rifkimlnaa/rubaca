<?php
use Illuminate\Support\Facades\Artisan;
Artisan::command('library:about', function () { $this->info('Perpustakaan Modern — Laravel 12'); })->purpose('Tampilkan identitas aplikasi');