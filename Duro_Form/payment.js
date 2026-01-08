document.addEventListener("DOMContentLoaded", () => {

    // Load payment records
    function loadPayments(){
        fetch("payment_crud.php?action=read")
        .then(res => res.text())
        .then(html => {
            document.getElementById("paymentTableBody").innerHTML = html;
        });
    }
    loadPayments();

    const customerSearch  = document.getElementById('customerSearch');
    const customerResults = document.getElementById('customerResults');
    const customerIdInput = document.getElementById('customer_id');
    const orderSelect     = document.getElementById('orderSelect');

    // -----------------------------
    // Customer autocomplete
    // -----------------------------
    customerSearch.addEventListener('input', async () => {
        const query = customerSearch.value.trim();

        if (query.length === 0) {
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

    // -----------------------------
    // Load orders for selected customer
    // -----------------------------
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

});