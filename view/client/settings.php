<?php 
include('components/header.php');
include('components/s-navbar.php');

if(isset($_POST['update_account'])) {
    $accountno =$_SESSION['an'];
    $fullname = strtoupper($_POST['fullname']);
    $position = strtoupper($_POST['position']);
    $department = strtoupper($_POST['department']);
    $email = $_POST['email'];
    $password = $_POST['password'];

    if($password != "") {
        if (strlen($password) < 6) {
            $_SESSION['notif_message'] = "Password must greater than 6 characters.";
            $_SESSION['notif_status'] = "error";
            header("Location: settings.php");
            exit();
        } else {
            $updateSql = "UPDATE account SET fullname = ?, position = ?, department = ?, email = ?, password = SHA2(?, 256), date_updated = CURRENT_TIMESTAMP() WHERE account_no = ?";
            $updateResult = $db->query($updateSql, [$fullname, $position, $department, $email, $password, $accountno]);
            if(!$updateResult) {
                $_SESSION['notif_message'] = "Something went wrong during updating information. Please try again.";
                $_SESSION['notif_status'] = "error";
                header("Location: settings.php");
                exit();
            } else {
                $_SESSION['fn'] = $fullname;
                $_SESSION['ps'] = $position;
                $_SESSION['dp'] = $department;
                $_SESSION['em'] = $email;
                $_SESSION['notif_message'] = "Information successfully updated.";
                $_SESSION['notif_status'] = "success";
                header("Location: settings.php");
                exit();
            }
        }
    } else {
        $updateSql = "UPDATE account SET fullname = ?, position = ?, department = ?, email = ?, date_updated = CURRENT_TIMESTAMP() WHERE account_no = ?";
        $updateResult = $db->query($updateSql, [$fullname, $position, $department, $email, $accountno]);
        if(!$updateResult) {
            $_SESSION['notif_message'] = "Something went wrong during updating information. Please try again.";
            $_SESSION['notif_status'] = "error";
            header("Location: settings.php");
            exit();
        } else {
            $_SESSION['fn'] = $fullname;
            $_SESSION['ps'] = $position;
            $_SESSION['dp'] = $department;
            $_SESSION['em'] = $email;
            $_SESSION['notif_message'] = "Information successfully updated.";
            $_SESSION['notif_status'] = "success";
            header("Location: settings.php");
            exit();
        }
    }
}

?>
<!-- Main Content Area -->
<main class="flex-1 p-4">
<div class="col-span-12 md:col-span-4">
            <div class="bg-white shadow-lg rounded-lg p-6 border-t-4">
                <div class="border-b border-[#4a7c7d]/20 pb-3 mb-4">
                    <h6 class="text-lg font-semibold text-[#4a7c7d]">Account Information</h6>
                </div>
                <form method="POST" enctype="multipart/form-data">
                    <div class="mb-4">
                        <label for="accountno" class="block text-sm font-medium text-[#4a7c7d]">Account No</label>
                        <input type="text" name="accountno" id="accountno" disabled
                            value="<?=$_SESSION['an']?>"
                            class="mt-1 block w-full rounded-md border border-[#4a7c7d]/30 
                                shadow-sm focus:ring-[#4a7c7d] focus:border-[#4a7c7d] 
                                bg-gray-200 placeholder-[#4a7c7d]/50 p-2">
                    </div>
                    <div class="mb-4">
                        <label for="fullname" class="block text-sm font-medium text-[#4a7c7d]">Fullname</label>
                        <input type="text" name="fullname" id="fullname" required 
                            value="<?=$_SESSION['fn']?>"
                            class="mt-1 block w-full rounded-md border border-[#4a7c7d]/30 
                                shadow-sm focus:ring-[#4a7c7d] focus:border-[#4a7c7d] 
                                bg-white placeholder-[#4a7c7d]/50 p-2">
                    </div>
                    <div class="mb-4">
                        <label for="position" class="block text-sm font-medium text-[#4a7c7d]">Position</label>
                        <input type="text" name="position" id="position" required
                            value="<?=$_SESSION['ps']?>" 
                            class="mt-1 block w-full rounded-md border border-[#4a7c7d]/30 
                                shadow-sm focus:ring-[#4a7c7d] focus:border-[#4a7c7d] 
                                bg-white placeholder-[#4a7c7d]/50 p-2">
                    </div>
                    <div class="mb-4">
                        <label for="department" class="block text-sm font-medium text-[#4a7c7d]">Department</label>
                        <input type="text" name="department" id="department" required
                            value="<?=$_SESSION['dp']?>" 
                            class="mt-1 block w-full rounded-md border border-[#4a7c7d]/30 
                                shadow-sm focus:ring-[#4a7c7d] focus:border-[#4a7c7d] 
                                bg-white placeholder-[#4a7c7d]/50 p-2">
                    </div>
                    <div class="mb-4">
                        <label for="email" class="block text-sm font-medium text-[#4a7c7d]">Email</label>
                        <input type="text" name="email" id="email" required
                            value="<?=$_SESSION['em']?>" 
                            class="mt-1 block w-full rounded-md border border-[#4a7c7d]/30 
                                shadow-sm focus:ring-[#4a7c7d] focus:border-[#4a7c7d] 
                                bg-white placeholder-[#4a7c7d]/50 p-2">
                    </div>
                    <div class="mb-4">
                        <label for="password" class="block text-sm font-medium text-[#4a7c7d]">Password</label>
                        <input type="password" name="password" id="password"
                            class="mt-1 block w-full rounded-md border border-[#4a7c7d]/30 
                                shadow-sm focus:ring-[#4a7c7d] focus:border-[#4a7c7d] 
                                bg-white placeholder-[#4a7c7d]/50 p-2">
                    </div>
                    <div>
                        <button type="submit" name="update_account" 
                            class="bg-[#4a7c7d] text-white py-2 px-4 rounded-md 
                                hover:bg-[#4a7c7d]/80 transition">
                            Update Account Information
                        </button>
                    </div>
                </form>
            </div>
        </div>
</main>
<?php 
include('components/footer.php');
?>