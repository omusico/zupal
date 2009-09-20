/**
 * Selects all of the options in a multiple-select element
 *
 * @author  Dan Delaney     http://fluidmind.org/
 * @param   element         The HTML select element to make the selection in
 */
function selectAllOptions(element) {
    if (typeof element == 'string') { element = document.getElementById(element); }

    // Then loop through the element's options and select all that
    // have keys in the new options associative array
    for (var i = 0; i < element.options.length; i++) {
        element.options[i].selected = true;
    }
}  // selectAllOptions()


/**
 * Deselects all of the options in a multiple-select element
 *
 * @author  Dan Delaney     http://fluidmind.org/
 * @param   element         The HTML select element to make the selection in
 */
function deselectAllOptions(element) {
    if (typeof element == 'string') { element = document.getElementById(element); }

    // Then loop through the element's options and select all that
    // have keys in the new options associative array
    for (var i = 0; i < element.options.length; i++) {
        element.options[i].selected = false;
    }
}  // deselectAllOptions()


/**
 * Selects a list of options in a multiple-select element, specified by numerical index
 *
 * @author  Dan Delaney     http://fluidmind.org/
 * @param   element         The HTML select element to make the selection in
 * @param   optionIndex     The numerical index of the option to select
 */
function selectOptionsByIndex(element) {
    if (typeof element == 'string') { element = document.getElementById(element); }

    for (var i = 1; i < arguments.length; i++) {
        element.options[arguments[i]].selected = true;
    }
}  // selectOptionsByIndex()


/**
 * Selects a list of options in a multiple-select element, specified by the option values
 *
 * @author  Dan Delaney     http://fluidmind.org/
 * @param   element         The HTML select element to make the selection in
 * @param   optionValue     The value of the option to select
 */
function selectOptionsByValue(element) {
    if (typeof element == 'string') { element = document.getElementById(element); }

    if (arguments.length > 1) {
        // First, make a little associative array object with the specified
        // options values as its keys
        var options = new Object();
        for (var i = 1; i < arguments.length; i++) {
            options['value_' + arguments[i]] = true;
        }

        // Then loop through the element's options and select all that
        // have keys in the new options associative array
        for (var i = 0; i < element.options.length; i++) {
            if (options.hasOwnProperty('value_' + element.options[i].value)) {
                element.options[i].selected = true;
            }
        }
    }
}  // selectOptionsByValue()


/**
 * Shifts selected options in a multiple select element up one position
 *
 * @author  Dan Delaney     http://fluidmind.org/
 * @param   element         The form select element with the options to me moved
 * @see     swapOptions
 */
function shiftSelectionsUp(element, direction)
{
    if (typeof element == 'string') { element = document.getElementById(element); }

    // Make sure it's a multiple select element
    if (element.type == 'select-multiple') {
        // Step through the options and move the selected ones
        for (var i = 0; i < element.options.length; i++) {
            // Is the option selected?
            if (element.options[i].selected) {
                // Only swap it if it's not the first option and it's not
                // below another selected option
                if (i > 0 && !element.options[i - 1].selected) {
                    swapOptions(element, i, i - 1);
                }
            }
        } // for
    }
} // shiftSelectionsUp()


/**
 * Shifts selected options in a multiple select element down one position
 *
 * @author  Dan Delaney     http://fluidmind.org/
 * @param   element         The HTML select element with the options to me moved
 * @see     swapOptions
 */
function shiftSelectionsDown(element)
{
    if (typeof element == 'string') { element = document.getElementById(element); }

    // Make sure it's a multiple select element
    if (element.type == 'select-multiple') {
        // Step through the options and move the selected ones
        for (var i = element.options.length - 1; i >= 0 ; i--) {
            // Is the option selected?
            if (element.options[i].selected) {
                // Only swap it if it's not the last option and it's not
                // above another selected option
                if (i < (element.options.length - 1) &&
                    !element.options[i + 1].selected) {
                    swapOptions(element, i, i + 1);
                }
            }
        } // for
    }
} // shiftSelectionsDown()


/**
 * Copies selected options in a multiple-select element to another multiple-select element.
 *
 * @author  Dan Delaney     http://fluidmind.org/
 * @param   fromElement     The HTML select element with the options to be copied
 * @param   toElement       The HTML select element to copy the options to
 */
function copySelections(fromElement, toElement)
{
    if (typeof fromElement == 'string') fromElement = document.getElementById(fromElement);
    if (typeof toElement == 'string') toElement = document.getElementById(toElement);

    // Make sure both parameters are multiple select element
    if (fromElement.type == 'select-multiple' && toElement.type == 'select-multiple') {
        // Step through the options and move the selected ones
        for (var i = 0; i < fromElement.options.length; i++) {
            // Is the option selected?
            if (fromElement.options[i].selected) {
                // Copy it to the end of the destination element
                toElement.options[toElement.options.length] = 
                    new Option(fromElement.options[i].text, 
                               fromElement.options[i].value, 
                               fromElement.options[i].defaultSelected, 
                               fromElement.options[i].selected);
            }
        } // for
    }
} // copySelections()


/**
 * Removes selected options from a multiple-select element
 *
 * @author  Dan Delaney     http://fluidmind.org/
 * @param   element         The HTML select element to remove the options from
 */
function removeSelections(element)
{
    if (typeof element == 'string') { element = document.getElementById(element); }

    // Make sure it's a multiple select element
    if (element.type == 'select-multiple') {
        for (var i = element.options.length - 1; i >= 0; i--) {
            if (element.options[i].selected) {
                // Delete it from the source element
                element.options[i] = null;
            }
        }
    }
} // removeSelections()


/**
 * Moves selected options in a multiple-select element to another multiple-select element.
 *
 * @author  Dan Delaney     http://fluidmind.org/
 * @param   fromElement     The HTML select element with the options to be moved
 * @param   toElement       The HTML select element to move the options to
 * @see     copyOptions
 * @see     removeOptions
 */
function moveSelections(fromElement, toElement)
{
    if (typeof element == 'string') { element = document.getElementById(element); }

    // Make sure both parameters are multiple select elements
    if (fromElement.type == 'select-multiple' && toElement.type == 'select-multiple') {
        copyOptions(fromElement, toElement);
        removeOptions(fromElement);
    }
} // moveSelections()
