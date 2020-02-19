import { Routing, addClickEventsToArray } from "./app.js";
import axios from "axios";

(() => {
    addClickEventsToArray(document.querySelectorAll("#admin-loans-table button"), async e => {
        const loanId = e.target.dataset.loanId;
        const penalty = e.target.dataset.loanPenalty;
        
        if (penalty !== undefined) {
            alert("Pay me " + penalty + " korun.");
        }
        
        await axios.patch(Routing.generate("return_book", { id: loanId }));

        await window.location.reload();
            
    });
})();