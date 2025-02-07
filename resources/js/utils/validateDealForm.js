export const validateDealForm = (deal) => {
    let errors = {}

    if (deal.name.length < 3) {
        errors.deal_name = 'Deal name must be at least 3 characters.'
    }

    if (deal.stage.length < 3) {
        errors.deal_stage = 'Deal stage must be at least 3 characters.'
    }

    return errors
}
