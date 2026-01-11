// Tabs
const tabButtons = document.querySelectorAll(".tab-btn");
const tabContents = document.querySelectorAll(".tab-content");

tabButtons.forEach(btn => {
    btn.addEventListener("click", () => {
        tabButtons.forEach(b => b.classList.remove("active"));
        tabContents.forEach(c => c.classList.remove("active"));

        btn.classList.add("active");
        document.getElementById(btn.dataset.tab).classList.add("active");
    });
});

// Go to customer registration
const goToCustomerForm = document.getElementById("goToCustomerForm");
if (goToCustomerForm) {
    goToCustomerForm.addEventListener("click", () => {
        window.location.href = "Pimpolio_Form/customer.php";
    });
}

// LOGIN FORM HANDLER
const loginForm = document.querySelector("#login form");
const loginError = document.getElementById("login-error");

loginForm.addEventListener("submit", async (e) => {
    e.preventDefault();

    const formData = new FormData(loginForm);

    const response = await fetch("login.php", {
        method: "POST",
        body: formData
    });

    const data = await response.json();

    if (data.success) {
        // redirect on success
        window.location.href = data.redirect;
    } else {
        // show red error text
        loginError.textContent = data.error;
    }
});
