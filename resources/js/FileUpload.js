// Initialize file upload functionality
window.initFileUpload = function() {
    const fileInput = document.getElementById('chat-file-input');
    const selectFileBtn = document.getElementById('select-file-btn');
    const clearFileBtn = document.getElementById('clear-file-btn');
    const fileNameDisplay = document.getElementById('file-name-display');
    const imagePreviewContainer = document.getElementById('image-preview-container');
    const imagePreview = document.getElementById('image-preview');
    const removePreviewBtn = document.getElementById('remove-preview-btn');
    const fileErrorMessage = document.getElementById('file-error-message');

    if (!fileInput || !selectFileBtn) return; // Exit if elements don't exist

    // Allowed file types
    const allowedTypes = {
        images: ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'],
        documents: ['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 
                    'application/vnd.ms-excel', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 'text/plain']
    };

    // Maximum file size: 10MB
    const maxFileSize = 10 * 1024 * 1024;

    // فتح اختيار الملف
    selectFileBtn.addEventListener('click', () => {
        fileInput.click();
    });

    // عند اختيار ملف
    fileInput.addEventListener('change', () => {
        hideError();
        
        if (fileInput.files.length > 0) {
            const file = fileInput.files[0];
            
            // Validate file type
            const isValidType = [...allowedTypes.images, ...allowedTypes.documents].includes(file.type);
            if (!isValidType) {
                showError('نوع الملف غير مدعوم. يُسمح بالصور (JPG, PNG, GIF) والملفات (PDF, DOC, DOCX, XLS, XLSX, TXT) فقط.');
                fileInput.value = '';
                return;
            }
            
            // Validate file size
            if (file.size > maxFileSize) {
                showError('حجم الملف كبير جداً. الحد الأقصى هو 10 ميجابايت.');
                fileInput.value = '';
                return;
            }
            
            // Display file name
            if (fileNameDisplay) {
                fileNameDisplay.textContent = file.name;
            }
            
            // Show clear button
            if (clearFileBtn) {
                clearFileBtn.style.display = 'inline-flex';
            }
            
            // Show image preview if it's an image
            if (allowedTypes.images.includes(file.type)) {
                const reader = new FileReader();
                reader.onload = (e) => {
                    if (imagePreview) {
                        imagePreview.src = e.target.result;
                    }
                    if (imagePreviewContainer) {
                        imagePreviewContainer.classList.remove('hidden');
                    }
                };
                reader.readAsDataURL(file);
            } else {
                // Hide image preview for non-image files
                if (imagePreviewContainer) {
                    imagePreviewContainer.classList.add('hidden');
                }
            }
        } else {
            clearFileSelection();
        }
    });

    // مسح الملف المحدد
    if (clearFileBtn) {
        clearFileBtn.addEventListener('click', () => {
            clearFileSelection();
        });
    }

    // Remove preview button
    if (removePreviewBtn) {
        removePreviewBtn.addEventListener('click', () => {
            clearFileSelection();
        });
    }

    function clearFileSelection() {
        if (fileInput) {
            fileInput.value = '';
        }
        if (fileNameDisplay) {
            fileNameDisplay.textContent = '';
        }
        if (clearFileBtn) {
            clearFileBtn.style.display = 'none';
        }
        if (imagePreviewContainer) {
            imagePreviewContainer.classList.add('hidden');
        }
        if (imagePreview) {
            imagePreview.src = '';
        }
        hideError();
    }

    function showError(message) {
        if (fileErrorMessage) {
            fileErrorMessage.textContent = message;
            fileErrorMessage.classList.remove('hidden');
        }
    }

    function hideError() {
        if (fileErrorMessage) {
            fileErrorMessage.textContent = '';
            fileErrorMessage.classList.add('hidden');
        }
    }
};
