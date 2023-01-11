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
    <aside class="alpha__prologue-secondary-client">
        <h3>Test User <span class="money">$100,000</span></h3>
        <menu class="stats">
            <li data-type="location">Florida</li>
            <li data-type="age">65</li>
            <li data-type="marital">Single</li>
        </menu>
        <menu class="controls">
            <li data-type="change"><a href="#/change" title="Change Client">Change Client</a></li>
            <li data-type="edit"><a href="#/edit" title="Edit Client">Edit Client</a></li>
            <li data-type="add"><a href="#/add" title="Add Client">Add Client</a></li>
        </menu>
    </aside>
</template>
