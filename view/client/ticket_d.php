<?php 
include('components/header.php');
include('components/s-navbar.php');

$ticket_status = [
    "WAITING FOR ACTION" => "bg-yellow-500",
    "ACCEPTED" => "bg-blue-500",
    "DONE" => "bg-green-500"
];


$ticketCode = "";
if(!isset($_GET['ticketcode'])) {
    header('Location: ./');
    exit();
} else {
    $ticketCode = $_GET['ticketcode'];
    $ticket_details_sql = "SELECT t.*,a.fullname 
                        FROM ticket t 
                        LEFT JOIN account a ON t.assigned_to = a.account_no
                        WHERE t.ticket_code = ?";
    $result = $db->fetchAll($ticket_details_sql, [$ticketCode]);
    $t = $result[0];

    $progressClass = strtoupper($t['status']) == "WAITING FOR ACTION" ? 'w-[33%]' : (strtoupper($t['status']) == "ACCEPTED" ? 'w-[66%]' : (strtoupper($t['status']) == "DONE" ? 'w-[100%]' : 'w-[0%]'));
}

if (isset($_POST['submit_feedback'])) {
    $ticketCode = $_POST['ticket_code'];
    $feedbackMessage = $_POST['feedback_message'];
    $rating = $_POST['rating'];
    $assignedTo = $_POST['assigned_to'];

    $sql = "INSERT INTO ticket_feedback (ticket_code,feedback,rating,it_assigned) VALUES (?,?,?,?)";
    $result = $db->query($sql, [$ticketCode, $feedbackMessage, $rating, $assignedTo]);
    if ($result) {
        $update_ticket = "UPDATE ticket SET feedback = 'YES', updated_date = CURRENT_TIMESTAMP() WHERE ticket_code = ? ";
        $result_update = $db->query($update_ticket, [$ticketCode]);

        $_SESSION['notif_message'] = "Feedback sent successfully. Thank you!";
        $_SESSION['notif_status'] = "success";
        
    } else {
        $_SESSION['notif_message'] = "Failed to submit a feedback. Please try again.";
        $_SESSION['notif_status'] = "error";
    }
    header("Location: ./");
    exit();
}

// reRequest-ticket
if(isset($_POST['reRequest-ticket'])) {
    $previousTicketCode = $_POST['previousTicketCode'];
    $ticketcode = 'TC'. date('His') . rand(1000, 9999);
    $description = $_POST['ticket_description'];
    $category = $_POST['category_value'];
    $requestBy = $_SESSION['fn'];
    $status = "WAITING FOR ACTION"; // Default

    try {

        $updatePreviousTicket = "UPDATE ticket SET re_requested = 'YES' WHERE ticket_code = ?";
        $db->query($updatePreviousTicket, [$previousTicketCode]);

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
}

?>
<!-- Main Content Area -->
<main class="flex-1 p-4">
    <table class="min-w-full border border-[#4a7c7d]/20 text-sm">
        <thead class="bg-[#4a7c7d]">
            <tr>
                <th class="px-4 py-2 border text-left text-white w-1/4">Description</th>
                <th class="px-4 py-2 border text-left text-white">Value</th>
            </tr>
        </thead>
        <tbody>
            <tr class="odd:bg-white even:bg-[#4a7c7d]/5">
                <td class="px-4 py-2 border border-[#4a7c7d]/20">Ticket Code</td>
                <td class="px-4 py-2 border border-[#4a7c7d]/20"><?=$t['ticket_code']?></td>
            </tr>
            <tr class="odd:bg-white even:bg-[#4a7c7d]/5">
                <td class="px-4 py-2 border border-[#4a7c7d]/20 align-top">Description</td>
                <td class="px-4 py-2 border border-[#4a7c7d]/20"><?=strtoupper($t['description'])?></td>
            </tr>
            <tr class="odd:bg-white even:bg-[#4a7c7d]/5">
                <td class="px-4 py-2 border border-[#4a7c7d]/20">Category</td>
                <td class="px-4 py-2 border border-[#4a7c7d]/20"><?=strtoupper($t['category'])?></td>
            </tr>
            <tr class="odd:bg-white even:bg-[#4a7c7d]/5">
                <td class="px-4 py-2 border border-[#4a7c7d]/20">Request By</td>
                <td class="px-4 py-2 border border-[#4a7c7d]/20"><?=strtoupper($t['request_by'])?></td>
            </tr>
            <tr class="odd:bg-white even:bg-[#4a7c7d]/5">
                <td class="px-4 py-2 border border-[#4a7c7d]/20">Status</td>
                <td class="px-4 py-2 border border-[#4a7c7d]/20"><span class="py-1 px-2 rounded-xl shadow-lg <?=$ticket_status[$t['status']]?>"><?=strtoupper($t['status'])?></span></td>
            </tr>
            <tr class="odd:bg-white even:bg-[#4a7c7d]/5">
                <td class="px-4 py-2 border border-[#4a7c7d]/20">Priority Level</td>
                <td class="px-4 py-2 border border-[#4a7c7d]/20 <?= ($t['priority_level'] ?? null) === null ? 'italic' : 'font-bold' ?>"><?=strtoupper($t['priority_level'] ?? 'WAITING')?></td>
            </tr>
            <tr class="odd:bg-white even:bg-[#4a7c7d]/5">
                <td class="px-4 py-2 border border-[#4a7c7d]/20">Assigned To</td>
                <td class="px-4 py-2 border border-[#4a7c7d]/20 <?= ($t['assigned_to'] ?? null) === null ? 'italic' : 'font-bold' ?>"><?=strtoupper($t['fullname'] ?? 'WAITING')?></td>
            </tr>
        </tbody>
    </table>

    <div class="w-full mx-auto p-4 border-b-4">
        <!-- Progress Bar Container -->
        <div class="relative w-full h-6 bg-gray-200 rounded-full">
            <div id="progress-bar" class="h-full bg-white border border-[#4a7c7d]/50 rounded-full transition-all duration-500 ease-in-out">
                <div id="inner-progress" 
                    class="h-full bg-green-700 <?=$progressClass?> rounded-full"></div>
            </div>
        </div>

        <!-- Steps -->
        <div class="flex justify-between mt-4">
            <span id="step-1" class="text-xl font-semibold">Waiting</span>
            <span id="step-2" class="text-xl font-semibold">Accepted</span>
            <span id="step-3" class="text-xl font-semibold">Done</span>
        </div>
    </div>
    <?php 
    if (strtoupper($t['status']) == "DONE" && strtoupper($t['feedback']) == "NO") { ?>
    <form method="POST">
    <div class="mt-4">
        <h3 class="text-xl font-semibold text-gray-700">Feedback</h3>
        <!-- Feedback Textarea -->
        <input type="hidden" name="ticket_code" value="<?=$t['ticket_code']?>">
        <input type="hidden" name="assigned_to" value="<?=$t['assigned_to']?>">
        <textarea id="feedback" name="feedback_message" required rows="4" class="w-full mt-2 p-3 border border-[#4a7c7d]/20 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#4a7c7d]" placeholder="Enter your feedback here..."></textarea>
        <!-- Star Rating -->
        <div class="flex items-center mt-4">
            <span class="text-sm text-gray-600 mr-2">Rate your experience: <i class="text-red-500 text-xs">(Required)</i></span>
            <input type="hidden" value="0" id="rating_value" name="rating">
            <div id="star-rating" class="flex space-x-1">
                <!-- Star icons for rating using FontAwesome -->
                <i class="fas fa-star text-gray-400 cursor-pointer hover:text-yellow-400" onclick="setRating(1)"></i>
                <i class="fas fa-star text-gray-400 cursor-pointer hover:text-yellow-400" onclick="setRating(2)"></i>
                <i class="fas fa-star text-gray-400 cursor-pointer hover:text-yellow-400" onclick="setRating(3)"></i>
                <i class="fas fa-star text-gray-400 cursor-pointer hover:text-yellow-400" onclick="setRating(4)"></i>
                <i class="fas fa-star text-gray-400 cursor-pointer hover:text-yellow-400" onclick="setRating(5)"></i>
            </div>
        </div>
    </div>
    <div class="mt-4">
        <button type="sumbit" name="submit_feedback" 
            class="bg-[#4a7c7d] text-white py-2 px-4 rounded-md hover:bg-[#4a7c7d]/80 transition">
            Sumbit feedback
        </button>
    </div>
    </form>
    <?php } ?>
    <div class="mt-4">
    <?php 
        if($t["re_requested"] == "NO") { ?>
            <button type="button" 
                name="Add_Ticket" 
                title="Re-request ticket" 
                class="bg-green-700 text-white py-2 px-4 rounded-md hover:bg-green-700/80 transition"
                onclick="reRequestTicketModal('<?=$t['ticket_code'];?>', '<?=$t['category']?>','<?=$t['description']?>')"
            >
                Re-request Ticket
            </button>
        <?php }
    ?>
    </div>


<div id="reRequestModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden items-center justify-center">
    <div class="bg-white p-8 rounded-lg shadow-xl w-[650px]">
        <h2 class="text-xl font-bold text-primary mb-4">Re-request ticket: <span id="ticketid"></span>?</h2>
        <form id="assignForm" method="POST">
            <input type="hidden" id="previousTicketCode" name="previousTicketCode">
            <input type="hidden" id="category_value" name="category_value">
            <div class="mb-4">
                <label for="ticketTitle" class="block text-sm font-medium text-[#4a7c7d]">Category</label>
                <input disabled name="category" id="category" name="category"
                class="mt-1 block w-full rounded-md border border-[#4a7c7d]/30 
                    shadow-sm focus:ring-[#4a7c7d] focus:border-[#4a7c7d] 
                    bg-white placeholder-[#4a7c7d]/50 p-2">
            </div>
            <div class="mb-4">
                <label for="ticketDescription" class="block text-sm font-medium text-[#4a7c7d]">Description</label>
                <textarea name="ticket_description" id="description" rows="4" 
                    class="mt-1 block w-full rounded-md border border-[#4a7c7d]/30 
                        shadow-sm focus:ring-[#4a7c7d] focus:border-[#4a7c7d] 
                        bg-white placeholder-[#4a7c7d]/50 p-2">
                </textarea>
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
            <div class="flex justify-end">
                <button type="button" onclick="closeReRequestTicketModal()" class="mr-2 px-4 py-2 text-xs font-medium text-gray-700 bg-gray-200 rounded-md hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-400">Cancel</button>
                <button type="submit" name="reRequest-ticket" class="px-4 py-2 text-xs font-medium text-white bg-primary rounded-md hover:bg-primary-dark focus:outline-none focus:ring-2 focus:ring-primary">Re-request</button>
            </div>
        </form>
    </div>
</div>


</main>
<script>
    let currentRating = 0;
    function setRating(rating) {
        currentRating = rating;
        const stars = document.querySelectorAll('#star-rating i');
        const ratingInput = document.getElementById('rating_value');
        ratingInput.value = rating;
        // Reset all stars to default color
        stars.forEach((star, index) => {
            if (index < rating) {
                star.classList.add('text-yellow-400'); // Highlight stars up to the current rating
            } else {
                star.classList.remove('text-yellow-400'); // Remove highlight for the rest
            }
        });
    }

    function reRequestTicketModal(ticketId, category, description) {
        const modal = document.getElementById('reRequestModal');
        // const ticketCodeDisplay = document.getElementById('ticket_code_display');
        if (modal) {
            document.getElementById('ticketid').innerText = ticketId;
            document.getElementById('previousTicketCode').value = ticketId;
            document.getElementById('category').value = category;
            document.getElementById('category_value').value = category;
            document.getElementById('description').value = description;
            // ticketCodeDisplay.innerText = ticketId;
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }
    }
    function closeReRequestTicketModal() {
        const modal = document.getElementById('reRequestModal');
        if (modal) {
            modal.classList.remove('flex');
            modal.classList.add('hidden');
        }
    }
</script>
<?php 
include('components/footer.php');
?>