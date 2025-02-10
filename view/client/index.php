<?php 
ob_start();
include('components/header.php');
include('components/s-navbar.php');

$ticket_status = [
    "WAITING FOR ACTION" => "bg-yellow-500",
    "ACCEPTED" => "bg-blue-500",
    "DONE" => "bg-green-500"
];

$requestBy = $_SESSION['fn']; // Account login

if(isset($_POST['Add_Ticket'])) {
    $ticketcode = date('His') . rand(1000, 9999);
    $description = $_POST['ticket_description'];
    $category = $_POST['ticket_category'];

    $status = "WAITING FOR ACTION"; // Default

    try {
        $sql = "INSERT INTO ticket (ticket_code, category, description, request_by, request_date, status) VALUES (?,?,?,?,CURRENT_TIMESTAMP(),?)";
        $result = $db->query($sql, [$ticketcode, $category, $description, $requestBy, $status]);
        if ($result) {
            try {
                $uploadDir = '../../img/tickets/';
                if (!is_dir($uploadDir)) { mkdir($uploadDir, 0777, true); }
                $fileExtension = pathinfo($_FILES['ticket_attachment']['name'], PATHINFO_EXTENSION);
                $filename = $ticketcode . '.' . $fileExtension;
                $uploadPath = $uploadDir . $filename;
                if (!move_uploaded_file($_FILES['ticket_attachment']['tmp_name'], $uploadPath)) {
                    $_SESSION['notif_message'] = "Ticket submitted, but file upload failed.";
                    $_SESSION['notif_status'] = "warning";
                } else {
                    $_SESSION['notif_message'] = "Ticket submitted successfully.";
                    $_SESSION['notif_status'] = "success";
                }
            } catch (\Exception $e) {
                $_SESSION['notif_message'] = "Uploading file failed";
                $_SESSION['notif_status'] = "error";
            } 
            header("Location: ./");
            exit();
        } else {
            $_SESSION['notif_message'] = "Failed to submit ticket. Please try again.";
            $_SESSION['notif_status'] = "error";
            header("Location: ./");
            exit();
        }
    } catch(Exception $e) {
        $_SESSION['notif_message'] = "Unexpected error occurred: " . $e->getMessage();
        $_SESSION['notif_status'] = "error";
        header("Location: ./");
        exit();
    }
ob_end_flush();
}
?>
<!-- Main Content Area -->
<main class="flex-1 p-4">
    <div class="grid grid-cols-12 gap-6">
    <!-- Left Side - Create Ticket Form -->
        <div class="col-span-12 md:col-span-4">
            <div class="bg-white shadow-lg rounded-lg p-6 border-t-4">
                <div class="border-b border-[#4a7c7d]/20 pb-3 mb-4">
                    <h6 class="text-lg font-semibold text-[#4a7c7d]">New Ticket</h6>
                </div>
                <form method="POST" enctype="multipart/form-data">
                    <div class="mb-4">
                        <label for="ticketTitle" class="block text-sm font-medium text-[#4a7c7d]">Category</label>
                        <select type="text" name="ticket_category" id="ticketTitle" required 
                            class="mt-1 block w-full rounded-md border border-[#4a7c7d]/30 
                                shadow-sm focus:ring-[#4a7c7d] focus:border-[#4a7c7d] 
                                bg-white placeholder-[#4a7c7d]/50 p-2">
                            <option value=""></option>
                            <option value="Hardware Issues">Hardware Issues</option>
                            <option value="Software Issues">Software Issues</option>
                            <option value="Network Issues">Network Issues</option>
                            <option value="Security Issues">Security Issues</option>
                            <option value="Access Issues">Access Issues</option>
                            <option value="General Inquiries">General Inquiries</option>
                        </select>
                    </div>
                    <div class="mb-4">
                        <label for="ticketDescription" class="block text-sm font-medium text-[#4a7c7d]">Description</label>
                        <textarea name="ticket_description" id="ticketDescription" rows="4" required 
                            class="mt-1 block w-full rounded-md border border-[#4a7c7d]/30 
                                shadow-sm focus:ring-[#4a7c7d] focus:border-[#4a7c7d] 
                                bg-white placeholder-[#4a7c7d]/50 p-2"></textarea>
                    </div>
                    <div class="mb-4">
                        <label for="fileUpload" class="block text-sm font-medium text-[#4a7c7d] mb-1">Attachments</label>
                        <div class="flex items-center gap-2">
                            <label for="fileUpload" class="cursor-pointer px-3 py-1.5 text-sm border border-[#4a7c7d]/30 rounded-md hover:bg-[#4a7c7d]/5 transition flex items-center gap-2 text-[#4a7c7d]">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path>
                                </svg>
                                Attach File
                            </label>
                            <input id="fileUpload" name="ticket_attachment" type="file" hidden accept=".pdf,.png,.jpg,.jpeg,.gif" >
                            <span class="text-xs text-[#4a7c7d]/70">PDF, PNG or JPG (MAX. 10MB)</span>
                        </div>
                        <div id="file-name" class="mt-2 text-sm text-[#4a7c7d]/70"></div>
                    </div>
                    <div class="mb-4">
                        <p class="text-red-500" id="error-message"></p>
                    </div>
                    <div>
                        <button type="submit" name="Add_Ticket" 
                            class="w-full bg-[#4a7c7d] text-white py-2 px-4 rounded-md 
                                hover:bg-[#4a7c7d]/80 transition">
                            Submit Ticket
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <div class="col-span-12 md:col-span-8">
            <div class="bg-white shadow-lg rounded-lg mb-6 border-t-4">
                <div class="border-b border-[#4a7c7d]/20 py-3 px-6 flex justify-between items-center">
                    <h6 class="text-lg font-semibold text-[#4a7c7d]">Ticket List</h6>
                </div>
                <div class="p-6">
                    <div class="overflow-x-auto">
                        <table class="min-w-full border border-[#4a7c7d]/20 text-sm">
                            <thead class="bg-[#4a7c7d]/5">
                                <tr>
                                    <th class="px-4 py-2 border-b border-[#4a7c7d]/20 text-left text-[#4a7c7d]">Ticket No.</th>
                                    <th class="px-4 py-2 border-b border-[#4a7c7d]/20 text-left text-[#4a7c7d] text-center">Status</th>
                                    <th class="px-4 py-2 border-b border-[#4a7c7d]/20 text-center text-[#4a7c7d]">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php 
                            $items_per_page = 10;
                            $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
                            $offset = ($page - 1) * $items_per_page;
                            // Get total count for pagination
                            $count_sql = "SELECT COUNT(*) as total FROM ticket WHERE request_by = ?";
                            $total_result = $db->fetchAll($count_sql, [$requestBy]);
                            $total_items = $total_result[0]['total'];
                            $total_pages = ceil($total_items / $items_per_page);

                            $ticket_list_sql = "SELECT ticket_code, status, feedback FROM ticket WHERE request_by = ? LIMIT ? OFFSET ?";
                            $result = $db->fetchAll($ticket_list_sql, [$requestBy, $items_per_page, $offset]);

                            if ($total_items == 0) { ?>
                                <tr class="odd:bg-white even:bg-[#4a7c7d]/5">
                                    <td class="px-4 py-2 border-b border-[#4a7c7d]/20 text-center text-gray-400" colspan="4">No ticket submitted</td>
                                </tr>
                            <?php }  else { 
                                foreach ($result as $t) { ?>
                                    <tr class="odd:bg-white even:bg-[#4a7c7d]/5">
                                        <td class="px-4 py-2 border-b border-[#4a7c7d]/20"><?=$t['ticket_code']?></td>
                                        <td class="px-4 py-2 border-b border-[#4a7c7d]/20 text-center"><span class="py-1 px-2 rounded-xl shadow-lg <?=$ticket_status[$t['status']]?>"><?=strtoupper($t['status'])?></span></td>
                                        <td class="px-4 py-2 border-b border-[#4a7c7d]/20 text-center">
                                            <a href="ticket_d.php?ticketcode=<?=$t['ticket_code']?>" class="text-green-600 hover:underline">
                                                View
                                                <?php 
                                                    if(strtoupper($t['feedback']) == "NO" && strtoupper($t['status']) == "DONE") { ?>
                                                    <i class="fa fa-exclamation-circle ms-1 text-yellow-500" title="Feedback required"></i>
                                                <?php } ?>
                                            </a>
                                        </td>
                                    </tr>
                                <?php }
                            } ?>
                            </tbody>
                        </table>
                        <!-- Pagination Navigation -->
                        <?php if ($total_pages > 1): ?>
                        <div class="flex items-center justify-between border-t border-[#4a7c7d]/20 px-4 py-3 sm:px-6 mt-4">
                            <div class="hidden sm:flex sm:flex-1 sm:items-center sm:justify-between">
                                <div>
                                    <p class="text-sm text-[#4a7c7d]">
                                        Showing 
                                        <span class="font-medium"><?= min(($page-1) * $items_per_page + 1, $total_items) ?></span>
                                        to 
                                        <span class="font-medium"><?= min($page * $items_per_page, $total_items) ?></span>
                                        of 
                                        <span class="font-medium"><?= $total_items ?></span> results
                                    </p>
                                </div>
                            <div>
                                <nav class="isolate inline-flex items-center gap-1" aria-label="Pagination">
                                    <!-- Previous arrow -->
                                    <?php if ($page > 1): ?>
                                    <a href="?page=<?= ($page-1) ?>" class="relative inline-flex items-center rounded-md px-2 py-2 text-[#4a7c7d] hover:bg-[#4a7c7d]/5">
                                        <span class="sr-only">Previous</span>
                                        <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M12.79 5.23a.75.75 0 01-.02 1.06L8.832 10l3.938 3.71a.75.75 0 11-1.04 1.08l-4.5-4.25a.75.75 0 010-1.08l4.5-4.25a.75.75 0 011.06.02z" clip-rule="evenodd" />
                                        </svg>
                                    </a>
                                    <?php else: ?>
                                        <span class="relative inline-flex items-center rounded-md px-2 py-2 text-gray-300 cursor-not-allowed">
                                            <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M12.79 5.23a.75.75 0 01-.02 1.06L8.832 10l3.938 3.71a.75.75 0 11-1.04 1.08l-4.5-4.25a.75.75 0 010-1.08l4.5-4.25a.75.75 0 011.06.02z" clip-rule="evenodd" />
                                            </svg>
                                        </span>
                                    <?php endif; ?>
                                    <!-- Page numbers (non-clickable) -->
                                    <?php
                                    $range = 1;
                                    $start_page = max(1, $page - $range);
                                    $end_page = min($total_pages, $page + $range);
                                    for ($i = $start_page; $i <= $end_page; $i++):
                                    ?>
                                        <span class="relative inline-flex items-center px-4 py-2 text-sm font-medium <?= $i === $page ? 'bg-[#4a7c7d] text-white' : 'text-[#4a7c7d]' ?> rounded-md">
                                            <?= $i ?>
                                        </span>
                                    <?php endfor; ?>
                                    <!-- Next arrow -->
                                    <?php if ($page < $total_pages): ?>
                                        <a href="?page=<?= ($page+1) ?>" class="relative inline-flex items-center rounded-md px-2 py-2 text-[#4a7c7d] hover:bg-[#4a7c7d]/5">
                                            <span class="sr-only">Next</span>
                                            <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M7.21 14.77a.75.75 0 01.02-1.06L11.168 10 7.23 6.29a.75.75 0 111.04-1.08l4.5 4.25a.75.75 0 010 1.08l-4.5 4.25a.75.75 0 01-1.06-.02z" clip-rule="evenodd" />
                                            </svg>
                                        </a>
                                    <?php else: ?>
                                        <span class="relative inline-flex items-center rounded-md px-2 py-2 text-gray-300 cursor-not-allowed">
                                            <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M7.21 14.77a.75.75 0 01.02-1.06L11.168 10 7.23 6.29a.75.75 0 111.04-1.08l4.5 4.25a.75.75 0 010 1.08l-4.5 4.25a.75.75 0 01-1.06-.02z" clip-rule="evenodd" />
                                            </svg>
                                        </span>
                                    <?php endif; ?>
                                </nav>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</main>
<script>
    document.getElementById('fileUpload').addEventListener('change', function(e) {
        const file = e.target.files[0];
        const fileNameDisplay = document.getElementById('file-name');
        const errorMessage = document.getElementById('error-message');
        if (file) {
            const fileName = file.name;
            const fileType = file.type;
            const fileSize = file.size / (1024 * 1024); // Convert to MB
            const allowedTypes = ['image/png', 'image/jpeg', 'application/pdf'];
            const maxSize = 10; // 10MB limit
            if (!allowedTypes.includes(fileType)) {
                errorMessage.textContent = 'Only PNG, JPEG, or PDF files are allowed.';
                e.target.value = '';  // Reset file input
                fileNameDisplay.textContent = '';
            } else if (fileSize > maxSize) {
                errorMessage.textContent = 'File size must not exceed 10MB.';
                e.target.value = '';  // Reset file input
                fileNameDisplay.textContent = '';
            } else {
                fileNameDisplay.textContent = 'Selected file: ' + fileName;
                errorMessage.textContent = '';  // Clear error message
            }
        } else {
            fileNameDisplay.textContent = '';
            errorMessage.textContent = '';
        }
    });
</script>
<?php 
include('components/footer.php');
?>