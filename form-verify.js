jQuery(document).ready(function ($) {
    const formSections = $('.fluentform .ff-el-group')
        .not(':has(input[name="application_code"])')
        .not(':has(input[name="full_name"])');
    const codeField = $('input[name="application_code"]');
    const nameField = $('input[name="full_name"]');
    const confirmField = $('input[name="application_code_confirmed"]');
    const verifyBtn = $('#verify-code-btn');

    const verifyWrapper = $('#verify-code-wrapper');
    const loader = $('#verify-loader');

    formSections.hide(); // hide all fields until verified

    verifyBtn.on('click', function (e) {
        e.preventDefault();

        const code = codeField.val().trim();
        const name = nameField.val().trim();

        if (!code || !name) {
            alert('Please enter both your application code and full name.');
            return;
        }

        loader.show();
        verifyBtn.prop('disabled', true);

        $.ajax({
            type: 'POST',
            url: ffacm_ajax.ajax_url,
            data: {
                action: 'verify_application_code',
                code: code,
                name: name
            },
            success: function (response) {
                loader.hide();
                verifyBtn.prop('disabled', false);

                if (response.success) {
                    // Save verified code into hidden field
                    confirmField.val(code);

                    // Reveal the rest of the form
                    formSections.slideDown();

                    // Optionally hide the verification fields
                    verifyWrapper.hide();
                } else {
                    alert(response.data.message || 'Invalid or already used application code.');
                }
            },
            error: function () {
                loader.hide();
                verifyBtn.prop('disabled', false);
                alert('An error occurred. Please try again.');
            }
        });
    });
});
