<script>
import { RouterLink } from 'vue-router';
import { useSetsStore } from '@/stores/sets';

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
    <div class="loading">
        <div class="bookshelf_wrapper">
            <ul class="books_list">
                <li class="book_item first"></li>
                <li class="book_item second"></li>
                <li class="book_item third"></li>
                <li class="book_item fourth"></li>
                <li class="book_item fifth"></li>
                <li class="book_item sixth"></li>
            </ul>
            <div class="shelf"></div>
        </div>
    </div>
</template>


