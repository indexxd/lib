<template>
    <div>
        <span v-if="activeReservationExists">You have reserved this book.</span>
        <span v-else>This book is {{ available ? "available" : "not available"}}.</span>
        <button @click="buttonClicked" v-bind:class="{
            'btn btn-lg': true, 
            'btn-success' : available && !activeReservationExists, 
            'btn-danger': activeReservationExists,
            'btn-outline-secondary disabled': !available && !activeReservationExists,
        }">{{ activeReservationExists ? "Cancel reservation" : "Reserve" }}</button>
    </div>
</template>

<script>
export default {
    data() {
        return {
            available: false,
            activeReservationExists: true,
            userExists: typeof(user) !== "undefined",
        }
    },

    created() {
        fetch(this.Routing.generate("reservation_controls", {id: book.id}), {
            method: "GET",
        })
        .then(response => response.json())
        .then(data => {
            this.available = data.available;
            this.activeReservationExists = data.activeReservationExists
        });
    },

    methods: {
        buttonClicked() {
            if (this.activeReservationExists) {
                let confirmation = confirm("Are you sure you want to cancel the reservation?");

                if (confirmation) {
                    fetch(this.Routing.generate("delete_book_reservation", {id: book.id}), {
                        method: "DELETE",
                    })
                    .then(res => res)
                    .then(res => {
                        if (res.status === 200) {
                            this.activeReservationExists = false;
                        }
                    });
                }

                return ;
            }
            
            if (!this.available) {
                return ;
            }
            
            if (!this.userExists) {
                alert("You must be logged in to make a reservation.");
                return ;
            }
                        
            fetch(this.Routing.generate("create_reservation", {id: book.id}), {
                method: "POST",
                headers: {
                    'Content-Type': 'application/json',
                },
            })
            .then(res => res)
            .then(res => {
                if (res.status === 200) {
                    alert("Reservation could not be created.");
                }
                else if (res.status === 201) {
                    this.activeReservationExists = true;
                }
            });
        }
    }
}
</script>