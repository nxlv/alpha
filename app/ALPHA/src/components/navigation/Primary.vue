<script>
    import { RouterLink } from 'vue-router';

    export default {
        components {
            RouterLink
        },
        data() {
        }
    };
</script>
<template>
    <section class="alpha__prologue-primary">
        <RouterLink to="/" class="alpha__prologue-primary-logo"><span class="title">Alpha</span> by Annuity Association</RouterLink>

        <nav>
            <ol>
                <li>
                    <router-link :to="{ name: 'income-guaranteed-rider' }">Income Solver</router-link>

                    <ol>
                        <li><router-link :to="{ name: 'income-guaranteed-rider' }">Guaranteed Rider Calculator</router-link></li>
                        <li><a href="#">Option 2</a></li>
                        <li><a href="#">Option 3</a></li>
                    </ol>
                </li>
                <li>
                    <a href="#/performance">Performance</a>

                    <ol>
                        <li><a href="#">Option 1</a></li>
                        <li><a href="#">Option 2</a></li>
                        <li><a href="#">Option 3</a></li>
                    </ol>
                </li>
                <li>
                    <a href="#/research">Research</a>

                    <ol>
                        <li><a href="#">Option 1</a></li>
                        <li><a href="#">Option 2</a></li>
                        <li><a href="#">Option 3</a></li>
                    </ol>
                </li>
                <li>
                    <a href="#/reporting">Reporting</a>

                    <ol>
                        <li><a href="#">Option 1</a></li>
                        <li><a href="#">Option 2</a></li>
                        <li><a href="#">Option 3</a></li>
                    </ol>
                </li>
            </ol>
        </nav>
    </section>
</template>