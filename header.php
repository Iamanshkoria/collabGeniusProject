<?php
require_once('auth.php');


// Check if the mode is set in cookies, if not, set the default mode
$mode = isset($_COOKIE['mode']) ? $_COOKIE['mode'] : 'light'; // Default mode is light
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Front Page</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css" rel="stylesheet"> <!-- Bootstrap Icons Library -->
    <link rel="stylesheet" href="index.css">
    <style>
    /* General styles for dark mode */
    .dark-mode {
        background-color: #1a1a1a;
        color: #ffffff;
    }
    .dark-mode a {
        color: #8ab4f8;
    }
    .dark-mode a:hover {
        color: #e8eaed;
    }
    .dark-mode .btn {
        background-color: #1a1110;
        color: #ffffff;
        border: 1px solid #616161;
    }
    .dark-mode .btn:hover {
        background-color: #616161;
    }
    .dark-mode .navbar-nav .nav-link {
        color: #b0bec5;
    }
    .dark-mode .navbar-nav .nav-link:hover {
        color: #ffffff;
    }
    /* Styles for dark mode icon */
    .dark-mode .mode-switcher i {
        color: #f6e58d; /* Icon color in dark mode */
    }

    /* General styles for light mode */
    .light-mode {
        background-color: #ffffff;
        color: #000000;
    }
    .light-mode a {
        color: #007bff;
    }
    .light-mode a:hover {
        color: #0056b3;
    }
    .light-mode .btn {
        background-color: #e2e6ea;
        color: #000000;
        border: 1px solid #dae0e5;
    }
    .light-mode .btn:hover {
        background-color: #dae0e5;
    }
    .light-mode .navbar-nav .nav-link {
        color: #495057;
    }
    .light-mode .navbar-nav .nav-link:hover {
        color: #000000;
    }
    /* Styles for light mode icon */
    .light-mode .mode-switcher i {
        color: #130f40; /* Icon color in light mode */
    }
</style> 

</head>
<body class="<?php echo $mode; ?>">

<header class="magic-navbar">
  <nav class="navbar navbar-expand-lg <?php echo $mode === 'dark' ? 'navbar-dark bg-dark' : 'navbar-light bg-light'; ?>">
    <div class="container-fluid">
        <a class="navbar-brand magic-navbar-brand" href="#">Collab-Genius</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
          <ul class="navbar-nav me-auto magic-navbar-nav">
            <li class="nav-item">
              <a class="nav-link" href="home.php"><i class="bi bi-house-door-fill"></i> Home</a>
            </li>
            <!-- Other menu items -->
            <li class="nav-item">
              <a class="nav-link" href="idea.php"><i class="bi bi-search"></i> Browse Ideas/Products</a>
            </li>
            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown1" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="bi bi-trophy"></i> Competitions
              </a>
              <ul class="dropdown-menu" aria-labelledby="navbarDropdown1">
                <li><a class="dropdown-item" href="create_comptition.php">Create Competition</a></li>
                <li><a class="dropdown-item" href="dashboard.php?competition_id=1">Competition Details</a></li>
                <li><a class="dropdown-item" href="result.php?competition_id=1">View Results</a></li>
                <li><a class="dropdown-item" href="vote.php?competition_id=1">Vote Dashboard</a></li>
              </ul>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="about_us.php"><i class="bi bi-info-circle"></i> About Us</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="contact_us.html"><i class="bi bi-telephone-fill"></i> Contact Us</a>
            </li>
          </ul>
          <ul class="navbar-nav">
          
            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown2" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="bi bi-person-fill"></i> Profile Management
              </a>
              <ul class="dropdown-menu" aria-labelledby="navbarDropdown2">
                <li><a class="dropdown-item" href="create_profile.php">Create Profile</a></li>
                <li><a class="dropdown-item" href="edit_profile.php">Edit Profile</a></li>
                <li><a class="dropdown-item" href="delete_profile.php">Delete Profile</a></li>
              </ul>
            </li>
            
            <li class="nav-item">
              <a class="nav-link" href="logout.php"><i class="bi bi-box-arrow-right"></i> Log Out</a>
            </li>
            <?php if (!isLoggedIn()): ?>
            <li class="nav-item">
              <a class="nav-link" href="register.php"><i class="bi bi-person-plus-fill"></i> Sign Up</a>
            </li>
            <?php endif; ?>
            <li class="nav-item">
              <a class="nav-link" href="login.php"><i class="bi bi-box-arrow-in-right"></i> Log In</a>
            </li>
            <li class="nav-item">
              <button class="nav-link mode-switcher btn btn-link" type="button">
                <?php if ($mode === 'dark'): ?>
                    <i class="bi bi-sun-fill"></i> <!-- Light mode icon -->
                <?php else: ?>
                    <i class="bi bi-moon-fill"></i> <!-- Dark mode icon -->
                <?php endif; ?>
              </button>
            </li>
          </ul>

        </div>
      </div>
    </nav>
  </header>

<!-- JavaScript to handle mode switching -->
<script>
  
document.addEventListener('DOMContentLoaded', function() {
    const modeSwitcher = document.querySelector('.mode-switcher');
    const bodyClassList = document.body.classList;
    
    // Set initial mode based on cookies
    const modeCookie = document.cookie.split('; ').find(row => row.startsWith('mode='));
    if (modeCookie && modeCookie.split('=')[1] === 'dark') {
        bodyClassList.add('dark-mode');
        bodyClassList.remove('light-mode');
        modeSwitcher.innerHTML = '<i class="bi bi-sun-fill"></i>'; // Change to sun icon for light mode
    } else {
        bodyClassList.add('light-mode');
        bodyClassList.remove('dark-mode');
        modeSwitcher.innerHTML = '<i class="bi bi-moon-fill"></i>'; // Change to moon icon for dark mode
    }

    // Toggle mode on click
    modeSwitcher.addEventListener('click', function() {
        bodyClassList.toggle('dark-mode');
        bodyClassList.toggle('light-mode');
        
        // Store mode in cookies
        const currentMode = bodyClassList.contains('dark-mode') ? 'dark' : 'light';
        document.cookie = `mode=${currentMode}; path=/;`;

        // Update button icon
        if (currentMode === 'dark') {
            modeSwitcher.innerHTML = '<i class="bi bi-sun-fill"></i>'; // Change to sun icon for light mode
        } else {
            modeSwitcher.innerHTML = '<i class="bi bi-moon-fill"></i>'; // Change to moon icon for dark mode
        }
    });
});
</script>

<!-- Bootstrap JavaScript -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>

</body>
</html>
