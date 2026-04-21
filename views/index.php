<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SRM Panel - File Manager</title>
    <link href="assets/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="assets/css/style.css"
</head>
<body>

<nav class="navbar d-flex justify-content-between align-items-center">
    <a href="index.php" class="brand"><span>SRM</span> Pannel</a>
    <div class="d-flex align-items-center gap-2">
        <button class="btn btn-outline-light btn-sm" data-bs-toggle="modal" data-bs-target="#passwordModal"><i class="fas fa-user-shield"></i></button>
        <a href="logout.php?signin" class="btn btn-danger btn-sm px-3 fw-bold"><i class="fas fa-power-off"></i></a>
    </div>
</nav>

<!-- ===== TOOLBAR (এখানে রয়েছে, navbar এর ঠিক নিচে) ===== -->
<div class="cp-toolbar px-md-4">
    <button class="tool-btn" data-bs-toggle="modal" data-bs-target="#newFileModal"><i class="fas fa-file-circle-plus text-info"></i>Add File</button>
    <button class="tool-btn" data-bs-toggle="modal" data-bs-target="#newFolderModal"><i class="fas fa-folder-plus text-warning"></i>Add Folder</button>
    <button class="tool-btn" onclick="submitAction('delete')"><i class="fas fa-trash-alt text-danger"></i>Delete</button>
    <button id="editBtn" class="tool-btn" onclick="openEditModal()" style="display: none;"><i class="fas fa-file-code text-primary"></i>Edit</button>
    <button class="tool-btn" onclick="openRenameModal()"><i class="fas fa-edit text-success"></i>Rename</button>
    <button class="tool-btn" onclick="submitAction('copy')"><i class="fas fa-copy"></i>Copy</button>
    <button class="tool-btn" onclick="submitAction('cut')"><i class="fas fa-cut"></i>Move</button>
    <?php if (isset($_SESSION['clipboard'])): ?>
        <button class="tool-btn text-success fw-bold" onclick="submitAction('paste')"><i class="fas fa-paste"></i>Paste</button>
    <?php endif; ?>
    <button class="tool-btn" onclick="submitAction('zip')"><i class="fas fa-file-archive text-secondary"></i>Zip</button>
    <button id="extractBtn" class="tool-btn" onclick="submitAction('unzip')" style="display: none;"><i class="fas fa-box-open text-info"></i>Unzip</button>
    <button id="downloadBtn" class="tool-btn" onclick="submitAction('download')" style="display: none;"><i class="fas fa-download text-primary"></i>Download</button>
    <button class="tool-btn" data-bs-toggle="modal" data-bs-target="#uploadModal"><i class="fas fa-cloud-upload-alt text-primary"></i>Upload</button>
    <button class="tool-btn" onclick="location.reload()"><i class="fas fa-sync-alt"></i>Reload</button>
</div>

<!-- ===== MAIN CONTENT AREA ===== -->
<div class="container-fluid mt-4 px-md-4">
    <?php $disk = get_disk_stats(ROOT_PATH); ?>
    <div class="disk-usage-card shadow-sm">
        <div class="d-flex justify-content-between align-items-center mb-2 small">
            <span class="fw-bold text-dark"><i class="fas fa-hdd me-2 text-primary"></i>Storage Usage</span>
            <span class="text-muted"><?= $disk['used'] ?> / <?= $disk['total'] ?></span>
        </div>
        <div class="progress" style="height: 8px; background: #e2e8f0; border-radius: 10px;">
            <div class="progress-bar <?= ($disk['percent'] > 85) ? 'bg-danger' : 'bg-primary' ?>" style="width: <?= $disk['percent'] ?>%;"></div>
        </div>
    </div>

    <nav aria-label="breadcrumb">
        <ol class="breadcrumb shadow-sm mb-0">
            <li class="breadcrumb-item"><a href="index.php"><i class="fas fa-home me-1"></i>Root</a></li>
            <?php 
            $tmp=''; 
            if(!empty($rel_path)) {
                foreach(explode('/', trim($rel_path, '/')) as $p): 
                    $tmp.=$p.'/'; 
            ?>
                <li class="breadcrumb-item active"><a href="?path=<?=urlencode(rtrim($tmp,'/'))?>"><?=$p?></a></li>
            <?php endforeach; } ?>
        </ol>
    </nav>

    <div class="main-card shadow-sm mt-3">
        <form id="fileForm" method="POST" action="action.php">
            <input type="hidden" name="action" id="formAction">
            <input type="hidden" name="current_path" value="<?=htmlspecialchars($rel_path)?>">
            
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th width="45" class="text-center"><input type="checkbox" id="selectAll" class="form-check-input" onclick="toggleSelectAll(this)"></th>
                            <th>Name</th>
                            <th width="120" class="d-none d-md-table-cell">Size</th>
                            <th width="150" class="text-end">Modified</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($files)): ?>
                            <tr><td colspan="4" class="text-center py-5 text-muted">
                                <i class="fas fa-folder-open fa-3x mb-3 opacity-25"></i>
                                <p class="mb-0 fw-bold">This folder is empty</p>
                            </td></tr>
                        <?php else: ?>
                            <?php foreach ($files as $f): 
                                $full = $current_dir.DIRECTORY_SEPARATOR.$f; 
                                $is_d = is_dir($full);
                            ?>
                            <tr>
                                <td class="text-center"><input type="checkbox" name="items[]" value="<?=$f?>" class="item-check form-check-input" onchange="updateToolbarButtons()"></td>
                                <td>
                                    <?php if($is_d): ?>
                                        <a href="?path=<?=urlencode(($rel_path ? $rel_path.'/' : '').$f)?>" class="file-name-link">
                                            <i class="fas fa-folder folder-icon"></i> 
                                            <span><?=$f?></span>
                                        </a>
                                    <?php else: ?>
                                        <div class="file-name-link">
                                            <i class="fas <?=get_file_icon($f)?> file-icon"></i> 
                                            <span><?=$f?></span>
                                        </div>
                                    <?php endif; ?>
                                </td>
                                <td class="d-none d-md-table-cell text-muted small"><?= $is_d ? '--' : format_size(filesize($full)) ?></td>
                                <td class="text-end text-muted small"><?= date("d M, H:i", filemtime($full)) ?></td>
                            </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </form>
    </div>
</div>

<footer>
    <div class="container-fluid px-md-4 text-center">
        <p class="mb-0 small text-muted">&copy; <?= date('Y') ?> <span class="text-dark fw-bold">SRM Panel</span>. Developed by <span class="text-primary fw-semibold">OTTKing Team</span>. All Rights Reserved.</p>
    </div>
</footer>

<?php
$Models = __DIR__.'/widget/modal.php'; 
include($Models);
?>

<script src="assets/js/bootstrap.bundle.min.js"></script>
<script>


let actionToPerform = ''; 
let deleteModal;

document.addEventListener('DOMContentLoaded', function() {
    const confirmModalEl = document.getElementById('confirmModal');
    if (confirmModalEl) {
        deleteModal = new bootstrap.Modal(confirmModalEl);
    }

    
    document.getElementById('file-input')?.addEventListener('change', function() {
        let list = document.getElementById('file-list'); 
        if(list) {
            list.innerHTML = '<div class="alert alert-info py-2">Selected: ' + this.files.length + ' files</div>';
        }
    });

    document.getElementById('confirmYes')?.addEventListener('click', function() {
        if (deleteModal) deleteModal.hide();
        executeSubmit(actionToPerform);
    });
});


function updateToolbarButtons() {
    const selectedFiles = document.querySelectorAll('.item-check:checked');
    const extractBtn = document.getElementById('extractBtn');
    const editBtn = document.getElementById('editBtn');
    const downloadBtn = document.getElementById('downloadBtn');
    
    let hasZip = false;
    let editCount = 0;
    let isFolderSelected = false;
    
    const editableExtensions = ['.php', '.html', '.css', '.js', '.json', '.txt', '.xml', '.htaccess', '.py','.sys','.lic','.conf','.m3u','.m3u8','.xhtml','.log','.env'];

    selectedFiles.forEach(function(checkbox) {
        const fileName = checkbox.value.toLowerCase();
        const row = checkbox.closest('tr');
        
        // ফোল্ডার চেক (যদি ফোল্ডার আইকন থাকে)
        const isFolder = row.querySelector('.fa-folder') !== null;
        if (isFolder) isFolderSelected = true;

        // জিপ ফাইল চেক
        if (fileName.endsWith('.zip')) hasZip = true;
        
        // এডিটযোগ্য ফাইল চেক
        const ext = fileName.substring(fileName.lastIndexOf('.'));
        if (editableExtensions.includes(ext)) {
            editCount++;
        }
    });

    // বাটন দেখানো বা লুকানোর লজিক
    if (extractBtn) extractBtn.style.display = hasZip ? 'flex' : 'none';
    
    if (editBtn) {
        // ১টি ফাইল সিলেক্ট হলে এবং সেটি ফোল্ডার না হলে এডিট বাটন আসবে
        editBtn.style.display = (selectedFiles.length === 1 && editCount === 1 && !isFolderSelected) ? 'flex' : 'none';
    }

    if (downloadBtn) {
        // ১টি মাত্র সিলেকশন এবং সেটি ফোল্ডার না হলে ডাউনলোড আসবে
        downloadBtn.style.display = (selectedFiles.length === 1 && !isFolderSelected) ? 'flex' : 'none';
    }
}

// ৩. অ্যাকশন হ্যান্ডলার (Delete, Copy, Zip etc)
function submitAction(act) {
    const checkedCount = document.querySelectorAll('.item-check:checked').length;
    
    // সিলেকশন ভ্যালিডেশন
    if(act !== 'reload' && act !== 'paste' && checkedCount === 0) { 
        showModalError('Please select at least one item to ' + act + '.'); 
        return; 
    }

    // যদি ডিলিট অ্যাকশন হয়, কাস্টম মডেল দেখাবে
    if(act === 'delete') {
        actionToPerform = 'delete';
        const msg = document.getElementById('confirmMessage');
        if(msg) msg.innerText = "Are you sure you want to delete " + checkedCount + " selected item(s)?";
        
        if (deleteModal) {
            deleteModal.show();
        } else {
            // সেফটি ফলব্যাক: যদি মডেল লোড না হয় তবে ডিফল্ট কনফার্ম
            if(confirm('Are you sure you want to delete?')) executeSubmit('delete');
        }
        return;
    }

    // অন্য সব অ্যাকশনের জন্য সরাসরি সাবমিট
    executeSubmit(act);
}

// ৪. ফর্ম সাবমিট ফাংশন
function executeSubmit(act) {
    const actionField = document.getElementById('formAction');
    const form = document.getElementById('fileForm');
    if(actionField && form) {
        actionField.value = act; 
        form.submit();
    }
}

// ৫. রিনেম মডেল ওপেন করা
function openRenameModal() {
    const selected = document.querySelectorAll('.item-check:checked');
    if (selected.length !== 1) { 
        showModalError('Select exactly one item to rename.'); 
        return; 
    }
    document.getElementById('old_name_input').value = selected[0].value;
    document.getElementById('new_name_input').value = selected[0].value;
    new bootstrap.Modal(document.getElementById('renameModal')).show();
}

// ৬. এডিট মডেল ওপেন এবং কন্টেন্ট ফেচ করা
async function openEditModal() {
    const selected = document.querySelectorAll('.item-check:checked');
    if (selected.length !== 1) return;
    
    const fileName = selected[0].value;
    const path = "<?=urlencode($rel_path)?>";

    document.getElementById('editFileNameDisplay').innerText = fileName;
    document.getElementById('editFileNameInput').value = fileName;
    document.getElementById('editArea').value = "Loading contents from server...";

    const editModal = new bootstrap.Modal(document.getElementById('editModal'));
    editModal.show();

    try {
        // আপনার ব্যাকএন্ড ফাইলটির নাম cp-core-an-functions.php হলে সেটি ব্যবহার করুন
        const response = await fetch(`read.php?action=get_content&file=${encodeURIComponent(fileName)}&path=${path}`);
        if (!response.ok) throw new Error('Load failed');
        const data = await response.text();
        document.getElementById('editArea').value = data;
    } catch (err) {
        document.getElementById('editArea').value = "Error: Could not load the file. Check permissions.";
    }
}

// ৭. সিলেক্ট অল এবং এরর মেসেজ ফাংশন
function toggleSelectAll(source) {
    document.querySelectorAll('.item-check').forEach(c => c.checked = source.checked);
    updateToolbarButtons();
}

function showModalError(msg) {
    const errText = document.getElementById('errorMessage');
    if(errText) {
        errText.innerText = msg;
        new bootstrap.Modal(document.getElementById('errorModal')).show();
    } else {
        alert(msg);
    }
}
  
</script>
</body>
</html>