/**
 * @file
 * Show warning when a user is about to navigate away from an unsaved, dirty form
 */
(function ($, Drupal) {

    "use strict"

    Drupal.behaviors.form_protection = {
        initialFormState: {},

        attach: function (context) {
            let behavior = Drupal.behaviors.form_protection;
            let allowClick = false;
            let formIsDirty = false;

            // save initial form state only on initial load
            $( window ).on("load", function () {
                behavior.getInitialFormVals()
            })

            // Let all form submit buttons through.
            $("input[type='submit'], button[type='submit']").each(function() {
                $(this).addClass("allow-submit");
                $(this).click(function() {
                    allowClick = true;
                });
            });

            // Catch all links and buttons EXCEPT for "#" links.
            $("a, button, input[type='submit']:not(.allow-submit), button[type='submit']:not(.allow-submit)")
                .each(function() {
                    $(this).click(function() {
                        formIsDirty = behavior.checkForUnsavedChanges()
                        // Return when a "#" link is clicked so as to skip the
                        // window.onbeforeunload function.
                        if (formIsDirty && $(this).attr("href") !== "#") {
                            return 0;
                        }
                    });
                });

            // Handle backbutton, exit etc.
            window.onbeforeunload = function () {
                if (formIsDirty && !allowClick) {
                    allowClick = false;
                    return (Drupal.t("You have unsaved changes."))
                }
            }
        },

        getInitialFormVals: function () {
            let behavior = Drupal.behaviors.form_protection;
            $("form").each(function () {
                behavior.initialFormState[$(this).attr("id")] = $(this).serialize();
            });
        },

        checkForUnsavedChanges: function () {
            let behavior = Drupal.behaviors.form_protection;
            let formIsDirty = false
            $("form").each(function () {
                const updatedFormState = $(this).serialize();
                formIsDirty = updatedFormState !== behavior.initialFormState[$(this).attr("id")]
            })
            return formIsDirty
        }
    }
})(jQuery, Drupal)