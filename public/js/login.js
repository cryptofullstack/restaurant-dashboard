var SnippetAuth = function() {

    var showErrorMsg = function(form, type, msg) {
        var alert = $('<div class="m-alert m-alert--outline alert alert-' + type + ' alert-dismissible" role="alert">\
			<button type="button" class="close" data-dismiss="alert" aria-label="Close"></button>\
			<span></span>\
		</div>');

        form.find('.alert').remove();
        alert.prependTo(form);
        alert.addClass('fadeIn animated');
        alert.find('span').html(msg);
    }

    var handleSignInFormSubmit = function() {

        $('#m_login_signin_submit').click(function(e) {
            e.preventDefault();
            var btn = $(this);
            var form = $(this).closest('form');

            form.validate({
                rules: {
                    email: {
                        required: true,
                        email: true
                    },
                    password: {
                        required: true
                    }
                }
            });

            if (!form.valid()) {
                return;
            }

            btn.addClass('m-loader m-loader--right m-loader--light').attr('disabled', true);
            var loginUrl = form.attr('action');

            form.ajaxSubmit({
                url: loginUrl,
                success: function(response, status, xhr, $form) {
                    btn.removeClass('m-loader m-loader--right m-loader--light').attr('disabled', false);

                    if (response.result === "success") {
                        window.location.replace(response.url);
                    } else {
                        showErrorMsg(form, 'danger', 'Incorrect username or password. Please try again.');
                    }
                }
            });
        });
    }

    //== Public Functions
    return {
        // public functions
        init: function() {
            handleSignInFormSubmit();
        }
    };
}();

//== Class Initialization
jQuery(document).ready(function() {
    SnippetAuth.init();
});
