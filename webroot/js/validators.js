/**
 * Map of defined phone prefixes and related Regex validators
 *
 * @type {Map<string, RegExp>}
 */
const phoneNumberRegexes = new Map();
phoneNumberRegexes.set('+420', /^[1-9][0-9]{8}$/);
phoneNumberRegexes.set('+421', /^[1-9][0-9]{8}$/);
phoneNumberRegexes.set('+1', /^[1-9][0-9]{9}$/);

/**
 * Validate phone number by phone prefix
 *
 * @param prefix Phone prefix
 * @param numberInput Phone number input element
 * @param required True if phone number is required field
 */
function validatePhoneNumber(prefix, numberInput, required = false){
    const pattern = phoneNumberRegexes.get(prefix);

    numberInput.setCustomValidity('');
    if (numberInput.value !== '' || prefix !== ''){
        if (pattern === undefined){
            numberInput.setCustomValidity('Phone prefix not found');
            return;
        }

        if (!pattern.test(numberInput.value))
        {
            numberInput.setCustomValidity('Phone number incorrect');
        }
    } else if (required) {
        numberInput.setCustomValidity('Phone number required');
    }
}
