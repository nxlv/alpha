<script>
    import { RouterLink } from 'vue-router';

    export default {
        components: {
            RouterLink
        },
        data() {
            return {
            };
        }
    };
</script>
<template>
    <section class="income-solver">
        <aside class="form">
            <fieldset>
                <legend>Search Parameters</legend>

                <div class="form__row">
                    <div class="form__column">
                        <label>Solve for...</label>
                        <div class="form__buttons">
                            <div class="form__buttons-column">
                                <input type="radio" id="parameters__method-income" name="method" value="income">
                                <label for="parameters__method-income">Income</label>
                            </div>
                            <div class="form__buttons-column">
                                <input type="radio" id="parameters__method-premium" name="method" value="premium">
                                <label for="parameters__method-premium">Premium</label>
                            </div>
                        </div>
                    </div>

                    <div class="form__column">
                        <label>Premium</label>
                        <input type="text" id="parameters__premium" name="premium" class="money" value="100,000">
                    </div>

                    <div class="form__column">
                        <label>Withdrawal Frequency</label>
                        <div class="form__buttons">
                            <div class="form__buttons-column">
                                <input type="radio" id="parameters__frequency-annually" name="frequency" value="annually">
                                <label for="parameters__frequency-annually">Yearly</label>
                            </div>
                            <div class="form__buttons-column">
                                <input type="radio" id="parameters__frequency-monthly" name="frequency" value="monthly">
                                <label for="parameters__frequency-monthly">Monthly</label>
                            </div>
                        </div>
                    </div>
                </div>
            </fieldset>

            <fieldset>
                <legend>Filter Parameters</legend>

                <div class="form__row">
                    <div class="form__column">
                        <label>Carrier or Product Name</label>
                        <input type="text" id="filtering__name" name="name" value="" placeholder="i.e., Allianz">
                    </div>

                    <div class="form__column">
                        <label>Rating (A.M. Best)</label>
                        <select name="filtering__rating">
                            <option value="A">A</option>
                            <option value="A+">A+</option>
                            <option value="A++">A++</option>
                        </select>
                    </div>

                    <div class="form__column">
                        <label>Premium Bonus</label>
                        <select name="filtering__premium-bonus">
                            <option value="true">Has Premium Bonus</option>
                            <option value="false">No Premium Bonus</option>
                            <option value="5%">&gt;= 5%</option>
                            <option value="10%">&gt;= 10%</option>
                        </select>
                    </div>

                    <div class="form__column">
                        <label>Income Base Bonus</label>
                        <select name="filtering__base-bonus">
                            <option value="true">Has Income Base Bonus</option>
                            <option value="false">No Income Base Bonus</option>
                            <option value="5%">&gt;= 5%</option>
                            <option value="10%">&gt;= 10%</option>
                        </select>
                    </div>

                    <div class="form__column">
                        <label>Additional Premium</label>
                        <select name="filtering__premium-additional">
                            <option value="single">Single Premium</option>
                            <option value="life">Product Life</option>
                            <option value="first">First Year (or Less)</option>
                        </select>
                    </div>
                </div>
            </fieldset>
        </aside>
    </section>
</template>