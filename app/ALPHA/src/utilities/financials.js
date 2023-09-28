const financeUtils = {
    format_currency(value, currency) {
        let engine = new Intl.NumberFormat(
            'en-US',
            {
                style: 'currency',
                currency: currency
            }
        );

        return engine.format(value);
    },

    format_currency_compact(value, currency) {
        let engine = Intl.NumberFormat(
            'en-US',
            {
                currency: currency,
                notation: 'compact',
                maximumFractionDigits: 1
            }
        );

        return engine.format(value);
    }
}

export default financeUtils;
