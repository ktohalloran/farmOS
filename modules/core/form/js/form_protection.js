/**
 * @file
 * Show warning when a user is about to navigate away from an unsaved, dirty form.
 */
(function ($, Drupal) {

    "use strict";

    Drupal.behaviors.form_protection = {

        initialFormState: {},

        attach: function (context) {
            const behavior = Drupal.behaviors.form_protection;
            let allowClick = false;
            let formIsDirty = false;

            // Get initial form state on initial load.
            $("form.protected :input", context).each(function () {
                behavior.initialFormState[$(this).attr("id")] = $(this).serialize();
            });

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
                        formIsDirty = behavior.checkForUnsavedChanges();
                        // If the user clicked on a # link or the form is not dirty, let click through; otherwise,
                        // return something other than undefined to trigger onbeforeunload.
                        if (formIsDirty && $(this).attr("href") !== "#") {
                            return 0;
                        }
                    });
                });

            // Handle backbutton, exit etc.
            window.onbeforeunload = function () {
                if (formIsDirty && !allowClick) {
                    allowClick = false;
                    return (Drupal.t("You have unsaved changes."));
                }
            }
        },

        checkForUnsavedChanges: function () {
            const behavior = Drupal.behaviors.form_protection;
            let formIsDirty = false;

            $("form.protected :input").each(function () {
                const elId = $(this).attr("id");

                // Check new state against initial state if available; if it's not, move on.
                if (elId && Object.keys(behavior.initialFormState).includes(elId)) {
                    const updatedFormState = $(this).serialize();
                    formIsDirty = updatedFormState !== behavior.initialFormState[elId];
                }

                // If we find a value that has been changed, break out of the loop (which in jQuery apparently
                // means return false).
                if (formIsDirty) {
                    return false;
                }
            })
            return formIsDirty;
        },

    }
})(jQuery, Drupal)