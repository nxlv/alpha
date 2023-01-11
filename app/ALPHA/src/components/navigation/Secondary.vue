<script>
    import { RouterLink } from 'vue-router';

    import ClientControls from '@/components/clients/Controls.vue';
    import SearchBar from '@/components/header/SearchBar.vue';
    import ProfileControls from '@/components/profile/Controls.vue';

    export default {
        components: {
            RouterLink,
            ClientControls, SearchBar, ProfileControls
        },
        data() {
            return {
            };
        }
    };
</script>
<template>
    <section class="alpha__prologue-secondary">
        <ClientControls />
        <SearchBar />
        <ProfileControls />
    </section>
</template>