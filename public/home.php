<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Open Market - Orders</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            padding-top: 56px;
        }
        .navbar {
            background-color: #28a745; /* Green color */
        }
        .navbar-brand, .nav-link {
            color: white !important;
        }
        .nav-link:hover {
            background-color: #218838; /* Darker green on hover */
            border-radius: 5px;
        }
        .nav-item.active .nav-link {
            background-color: #218838; /* Darker green when active */
            border-radius: 5px;
        }
        .table-hover tbody tr:hover {
            background-color: #f1f1f1;
        }
    </style>
</head>
<body>
    <!-- Navigation Bar -->
    <nav class="navbar navbar-expand-lg navbar-dark fixed-top">
        <a class="navbar-brand" href="#">Open Market</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item active">
                    <a class="nav-link" href="home.php">Orders</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="profile.php">Profile</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Sign Out</a>
                </li>
            </ul>
        </div>
    </nav>

    <!-- Orders Table -->
    <div class="container mt-5">
        <h2>Orders</h2>
        <table class="table table-hover">
            <thead class="thead-dark">
                <tr>
                    <th scope="col">ID</th>
                    <th scope="col">Buyer Name</th>
                    <th scope="col">Email Address</th>
                    <th scope="col">Phone Number</th>
                    <th scope="col">Order Date</th>
                    <th scope="col">Delivery Status</th>
                </tr>
            </thead>
            <tbody id="orderDataTable">
                

                <!-- Add more rows as needed -->
            </tbody>
        </table>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
    fetch('http://localhost:8080/api/orders')
        .then(response => {
            if (!response.ok) {  // Check if response is OK (status 200)
                response.text().then(text => { throw new Error(text) }); // Throw error with response text if not OK
            }
            return response.json();
        })
        .then(data => {
            const tableBody = document.getElementById('orderDataTable');
            data.forEach(row => {
                const tr = document.createElement('tr');
                tr.innerHTML = `
                    <td>${row.id}</td>
                    <td>${row.buyer_name}</td>
                    <td>${row.email}</td>
                    <td>${row.contact_number}</td>
                    <td>${row.order_date}</td>
                    <td>${row.order_status}</td>
                    
                `;
                tr.addEventListener('click', () => {
                    window.location.href = `orderDetails.php?order_id=${row.id}`;
                });
                tableBody.appendChild(tr);
            });
        })
        .catch(error => console.error('Error fetching orders:', error));
});
        
    </script>
</body>
</html>

