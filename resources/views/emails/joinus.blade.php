<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    {{-- <title>Join Us Request</title> --}}
</head>
<body style="font-family: Arial, sans-serif; color: #333; line-height: 1.6;">
    <p>Hello Admin,</p>

    <p>You have received a new {{ ucfirst($data->type) }} registration request with the following details:</p>

    <ul>
    <li><strong>Type:</strong> {{ ucfirst($data->type) }}</li>
    <li><strong>Full Name:</strong> {{ strtoupper($data->first_name) }} {{ strtoupper($data->last_name) }}</li>
    <li><strong>Email:</strong> {{ $data->email }}</li>
    <li><strong>Phone Number:</strong> {{ $data->phone_number }}</li>
    <li><strong>Message:</strong> {{ $data->message ?: 'N/A' }}</li>
</ul>
    <p>Please review and take the necessary action.</p>

    <p><strong>Best regards,<br>Gomeds</strong></p>
</body>
</html>
