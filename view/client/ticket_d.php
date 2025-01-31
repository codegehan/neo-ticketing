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
            class="bg-[#4a7c7d] text-white py-2 px-4 rounded-md 
            hover:bg-[#4a7c7d]/80 transition">
            Sumbit feedback
        </button>
    </div>
    </form>
    <?php } ?>
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
</script>
<?php 
include('components/footer.php');
?>