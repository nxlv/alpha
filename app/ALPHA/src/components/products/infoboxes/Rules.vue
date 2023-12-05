<template>
    <div class="benefit" v-if="profile">
        Withdrawals
    </div>
</template>
<script>
export default {
    props: {
        profile: null,
        expanded: false
    },
    created() {
        console.log( 'rules', this.profile );
    },
    data() {
        return {
        };
    }
};
</script>