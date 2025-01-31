<?php 
include('components/header.php');
include('components/s-navbar.php');
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
}

if(isset($_POST['mark_ticket_done'])){
    $ticketCode = $_GET['ticketcode'];
    $checkTicketSql = "SELECT COUNT(ticket_code) AS count FROM ticket WHERE ticket_code = ?";
    $checkTicketExec = $db->fetchAll($checkTicketSql, [$ticketCode]);
    $resultCount = $checkTicketExec[0]['count'];
    if($result > 0) {
        $mark_ticket_done_sql = "UPDATE ticket SET `status` = 'DONE' WHERE `ticket_code` = ?";
        $result = $db->query($mark_ticket_done_sql, [$ticketCode]);
        if(!$result) {
            $_SESSION['notif_message'] = "Something went wrong during updating status.";
            $_SESSION['notif_status'] = "error";
            header("Location: ticket_d.php?ticketcode=$ticketCode");
            exit();
        } else {
            $_SESSION['notif_message'] = "Ticket $ticketCode marked as done.";
            $_SESSION['notif_status'] = "success";
            header("Location: ./");
            exit();
        }
    } else {
        $_SESSION['notif_message'] = "Ticket $ticketCode not found.";
        $_SESSION['notif_status'] = "error";
        header("Location: ticket_d.php?ticketcode=$ticketCode");
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
                <td class="px-4 py-2 border border-[#4a7c7d]/20">Request Date</td>
                <td class="px-4 py-2 border border-[#4a7c7d]/20"><?=strtoupper($t['request_date'])?></td>
            </tr>
            <tr class="odd:bg-white even:bg-[#4a7c7d]/5">
                <td class="px-4 py-2 border border-[#4a7c7d]/20">Priority Level</td>
                <td class="px-4 py-2 border border-[#4a7c7d]/20 <?= ($t['priority_level'] ?? null) === null ? 'italic' : 'font-bold' ?>"><?=strtoupper($t['priority_level'] ?? 'WAITING')?></td>
            </tr>
        </tbody>
    </table>

    <div class="relative w-full h-6 mt-4 rounded-full">
        <form method="POST">
            <button type="submit" name="mark_ticket_done" 
                class="bg-[#4a7c7d] text-white py-2 px-4 rounded-md 
                    hover:bg-[#4a7c7d]/80 transition">
                <i class="fas fa-check"></i>
                Mark as Done
            </button>
            <a href="./" 
                class="bg-green-600 text-white py-2 px-4 rounded-md 
                    hover:bg-green-700 transition">
                <i class="fas fa-arrow-left"></i>
                Back
            </a>
        </form>
    </div>
</main>
<?php 
include('components/footer.php');
?>