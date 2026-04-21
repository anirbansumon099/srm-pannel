<div class="modal fade" id="uploadModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <form method="POST" action="action.php" enctype="multipart/form-data" class="modal-content bg-dark text-light border-secondary shadow-lg">
            <div class="modal-header border-secondary">
                <h6 class="modal-title"><i class="fas fa-cloud-upload-alt me-2 text-primary"></i>Upload to Server</h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4 text-center">
                <input type="hidden" name="action" value="upload">
                <input type="hidden" name="current_path" value="<?=htmlspecialchars($rel_path ?? '')?>">
                <div class="p-4 border border-secondary rounded-3" id="drop-zone" style="border-style: dashed !important; background: rgba(255,255,255,0.03);">
                    <i class="fas fa-cloud-upload-alt fa-3x text-secondary mb-3"></i>
                    <p class="small mb-3 text-secondary">Drag & Drop or Select Files</p>
                    <input type="file" name="files[]" id="file-input" class="d-none" multiple required>
                    <button type="button" class="btn btn-sm btn-outline-primary px-4" onclick="document.getElementById('file-input').click()">Browse Files</button>
                </div>
                <div id="file-list" class="mt-3 small text-start text-info"></div>
            </div>
            <div class="modal-footer border-secondary">
                <button type="submit" class="btn btn-primary w-100 fw-bold shadow">Upload Now</button>
            </div>
        </form>
    </div>
</div>

<div class="modal fade" id="newFolderModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <form method="POST" action="action.php" class="modal-content bg-dark text-light border-secondary shadow-lg">
            <div class="modal-header border-secondary">
                <h6 class="modal-title"><i class="fas fa-folder-plus me-2 text-warning"></i>Create New Folder</h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <input type="hidden" name="action" value="new_folder">
                <input type="hidden" name="current_path" value="<?=htmlspecialchars($rel_path ?? '')?>">
                <label class="small text-secondary mb-2">Folder Name</label>
                <input type="text" name="name" class="form-control bg-secondary bg-opacity-10 text-white border-secondary shadow-none" placeholder="Enter Your Folder Name" required>
            </div>
            <div class="modal-footer border-secondary">
                <button type="submit" class="btn btn-warning w-100 fw-bold text-dark shadow">Create Folder</button>
            </div>
        </form>
    </div>
</div>

<div class="modal fade" id="newFileModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <form action="action.php" method="POST" class="modal-content bg-dark text-light border-secondary shadow-lg">
            <div class="modal-header border-secondary">
                <h5 class="modal-title"><i class="fas fa-file-alt me-2 text-info"></i>Create New File</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <input type="hidden" name="action" value="new_file">
                <input type="hidden" name="current_path" value="<?=htmlspecialchars($rel_path ?? '')?>">
                <label class="small text-secondary mb-2">File Name (e.g. index.php)</label>
                <input type="text" name="name" class="form-control bg-secondary bg-opacity-10 text-white border-secondary shadow-none" placeholder="Enter file name..." required>
            </div>
            <div class="modal-footer border-secondary">
                <button type="submit" class="btn btn-info w-100 fw-bold text-dark shadow">Create File</button>
            </div>
        </form>
    </div>
</div>

<div class="modal fade" id="renameModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <form method="POST" action="action.php" class="modal-content bg-dark text-light border-secondary shadow-lg">
            <div class="modal-header border-secondary">
                <h6 class="modal-title"><i class="fas fa-edit me-2 text-success"></i>Rename Item</h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <input type="hidden" name="action" value="rename">
                <input type="hidden" name="current_path" value="<?=htmlspecialchars($rel_path ?? '')?>">
                <input type="hidden" name="old_name" id="old_name_input">
                <label class="small text-secondary mb-2">New Name</label>
                <input type="text" name="new_name" id="new_name_input" class="form-control bg-secondary bg-opacity-10 text-white border-secondary shadow-none" required>
            </div>
            <div class="modal-footer border-secondary">
                <button type="submit" class="btn btn-success w-100 fw-bold text-white shadow">Apply Change</button>
            </div>
        </form>
    </div>
</div>

<div class="modal fade" id="editModal" tabindex="-1" data-bs-backdrop="static">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content bg-dark text-light border-secondary shadow-lg">
            <div class="modal-header border-secondary">
                <h5 class="modal-title text-success"><i class="fas fa-code me-2"></i>Editing: <span id="editFileNameDisplay" class="text-light"></span></h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="cp-core-an-functions.php" method="POST">
                <div class="modal-body p-0">
                    <input type="hidden" name="action" value="save_file">
                    <input type="hidden" name="current_path" value="<?=htmlspecialchars($rel_path ?? '')?>">
                    <input type="hidden" name="file_name" id="editFileNameInput">
                    <textarea name="content" id="editArea" class="form-control bg-dark text-light border-0 p-3" 
                        style="height: 550px; font-family: 'Consolas', monospace; font-size: 14px; outline: none; box-shadow: none; resize: none;" spellcheck="false"></textarea>
                </div>
                <div class="modal-footer border-secondary">
                    <button type="button" class="btn btn-outline-secondary px-4" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success px-5 fw-bold shadow">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="confirmModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content bg-dark text-light border-secondary shadow-lg">
            <div class="modal-body text-center p-5">
                <div class="mb-4">
                    <i class="fas fa-exclamation-triangle text-danger" style="font-size: 60px;"></i>
                </div>
                <h4 class="fw-bold">Are you sure?</h4>
                <p id="confirmMessage" class="text-secondary mb-4 small">Do you really want to delete these items? This process cannot be undone.</p>
                <div class="d-flex justify-content-center gap-2">
                    <button type="button" id="confirmYes" class="btn btn-danger px-4 fw-bold shadow">Delete Now</button>
                    <button type="button" class="btn btn-outline-secondary px-4" data-bs-dismiss="modal">Cancel</button>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="errorModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content bg-dark text-light border-secondary shadow-lg">
            <div class="modal-body p-5 text-center">
                <div class="mb-4">
                    <i class="fas fa-exclamation-circle fa-4x text-warning"></i>
                </div>
                <h5 class="fw-bold">Action Required</h5>
                <p id="errorMessage" class="text-secondary small mb-4">Please select at least one item to continue.</p>
                <button type="button" class="btn btn-outline-info w-100 fw-bold mt-2 shadow-sm" data-bs-dismiss="modal">Okay, I Understood</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="passwordModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <form method="POST" action="cp-core-an-functions.php" class="modal-content bg-dark text-light border-secondary shadow-lg">
            <div class="modal-header border-secondary">
                <h6 class="modal-title"><i class="fas fa-user-shield me-2 text-danger"></i>Security Settings</h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <input type="hidden" name="action" value="change_password">
                <div class="mb-3">
                    <label class="small text-secondary mb-2">Current Password</label>
                    <input type="password" name="current_password" class="form-control bg-secondary bg-opacity-10 text-white border-secondary shadow-none" required>
                </div>
                <div class="mb-3">
                    <label class="small text-secondary mb-2">New Password</label>
                    <input type="password" name="new_password" class="form-control bg-secondary bg-opacity-10 text-white border-secondary shadow-none" required>
                </div>
                <div class="mb-3">
                    <label class="small text-secondary mb-2">Confirm New Password</label>
                    <input type="password" name="confirm_password" class="form-control bg-secondary bg-opacity-10 text-white border-secondary shadow-none" required>
                </div>
            </div>
            <div class="modal-footer border-secondary">
                <button type="submit" class="btn btn-danger w-100 fw-bold shadow">Update Password</button>
            </div>
        </form>
    </div>
</div>