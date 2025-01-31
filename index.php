<?php 
date_default_timezone_set('Asia/Manila');
session_start();
include('database/config.php');
$db = new DatabaseConnector();

if(isset($_POST['login_account'])) {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    // check email if registered
    $checkEmail = "SELECT COUNT(account_no) AS count FROM account WHERE email = ? ";
    $checkEmailResult = $db->fetchAll($checkEmail, [$email]);
    $checkEmailRowCount = $checkEmailResult[0]['count'];
    if ($checkEmailRowCount == 0) {
        $_SESSION['notif_message'] = "Email not found.";
        $_SESSION['notif_status'] = "error";
        header("Location: ./");
        exit();
    }

    // check password if correct
    $checkPassword = "SELECT COUNT(account_no) AS count FROM account WHERE email = ? AND password = SHA2(?, 256) ";
    $checkPasswordResult = $db->fetchAll($checkPassword, [$email, $password]);
    $checkPasswordRowCount = $checkPasswordResult[0]['count'];
    if ($checkPasswordRowCount == 0) {
        $_SESSION['notif_message'] = "Password incorrect.";
        $_SESSION['notif_status'] = "error";
        header("Location: ./");
        exit();
    }

    // check if user is verified
    $checkIsVerified = "SELECT is_verified FROM account WHERE email = ? AND password = SHA2(?, 256) ";
    $checkIsVerifiedResult = $db->fetchAll($checkIsVerified, [$email, $password]);
    $checkIsVerifiedRowCount = $checkIsVerifiedResult[0]['is_verified'];
    if ($checkIsVerifiedRowCount == "NO") {
        $_SESSION['notif_message'] = "Account is not verified. Please contact your administrator.";
        $_SESSION['notif_status'] = "error";
        header("Location: ./");
        exit();
    }

    // all condition are true then
    $sql = "SELECT account_no, fullname, position, department, email FROM account WHERE email = ? and password = SHA2(?, 256)";
    $result = $db->fetchAll($sql, [$email, $password]);
    $_SESSION['an'] = $result[0]['account_no'];
    $_SESSION['fn'] = $result[0]['fullname'];
    $_SESSION['ps'] = $result[0]['position'];
    $_SESSION['dp'] = $result[0]['department'];
    $_SESSION['em'] = $result[0]['email'];

    //redirect according to department
    if ($result[0]['department'] == 0) {
        header("Location: view/admin/");
        exit();
    } elseif ($result[0]['department'] == 1) {
        header("Location: view/it-department/");
        exit();
    } else {
        header("Location: view/client/");
        exit();
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Neo Global IT Support Ticketing System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="node_modules/sweetalert2/dist/sweetalert2.min.css">
    <script src="node_modules/sweetalert2/dist/sweetalert2.all.min.js"></script>
    <script src="tailwind.config.js"></script>
    <link rel="stylesheet" href="style.css">
</head>
<body class="min-h-screen bg-white">
    <div class="flex min-h-screen">
        <!-- Left Side - Company Branding -->
        <div class="hidden lg:flex lg:w-1/2 relative bg-primary items-center justify-center overflow-hidden">
            <!-- Background Pattern -->
            <div class="absolute inset-0 bg-primary" style="background-image: radial-gradient(circle at 1px 1px, rgba(255,255,255,0.15) 1px, transparent 0); background-size: 40px 40px;"></div>
            
            <!-- Company Logo and Text -->
            <div class="relative z-10 px-8 text-center">
                <div class="mb-8">
                    <img src="img/logo.png" width="600" alt="Business Logo">
                </div>
                <h1 class="text-4xl font-bold text-white mb-4">Neo Global IT Support</h1>
                <p class="text-white/80 text-lg max-w-md mx-auto">
                    Empowering your business with cutting-edge IT solutions and round-the-clock support.
                </p>

            </div>
        </div>
        <!-- Right Side - Login Form -->
        <div class="w-full lg:w-1/2 flex items-center justify-center p-8 bg-gray-50">
            <div class="w-full max-w-md space-y-8">
                <!-- Mobile Logo (visible on smaller screens) -->
                <div class="lg:hidden text-center mb-8">
                    <div class="w-16 h-16 bg-primary rounded-xl mx-auto flex items-center justify-center">
                        <span class="text-white text-2xl font-bold">NG</span>
                    </div>
                </div>

                <div class="text-center mb-8">
                    <h2 class="text-3xl font-bold text-gray-900">Welcome back</h2>
                    <p class="mt-2 text-gray-600">Please sign in to your account</p>
                </div>

                <form class="space-y-6" action="#" method="POST">
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700">Email address</label>
                        <input type="email" id="email" name="email" required
                            class="mt-1 block w-full px-4 py-3 bg-white border border-gray-300 rounded-xl text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition-all duration-200"
                            placeholder="name@company.com">
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                        <input type="password" id="password" name="password" required
                            class="mt-1 block w-full px-4 py-3 bg-white border border-gray-300 rounded-xl text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition-all duration-200"
                            placeholder="Enter your password">
                    </div>

                    <div class="flex items-center justify-between">
                        <div class="text-sm">
                            <a href="#" class="font-medium text-primary hover:text-primary-light transition-colors duration-200">
                                Forgot password?
                            </a>
                        </div>
                    </div>

                    <button type="submit"
                        name="login_account"
                        class="w-full flex justify-center py-3 px-4 border border-transparent rounded-xl shadow-sm text-sm font-medium text-white bg-primary hover:bg-primary-light focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition-all duration-200">
                        Sign in
                    </button>
                </form>

                <p class="text-center text-sm text-gray-600">
                    Don't have an account?
                    <a href="register.php" class="font-medium text-primary hover:text-primary-light transition-colors duration-200">
                        Create account
                    </a>
                </p>
            </div>
        </div>
    </div>
</body>
</html>


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