<?php

   function get_file_icon($filename) {
    $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
    
    $icons = [
        
        
        'htaccess'=>'fa-solid fa-helicopter-symbol text-primary',

        // Archive
        'zip'  => 'fa-file-zipper text-warning',
        'rar'  => 'fa-file-zipper text-warning',
        '7z'   => 'fa-file-zipper text-warning',

        // Video
        'mp4'  => 'fa-file-video text-primary',
        'mkv'  => 'fa-file-video text-primary',
        'mov'  => 'fa-file-video text-primary',
        'ts'  => 'fa-file-video text-primary',


        // Audio
        'mp3'  => 'fa-file-audio text-info',
        'wav'  => 'fa-file-audio text-info',

        // Image
        'jpg'  => 'fa-file-image text-success',
        'jpeg' => 'fa-file-image text-success',
        'png'  => 'fa-file-image text-success',
        'gif'  => 'fa-file-image text-success',
                'ico'  => 'fa-file-image text-success',

        // Code
        'php'  => 'fa-brands fa-php text-success',
        'java'  => 'fa-brands fa-java text-danger',
        'html' => 'fa-brands fa-html5 text-danger',
        'css'  => 'fab fa-css3 text-info',
        'js'   => 'fa-brands fa-js text-warning',
        'json' => 'fa-file-lines text-success',
        
        'py'=>'fa-brands fa-python text-success',
        
        'env'=>'fa fa-cog text-primary',

        // Text
        'txt'  => 'fa-file-lines text-success',

        // PDF
        'pdf'  => 'fa-file-pdf text-danger',

        // Word
        'doc'  => 'fa-file-word text-primary',
        'docx' => 'fa-file-word text-primary',

        // Excel
        'xls'  => 'fa-file-excel text-success',
        'xlsx' => 'fa-file-excel text-success',
        'csv'  => 'fa-file-excel text-success',

        // PowerPoint
        'ppt'  => 'fa-file-powerpoint text-danger',
        'pptx' => 'fa-file-powerpoint text-danger',

        // Android
        'apk'  => 'fa-brands fa-android text-success',
        'apks'  => 'fa-brands fa-android text-success',


        // Windows
        'exe'  => 'fa fa-windows text-primary',
        'msi'  => 'fa fa-windows text-primary',

        // Mac
        'dmg'  => 'fa fa-apple text-dark',
        'pkg'  => 'fa fa-apple text-dark',

        // Linux
        'deb'  => 'fa fa-linux text-warning',
        'rpm'  => 'fa fa-linux text-warning',
        'sh'   => 'fa fa-linux text-warning',
        
        'db'=>'fa fa-database text-danger'

        
        
    ];


    $default_icon = 'fa-file-circle-question text-success';

    return $icons[$ext] ?? $default_icon;
}
 function format_size($bytes) {
    if ($bytes <= 0) return '0 B';
    $units = array('B', 'KB', 'MB', 'GB', 'TB');
    $i = floor(log($bytes, 1024));
    return round($bytes / pow(1024, $i), 2) . ' ' . $units[$i];
}

function get_disk_stats($root_path) 
{
    $path =$root_path;
    $total = @disk_total_space($path) ?: 0;
    $free = @disk_free_space($path) ?: 0;
    $used = $total - $free;
    $percent = ($total > 0) ? round(($used / $total) * 100) : 0;

    return [
        'total'    => format_size($total),
        'used'     => format_size($used),
        'free'     => format_size($free),
        'percent'  => $percent
    ];
}


