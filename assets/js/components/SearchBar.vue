<template>
    <div class="main-search-container">
        <div class="input-group lg-8">
            <input @input="inputChanged" v-model="searchQuery" type="text"  class="form-control" placeholder="harry..." aria-describedby="button-addon2">
        </div>
    </div>
</template>


<script>

import {bus} from "../app.js";

export default {
    data() {
        return {
            searchQuery: ""
        };
    },

    methods: {
        inputChanged() {
            fetch(this.Routing.generate("search_results"), {
                method: "POST",
                body: JSON.stringify({
                    value: this.searchQuery,
                }),
                headers: {
                    'Content-Type': 'application/json',
                },
            }) 
            .then(response => response.json())
            .then(data => {
                bus.$emit("searchBarResults", data);
            });
        }
    }
}
</script>