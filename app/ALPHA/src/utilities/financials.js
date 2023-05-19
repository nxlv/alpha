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
    }
}

export default financeUtils;
