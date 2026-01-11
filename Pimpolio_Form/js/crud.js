$(document).ready(function () {

    $("#customerForm").on("submit", function (e) {
    e.preventDefault();

    if (!this.checkValidity()) {
        this.classList.add("was-validated");
        return;
    }

    // Password confirmation check
    let password = $("#password").val();
    let confirmPassword = $("#confirm_password").val();

    if (password !== confirmPassword) {
        alert("❌ Passwords do not match.");
        return; // stop submission
    }

    let formData = new FormData(this);
        formData.append("func_name", "CreateCustomerAccount");

        $.ajax({
            type: "POST",
            url: "crud.php",
            data: formData,
            contentType: false,
            processData: false,
            dataType: "json",
            success: function (res) {

                if (res.success) {
                    alert("✅ Account successfully created!");

                    setTimeout(() => {
                        window.location.href = "../login_signup.html";
                    }, 1200);

                } else {
                    alert("❌ " + res.message);
                }
            },
            error: function () {
                alert("❌ Server error. Please try again.");
            }
        });
    });

});
