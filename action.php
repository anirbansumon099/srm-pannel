<?php
require_once(__DIR__."/config.php");
setCSRF_token();
if ($_SERVER['REQUEST_METHOD'] === 'POST' && is_authenticated()) {
    
    $action = $_POST['action'] ?? '';
    $path = $_POST['current_path'] ?? '';
    $items = $_POST['items'] ?? [];
    
    $target_dir = realpath(ROOT_PATH . DS . $path);

    if ($target_dir && strpos($target_dir, ROOT_PATH) === 0) {
        
        $filtered_items = array_filter($items, function($item) {
            return $item !== APP_DIR_NAME;
        });

        switch ($action) {
            
            case 'save_file':
                $file_name = $_POST['file_name'] ?? '';
                $content = $_POST['content'] ?? '';
                $file_full_path = $target_dir . DS . $file_name;

                if (file_exists($file_full_path) && strpos(realpath($file_full_path), ROOT_PATH) === 0) {
                    file_put_contents($file_full_path, $content);
                }
                break;

            case 'new_folder':
                $new_folder_name = trim($_POST['name'] ?? '');
                if (!empty($new_folder_name) && $new_folder_name !== APP_DIR_NAME) {
                    $new_path = $target_dir . DS . $new_folder_name;
                    if (!file_exists($new_path)) mkdir($new_path, 0755, true);
                }
                break;
                
                case 'new_file':
    $new_file_name = trim($_POST['name'] ?? '');
    // সিকিউরিটি চেক: নাম খালি কিনা এবং সিস্টেম ফাইলের সাথে মিলছে কিনা
    if (!empty($new_file_name) && $new_file_name !== APP_DIR_NAME) {
        $file_path = $target_dir . DS . $new_file_name;
        
        // ফাইলটি আগে থেকে না থাকলে খালি ফাইল তৈরি করবে
        if (!file_exists($file_path)) {
            file_put_contents($file_path, ""); // একটি খালি ফাইল তৈরি হবে
            chmod($file_path, 0644); // সঠিক পারমিশন সেট করা
        }
    }
    break;
                
            case 'copy':
            case 'cut':
                if (!empty($filtered_items)) {
                    $_SESSION['clipboard'] = [
                        'mode' => $action,
                        'source_dir' => $target_dir,
                        'items' => $filtered_items
                    ];
                }
                break;

       case 'paste':
             if (isset($_SESSION['clipboard'])) {
        $clip = $_SESSION['clipboard'];
        foreach ($clip['items'] as $item) {
            $source = $clip['source_dir'] . DS . $item;
            $dest = $target_dir . DS . $item;
            
            if (file_exists($source)) {
                if ($clip['mode'] === 'copy') {
                    // ফোল্ডারসহ কপি করার জন্য
                    is_dir($source) ? 
                        shell_exec("cp -r " . escapeshellarg($source) . " " . escapeshellarg($dest)) : 
                        copy($source, $dest);
                } else {
                    // কাট করার জন্য (মুভ)
                    rename($source, $dest);
                }
            }
        }
        // কাজ শেষ, এবার সেশন ক্লিয়ার করে দিন যাতে দ্বিতীয়বার পেস্ট না হয়
        unset($_SESSION['clipboard']);
    }
    break;
            case 'delete':
                foreach ($filtered_items as $item) {
                    $target = $target_dir . DS . $item;
                    is_dir($target) ? shell_exec("rm -rf " . escapeshellarg($target)) : unlink($target);
                }
                break;
                
            case 'upload':
                if (!empty($_FILES['files']['name'][0])) {
                    foreach ($_FILES['files']['tmp_name'] as $key => $tmp_name) {
                        $file_name = $_FILES['files']['name'][$key];
                        $dest = $target_dir . DS . $file_name;
                        move_uploaded_file($tmp_name, $dest);
                    }
                }
                break;



case 'zip':
    if (!empty($filtered_items) && class_exists('ZipArchive')) {
        $zip = new ZipArchive();
        $zip_name = 'backup_' . date('Ymd_His') . '.zip';
        $zip_full_path = $target_dir . DS . $zip_name;

        if ($zip->open($zip_full_path, ZipArchive::CREATE) === TRUE) {
            foreach ($filtered_items as $item) {
                $item_path = $target_dir . DS . $item;

                if (is_file($item_path)) {
                    // যদি ফাইল হয়, সরাসরি অ্যাড হবে
                    $zip->addFile($item_path, $item);
                } elseif (is_dir($item_path)) {
                    // যদি ফোল্ডার হয়, ভেতরের সব ফাইলসহ অ্যাড করার জন্য RecursiveIterator ব্যবহার
                    $files = new RecursiveIteratorIterator(
                        new RecursiveDirectoryIterator($item_path, RecursiveDirectoryIterator::SKIP_DOTS),
                        RecursiveIteratorIterator::SELF_FIRST
                    );

                    foreach ($files as $file) {
                        $file_path = $file->getRealPath();
                        // জিপের ভেতরে ফাইলের রিলেটিভ পাথ তৈরি করা
                        $relative_path = $item . DIRECTORY_SEPARATOR . substr($file_path, strlen($item_path) + 1);

                        if ($file->isDir()) {
                            $zip->addEmptyDir($relative_path);
                        } else {
                            $zip->addFile($file_path, $relative_path);
                        }
                    }
                }
            }
            $zip->close();
        }
    }
    break;




            case 'unzip':
                if (!empty($filtered_items)) {
                    foreach ($filtered_items as $item) {
                        $file_path = $target_dir . DS . $item;
                        if (is_file($file_path) && pathinfo($file_path, PATHINFO_EXTENSION) === 'zip') {
                            $zip = new ZipArchive;
                            if ($zip->open($file_path) === TRUE) {
                                $zip->extractTo($target_dir);
                                $zip->close();
                            }
                        }
                    }
                }
                break;


case 'download':
    $items = $_POST['items'] ?? [];
    $file_to_download = $items[0] ?? ''; // প্রথম আইটেমটি নিচ্ছি
    $file_path = $target_dir . DS . $file_to_download;

    // ১. ফাইলটি খালি কিনা চেক করা
    // ২. ফাইলটি হার্ডড্রাইভে আছে কিনা চেক করা
    // ৩. এবং সবচেয়ে গুরুত্বপূর্ণ: এটি একটি ফাইল কি না (is_file) চেক করা
    if (!empty($file_to_download) && file_exists($file_path) && is_file($file_path)) {
        
        // ডিরেক্টরি ট্রাভার্সাল প্রোটেকশন
        if (strpos(realpath($file_path), ROOT_PATH) !== 0) {
            die("Access denied!");
        }

        // আউটপুট বাফার ক্লিন করা (যাতে ফাইল করাপ্ট না হয়)
        while (ob_get_level()) ob_end_clean();

        // ========== ✅ নতুন কোড: MIME Type ডায়নামিকভাবে সেট করা ==========
        $file_extension = strtolower(pathinfo($file_path, PATHINFO_EXTENSION));
        
        // File type অনুযায়ী proper MIME type
        $mime_types = [
            'txt'   => 'text/plain; charset=UTF-8',
            'php'   => 'text/plain; charset=UTF-8',  // PHP কোড দেখানোর জন্য text/plain
            'html'  => 'text/html; charset=UTF-8',
            'htm'   => 'text/html; charset=UTF-8',
            'csv'   => 'text/csv; charset=UTF-8',
            'json'  => 'application/json; charset=UTF-8',
            'xml'   => 'text/xml; charset=UTF-8',
            'pdf'   => 'application/pdf',
            'zip'   => 'application/zip',
            'jpg'   => 'image/jpeg',
            'jpeg'  => 'image/jpeg',
            'png'   => 'image/png',
            'gif'   => 'image/gif',
            'doc'   => 'application/msword',
            'docx'  => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'xls'   => 'application/vnd.ms-excel',
            'xlsx'  => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'mp3'   => 'audio/mpeg',
            'mp4'   => 'video/mp4',
            'avi'   => 'video/x-msvideo',
        ];
        
        $mime = $mime_types[$file_extension] ?? 'application/octet-stream';
        // ========================================================

        // হেডার কনফিগারেশন
        header('Content-Description: File Transfer');
        header('Content-Type: ' . $mime);
        
        // ✅ Filename এ charset যোগ করা (বিশেষ ক্যারেক্টার/বাংলা নামের জন্য)
        $basename = basename($file_path);
        header('Content-Disposition: attachment; filename="' . $basename . '"; filename*=UTF-8\'\''.rawurlencode($basename));
        
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Pragma: public');
        header('Content-Length: ' . filesize($file_path));

        // ফাইলটি রিড করা
        readfile($file_path);
        exit;
    } else {
        // যদি এটি ফোল্ডার হয় বা ফাইল না পাওয়া যায়
        $_SESSION['error'] = "You can only download files. To download a folder, please ZIP it first.";
        header("Location: index.php?path=" . urlencode($path));
        exit;
    }
    break;



            case 'rename':
                $old_name = $_POST['old_name'] ?? '';
                $new_name = $_POST['new_name'] ?? '';
                if (!empty($old_name) && !empty($new_name) && $old_name !== APP_DIR_NAME) {
                    $old_path = $target_dir . DS . $old_name;
                    $new_path = $target_dir . DS . $new_name;
                    if (file_exists($old_path) && !file_exists($new_path)) rename($old_path, $new_path);
                }
                break;
                
            case 'change_password':
                $current_pass = $_POST['current_password'] ?? '';
                $new_pass = $_POST['new_password'] ?? '';
                $confirm_pass = $_POST['confirm_password'] ?? '';
                $stored_hash = include(PASS_FILE);
                if (password_verify($current_pass, $stored_hash)) {
                    if ($new_pass === $confirm_pass && !empty($new_pass)) {
                        $new_hash = password_hash($new_pass, PASSWORD_DEFAULT);
                        file_put_contents(PASS_FILE, "<?php return '$new_hash'; ?>");
                        $_SESSION['password_changed'] = true;
                    }
                }
                break;
        }
    }
    

  // সব কাজ শেষ হওয়ার পর রিডাইরেক্ট লজিক
$current_path = $_POST['current_path'] ?? '';
header("Location: index.php?path=" . urlencode($current_path));
    exit;
} else{
  $current_path = $_GET['current_path'] ?? '';
header("Location: index.php?path=" . urlencode($current_path));
exit;
}