<?php 
date_default_timezone_set('Asia/Manila');
session_start(); 
include('../../database/config.php');
$db = new DatabaseConnector();

if(!isset($_SESSION['an'])) {
    header("Location: ../../");
    exit();
}

if(isset($_POST['logout_user'])){
    session_unset();
    session_destroy();
    header("Location: ../../");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Neo Global IT Support Ticketing System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../../node_modules/sweetalert2/dist/sweetalert2.min.css">
    <script src="../../node_modules/sweetalert2/dist/sweetalert2.all.min.js"></script>
    <script src="../../tailwind.config.js"></script>
    <link rel="stylesheet" href="../../style.css">
</head>
<body class="bg-secondary-light min-h-screen flex flex-col">
<!-- Top Navigation Bar -->
<nav class="bg-primary text-white py-2 px-4 flex justify-between items-center sticky top-0">
    <div class="flex items-center">
        <!-- <button id="sidebar-toggle" class="text-white focus:outline-none mr-4">
            <i class="fas fa-bars"></i>
        </button> -->
        <img src="../../img/logo.png" width="60" alt="Business Logo">
        <h1 class="text-xl font-bold ms-2">Neo Global IT Support Ticketing System</h1>
    </div>
    <div class="flex items-center">
        <div class="w-8 h-8 rounded-full bg-white text-primary flex items-center justify-center">
            <i class="fas fa-user"></i>
        </div>
        <p class="ms-2 pe-2 border-r-4"><?=$_SESSION['fn']?></p>
        <form method="post">
            <button type="submit" name="logout_user" class="hover:text-red-500 text-white font-bold py-1 px-2 ms-2 rounded mr-4">
                <i class="fas fa-power-off"></i>
            </button>
        </form>
    </div>
</nav>

<?php 
if(isset($_SESSION['notif_message']) && isset($_SESSION['notif_status'])) {
$message = $_SESSION['notif_message'];
$status = $_SESSION['notif_status'];
echo "
<script>
    Swal.fire({
        title: '".htmlspecialchars($message, ENT_QUOTES)."',
        icon: '".htmlspecialchars($status, ENT_QUOTES)."',
        showConfirmButton: false,
        timer: 2500
    });
</script>
";
unset($_SESSION['notif_message']);
unset($_SESSION['notif_status']);
}
?>