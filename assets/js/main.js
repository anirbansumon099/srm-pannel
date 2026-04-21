

let actionToPerform = ''; 
let deleteModal;

document.addEventListener('DOMContentLoaded', function() {
    // ডিলিট কনফার্মেশন মডেল ইনিশিয়ালাইজ
    const confirmModalEl = document.getElementById('confirmModal');
    if (confirmModalEl) {
        deleteModal = new bootstrap.Modal(confirmModalEl);
    }

    // আপলোড ফাইল সিলেক্ট করলে লিস্ট দেখানো
    document.getElementById('file-input')?.addEventListener('change', function() {
        let list = document.getElementById('file-list'); 
        if(list) {
            list.innerHTML = '<div class="alert alert-info py-2">Selected: ' + this.files.length + ' files</div>';
        }
    });

    // মডেলের 'Yes' বাটনে ক্লিক করলে ফর্ম সাবমিট হবে
    document.getElementById('confirmYes')?.addEventListener('click', function() {
        if (deleteModal) deleteModal.hide();
        executeSubmit(actionToPerform);
    });
});

// ২. টুলবার বাটন কন্ট্রোল (ডাউনলোড, এডিট, এক্সট্রাক্ট লজিক)
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
        const response = await fetch(`cp-core-an-functions.php?action=get_content&file=${encodeURIComponent(fileName)}&path=${path}`);
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
