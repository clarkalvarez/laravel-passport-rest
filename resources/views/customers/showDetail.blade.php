<!DOCTYPE html>
<html>
<head>
    <title>Customer Details</title>
    <style>
        .container {
            margin: 20px;
        }
        .details {
            margin-bottom: 20px;
        }
        .details p {
            font-size: 18px;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Customer Details</h1>
        <div class="details">
            <p><strong>ID:</strong> {{ $customer->id }}</p>
            <p><strong>First Name:</strong> {{ $customer->first_name }}</p>
            <p><strong>Last Name:</strong> {{ $customer->last_name }}</p>
            <p><strong>Age:</strong> {{ $customer->age }}</p>
            <p><strong>DOB:</strong> {{ $customer->dob ? \Carbon\Carbon::parse($customer->dob)->format('Y-m-d') : 'N/A' }}</p>
            <p><strong>Email:</strong> {{ $customer->email }}</p>
            <p><strong>Creation Date:</strong> {{ $customer->creation_date ? \Carbon\Carbon::parse($customer->creation_date)->format('Y-m-d H:i:s') : 'N/A' }}</p>
        </div>
        <a href="{{ url('/customers') }}">Back to Customer List</a>
    </div>
</body>
</html>
