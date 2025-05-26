document.addEventListener("DOMContentLoaded", function () {
    (function () {
        const userCreateForm = document.getElementById("userCreateForm"),
            role_idEle = jQuery(
                userCreateForm.querySelector('[name="role_id"]')
            );

        // Initialize FormValidation
        const fv = FormValidation.formValidation(userCreateForm, {
            fields: {
                name: {
                    validators: {
                        notEmpty: {
                            message: "Please enter your name",
                        },
                        // regexp: {
                        //     regexp: /^[a-zA-Z0-9 ]+$/,
                        //     message:
                        //         "The name can only consist of alphabetical, number and space",
                        // },
                    },
                },
                email: {
                    validators: {
                        notEmpty: {
                            message: "Please enter your email",
                        },
                        emailAddress: {
                            message: "The value is not a valid email address",
                        },
                    },
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

                password_confirmation: {
                    validators: {
                        notEmpty: {
                            message: "Please confirm password",
                        },
                        identical: {
                            compare: function () {
                                return userCreateForm.querySelector(
                                    '[name="password"]'
                                ).value;
                            },
                            message:
                                "The password and its confirm are not the same",
                        },
                    },
                },
                role_id: {
                    validators: {
                        notEmpty: {
                            message: "Please select a role",
                        },
                    },
                },
            },

            plugins: {
                trigger: new FormValidation.plugins.Trigger(),
                bootstrap5: new FormValidation.plugins.Bootstrap5({
                    eleValidClass: "",
                    rowSelector: function (field, ele) {
                        return ".col-md-6"; // Adjust this selector based on your layout
                    },
                }),
                submitButton: new FormValidation.plugins.SubmitButton(),
                defaultSubmit: new FormValidation.plugins.DefaultSubmit(),
                autoFocus: new FormValidation.plugins.AutoFocus(),
            },
            init: (instance) => {
                instance.on("plugins.message.placed", function (e) {
                    // Move error message if inside input-group
                    if (
                        e.element.parentElement.classList.contains(
                            "input-group"
                        )
                    ) {
                        e.element.parentElement.insertAdjacentElement(
                            "afterend",
                            e.messageElement
                        );
                    }
                    // Move error message if inside custom-option
                    if (
                        e.element.parentElement.parentElement.classList.contains(
                            "custom-option"
                        )
                    ) {
                        e.element
                            .closest(".row")
                            .insertAdjacentElement(
                                "afterend",
                                e.messageElement
                            );
                    }
                });
            },
        });

        // Initialize Select2 for the role field
        if (role_idEle.length) {
            select2Focus(role_idEle);
            role_idEle.wrap('<div class="position-relative"></div>');
            role_idEle
                .select2({
                    placeholder: "Select Role",
                    dropdownParent: role_idEle.parent(),
                })
                .on("change.select2", function () {
                    fv.revalidateField("role_id");
                });
        }
    })();
});

// Auto-trim whitespace in 'name' field when it loses focus
document.getElementById("name").addEventListener("blur", function () {
    this.value = this.value.trim().replace(/\s+/g, " ");
});
