export const validateAccountForm = (account) => {
    let errors = {}

    if (account.name.length < 3) {
        errors.name = 'Account name must be at least 3 characters.'
    }

    const domainRegex = /^(https?:\/\/)?([a-zA-Z0-9-]+\.)+[a-zA-Z]{2,6}(\/.*)?$/;
    if (!domainRegex.test(account.website)) {
        errors.website = 'Please enter a website.'
    }

    const phoneRegex = /^\+[\d]{12,}$/;
    if (!phoneRegex.test(account.phone)) {
        errors.phone = 'Phone must start with + and be at least 12 characters.'
    }

    return errors
}
