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
    <aside class="alpha__prologue-secondary-user">
        <h3>Logged In! <i class="fal fa-hand-wave" aria-hidden="true"></i> </h3>
        <ol>
            <li><a href="#/settings">Settings</a></li>
            <li><a href="#/logout">Logout</a></li>
        </ol>
    </aside>
</template>
