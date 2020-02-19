import { addClickEventsToArray, Routing } from "./app.js";
import axios from "axios";


(() => {
    let list = document.querySelectorAll(".book-card-controls__delete");
    
    addClickEventsToArray(list, async (e) => {
        const bookId = e.target.dataset.bookId;
        const url = Routing.generate("admin_books_delete", { id: bookId});


        const response = await axios.delete(url);
        
        if (response.status === 200) {
            window.location.reload();
        }
    });
})();