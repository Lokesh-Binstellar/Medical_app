
const getFileValidators = (label = 'file') => ({
    notEmpty: {
        message: `Please upload your ${label}.`
    },
    file: {
        extension: 'jpg,jpeg,png',
        type: 'image/jpeg,image/png',
        message: 'Only JPG, JPEG, and PNG image files are allowed.'
    },
    // fileSize: {
    //     max: '1MB',
    //     message: 'The file size must not exceed 2MB.'
    // }
});
var image = $('#image').attr('src');
document.addEventListener('DOMContentLoaded', function () {
    (function () {
        const importForm = document.getElementById('importForm');

        // Initialize FormValidation
        const fv = FormValidation.formValidation(importForm, {
            fields: {
                 priority: {
                    validators: {
                        notEmpty: {
                            message: 'Priority is required'
                        }
                    }
                },
                
                image:(image == null) ? { validators: getFileValidators('banner photo') } : '',
                
            },
            
            plugins: {
                trigger: new FormValidation.plugins.Trigger(),
                bootstrap5: new FormValidation.plugins.Bootstrap5({
                    eleValidClass: '',
                    rowSelector: function (field, ele) {
                        return '.col-md-6,.col-md-12','.error-msg'; 
                    }
                }),
                submitButton: new FormValidation.plugins.SubmitButton(),
                defaultSubmit: new FormValidation.plugins.DefaultSubmit(),
                autoFocus: new FormValidation.plugins.AutoFocus()
            },
            init: instance => {
                instance.on('plugins.message.placed', function (e) {
                    // Move error message if inside input-group
                    if (e.element.parentElement.classList.contains('input-group')) {
                        e.element.parentElement.insertAdjacentElement('afterend', e.messageElement);
                    }
                    // Move error message if inside custom-option
                    if (e.element.parentElement.parentElement.classList.contains('custom-option')) {
                        e.element.closest('.row').insertAdjacentElement('afterend', e.messageElement);
                    }
                    
                });
            }
        });

    })();
});

// Auto-trim whitespace in 'name' field when it loses focus
document.getElementById('pharmacy_name').addEventListener('blur', function () {
    this.value = this.value.trim().replace(/\s+/g, ' ');
});
