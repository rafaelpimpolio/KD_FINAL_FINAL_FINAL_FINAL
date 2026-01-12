document.addEventListener("DOMContentLoaded", () => {

    const paymentForm = document.getElementById("paymentForm");
    const tableBody = document.getElementById("paymentTableBody");

    function loadPayments(){
        fetch("payment_crud.php?action=read")
        .then(res => res.text())
        .then(html => tableBody.innerHTML = html);
    }
    loadPayments();

    // Handle form submit via AJAX
    paymentForm.addEventListener("submit", e => {
        e.preventDefault();
        const formData = new FormData(paymentForm);

        const action = formData.get("payment_id") ? "update" : "create";

        fetch(`payment_crud.php?action=${action}`, {
            method: "POST",
            body: formData
        })
        .then(res => res.text())
        .then(msg => {
            // Updated: only show clean message, no HTML stripping
            alert(msg);
            paymentForm.reset();
            loadPayments();
        })
        .catch(err => console.error(err));
    });

    // Customer autocomplete
    const customerSearch  = document.getElementById('customerSearch');
    const customerResults = document.getElementById('customerResults');
    const customerIdInput = document.getElementById('customer_id');
    const orderSelect     = document.getElementById('orderSelect');

    customerSearch.addEventListener('input', async () => {
        const query = customerSearch.value.trim();
        if (!query.length) {
            customerResults.innerHTML = '';
            orderSelect.innerHTML = `<option value="">-- Select order --</option>`;
            return;
        }

        const res = await fetch(`customer_search.php?q=${encodeURIComponent(query)}`);
        const customers = await res.json();

        customerResults.innerHTML = '';
        customers.forEach(customer => {
            const item = document.createElement('a');
            item.href = "#";
            item.className = "list-group-item list-group-item-action";
            item.textContent = customer.full_name;

            item.addEventListener('click', e => {
                e.preventDefault();
                customerSearch.value = customer.full_name;
                customerIdInput.value = customer.customer_id;
                customerResults.innerHTML = '';
                loadCustomerOrders(customer.customer_id);
            });

            customerResults.appendChild(item);
        });
    });

    document.addEventListener('click', e => {
        if (!customerResults.contains(e.target) && e.target !== customerSearch) {
            customerResults.innerHTML = '';
        }
    });

    async function loadCustomerOrders(customerId) {
        orderSelect.innerHTML = `<option value="">Loading orders...</option>`;
        try {
            const res = await fetch(`fetch_customer_orders.php?customer_id=${customerId}`);
            const orders = await res.json();
            if (!orders.length) {
                orderSelect.innerHTML = `<option value="">No FOR PAYMENT orders</option>`;
                return;
            }

            orderSelect.innerHTML = `<option value="">-- Select order --</option>`;
            orders.forEach(order => {
                const opt = document.createElement('option');
                opt.value = order.order_id;
                opt.textContent =
                    `Order #${order.order_id} | Inquiry ${order.inquiry_id} | ₱${order.total_amount} | ${order.created_at}`;
                orderSelect.appendChild(opt);
            });
        } catch (err) {
            console.error(err);
            orderSelect.innerHTML = `<option value="">Error loading orders</option>`;
        }
    }

    // Delegate table actions (Delete & Edit)
    tableBody.addEventListener("click", e => {

        // Delete button
        if(e.target.matches(".btn-danger")){
            e.preventDefault();
            if(!confirm("Delete this payment?")) return;

            fetch(e.target.href)
            .then(res => res.text())
            .then(msg => {
                alert(msg); // <-- clean alert
                loadPayments();
            })
            .catch(err => console.error(err));
        }

        // Edit button
        if (e.target.classList.contains("btn-edit")) {
            e.preventDefault();
            const payment = JSON.parse(e.target.dataset.payment);

            paymentForm.payment_id.value = payment.payment_id;
            document.getElementById("customerSearch").value = payment.customer_name;
            document.getElementById("customer_id").value = payment.customer_id;

            loadCustomerOrders(payment.customer_id).then(() => {
                document.getElementById("orderSelect").value = payment.order_id;
            });

            paymentForm.employee_id.value = payment.employee_id;
            paymentForm.method_of_payment.value = payment.method_of_payment;
            paymentForm.total_amount.value = payment.total_amount;
            paymentForm.downpayment.value = payment.downpayment;
            paymentForm.balance.value = payment.balance;
            paymentForm.date.value = payment.date;
            paymentForm.status.value = payment.status;

            paymentForm.scrollIntoView({ behavior: 'smooth' });
        }
    });

    // Logout confirmation
    window.confirmLogout = function(event) {
        event.preventDefault();
        if(confirm('Are you sure you want to logout?')) {
            window.location.href = '../login_signup.html';
        }
    };

});
