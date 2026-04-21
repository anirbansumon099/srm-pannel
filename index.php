
<?php
require_once(__DIR__."/config.php");

require_once(__DIR__."/functions/classes.php");


function render_auth_view($t, $s, $e = '') {
    $page_title = $t;
    $page_subtitle = $s;
    $error_msg = $e;
    
    $AuthView = __DIR__ . '/views/login.php'; 
    if (file_exists($AuthView)) {
        include $AuthView;
    } else {
        $AuthViewUp = dirname(__DIR__) . '/authView.php';
        if (file_exists($AuthViewUp)) {
            include $AuthViewUp;
        } else {
            die("Error: authView.php খুঁজে পাওয়া যায়নি।");
        }
    }
}

// Auth Setup & Login Logic
if (!file_exists(PASS_FILE)) {
    if (isset($_POST['setup_pass'])) {
        $hash = password_hash($_POST['password'], PASSWORD_DEFAULT);
        file_put_contents(PASS_FILE, "<?php return '$hash'; ?>");
        $_SESSION['auth'] = true;
        header("Location: index.php"); exit;
    }
    render_auth_view("Initial Setup", "OTTKing প্যানেলের জন্য পাসওয়ার্ড সেট করুন"); 
    exit;
}

$stored_hash = include(PASS_FILE);
if (!is_authenticated()) {
    $err = '';
    if (isset($_POST['login'])) {
        
        // আপনার ইনডেক্স ফাইলে যেখানে পাসওয়ার্ড মিলে যায়:
if (password_verify($_POST['password'], $stored_hash)) {
    session_regenerate_id(true); // নতুন সেশন আইডি তৈরি (Security)
    $_SESSION['auth'] = true;
    $_SESSION['user_agent'] = $_SERVER['HTTP_USER_AGENT']; // ব্রাউজার তথ্য সেভ
    $_SESSION['user_ip'] = $_SERVER['REMOTE_ADDR'];       // আইপি সেভ
    header("Location: index.php"); 
    exit;
}
         else { 
            $err = "ভুল পাসওয়ার্ড!"; 
        }
    }
    render_auth_view("Secure Login", "OTTKing cPanel এক্সেস করুন", $err); 
    exit;
}

// --- স্মার্ট সিস্টেম প্রোটেকশন ---
$rel_path = $_GET['path'] ?? '';
$real_root = realpath(ROOT_PATH); 
$current_dir = realpath($real_root . DIRECTORY_SEPARATOR . $rel_path);

if (!$current_dir || strpos($current_dir, $real_root) !== 0) { 
    $current_dir = $real_root; 
    $rel_path = ''; 
}

$panel_real_path = realpath(__DIR__); 

// ৩. ডাইরেক্ট অ্যাক্সেস ব্লক
if (strpos($current_dir, $panel_real_path) === 0) {
    http_response_code(403);
    die("<div style='background:#f1f5f9; color:#e11d48; height:100vh; display:flex; align-items:center; justify-content:center; font-family:sans-serif; text-align:center;'>
            <div style='background:#fff; padding:40px; border-radius:16px; border:1px solid #e2e8f0; box-shadow: 0 4px 20px rgba(0,0,0,0.08);'>
                <h1 style='font-size:80px; margin:0;'>403</h1>
                <p style='color:#64748b; font-size:18px;'>Access Denied: সিস্টেম ফোল্ডারে প্রবেশের অনুমতি নেই।</p>
                <a href='index.php' style='color:#fff; text-decoration:none; background:#e11d48; padding:12px 30px; border-radius:8px; display:inline-block; margin-top:20px; font-weight:bold;'>Go Back Home</a>
            </div>
        </div>");
}

// ৪. ফাইল লিস্ট ফিল্টারিং
$all_files = array_diff(scandir($current_dir), array('.', '..'));
$files = [];
$system_files = ['cp-core-an-functions.php', 'pass.php', 'authView.php', '.git', 'cp-core-an-config.php'];

foreach ($all_files as $f) {
    $target_file_path = realpath($current_dir . DIRECTORY_SEPARATOR . $f);
    if ($target_file_path && strpos($target_file_path, $panel_real_path) === 0) continue;
    if (in_array($f, $system_files)) continue;
    $files[] = $f;
}

require_once(__DIR__."/views/index.php");
?>

