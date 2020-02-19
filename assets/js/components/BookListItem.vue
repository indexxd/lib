<template>
    <div class="card book-item mb-2">
        <div class="card-body">   
            <div><img :src="'uploads/covers/' + book.cover" alt="anythin"></div>   
            <div class="card-title">{{book.title}}</div>   
        </div>
    </div>
</template>

<script>
export default {
    props:  {
        book: {
            title: String,
            titleOriginal: String,
            author: Object,
            cover: String,
            currentQuantity: Number,
        }
    }
}
</script>