<?php 
ob_start();
include('components/header.php');
include('components/s-navbar.php');

$account_email = $_SESSION['em'];

if(isset($_GET['searchKey'])) {
    $search_key = htmlspecialchars($_GET['searchKey'], ENT_QUOTES, 'UTF-8');
    $sql = "SELECT account_no, fullname, position, department, email, is_verified , department_name, position_name
            FROM account
            LEFT JOIN department ON account.department = department.department_id 
            LEFT JOIN position ON account.position = position.position_id
            WHERE department > 0
            AND (fullname LIKE ? OR position LIKE ? OR department LIKE ? OR email LIKE ?)";
    $search_param = "%".$search_key."%";
    $accountLists = $db->fetchAll($sql, [$search_param, $search_param, $search_param, $search_param]);
} else {
    $sql = "SELECT account_no, fullname, position, department, email, is_verified , department_name, position_name
            FROM account
            LEFT JOIN department ON account.department = department.department_id 
            LEFT JOIN position ON account.position = position.position_id
            WHERE department > 0";
    $accountLists = $db->fetchAll($sql);
}

if(isset($_POST['verifyModal'])){
    $adminPassword = $_POST['passwordToVerify'];
    $accountNoToVerify = $_POST['account_no'];
    try {
        $sql = "SELECT COUNT(account_no) AS count FROM account WHERE email = ? AND password = SHA2(?, 256)";
        $result = $db->fetchAll($sql, [$account_email, $adminPassword]);
        if($result[0]['count'] > 0) {
            $verifyAccount = "UPDATE account SET is_verified = 'YES' WHERE account_no = ?";
            $verifyAccountResult = $db->query($verifyAccount, [$accountNoToVerify]);
            if (!$verifyAccountResult) {
                $_SESSION['notif_message'] = "Something went wrong during request.";
                $_SESSION['notif_status'] = "error";
                header("Location: account.php");
                exit();
            } else {
                $_SESSION['notif_message'] = "Account verfied successfully.";
                $_SESSION['notif_status'] = "success";
                header("Location: account.php");
                exit();
            }
        } else {
            $_SESSION['notif_message'] = "Incorrect admin password. Please try again.";
            $_SESSION['notif_status'] = "error";
            header("Location: account.php");
            exit();
        }
    } catch (Exception $e) {
        $_SESSION['notif_message'] = "Something went wrong during request.";
        $_SESSION['notif_status'] = "error";
        header("Location: account.php");
        exit();
    }
}
ob_end_flush();
?>
<div class="mx-auto w-full px-4 py-8 text-sm">
    <!-- Incoming Tickets Panel -->
    <div class="flex items-center mb-4"> 
        <input 
            type="text" 
            id="search_account_key" 
            name="search_account_key" 
            placeholder="Search account here..."
            class="w-64 px-3 py-2 border border-gray-300 rounded-l-md focus:outline-none focus:ring-2 focus:ring-primary">
        <button
            type="button"
            onclick="searchAccount()"
            class="bg-primary text-white px-3 py-2 ms-2 rounded text-xs hover:bg-secondary-dark hover:cursor-pointer">
            Search
        </button>
        <a
            href="account.php"
            class="bg-green-700 text-white px-3 py-2 ms-2 rounded text-xs hover:bg-secondary-dark hover:cursor-pointer">
            Back
        </a>
    </div>

    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <table class="w-full">
            <thead class="bg-primary text-white">
                <tr>
                    <th class="px-4 py-2 text-left">Fullname</th>
                    <th class="px-4 py-2 text-left">Department</th>
                    <th class="px-4 py-2 text-left">Position</th>
                    <th class="px-4 py-2 text-left">Email</th>
                    <th class="px-4 py-2 text-center">Verified</th>
                    <th class="px-4 py-2 text-center">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($accountLists) > 0): ?>
                    <?php foreach ($accountLists as $a): ?>
                        <tr class="border-b hover:bg-gray-100">
                            <td class="px-4 py-2"><?=$a['fullname'];?></td>
                            <td class="px-4 py-2"><?=$a['department_name'];?></td>
                            <td class="px-4 py-2"><?=$a['position_name'];?></td>
                            <td class="px-4 py-2"><?=$a['email'];?></td>
                            <td class="px-4 py-2 text-center"><span class="<?=$a['is_verified'] == 'YES' ? 'bg-green-600' : 'bg-red-300'?> text-white px-2 rounded"><?=$a['is_verified'];?></span></td>
                            <?php if(strtoupper($a['is_verified']) == "NO") : ?>
                                <td class="px-4 py-2 text-center">
                                    <button 
                                        type="button" 
                                        class="bg-primary text-white px-3 py-1 rounded text-xs hover:bg-secondary-dark"
                                        onclick="openVerifyModal('<?=$a['account_no'];?>', '<?=$a['fullname'];?>')"
                                        >
                                        Verify
                                    </button>
                                </td>
                            <?php endif; ?>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" class="px-4 py-2 text-center">No record</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>


<div id="verifyModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden items-center justify-center">
    <div class="bg-white p-8 rounded-lg shadow-xl w-96">
        <h2 class="text-xl font-bold text-primary mb-4">Verify Account</h2>
        <form id="assignForm" method="POST">
            <input type="hidden" id="account_no" name="account_no">
            <div class="mb-4">
                <span>Sure to verify <span id="fnameToVerify"></span></span>
            </div>
            <div class="mb-4">
                <input 
                    type="password" 
                    id="passwordToVerify" 
                    name="passwordToVerify" 
                    placeholder="Enter admin password to verify"
                    class="w-full px-3 py-2 border border-gray-300 rounded-l-md focus:outline-none focus:ring-2 focus:ring-primary">
            </div>
            <div class="flex justify-end">
                <button type="button" onclick="closeVerifyModal()" class="mr-2 px-4 py-2 text-xs font-medium text-gray-700 bg-gray-200 rounded-md hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-400">Cancel</button>
                <button 
                    type="submit" 
                    name="verifyModal"
                    onclick="handleVerify(event)" 
                    class="px-4 py-2 text-xs font-medium text-white bg-primary rounded-md hover:bg-primary-dark focus:outline-none focus:ring-2 focus:ring-primary">
                    Verify
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function openVerifyModal(accountNo, fullname) {
    const modal = document.getElementById('verifyModal');
    if (modal) {
        document.getElementById('account_no').value = accountNo;
        document.getElementById('fnameToVerify').textContent = fullname + '?';
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }
}
function closeVerifyModal() {
    const modal = document.getElementById('verifyModal');
    if (modal) {
        modal.classList.remove('flex');
        modal.classList.add('hidden');
    }
}
function searchAccount() {
    const searchKey = document.getElementById('search_account_key');
    if (searchKey.value === '') {
        Swal.fire({
            title: "Please enter search key",
            icon: "error",
            showConfirmButton: false,
            timer: 2000
        });
    } else {
        window.location.href = "account.php?searchKey=" + searchKey.value;
    }
}
function handleVerify(event) {
    const password = document.getElementById('passwordToVerify').value;
    if(!password.trim()) {
        Swal.fire({
            title: "Please enter the admin password to verify.",
            icon: "error",
            showConfirmButton: false,
            timer: 2000
        });
        event.preventDefault();
    }
}
</script>
<?php 
include('components/footer.php');
?>