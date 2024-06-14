<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile - Open Market</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 50%;
            margin: 0 auto;
            background: #fff;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            margin-top: 50px;
        }
        h2 {
            text-align: center;
            margin-bottom: 20px;
        }
        .profile-form {
            display: flex;
            flex-direction: column;
        }
        .profile-form label {
            margin-bottom: 5px;
            font-weight: bold;
        }
        .profile-form input {
            margin-bottom: 15px;
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .form-group {
            display: flex;
            justify-content: space-between;
        }
        .form-group div {
            flex: 1;
            margin-right: 10px;
        }
        .form-group div:last-child {
            margin-right: 0;
        }
        .profile-form button {
            padding: 10px;
            font-size: 16px;
            color: #fff;
            background-color: #4CAF50;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        .profile-form button:hover {
            background-color: #45a049;
        }
        .nav-item.active .nav-link {
            background-color: #218838; /* Darker green when active */
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark fixed-top" style="background-color: #28a745;">
        <a class="navbar-brand" href="#">Open Market</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link" href="home.php">Orders</a>
                </li>
                <li class="nav-item active">
                    <a class="nav-link" href="profile.php">Profile</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#" onclick="confirmSignOut()">Sign Out</a>
                </li>
            </ul>
        </div>
    </nav>

    <div class="container">
        <h2>Profile Page</h2>
        <form class="profile-form" id="profileForm">
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" value="">

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" value="">

            <label for="phone_number">Phone Number:</label>
            <input type="text" id="phone_number" name="phone_number" value="">

            <label for="street">Street:</label>
            <input type="text" id="street" name="street" value="">

            <label for="suburb">Suburb:</label>
            <input type="text" id="suburb" name="suburb" value="">

            <div class="form-group">
                <div>
                    <label for="city">City:</label>
                    <input type="text" id="city" name="city" value="">
                </div>
                <div>
                    <label for="zip">ZIP Code:</label>
                    <input type="text" id="zip" name="zip" value="">
                </div>
            </div>

            <button type="submit">Update Profile</button>
        </form>
    </div>
    
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
    document.addEventListener('DOMContentLoaded', function() {
        const token = localStorage.getItem('token');
        
        // Fetch user profile
        fetch('http://localhost:8080/api/profile', {
            method: 'GET',
            headers: {
                'Authorization': `Bearer ${token}`,
                'Content-Type': 'application/json'
            },
        })
        .then(response => response.json())
        .then(data => {
            if (data.user) {
                console.log('Profile data:', data.user);
                document.getElementById('name').value = data.user.name || '';
                document.getElementById('email').value = data.user.email || '';
                document.getElementById('phone_number').value = data.user.phone_number || '';
                document.getElementById('street').value = data.user.street || '';
                document.getElementById('suburb').value = data.user.suburb || '';
                document.getElementById('city').value = data.user.city || '';
                document.getElementById('zip').value = data.user.zip || '';
            } else {
                alert('Failed to fetch profile data.');
            }
        })
        .catch(error => {
            console.error('Error fetching profile:', error);
            alert('An error occurred while fetching profile data.');
        });

        document.getElementById('profileForm').addEventListener('submit', function(e) {
            e.preventDefault();

            const name = document.getElementById('name').value;
            const email = document.getElementById('email').value;
            const phone_number = document.getElementById('phone_number').value;
            const street = document.getElementById('street').value;
            const suburb = document.getElementById('suburb').value;
            const city = document.getElementById('city').value;
            const zip = document.getElementById('zip').value;

            fetch('http://localhost:8080/api/profile', {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'Authorization': `Bearer ${token}`,
                },
                body: JSON.stringify({ name, email, phone_number, street, suburb, city, zip }),
            })
            .then(response => response.json())
            .then(data => {
                alert('Profile updated successfully');
            })
            .catch(error => console.error('Error:', error));
        });
    });

    function confirmSignOut() {
        $('#signOutModal').modal('show');
    }

    function signOut() {
        // Clear the token from local storage and redirect to the login page after signing out
        localStorage.removeItem('token');
        window.location.href = '/login.html'; // Change this URL to your actual login page URL
    }
    </script>
</body>
</html>
