document.addEventListener('DOMContentLoaded', () => {
    let currentEditId = null; // Tracks which DB user we are editing
    const registerBtn = document.getElementById('registerBtn');
    const tableBody = document.querySelector('#customerTable tbody');

    fetchUsers(); // Load from phpMyAdmin on start

    // Fetch All Users
    async function fetchUsers() {
        const res = await fetch('api.php');
        const users = await res.json();
        renderTable(users);
    }

    // Toggle Password Visibility
    window.toggleVisibility = (fieldId, eyeId) => {
        const field = document.getElementById(fieldId);
        const eyeBtn = document.getElementById(eyeId);
        field.type = field.type === "password" ? "text" : "password";
        eyeBtn.innerText = field.type === "password" ? "🙈" : "👁️";
    };

    // Submit Form (Register or Update)
    registerBtn.addEventListener('click', async () => {
        const username = document.getElementById('username').value.trim();
        const phone = document.getElementById('phone').value.trim();
        const role = document.getElementById('userRole').value;
        const password = document.getElementById('pass').value;

        const userData = { username, phone, role, password };
        if (currentEditId) userData.id = currentEditId; // Add ID if updating

        const res = await fetch('api.php', {
            method: 'POST',
            body: JSON.stringify(userData)
        });

        const result = await res.json();
        if (result.success) {
            alert(currentEditId ? "User Updated!" : "User Registered!");
            currentEditId = null;
            registerBtn.innerText = "Register Account";
            fetchUsers(); // Refresh list from DB
            document.getElementById('regForm').reset();
        } else {
            alert("Error: " + result.error);
        }
    });

    function renderTable(users) {
        tableBody.innerHTML = users.map(u => `
            <tr>
                <td>${u.user_id}</td>
                <td>${u.username}</td>
                <td><span class="role-badge role-${u.role.toLowerCase()}">${u.role}</span></td>
                <td>${u.phone_number}</td>
                <td>${u.date_created}</td>
                <td>
                    <button class="edit-btn" onclick="prepareEdit(${u.user_id}, '${u.username}', '${u.phone_number}', '${u.role}')">Edit</button>
                    <button class="del-btn" onclick="deleteUser(${u.user_id})">Delete</button>
                </td>
            </tr>
        `).join('');
    }

    // Set form to Edit mode
    window.prepareEdit = (id, username, phone, role) => {
        document.getElementById('username').value = username;
        document.getElementById('phone').value = phone;
        document.getElementById('userRole').value = role;
        currentEditId = id;
        registerBtn.innerText = "Update User Info";
        window.scrollTo(0, 0);
    };

    // Delete from Database
    window.deleteUser = async (id) => {
        if (confirm("Permanently delete this user from database?")) {
            await fetch(`api.php?id=${id}`, { method: 'DELETE' });
            fetchUsers();
        }
    };
});