<script setup>
    import { RouterView } from 'vue-router';

    import NavigationPrimary from '@/components/navigation/Primary.vue';
    import NavigationSecondary from '@/components/navigation/Secondary.vue';
</script>

<template>
    <main id="alpha" class="alpha">
        <input type="checkbox" id="alpha__prologue-collapse" class="alpha__prologue-collapse" value="1">
        <label class="alpha__prologue-collapse-control" for="alpha__prologue-collapse">Collapse</label>

        <header class="alpha__prologue">
            <NavigationPrimary />
            <NavigationSecondary />
        </header>
        <article>
            <RouterView />
        </article>
    </main>
</template>