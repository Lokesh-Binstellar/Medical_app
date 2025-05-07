document.addEventListener('DOMContentLoaded', function () {
    (function () {
        const roleCreateForm = document.getElementById('roleCreateForm');

        // Initialize FormValidation
        const fv = FormValidation.formValidation(roleCreateForm, {
            fields: {
                name: {
                    validators: {
                        notEmpty: {
                            message: 'Please enter your role'
                        }
                    }
                }           
            },
            
            plugins: {
                trigger: new FormValidation.plugins.Trigger(),
                bootstrap5: new FormValidation.plugins.Bootstrap5({
                    eleValidClass: '',
                    rowSelector: function (field, ele) {
                        return '.col-md-6'; // Adjust this selector based on your layout
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
document.getElementById('name').addEventListener('blur', function () {
    this.value = this.value.trim().replace(/\s+/g, ' ');
});
