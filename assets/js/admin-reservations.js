import { Routing, addClickEventsToArray } from "./app.js";


document.addEventListener("DOMContentLoaded", () => {
    
    addClickEventsToArray(document.querySelectorAll(".toggle-reservation-ready"), (e) => {
        let reservationId = e.target.dataset.reservationId;

        fetch(Routing.generate("toggle_reservation_ready", { id: reservationId }), {
            method: "PATCH",
        })
        .then(window.location.reload());

    })




    addClickEventsToArray(document.querySelectorAll("#admin-available-reservations-table .make-loan"), (e) => {
        let reservationId = e.target.dataset.reservationId;

        fetch(Routing.generate("create_loan_from_reservation", { id: reservationId }), {
            method: "POST",
        })
        .then(window.location.reload());
    });


});