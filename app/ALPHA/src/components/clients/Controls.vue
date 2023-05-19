<script>
    import { RouterLink } from 'vue-router';
    import { useCommonStore } from '@/stores/common';

    export default {
        components: {
            RouterLink
        },
        methods: {
            show_manager() {
                const common = useCommonStore();

                common.commit( 'modal', 'client_manager' );
            }
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
            <li data-type="change"><a href="javascript:;" title="Change Client" v-on:click="show_manager()">Change Client</a></li>
            <li data-type="edit"><a href="javascript:;" title="Edit Client" v-on:click="show_manager()">Edit Client</a></li>
            <li data-type="add"><a href="javascript:;" title="Add Client" v-on:click="show_manager()">Add Client</a></li>
        </menu>
    </aside>
</template>
