let rowIndex = 0;

document.addEventListener('DOMContentLoaded', function () {
    (function () {
        const medicineCreateForm = document.getElementById('medicineCreateForm');

        if (!medicineCreateForm) return;

        // Initialize FormValidation
        const fv = FormValidation.formValidation(medicineCreateForm, {
            fields: {
                'customer[0][customer_id]': {
                    validators: {
                        notEmpty: {
                            message: 'Please select a customer.'
                        }
                    }
                },
                'medicine[0][medicine_id]': {
                    validators: {
                        notEmpty: {
                            message: 'Please select a medicine.'
                        }
                    }
                },
                'medicine[0][mrp]': {
                    validators: {
                        notEmpty: {
                            message: 'MRP is required.'
                        },
                        numeric: {
                            message: 'MRP must be a number.',
                            format: 'number'
                        }
                    }
                },
                'medicine[0][discount]': {
                    validators: {
                        notEmpty: {
                            message: 'Final Amount is required.'
                        },
                        numeric: {
                            message: 'Final Amount must be a number.',
                            format: 'number'
                        }
                    }
                },
                'medicine[0][discount_percent]': {
                    validators: {
                        notEmpty: {
                            message: 'Discount % is required.'
                        },
                        numeric: {
                            message: 'Discount % must be a number.',
                            format: 'number'
                        }
                    }
                }
            },

            plugins: {
                trigger: new FormValidation.plugins.Trigger(),
                bootstrap5: new FormValidation.plugins.Bootstrap5({
                    rowSelector: 'td',
                    eleInvalidClass: '',
                    eleValidClass: ''
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

        // Dynamically add validation for new rows
        function addValidationToRow(index) {
            fv.addField(`medicine[${index}][medicine_id]`, {
                validators: {
                    notEmpty: {
                        message: 'Please select a medicine.'
                    }
                }
            });
            fv.addField(`medicine[${index}][mrp]`, {
                validators: {
                    notEmpty: {
                        message: 'MRP is required.'
                    },
                    numeric: {
                        message: 'MRP must be a number.',
                        format: 'number'
                    }
                }
            });
            fv.addField(`medicine[${index}][discount]`, {
                validators: {
                    notEmpty: {
                        message: 'Final Amount is required.'
                    },
                    numeric: {
                        message: 'Final Amount must be a number.',
                        format: 'number'
                    }
                }
            });
            fv.addField(`medicine[${index}][discount_percent]`, {
                validators: {
                    notEmpty: {
                        message: 'Discount % is required.'
                    },
                    numeric: {
                        message: 'Discount % must be a number.',
                        format: 'number'
                    }
                }
            });
        }

        // Example event listener to add new rows
        document.getElementById('add-row').addEventListener('click', function () {
            addValidationToRow(rowIndex);
            rowIndex++;
        });

    })();
});

// Auto-trim whitespace in 'medicine_name' field when it loses focus
document.querySelectorAll('.medicine-search').forEach(function (medicineField) {
    medicineField.addEventListener('blur', function () {
        this.value = this.value.trim().replace(/\s+/g, ' ');
    });
});
