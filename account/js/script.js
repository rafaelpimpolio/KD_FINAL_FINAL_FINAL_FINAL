// Bootstrap 5 form validation
(() => {
  'use strict'
  const forms = document.querySelectorAll('.needs-validation');
  Array.from(forms).forEach(form => {
    form.addEventListener('submit', event => {
      if (!form.checkValidity()) {
        event.preventDefault();
        event.stopPropagation();
      }
      form.classList.add('was-validated');
    }, false)
  });
})();

$('#customerForm, #employeeForm').on('submit', function(e){
    if(!this.checkValidity()){
        e.preventDefault();
        e.stopPropagation();
        this.classList.add('was-validated');
        return false;
    }

    e.preventDefault();
    let form = $(this);
    let url = form.attr('id') === 'customerForm' ? 'customer_crud.php' : 'employee_crud.php';

    $.post(url, form.serialize(), function(res){
        if(form.attr('id') === 'customerForm'){
            $('#customerTable').html(res);
            $('#createCustomerModal').modal('hide');
        } else {
            $('#employeeTable').html(res);
            $('#createEmployeeModal').modal('hide');
        }
        form[0].reset();
        form.removeClass('was-validated');
    });
});

// Edit Customer
$(document).on('click', '.editCustomerBtn', function(){
    let id = $(this).data('id');

    $.post('customer_crud.php', {action:'fetch', id:id}, function(res){
        let data = JSON.parse(res);

        let modal = $('#createCustomerModal');
        let form = modal.find('form');

        form.find('input[name="action"]').val('update');
        form.find('input[name="id"]').remove();
        form.append('<input type="hidden" name="id" value="'+id+'">');

        modal.find('input[name="username"]').val(data.username);
        modal.find('input[name="password"]').val(''); // optional
        modal.find('input[name="first_name"]').val(data.first_name);
        modal.find('input[name="last_name"]').val(data.last_name);
        modal.find('input[name="phone_number"]').val(data.phone_number);
        modal.find('input[name="email"]').val(data.email);
        modal.find('input[name="barangay"]').val(data.barangay);
        modal.find('input[name="city_municipality"]').val(data.city_municipality);
        modal.find('input[name="province"]').val(data.province);
        modal.find('input[name="postal_code"]').val(data.postal_code);

        modal.modal('show');
    });
});

// Edit Employee
$(document).on('click', '.editEmployeeBtn', function(){
    let id = $(this).data('id');

    $.post('employee_crud.php', {action:'fetch', id:id}, function(res){
        let data = JSON.parse(res);

        let modal = $('#createEmployeeModal');
        let form = modal.find('form');

        form.find('input[name="action"]').val('update');
        form.find('input[name="id"]').remove();
        form.append('<input type="hidden" name="id" value="'+id+'">');

        modal.find('input[name="username"]').val(data.username);
        modal.find('input[name="password"]').val(''); // optional
        modal.find('input[name="first_name"]').val(data.first_name);
        modal.find('input[name="middle_initial"]').val(data.middle_initial);
        modal.find('input[name="last_name"]').val(data.last_name);

        modal.modal('show');
    });
});

function deleteCustomer(id){
    if(confirm('Are you sure?')) $.post('customer_crud.php',{action:'delete',id:id},res=>$('#customerTable').html(res));
}
function deleteEmployee(id){
    if(confirm('Are you sure?')) $.post('employee_crud.php',{action:'delete',id:id},res=>$('#employeeTable').html(res));
}

// Edit User
$(document).on('click', '.editUserBtn', function(){
    let id = $(this).data('id');
    let modal = $('#editUserModal');
    let form = modal.find('form');

    $.post('user_crud.php', {action:'fetch', id:id}, function(res){
        let data = JSON.parse(res);
        form.find('input[name="id"]').val(data.user_id);
        form.find('input[name="username"]').val(data.username);
        form.find('input[name="password"]').val('');
        form.find('select[name="role"]').val(data.role);
        form.find('select[name="status"]').val(data.status);

        modal.modal('show');
    });
});

// Delete User
function deleteUser(id){
    if(confirm('Are you sure?')) $.post('user_crud.php',{action:'delete',id:id},res=>{
        $('#userTable').html(res);
    });
}

// Submit form
$('#userForm').on('submit', function(e){
    e.preventDefault();
    let form = $(this);
    $.post('user_crud.php', form.serialize(), function(res){
        $('#userTable').html(res);
        $('#editUserModal').modal('hide');
    });
});

function attachPasswordValidation(form) {
    const password = form.querySelector('input[name="password"]');
    const confirm = form.querySelector('input[name="confirm_password"]');

    if (!password || !confirm) return;

    function validate() {
        // If password is empty (editing user), allow it
        if (password.value === "") {
            confirm.setCustomValidity("");
            return;
        }

        if (password.value !== confirm.value) {
            confirm.setCustomValidity("Passwords do not match");
        } else {
            confirm.setCustomValidity("");
        }
    }

    password.addEventListener("input", validate);
    confirm.addEventListener("input", validate);
}

attachPasswordValidation(document.getElementById("customerForm"));
attachPasswordValidation(document.getElementById("employeeForm"));
attachPasswordValidation(document.getElementById("userForm"));

