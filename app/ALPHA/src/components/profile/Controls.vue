<script>
    import { RouterLink } from 'vue-router';

    export default {
        components: {
            RouterLink
        },
        data() {
        }
    };
</script>
<template>
    <aside class="alpha__prologue-secondary-user">
        <h3>Welcome, User! <i class="fal fa-hand-wave" aria-hidden="true"></i> </h3>
        <ol>
            <li><a href="#/profile">Profile</a></li>
            <li><a href="#/logout">Logout</a></li>
        </ol>
    </aside>
</template>
