<?php 
date_default_timezone_set('Asia/Manila');
session_start();
include('database/config.php');
$db = new DatabaseConnector();

if(isset($_POST['register_account'])) {
    try {
        $fullname = strtoupper($_POST['fullname']);
        $position = strtoupper($_POST['position']);
        $department = strtoupper($_POST['department']);
        $email = $_POST['email'];
        $password = $_POST['password'];
        $confirmPassword = $_POST['confirm-password'];
        if($password != $confirmPassword) {
            $_SESSION['notif_message'] = "Password not matched";
            $_SESSION['notif_status'] = "error";
            header("Location: register.php");
            exit();
        } else {
            // check similar fullname
            $checkFullnameSql = "SELECT COUNT(account_no) AS count FROM account WHERE fullname LIKE ?";
            $checkFullnameResult = $db->fetchAll($checkFullnameSql, ["%$fullname%"]);
            $rowFullnameCount = $checkFullnameResult[0]['count'];
            if($rowFullnameCount > 0) {
                $_SESSION['notif_message'] = "Fullname already exists";
                $_SESSION['notif_status'] = "error";
                header("Location: register.php");
                exit();
            }

            // check email if exists 
            $checkEmailSql = "SELECT COUNT(account_no) AS count FROM account WHERE email = ?";
            $stmtEmailResult = $db->fetchAll($checkEmailSql, [$email]);
            $rowEmailCount = $stmtEmailResult[0]['count'];
            if($rowEmailCount > 0) {
                $_SESSION['notif_message'] = "Email already exists";
                $_SESSION['notif_status'] = "error";
                header("Location: register.php");
                exit();
            }

            $sql = "INSERT INTO account (fullname, position, department, email, password, is_verified, date_added) VALUES (?,?,?,?,SHA2(?, 256), 'NO', CURRENT_TIMESTAMP())";
            $result = $db->query($sql, [$fullname, $position, $department, $email, $password]);
            if(!$result) {
                $_SESSION['notif_message'] = "Something went wrong during registration. Please try again.";
                $_SESSION['notif_status'] = "error";
                header("Location: register.php");
                exit();
            } else {
                $_SESSION['notif_message'] = "Resigter successfully. Please wait for verification.";
                $_SESSION['notif_status'] = "success";
                header("Location: ./");
                exit();
            }
        }
    } catch(Exception $e) { 
        $_SESSION['notif_message'] = "Unexpected error occurred: " . $e->getMessage();
        $_SESSION['notif_status'] = "error";
        header("Location: register.php");
        exit();
    }
}

$fetchDepartmentSql = "SELECT department_id, department_name FROM department";
$resultDepartmentSql = $db->fetchAll($fetchDepartmentSql);

$fetchPositionSql = "SELECT position_id, position_name FROM position";
$resultPositionSql = $db->fetchAll($fetchPositionSql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Ticketing Web App</title>
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
        <div class="w-full lg:w-1/2 flex items-center justify-center bg-gray-50">
            <div class="w-full max-w-xl space-y-8">
                <!-- Mobile Logo (visible on smaller screens) -->
                <div class="lg:hidden text-center mb-8">
                    <div class="w-16 h-16 bg-primary rounded-xl mx-auto flex items-center justify-center">
                        <span class="text-white text-2xl font-bold">NG</span>
                    </div>
                </div>

                <div class="text-center mb-8">
                    <h2 class="text-3xl font-bold text-gray-900">Create an Account</h2>
                    <p class="mt-2 text-gray-600">Please wait for approval after registration</p>
                </div>
                <form class="space-y-6" method="POST">
                    <div>
                        <label for="fullname" class="block text-sm font-medium text-primary">Full Name</label>
                        <input type="text" id="fullname" name="fullname" required
                            class="mt-1 block w-full px-4 py-3 bg-white border border-gray-300 rounded-xl text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition-all duration-200">
                    </div>
                    <div>
                        <label for="position" class="block text-sm font-medium text-primary">Position</label>
                        <select id="position" name="position" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary">
                            <option value=""></option>
                            <?php 
                                foreach ($resultPositionSql as $p): ?>
                                <option value="<?=$p['position_id']?>"><?=$p['position_name']?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div>
                        <label for="department" class="block text-sm font-medium text-primary">Department</label>
                        <select id="department" name="department" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary">
                            <option value=""></option>
                            <?php 
                                foreach ($resultDepartmentSql as $d): ?>
                                <option value="<?=$d['department_id']?>"><?=$d['department_name']?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div>
                        <label for="email" class="block text-sm font-medium text-primary">Email address</label>
                        <input type="email" id="email" name="email" required
                            class="mt-1 block w-full px-4 py-3 bg-white border border-gray-300 rounded-xl text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition-all duration-200">
                    </div>
                    <div class="flex flex-col sm:flex-row">
                        <div class="w-full me-2">
                            <label for="password" class="block text-sm font-medium text-primary">Password</label>
                            <input type="password" id="password" name="password" required
                                class="mt-1 block w-full px-4 py-3 bg-white border border-gray-300 rounded-xl text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition-all duration-200">
                        </div>
                        <div class="w-full">
                            <label for="confirm-password" class="block text-sm font-medium text-primary">Confirm Password</label>
                            <input type="password" id="confirm-password" name="confirm-password" required
                                class="mt-1 block w-full px-4 py-3 bg-white border border-gray-300 rounded-xl text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition-all duration-200">
                        </div>
                    </div>
                    <div class="mt-1">
                        <div id="password-strength-bar" class="h-2 rounded-full bg-gray-200 hidden"></div>
                        <p id="password-strength-text" class="text-sm mt-1"></p>
                    </div>
                    <div>
                        <p id="password-match" class="text-sm hidden"></p>
                    </div>
                    <button type="submit"
                        name="register_account"
                        class="w-full flex justify-center py-3 px-4 border border-transparent rounded-xl shadow-sm text-sm font-medium text-white bg-primary hover:bg-primary-light focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition-all duration-200">
                        Register
                    </button>
                </form>
                <p class="text-center text-sm text-gray-600">
                    Already have an account?
                    <a href="./" class="font-medium text-primary hover:text-primary-light transition-colors duration-200">
                        Login Now
                    </a>
                </p>
            </div>
        </div>
    </div>
</body>
<script>
    const passwordInput = document.getElementById('password');
    const confirmPasswordInput = document.getElementById('confirm-password');
    const passwordStrengthBar = document.getElementById('password-strength-bar');
    const passwordStrengthText = document.getElementById('password-strength-text');
    const passwordMatch = document.getElementById('password-match');

    passwordInput.addEventListener('input', updatePasswordStrength);
    confirmPasswordInput.addEventListener('input', checkPasswordMatch);

    function updatePasswordStrength() {
        const password = passwordInput.value;
        let strength = 0;
        let message = '';

        if (password.length === 0) {
            passwordStrengthBar.style.display = 'none';
            passwordStrengthText.textContent = '';
            return;
        } else {
            passwordStrengthBar.style.display = 'block';
        }

        if (password.length >= 8) strength++;
        if (password.match(/[a-z]/)) strength++;
        if (password.match(/[A-Z]/)) strength++;
        if (password.match(/[0-9]/)) strength++;
        if (password.match(/[^a-zA-Z0-9]/)) strength++;

        switch (strength) {
            case 0:
            case 1:
                message = 'Very weak';
                passwordStrengthBar.style.display = 'block';
                passwordStrengthBar.style.width = '20%';
                passwordStrengthBar.style.backgroundColor = '#EF4444';
                break;
            case 2:
                message = 'Weak';
                passwordStrengthBar.style.display = 'block';
                passwordStrengthBar.style.width = '40%';
                passwordStrengthBar.style.backgroundColor = '#F59E0B';
                break;
            case 3:
                message = 'Medium';
                passwordStrengthBar.style.display = 'block';
                passwordStrengthBar.style.width = '60%';
                passwordStrengthBar.style.backgroundColor = '#EAB308';
                break;
            case 4:
                message = 'Strong';
                passwordStrengthBar.style.display = 'block';
                passwordStrengthBar.style.width = '80%';
                passwordStrengthBar.style.backgroundColor = '#22C55E';
                break;
            case 5:
                message = 'Very strong';
                passwordStrengthBar.style.display = 'block';
                passwordStrengthBar.style.width = '100%';
                passwordStrengthBar.style.backgroundColor = '#15803D';
                break;
        }

        passwordStrengthText.textContent = `Password strength: ${message}`;
        passwordStrengthText.style.color = passwordStrengthBar.style.backgroundColor;
    }

    function checkPasswordMatch() {
        if (passwordInput.value === confirmPasswordInput.value) {
            passwordMatch.textContent = '';
            passwordMatch.style.display = 'hidden';
        } else {
            passwordMatch.textContent = 'Passwords do not match';
            passwordMatch.style.color = '#EF4444';
            passwordMatch.style.display = 'block';
        }
    }
</script>
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