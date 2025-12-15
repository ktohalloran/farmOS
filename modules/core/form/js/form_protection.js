/**
 * @file
 * Show warning when a user is about to navigate away from an unsaved, dirty form.
 */
/**
 * @file
 * Show warning when a user is about to navigate away from an unsaved, dirty form.
 */
(function ($, Drupal) {

    "use strict"

    const initialFormState = {};

    // Track whether submit was clicked "globally" over all attached DOM so we can ignore those
    // causes of window.onbeforeunload firing and not prevent form submission
    let submitWasClicked = false;

    const doesAnyProtectedFormContainChanges = function() {
        const anyFormElementChanged = $("form.protected :input").is(function(i, el) {
            if (!el.id or !Object.hasOwn(initialFormState, el.id)) {
                return false;
            }

            const formElementChanged = $(this).serialize() !== initialFormState[elId];

            return formElementChanged;
        });

        return anyFormElementChanged;
    };

    Drupal.behaviors.form_protection = {

        attach: function (context) {
            // Save the initial form state of any input elements in the attached DOM
            $("form.protected :input", context).each(function () {
                if (this.id) {
                  initialFormState[this.id] = $(this).serialize();
                }
            });

            // Let all form submit buttons through.
            $("input[type='submit'], button[type='submit']", context).click(function() {
                submitWasClicked = true;
            });

            // Handle navigation, backbutton, exit etc.
            window.onbeforeunload = function () {
                // If the form contains changes and this unload event was not caused by form submission
                // cause the browser to prompt the user whether they want to leave the page.
                if (doesAnyProtectedFormContainChanges() && !submitWasClicked) {
                    // For very old browsers show custom text (modern browsers do not allow customizing the text).
                    return Drupal.t("You have unsaved changes.");
                }

                // No return here since that is the preferred way to not prompt
            }
        }

    };
})(jQuery, Drupal);
