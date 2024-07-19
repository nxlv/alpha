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
                    <router-link :to="{ name: 'income__fixed' }">Income Solver</router-link>

                    <ol>
                        <li><router-link :to="{ name: 'income__fixed' }">FIA with Income Rider</router-link></li>
                        <li><router-link :to="{ name: 'income__immediate' }">SPIA / DIA Solver</router-link></li>
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
                        <li><router-link :to="{ name: 'indexes__tracker' }">Index Tracker & History</router-link></li>
                        <li><a href="#">Annuity Products Data</a></li>
                    </ol>
                </li>
            </ol>
        </nav>
    </section>
</template>