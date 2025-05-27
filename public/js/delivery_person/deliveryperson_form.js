
const getFileValidators = (label = 'file') => ({
    notEmpty: {
        message: `Please upload your ${label}.`
    },
    file: {
        extension: 'jpg,jpeg,png',
        type: 'image/jpeg,image/png',
        message: 'Only JPG, JPEG, and PNG image files are allowed.'
    },
    fileSize: {
        max: '1MB',
        message: 'The file size must not exceed 2MB.'
    }
});
var image = $('#image').attr('src');
document.addEventListener('DOMContentLoaded', function () {
    (function () {
        const deliveryPersonCreateForm = document.getElementById('deliveryPersonCreateForm');

        // Initialize FormValidation
        const fv = FormValidation.formValidation(deliveryPersonCreateForm, {
            fields: {
                delivery_person_name: {
                    validators: {
                        notEmpty: {
                            message: 'Delivery person name is required'
                        },
                        
                    }
                },
                
                email: {
                    validators: {
                        notEmpty: {
                            message: 'Email is required'
                        },
                        regexp: {
                            regexp: /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/,
                            message: 'Enter a valid email address'
                        }
                    }
                },
                phone: {
                    validators: {
                        notEmpty: {
                            message: 'Phone number is required'
                        },
                        regexp: {
                            regexp: /^\d{7,12}$/,
                            message: 'Phone must be 7 to 12 digits'
                        }
                    }
                },
                username: {
                    validators: {
                        notEmpty: {
                            message: 'Username is required'
                        }
                    }
                },
               password: {
                    validators: {
                        notEmpty: {
                            message: "Please enter your password",
                        },
                        stringLength: {
                            min: 6,
                            message: "Password must be more than 6 characters",
                        },
                        regexp: {
                            regexp: /^[^\s]+$/,
                            message: "Password must not contain spaces",
                        },
                    },
                },
                city: {
                    validators: {
                        notEmpty: {
                            message: 'City is required'
                        }
                    }
                },
                pincode: {
                    validators: {
                        notEmpty: {
                            message: 'Pincode is required'
                        },
                        regexp: {
                            regexp: /^\d{6}$/,
                            message: 'Pincode must be exactly 6 digits'
                        }
                    }
                },
                state: {
                    validators: {
                        notEmpty: {
                            message: 'State is required'
                        }
                    }
                },
                
                image:(image == null) ? { validators: getFileValidators('Pharmacies photo') } : '',
                
                address: {
                    validators: {
                        notEmpty: {
                            message: 'Address is required'
                        },
                        stringLength: {
                            min: 10,
                            message: 'Address must be at least 10 characters'
                        }
                    }
                }
            },
            
            plugins: {
                trigger: new FormValidation.plugins.Trigger(),
                bootstrap5: new FormValidation.plugins.Bootstrap5({
                    eleValidClass: '',
                    rowSelector: function (field, ele) {
                        return '.col-md-6,.col-md-12'; // Adjust this selector based on your layout
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
