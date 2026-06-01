document.addEventListener("DOMContentLoaded", function () {
    const menuToggle = document.getElementById("menu-toggle");

    const navLinks = document.getElementById("nav-links");

    menuToggle.addEventListener("click", function () {
        navLinks.classList.toggle("show");

        
    });
});
document.querySelectorAll("#nav-links a").forEach(link => {
            link.addEventListener("click", () => {
                document.getElementById("nav-links").classList.remove("show");
            });
        });

// Function to show a specific form and hide others
function showForm(formId){
    document.querySelectorAll(".form-group").forEach(form => form.classList.remove("active"));
    document.getElementById(formId).classList.add("active");
}


// Bus Ticket Booking Logic
const seatsContainer = document.getElementById("seats-container");

if (seatsContainer) {


const summary = document.getElementById("summary");


const seatsInput = document.getElementById("selected_seats");

let selectedSeats = [];

function updatePricePreview() {

    const from = document.getElementById("from").value;
    const to = document.getElementById("to").value;

    // If route not selected → reset everything
    if (!from || !to) {
        selectedSeats = [];
        seatsInput.value = "";
        summary.innerText = "";
        document.getElementById("price-preview").innerText =
            "Total Price: 0 ETB";
        return;
    }

    // If no seats selected → show 0 price
    if (selectedSeats.length === 0) {
        document.getElementById("price-preview").innerText =
            "Total Price: 0 ETB";
        return;
    }

    // Calculate preview price
    const key1 = (from + "-" + to).toLowerCase();
const key2 = (to + "-" + from).toLowerCase();

const distance = routeDistances[key1] ?? routeDistances[key2] ?? 300;


    const total = distance * pricePerKm * selectedSeats.length;

    document.getElementById("price-preview").innerText =
        "Total Price: " + total + " ETB";
}




function renderSeats() {
    seatsContainer.innerHTML = "";
    selectedSeats = [];
    seatsInput.value = "";

    for (let i = 1; i <= 25; i++) {
        const seat = document.createElement("div");
        seat.classList.add("seat");
        seat.innerText = i;

        //  Already booked
        if (bookedSeats.includes(String(i))) {
            seat.classList.add("booked");
            seat.style.pointerEvents = "none";
        } else {
            seat.addEventListener("click", () => {
                if (seat.classList.contains("selected")) {
                    seat.classList.remove("selected");
                    selectedSeats = selectedSeats.filter(s => s !== i);
                } else {
                    seat.classList.add("selected");
                    selectedSeats.push(i);
                }

                summary.innerText =
                    "Selected Seats: " + selectedSeats.join(", ");

                seatsInput.value = selectedSeats.join(",");
                updatePricePreview();
            });
        }

        seatsContainer.appendChild(seat);
    }
}

// render seats on page load
renderSeats();


document.getElementById("from").addEventListener("change", updatePricePreview);
document.getElementById("to").addEventListener("change", updatePricePreview);


}

// 🔐 Show / Hide Admin Secret based on role
document.addEventListener("DOMContentLoaded", function () {
    const roleSelect = document.getElementById("role");
    const adminBox   = document.getElementById("admin-secret-box");
    const adminInput = document.getElementById("admin_secret");

    if (!roleSelect || !adminBox) return;

    roleSelect.addEventListener("change", function () {
        if (this.value === "admin") {
            adminBox.style.display = "block";
            adminInput.required = true;
        } else {
            adminBox.style.display = "none";
            adminInput.required = false;
            adminInput.value = "";
        }
    });
});







