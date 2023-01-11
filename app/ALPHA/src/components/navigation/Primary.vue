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
    <section class="alpha__prologue-primary">
        <RouterLink to="/" class="alpha__prologue-primary-logo"><span class="title">Alpha</span> by Annuity Association</RouterLink>

        <nav>
            <ol>
                <li>
                    <router-link :to="{ name: 'income__guaranteed-rider' }">Income Solver</router-link>

                    <ol>
                        <li><router-link :to="{ name: 'income__guaranteed-rider' }">Guaranteed Rider Calculator</router-link></li>
                        <li><a href="#">Advanced Rider</a></li>
                        <li><a href="#">SPIA/DIA</a></li>
                        <li><a href="#">Illustrations</a></li>
                    </ol>
                </li>
                <li>
                    <a href="#/performance">Performance</a>

                    <ol>
                        <li><a href="#">Annuity Analyzer</a></li>
                        <li><a href="#">Backtesting</a></li>
                        <li><a href="#">Top Products</a></li>
                    </ol>
                </li>
                <li>
                    <a href="#/research">Research</a>

                    <ol>
                        <li><a href="#">Index Tracker & History</a></li>
                        <li><router-link :to="{ name: 'products__all' }">Annuity Products Data</router-link></li>
                    </ol>
                </li>
                <li>
                    <a href="#/reporting">Reporting</a>

                    <ol>
                        <li><a href="#">TBD</a></li>
                    </ol>
                </li>
            </ol>
        </nav>
    </section>
</template>