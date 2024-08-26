<!DOCTYPE html>
<html>
<head>
    <title>Customer List</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
    <style>
        table {
            border-collapse: collapse;  
            width: 100%; 
        }
        th, td {
            border: 1px solid #dddddd; 
            padding: 8px;  
            text-align: left; 
        }
        th {
            background-color: #f2f2f2; 
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;  
        }
        tr:hover {
            background-color: #f1f1f1; 
        }
        .btn-delete, .btn-edit {
            background-color: #ff4d4d;  
            color: white;  
            border: none;  
            padding: 5px 10px; 
            cursor: pointer; 
            margin-right: 5px;  
        }
        .btn-edit {
            background-color: #4CAF50;  
        } 
        .modal {
            display: none;  
            position: fixed;  
            z-index: 1; 
            left: 0;
            top: 0;
            width: 100%;  
            height: 100%;  
            overflow: auto;  
            background-color: rgb(0,0,0);  
            background-color: rgba(0,0,0,0.4); 
        }
        .modal-content {
            background-color: #fefefe;
            margin: 15% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
        }
        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }
        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }
    </style>
    
<body>

    <div>
        <h1>Login/Register</h1>
        <div id="auth-area"></div>
    </div>

    <div id="customer-data" style="display:none;">  
        <h2>Create New Customer</h2>
        <hr/>
        <form id="create-customer-form">
            <label for="first_name">First Name:</label>
            <input type="text" id="first_name" name="first_name" required>
            <br>
            <label for="last_name">Last Name:</label>
            <input type="text" id="last_name" name="last_name" required>
            <br>
            <label for="age">Age:</label>
            <input type="number" id="age" name="age" required>
            <br>
            <label for="dob">Date of Birth:</label>
            <input type="date" id="dob" name="dob" required>
            <br>
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>
            <br>
            <button type="submit">Create Customer</button>
        </form>

        <h1>Customer List</h1>
        <hr/>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Age</th>
                    <th>Date of Birth</th>
                    <th>Email</th>
                    <th>Creation Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="customer-table-body"></tbody>
        </table>
    </div>
     <div id="editModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Edit Customer</h2>
            <form id="edit-customer-form">
                <input type="hidden" id="edit-customer-id">
                <label for="edit-first_name">First Name:</label>
                <input type="text" id="edit-first_name" name="first_name" required>
                <br>
                <label for="edit-last_name">Last Name:</label>
                <input type="text" id="edit-last_name" name="last_name" required>
                <br>
                <label for="edit-dob">Date of Birth:</label>
                <input type="date" id="edit-dob" name="dob" required>
                <br>
                <label for="edit-email">Email:</label>
                <input type="email" id="edit-email" name="email" required>
                <br>
                <button type="submit">Update Customer</button>
            </form>
        </div>
    </div>
    <script>
        $(document).ready(function() {
            function isLoggedIn() {
                return !!localStorage.getItem('accessToken');
            }

            function updateAuthArea() {
                if (isLoggedIn()) {
                    let userName = localStorage.getItem('userName');
                    $('#auth-area').html(`
                        <div id="user-info">
                            <p>Welcome, ${userName}!</p>
                            <form id="logout-form" method="POST">
                                <button type="submit">Logout</button>
                            </form>
                        </div>
                    `);
                    loadCustomerData(); 
                } else {
                    $('#auth-area').html(`
                        <h2>Login</h2>
                        <form id="login-form" method="POST">
                            <label for="email">Email:</label>
                            <input type="email" id="email" name="email" required>
                            <br>
                            <label for="password">Password:</label>
                            <input type="password" id="password" name="password" required>
                            <br>
                            <button type="submit">Login</button>
                        </form>
                        <h2>Register</h2>
                        <form id="register-form" method="POST">
                            <label for="name">Name:</label>
                            <input type="text" id="name" name="name" required>
                            <br>
                            <label for="email">Email:</label>
                            <input type="email" id="email" name="email" required>
                            <br>
                            <label for="password">Password:</label>
                            <input type="password" id="password" name="password" required>
                            <br>
                            <label for="password_confirmation">Confirm Password:</label>
                            <input type="password" id="password_confirmation" name="password_confirmation" required>
                            <br>
                            <button type="submit">Register</button>
                        </form>
                    `);
                }
            }

            function loadCustomerData() {
                $.ajax({
                    url: '{{ url("api/customers") }}',
                    method: 'GET',
                    headers: {
                        'Authorization': 'Bearer ' + localStorage.getItem('accessToken')
                    },
                    success: function(response) {
                        $('#customer-data').show();
                        let customerRows = '';
                        response.forEach(function(customer) {
                            customerRows += `
                                <tr data-id="${customer.id}">
                                    <td>${customer.id}</td>
                                    <td>${customer.first_name}</td>
                                    <td>${customer.last_name}</td>
                                    <td>${customer.age}</td>
                                    <td>${customer.dob ? customer.dob : 'N/A'}</td>
                                    <td>${customer.email}</td>
                                    <td>${customer.creation_date}</td>
                                    <td>
                                        <button class="btn-edit">Edit</button>
                                        <button class="btn-delete">Delete</button>
                                    </td>
                                    <td><a href="{{ url('customers') }}/${customer.id}">View Details</a></td>
                                </tr>
                            `;
                        });
                        $('#customer-table-body').html(customerRows);
                    },
                    error: function(xhr) {
                        alert('Failed to load customer data: ' + xhr.responseJSON.error);
                    }
                });
            }

            $(document).on('submit', '#login-form', function(event) {
                event.preventDefault();
                $.ajax({
                    url: '{{ url("api/login") }}',
                    method: 'POST',
                    data: $(this).serialize(),
                    success: function(response) {
                        if (response.status === 'success') {
                            localStorage.setItem('accessToken', response.token);
                            localStorage.setItem('userName', response.user_info.name);
                            updateAuthArea();  
                        } else {
                            alert(response.message);
                        }
                    },
                    error: function(xhr) {
                        alert('Login failed: ' + xhr.responseJSON.message);
                    }
                });
            });

            $(document).on('submit', '#register-form', function(event) {
                event.preventDefault();
                $.ajax({
                    url: '{{ url("api/register") }}',
                    method: 'POST',
                    data: $(this).serialize(),
                    success: function(response) {
                        if (response.status === 'success') {
                            alert('Registration successful. Please login.');
                            $('#register-form')[0].reset();
                        } else {
                            alert(response.message);
                        }
                    },
                    error: function(xhr) {
                        alert('Registration failed: ' + xhr.responseJSON.message);
                    }
                });
            });

            $(document).on('submit', '#logout-form', function(event) {
                event.preventDefault();
                $.ajax({
                    url: '{{ url("api/logout") }}', 
                    method: 'POST',
                    headers: {
                        'Authorization': 'Bearer ' + localStorage.getItem('accessToken')
                    },
                    success: function(response) {
                        localStorage.removeItem('accessToken');
                        localStorage.removeItem('userName');
                        $('#customer-data').hide();
                        updateAuthArea(); 
                    },
                    error: function(xhr) {
                        alert('Logout failed: ' + xhr.responseJSON.message);
                    }
                });
            });

            $(document).on('submit', '#create-customer-form', function(event) {
                event.preventDefault();
                $.ajax({
                    url: '{{ url("api/customers") }}',
                    method: 'POST',
                    data: $(this).serialize(),
                    headers: {
                        'Authorization': 'Bearer ' + localStorage.getItem('accessToken')
                    },
                    success: function(response) {
                        alert('Customer created successfully!');
                        $('#create-customer-form')[0].reset();
                        loadCustomerData();   
                    },
                    error: function(xhr) {
                        alert('Failed to create customer: ' + xhr.responseJSON.message);
                    }
                });
            });

            $(document).on('submit', '#edit-customer-form', function(event) {
                event.preventDefault();
                let customerId = $('#edit-customer-id').val();
                $.ajax({
                    url: '{{ url("api/customers") }}/' + customerId,
                    method: 'PUT',
                    data: $(this).serialize(),
                    headers: {
                        'Authorization': 'Bearer ' + localStorage.getItem('accessToken')
                    },
                    success: function(response) {
                        alert('Customer updated successfully!');
                        loadCustomerData();  
                        $('#editModal').hide();
                    },
                    error: function(xhr) {
                        alert('Failed to update customer: ' + xhr.responseJSON.message);
                    }
                });
            });

            $(document).on('click', '.btn-delete', function() {
                let customerId = $(this).closest('tr').data('id');
                if (confirm('Are you sure you want to delete this customer?')) {
                    $.ajax({
                        url: '{{ url("api/customers") }}/' + customerId,
                        method: 'DELETE',
                        headers: {
                            'Authorization': 'Bearer ' + localStorage.getItem('accessToken')
                        },
                        success: function(response) {
                            alert('Customer deleted successfully!');
                            loadCustomerData();  
                        },
                        error: function(xhr) {
                            alert('Failed to delete customer: ' + xhr.responseJSON.message);
                        }
                    });
                }
            });

            $(document).on('click', '.btn-edit', function() {
                let row = $(this).closest('tr');
                let customerId = row.data('id');
                $.ajax({
                    url: '{{ url("api/customers") }}/' + customerId,
                    method: 'GET',
                    headers: {
                        'Authorization': 'Bearer ' + localStorage.getItem('accessToken')
                    },
                    success: function(response) {
                        let customer = response;
                        $('#edit-customer-id').val(customer.id);
                        $('#edit-first_name').val(customer.first_name);
                        $('#edit-last_name').val(customer.last_name);
                        $('#edit-dob').val(customer.dob ? customer.dob.split('T')[0] : '');
                        $('#edit-email').val(customer.email);
                        $('#editModal').show();
                    },
                    error: function(xhr) {
                        alert('Failed to load customer data for editing: ' + xhr.responseJSON.message);
                    }
                });
            });

            $(document).on('click', '.close', function() {
                $('#editModal').hide(); 
            });
            updateAuthArea();
        });
    </script>
</body>
</html>