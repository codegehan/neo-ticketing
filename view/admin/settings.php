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

if(isset($_POST['add_position'])) {
    $positionName = trim(strtoupper($_POST['position_name']));
    $addedBy = $_SESSION['fn'];
    $addPositionSql = "INSERT INTO position (position_name, added_by, date_added) VALUES (?,?,CURRENT_TIMESTAMP())";
    $executeAddPositionSql = $db->query($addPositionSql, [$positionName, $addedBy]);
    if (!$executeAddPositionSql) {
        $_SESSION['notif_message'] = "Failed to add new position.";
        $_SESSION['notif_status'] = "error";
        header("Location: settings.php");
        exit();
    } else {
        $_SESSION['notif_message'] = "New position successfully added.";
        $_SESSION['notif_status'] = "success";
        header("Location: settings.php");
        exit();
    }
}
if(isset($_POST['edit_position'])) {
    $positionid = trim(strtoupper($_POST['position_id']));
    $positionName = trim(strtoupper($_POST['position_name']));
    $updatePositionSql = "UPDATE position SET position_name = ?, date_updated = CURRENT_TIMESTAMP() WHERE position_id = ?";
    $executeUpdatePositionSql = $db->query($updatePositionSql, [$positionName, $positionid]);
    if (!$executeUpdatePositionSql) {
        $_SESSION['notif_message'] = "Failed to update position.";
        $_SESSION['notif_status'] = "error";
        header("Location: settings.php");
        exit();
    } else {
        $_SESSION['notif_message'] = "Position successfully updated.";
        $_SESSION['notif_status'] = "success";
        header("Location: settings.php");
        exit();
    }
}

if(isset($_POST['add_department'])) {
    $departmentName = trim(strtoupper($_POST['department_name']));
    $addedBy = $_SESSION['fn'];
    $addDepartmentSql = "INSERT INTO department (department_name, added_by, date_added) VALUES (?,?,CURRENT_TIMESTAMP())";
    $executeAddDepartmentSql = $db->query($addDepartmentSql, [$departmentName, $addedBy]);
    if (!$executeAddDepartmentSql) {
        $_SESSION['notif_message'] = "Failed to add new department.";
        $_SESSION['notif_status'] = "error";
        header("Location: settings.php");
        exit();
    } else {
        $_SESSION['notif_message'] = "New department successfully added.";
        $_SESSION['notif_status'] = "success";
        header("Location: settings.php");
        exit();
    }
}
if(isset($_POST['edit_department'])) {
    $departmentid = trim(strtoupper($_POST['department_id']));
    $departmentName = trim(strtoupper($_POST['department_name']));
    $updateDepartmentSql = "UPDATE department SET department_name = ?, date_updated = CURRENT_TIMESTAMP() WHERE department_id = ?";
    $executeUpdateDepartmentSql = $db->query($updateDepartmentSql, [$departmentName, $departmentid]);
    if (!$executeUpdateDepartmentSql) {
        $_SESSION['notif_message'] = "Failed to update department.";
        $_SESSION['notif_status'] = "error";
        header("Location: settings.php");
        exit();
    } else {
        $_SESSION['notif_message'] = "Department successfully updated.";
        $_SESSION['notif_status'] = "success";
        header("Location: settings.php");
        exit();
    }
}

// Fetch department information lists
$dep_items_per_page = 10;
$dep_current_page = isset($_GET['dep_page']) ? (int)$_GET['dep_page'] : 1;
$dep_offset = ($dep_current_page - 1) * $dep_items_per_page;
$depCountSql = "SELECT COUNT(*) as total FROM department";
$depRows = $db->fetchAll($depCountSql);
$dep_total_pages = ceil($depRows[0]['total'] / $dep_items_per_page);

$fetchDepartmentSql = "SELECT department_id, department_name 
                      FROM department 
                      ORDER BY department_name 
                      LIMIT $dep_items_per_page OFFSET $dep_offset";
$resultDepartmentSql = $db->fetchAll($fetchDepartmentSql);

// Fetch position information lists
$pos_items_per_page = 10;
$pos_current_page = isset($_GET['pos_page']) ? (int)$_GET['pos_page'] : 1;
$pos_offset = ($pos_current_page - 1) * $pos_items_per_page;
$posCountSql = "SELECT COUNT(*) as total FROM position";
$posRows = $db->fetchAll($posCountSql);
$pos_total_pages = ceil($posRows[0]['total'] / $pos_items_per_page);
$fetchPositionSql = "SELECT position_id, position_name 
                    FROM position 
                    ORDER BY position_name
                    LIMIT $pos_items_per_page OFFSET $pos_offset";
$resultPositionSql = $db->fetchAll($fetchPositionSql);

?>
<!-- Main Content Area -->
<main class="flex-1 p-4">
    <div class="col-span-12 md:col-span-4">
        <div class="bg-white shadow-lg rounded-lg p-6 border-t-4">
            <div class="border-b border-[#4a7c7d]/20 pb-3 mb-4">
                <h6 class="text-lg font-semibold text-[#4a7c7d]"><i class="fas fa-address-card me-2"></i>Account Information</h6>
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

    <div class="flex-auto grid grid-cols-2 gap-4 mt-4">
        <div class="col-span-1">
            <div class="bg-white shadow-lg rounded-lg p-6 border-t-4">
                <div class="border-b border-[#4a7c7d]/20 pb-3 mb-4">
                    <h6 class="text-lg font-semibold text-[#4a7c7d]"><i class="fas fa-building me-2"></i>Departments</h6>
                </div>
                <div class="mb-4">
                    <form method="POST" class="flex items-center space-x-2">
                        <input type="hidden" name="department_id" id="department_id">
                        <input type="text" name="department_name" id="department_name" required
                        placeholder="Add department"
                        class="mt-1 block w-full rounded-md border border-[#4a7c7d]/30 
                            shadow-sm focus:ring-[#4a7c7d] focus:border-[#4a7c7d] 
                            bg-white placeholder-[#4a7c7d]/50 p-2">
                        <button type="submit" name="add_department" id="addDepartmentBtn" class="bg-primary text-white px-3 py-2 rounded text-xs transition-all hover:scale-125"><i class="fas fa-plus"></i></button>
                        <button type="submit" name="edit_department" id="editDepartmentBtn" class="hidden bg-green-700 text-white px-3 py-2 rounded text-xs transition-all hover:scale-125"><i class="fas fa-check"></i></button>
                    </form>
                </div>
                <table class="w-full table-border">
                    <thead class="bg-primary text-white">
                        <tr>
                            <th class="py-1 px-2 text-left">Lists</th>
                            <th class="py-1 px-2 text-center w-6">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(count($resultDepartmentSql) > 0) :?>
                            <?php foreach($resultDepartmentSql as $d) : ?>
                                <tr>
                                    <td class="border-b border-[#4a7c7d]/20 py-1"><?=$d['department_name']?></td>
                                    <td class="text-center">
                                        <button
                                            type="button"
                                            onclick="editDepartment('<?=$d['department_id']?>','<?=$d['department_name']?>');" 
                                            class="bg-green-700 text-white px-3 py-1 rounded text-xs transition-all hover:scale-125">
                                            <i class="fas fa-pencil"></i>
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else : ?>
                            <tr>
                                <td colspan="2" class="border-b border-[#4a7c7d]/20 py-1">No data</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
                <!-- Pagination Controls for Department -->
                <?php if($dep_total_pages > 1): ?>
                    <div class="flex justify-center items-center gap-2 mt-6">
                    <?php if($dep_current_page > 1): ?>
                        <a href="?dep_page=<?= $dep_current_page - 1 ?>" 
                        class="px-3 py-1 bg-primary text-white rounded-md text-sm hover:bg-primary/80 transition">
                            Previous
                        </a>
                    <?php endif; ?>
                    <div class="flex items-center gap-1">
                        <span class="text-sm font-medium"><?= $dep_current_page ?></span>
                        <span class="text-sm text-gray-500">/</span>
                        <span class="text-sm text-gray-500"><?= $dep_total_pages ?></span>
                    </div>
                    <?php if($dep_current_page < $dep_total_pages): ?>
                        <a href="?dep_page=<?= $dep_current_page + 1 ?>" 
                        class="px-3 py-1 bg-primary text-white rounded-md text-sm hover:bg-primary/80 transition">
                            Next
                        </a>
                    <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        <div class="col-span-1">
            <div class="bg-white shadow-lg rounded-lg p-6 border-t-4">
                <div class="border-b border-[#4a7c7d]/20 pb-3 mb-4">
                    <h6 class="text-lg font-semibold text-[#4a7c7d]"><i class="fas fa-user-tie me-2"></i>Positions</h6>
                </div>
                <div class="mb-4">
                    <form method="POST" class="flex items-center space-x-2">
                        <input type="hidden" name="position_id" id="position_id">
                        <input type="text" name="position_name" id="position_name" required
                        placeholder="Add position"
                        class="mt-1 block w-full rounded-md border border-[#4a7c7d]/30 
                            shadow-sm focus:ring-[#4a7c7d] focus:border-[#4a7c7d] 
                            bg-white placeholder-[#4a7c7d]/50 p-2">
                        <button type="submit" name="add_position" id="addPositionBtn" class="bg-primary text-white px-3 py-2 rounded text-xs transition-all hover:scale-125"><i class="fas fa-plus"></i></button>
                        <button type="submit" name="edit_position" id="editPositionBtn" class="hidden bg-green-700 text-white px-3 py-2 rounded text-xs transition-all hover:scale-125"><i class="fas fa-check"></i></button>
                    </form>
                </div>
                <table class="w-full table-border">
                    <thead class="bg-primary text-white">
                        <tr>
                            <th class="py-1 px-2 text-left">Lists</th>
                            <th class="p-1 px-2 text-left w-5">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(count($resultPositionSql) > 0) :?>
                            <?php foreach($resultPositionSql as $p) : ?>
                                <tr>
                                    <td class="border-b border-[#4a7c7d]/20 py-1"><?=$p['position_name']?></td>
                                    <td class="text-center">
                                        <button
                                            type="button"
                                            onclick="editPostion('<?=$p['position_id']?>','<?=$p['position_name']?>');" 
                                            class="bg-green-700 text-white px-3 py-1 rounded text-xs transition-all hover:scale-125">
                                            <i class="fas fa-pencil"></i>
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else : ?>
                            <tr>
                                <td colspan="2" class="border-b border-[#4a7c7d]/20 py-1">No data</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
                <!-- Pagination Controls for Position -->
                <?php if($pos_total_pages > 1): ?>
                    <div class="flex justify-center items-center gap-2 mt-6">
                    <?php if($pos_current_page > 1): ?>
                        <a href="?pos_page=<?= $pos_current_page - 1 ?>" 
                        class="px-3 py-1 bg-primary text-white rounded-md text-sm hover:bg-primary/80 transition">
                            Previous
                        </a>
                    <?php endif; ?>
                    <div class="flex items-center gap-1">
                        <span class="text-sm font-medium"><?= $pos_current_page ?></span>
                        <span class="text-sm text-gray-500">/</span>
                        <span class="text-sm text-gray-500"><?= $pos_total_pages ?></span>
                    </div>
                    <?php if($pos_current_page < $pos_total_pages): ?>
                        <a href="?pos_page=<?= $pos_current_page + 1 ?>" 
                        class="px-3 py-1 bg-primary text-white rounded-md text-sm hover:bg-primary/80 transition">
                            Next
                        </a>
                    <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</main>
<script>
    function editDepartment(depId, depName) {
        document.getElementById('department_id').value = depId;
        document.getElementById('department_name').value = depName;
        document.getElementById('addDepartmentBtn').classList.remove('block');
        document.getElementById('addDepartmentBtn').classList.add('hidden');
        document.getElementById('editDepartmentBtn').classList.remove('hidden');
        document.getElementById('editDepartmentBtn').classList.add('block');
    }
    function editPostion(posId, posName) {
        document.getElementById('position_id').value = posId;
        document.getElementById('position_name').value = posName;
        document.getElementById('addPositionBtn').classList.remove('block');
        document.getElementById('addPositionBtn').classList.add('hidden');
        document.getElementById('editPositionBtn').classList.remove('hidden');
        document.getElementById('editPositionBtn').classList.add('block');
    }
</script>
<?php 
include('components/footer.php');
?>