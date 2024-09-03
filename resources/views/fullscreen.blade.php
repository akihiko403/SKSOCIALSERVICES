<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Receipt</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}"> <!-- Link to your CSS file if needed -->
    <style>
        body, html {
            margin: 0;
            padding: 0;
            height: 100%;
            width: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
            background-color: #f8f9fa; /* Optional: Background color */
        }

        .receipt-container {
            width: 100vw; /* Make the container span the full width of the viewport */
            height: 100vh; /* Make the container span the full height of the viewport */
            display: flex;
            justify-content: center;
            align-items: center;
            background-color: white;
            padding: 0;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.2);
            overflow: hidden; /* Prevent scrollbars */
        }

        .receipt-image {
            width: 100vw; /* Make the image span the full width of the viewport */
            height: 100vh; /* Make the image span the full height of the viewport */
            object-fit: contain; /* Scale the image to fit within the viewport while maintaining its aspect ratio */
            display: block; /* Remove any extra space below the image */
        }
    </style>
</head>
<body>
    <div class="receipt-container">
        <!-- Display the receipt image -->
        @if($receipt->receipt)
            <img src="{{ asset('storage/' . $receipt->receipt) }}" alt="Receipt Image" class="receipt-image">
        @endif
    </div>
</body>
</html>
