<script>
    import { RouterLink } from 'vue-router';
    import { useCommonStore } from '@/stores/common';

    export default {
        components: {
            RouterLink
        },
        methods: {
            show_manager( view ) {
                const common = useCommonStore();

                console.log( 'view', view );

                common.commit( 'view', view );
                common.commit( 'modal', 'profile_manager' );
            },
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
            <li data-type="profile"><a href="javascript:;" title="Edit Profile" v-on:click="show_manager( null )">Edit Profile</a></li>
            <li><a href="#/logout">Logout</a></li>
        </ol>
    </aside>
</template>
