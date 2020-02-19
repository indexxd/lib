<template>
<div class="book-cards-container container">
    <book-list-item :book="book" :key="book.id" v-for="book in books"></book-list-item>
</div>
</template>

<script>
export default {
    data() {
        return {
            books: []
        }
    },
}
</script>