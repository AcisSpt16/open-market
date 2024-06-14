<?php
$order_id = $_GET['order_id'] ?? 'default_id'; 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Details - Open Market</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            padding-top: 56px;
        }
        .navbar {
            background-color: #28a745; 
        }
        .navbar-brand, .nav-link {
            color: white !important;
        }
        .nav-link:hover {
            background-color: #218838; 
            border-radius: 5px;
        }
        .nav-item.active .nav-link {
            background-color: #218838; 
            border-radius: 5px;
        }
        .table-hover tbody tr:hover {
            background-color: #f1f1f1;
        }
        #map {
            height: 400px;
            width: 100%;
        }
        .status-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
    </style>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyB7ovfNqIIJ-YGKOTn2G74vwsooQ0s6NXk&callback=initMap" async defer></script>
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
                    <a class="nav-link" href="#" onclick="confirmSignOut()">Sign Out</a>
                </li>
            </ul>
        </div>
    </nav>

    <!-- Order Details -->
    <div class="container mt-5">
        <h2>Order Details</h2>
        <div class="card mb-4">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-8">
                        <h5 class="card-title">Order Details</h5>
                        <div id="order-info" data-order-id="<?= htmlspecialchars($order_id) ?>"></div>
                        <p class="card-text">Delivery Status: <span id="deliveryStatus"></span></p>
                        <h5>Change Order Status</h5>
                        <div class="btn-group" role="group" id="statusButtons">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <h5 class="card-title">Delivery Location</h5>
                        <div id="map"></div>
                    </div>
                </div>
                <!-- Products Table -->
                <h5 class="mt-4">Products</h5>
                <table class="table table-hover" id="products-table">
                    <thead>
                        <tr>
                            <th>Product ID</th>
                            <th>Product Name</th>
                            <th>Description</th>
                            <th>Quantity</th>
                            <th>Price</th>
                        </tr>
                    </thead>
                    <tbody>
          
                </table>
            </div>
        </div>
    </div>

    <!-- Confirmation Modal -->
    <div class="modal fade" id="confirmModal" tabindex="-1" role="dialog" aria-labelledby="confirmModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="confirmModalLabel">Confirm Status Change</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    Are you sure you want to change the order status to <span id="newStatus"></span>?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="confirmButton">Confirm</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Confirmation Modal for Sign Out -->
    <div class="modal fade" id="signOutModal" tabindex="-1" role="dialog" aria-labelledby="signOutModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="signOutModalLabel">Confirm Sign Out</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    Are you sure you want to sign out?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" onclick="signOut()">Sign Out</button>
                </div>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        let map, directionsService, directionsRenderer;
        window.initMap = initMap;
        document.addEventListener('DOMContentLoaded', function() {
            var orderId = document.getElementById('order-info').getAttribute('data-order-id');
            fetch(`http://localhost:8080/api/orders/${orderId}`)
                .then(response => response.json())
                .then(data => {
                    const orderInfo = document.getElementById('order-info');
                    orderInfo.innerHTML = `
                        <p>Order ID: ${data.id}</p>
                        <p>Buyer Name: ${data.buyer_name}</p>
                        <p>Email: ${data.email}</p>
                        <p>Contact Number: ${data.contact_number}</p>
                        <p>Order Date: ${data.order_date}</p>
                        <p>Delivery Address: ${data.delivery_address}</p>
                    `;
                    document.getElementById('deliveryStatus').innerText = data.order_status;
                    populateStatusButtons(data.order_status);
                    populateProductsTable(data.products);
                    getCurrentLocation(data.delivery_address); // Adjust this if necessary for how you handle map coordinates
                })
                .catch(error => console.error('Error loading order details:', error));
        });

        function initMap() {
            map = new google.maps.Map(document.getElementById("map"), {
                zoom: 7,
                center: { lat: 41.85, lng: -87.65 },
            });
            directionsService = new google.maps.DirectionsService();
            directionsRenderer = new google.maps.DirectionsRenderer();
            directionsRenderer.setMap(map);
        }

        function getCurrentLocation(addrs) {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(
                    (position) => {
                        const currentLocation = {
                            lat: position.coords.latitude,
                            lng: position.coords.longitude,
                        };
                        calculateAndDisplayRoute(currentLocation, addrs);
                    },
                    () => {
                        alert("Geolocation failed. Please enter your location manually.");
                    }
                );
            } else {
                alert("Geolocation is not supported by this browser.");
            }
        }

        function calculateAndDisplayRoute(currentLocation, addrs) {
            directionsService
                .route({
                    origin: currentLocation,
                    destination: { query: addrs },
                    travelMode: google.maps.TravelMode.DRIVING,
                })
                .then((response) => {
                    directionsRenderer.setDirections(response);
                })
                .catch((e) => alert("Directions request failed due to " + status));
        }

        function populateStatusButtons(currentStatus) {
            const statusButtons = document.getElementById('statusButtons');
            statusButtons.innerHTML = '';
            if (currentStatus === 'Pending') {
                statusButtons.innerHTML = `
                    <button type="button" class="btn btn-warning" onclick="confirmStatusChange('Ongoing')">Ongoing</button>
                    <button type="button" class="btn btn-success" onclick="confirmStatusChange('Delivered')">Delivered</button>
                `;
            } else if (currentStatus === 'Ongoing') {
                statusButtons.innerHTML = `
                    <button type="button" class="btn btn-success" onclick="confirmStatusChange('Delivered')">Delivered</button>
                `;
            }
            else if (currentStatus === 'Delivered') {
        statusButtons.innerHTML = `
            <button type="button" class="btn btn-success" disabled>Delivered</button>
        `;
    }
         
        }

        function confirmStatusChange(status) {
            document.getElementById('newStatus').innerText = status;
            $('#confirmModal').modal('show');
            document.getElementById('confirmButton').onclick = function() {
                changeStatus(status);
                $('#confirmModal').modal('hide');
            };
        }

        function changeStatus(status) {
            document.getElementById('deliveryStatus').innerText = status;
        
        }

        function confirmSignOut() {
    $('#signOutModal').modal('show');
}

function signOut() {
    window.location.href = '/login.php'; 
}

function populateProductsTable(products) {
    const productsTableBody = document.getElementById('products-table').querySelector('tbody');
    productsTableBody.innerHTML = '';
    products.forEach(product => {
        const row = `
            <tr>
                <td>${product.id}</td>
                <td>${product.product_name}</td>
                <td>${product.product_description}</td>
                <td>${product.quantity}</td>
                <td>${product.price}</td>
            </tr>
        `;
        productsTableBody.insertAdjacentHTML('beforeend', row);
    });
}
    </script>
</body>
</html>
