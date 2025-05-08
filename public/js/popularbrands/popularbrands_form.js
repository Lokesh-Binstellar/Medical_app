const getFileValidators = (label = 'file') => ({
    notEmpty: {
        message: `Please upload your ${label}.`
    },
    // file: {
    //     extension: 'csv,xlsx,xls,pdf,doc,docx,zip', // or leave out extension if no restrictions
    //     maxSize: 5 * 1024 * 1024, 
    //     message: 'Please upload a valid file (max 5MB).'
    // }
});

document.addEventListener('DOMContentLoaded', function () {
    const importForm = document.getElementById('importForm');

    const fv = FormValidation.formValidation(importForm, {
        fields: {
            name: {
                validators: {
                    notEmpty: {
                        message: 'Please select a brand.'
                    }
                }
            },
            logo: {
                validators: getFileValidators('file')
            }
        },
        plugins: {
            trigger: new FormValidation.plugins.Trigger(),
            bootstrap5: new FormValidation.plugins.Bootstrap5({
                eleValidClass: '',
                rowSelector: ' .error-msg'
            }),
            submitButton: new FormValidation.plugins.SubmitButton(),
            defaultSubmit: new FormValidation.plugins.DefaultSubmit(),
            autoFocus: new FormValidation.plugins.AutoFocus()
        },
        init: instance => {
            instance.on('plugins.message.placed', function (e) {
                // e.messageElement.style.minHeight = '1.5rem';
                if (e.element.parentElement.classList.contains('input-group')) {
                    e.element.parentElement.insertAdjacentElement('afterend', e.messageElement);
                }
                if (e.element.parentElement.parentElement.classList.contains('custom-option')) {
                    e.element.closest('.row').insertAdjacentElement('afterend', e.messageElement);
                }
            });
        }
    });
});