import { addClickEventsToArray } from "./app.js";

(() => {
    addClickEventsToArray(document.querySelectorAll("#reservations-table button"), (e) => {
        const reservationId = e.target.dataset.reservationId;
        
        fetch(Routing.generate("delete_reservation", { id: reservationId }), {
            method: "DELETE",
        }).then(window.location.reload());
        
    });
})();