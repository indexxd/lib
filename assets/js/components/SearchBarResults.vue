<template>
    <div class="search-bar-results">
        <div v-for="book in books" v-bind:key="book.id">
            <div class="sb-result-row">
                <a :href="'book/' + book.slug">
                    <span class="title">{{book.title}}</span>
                    <span class="original-title">{{book.originalTitle}}</span>
                    <span class="author">{{book.author}}</span>
                    <span class="cover"><img v-bind:src="'uploads/covers/' + book.cover" alt=""></span>
                </a>
            </div>    
        </div>
    </div>
</template>


<script>

import {bus} from "../app.js";

export default {
    data() {
        return {
            books: [],
        }
    },

    created() {
        bus.$on("searchBarResults", data => this.books = data);
    },
    
    methods: {
        update() {
            
        }
    }
}
</script>