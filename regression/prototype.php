<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>ALPHA - Heuristic Prototype</title>
    </head>
    <body>
        <header>
            <section class="container">
                <h1>ALPHA <strong>Heuristic Prototype</strong></h1>
            </section>
        </header>

        <main>
            <aside>
                <div class="form-row">
                    <label for="deferral">Years to Defer Income</label>
                    <input type="range" name="deferral" id="deferral" min="5" max="35" step="1" value="10">
                </div>
            </aside>

            <article>
                <aside>
                    <div class="form-row">
                        <label for="indexes">Premium ($USD)</label>
                        <input type="text" name="premium" id="premium" value="100,000">
                    </div>

                    <div class="form-row">
                        <label for="owner_state">Owner's State</label>
                        <select name="owner_state" id="owner_state">
                            <option value="FL">(FL) Florida</option>
                        </select>
                    </div>

                    <div class="form-row">
                        <label for="indexes">Index(es)</label>
                        <select name="indexes" id="indexes" multiple>
                            <option value="S&P 500">S&P 500</option>
                        </select>
                    </div>

                    <div class="form-row">
                        <label for="strategy_type">Strategy Type</label>
                        <select name="strategy_type" id="strategy_type" multiple>
                            <option value="PP">Point-to-Point</option>
                        </select>
                    </div>

                    <div class="form-row">
                        <label for="strategy_configuration">Strategy Configuration</label>
                        <select name="strategy_configuration" id="strategy_configuration" multiple>
                            <option value="03">Cap + Participation</option>
                        </select>
                    </div>

                    <div class="form-row">
                        <label for="calculation_frequency">Calculation Frequency</label>
                        <select name="calculation_frequency" id="calculation_frequency" multiple>
                            <option value="A">Annually</option>
                        </select>
                    </div>

                    <div class="form-row">
                        <label for="crediting_frequency">Crediting Frequency</label>
                        <select name="crediting_frequency" id="crediting_frequency" multiple>
                            <option value="A">Annually</option>
                        </select>
                    </div>

                    <div class="form-row">
                        <label for="participation_rate">Participation Rate</label>
                        <select name="participation_rate" id="participation_rate" multiple>
                            <option value="100">100%</option>
                        </select>
                    </div>

                    <div class="form-row">
                        <label for="strategy_configuration">Guarantee Period</label>

                        <div class="form-row-content">
                            <div class="form-row">
                                <label for="guarantee_period_years">Years</label>
                                <input type="number" name="guarantee_period_years" id="guarantee_period_years" value="1" >
                            </div>
                            <div class="form-row">
                                <label for="guarantee_period_months">Months</label>
                                <input type="number" name="guarantee_period_months" id="guarantee_period_months" value="0" >
                            </div>
                        </div>
                    </div>
                </aside>
                <section>
                    <div class="card card__analysis">
                        <h1>Product Title</h1>
                        <h2>Carrier</h2>
                    </div>
                </section>
            </article>
        </main>
    </body>
</html>