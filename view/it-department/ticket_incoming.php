<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

ob_start();
include('components/header.php');
include('components/s-navbar.php');

$sql = "SELECT ticket_code, request_by, request_date, category FROM ticket WHERE status = 'WAITING FOR ACTION'";
$incomingTickets = $db->fetchAll($sql);

$itDepartmentSql = "SELECT account_no, fullname, position.position_name FROM account LEFT JOIN position ON account.position = position.position_id WHERE department = 1";
$resultItDepartment = $db->fetchAll($itDepartmentSql);

if(isset($_POST['acquire_ticket'])) {
    $ticketcode = $_POST['ticket_code'];
    $acquiredBy = $_SESSION['an']; 

    $updateTiketAcquired = "UPDATE ticket SET assigned_to = ?, `priority_level` = 'HIGH', `status`= 'ACCEPTED', updated_date = CURRENT_TIMESTAMP() WHERE ticket_code = ?";
    $resultUpdateTicketAcquired = $db->query($updateTiketAcquired, [$acquiredBy, $ticketcode]);
    if (!$resultUpdateTicketAcquired) {
        $_SESSION['notif_message'] = "Something went wrong acquiring ticket.";
        $_SESSION['notif_status'] = "error";
        header("Location: ticket_incoming.php");
        exit();
    } else {
        $_SESSION['notif_message'] = "Ticket acquired successfully.";
        $_SESSION['notif_status'] = "success";
        header("Location: ticket_incoming.php");
        exit();
    }
ob_end_flush();
}
?>

<div class="mx-auto w-full px-4 py-8 text-sm">
    <!-- Incoming Tickets Panel -->
    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <table class="w-full">
            <thead class="bg-primary text-white">
                <tr>
                    <th class="px-4 py-2 text-left">Code</th>
                    <th class="px-4 py-2 text-left">Fullname</th>
                    <th class="px-4 py-2 text-left">Category</th>
                    <th class="px-4 py-2 text-left">Date</th>
                    <th class="px-4 py-2">Action</th>
                </tr>
            </thead>
            <tbody>
            <?php if (count($incomingTickets) > 0): ?>
            <?php foreach ($incomingTickets as $ticket): ?>
                <tr class="border-b hover:bg-gray-100">
                    <td class="px-4 py-2"><?php echo $ticket['ticket_code']; ?></td>
                    <td class="px-4 py-2"><?php echo $ticket['request_by']; ?></td>
                    <td class="px-4 py-2"><?php echo $ticket['category']; ?></td>
                    <td class="px-4 py-2"><?php echo $ticket['request_date']; ?></td>
                    <td class="px-4 py-2 text-center">
                        <button class="bg-primary text-white px-3 py-1 rounded text-xs hover:bg-secondary-dark" onclick="acceptTicketModal('<?php echo $ticket['ticket_code'];?>')">Acquire</button>
                    </td>
                </tr>
            <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="5" class="px-4 py-2 text-center">No record</td>
                </tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Assignment Modal -->
    <div id="acquireModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden items-center justify-center">
        <div class="bg-white p-8 rounded-lg shadow-xl w-96">
            <h2 class="text-xl font-bold text-primary mb-4">Confirmation</h2>
            <form id="assignForm" method="POST">
                <input type="hidden" id="ticket_code" name="ticket_code">
                <h1 class="mb-6">Accept ticket request: <span class="font-bolder" id="ticket_code_display"></span> ?</h1>
                <div class="flex justify-end">
                    <button type="button" onclick="closeAcquireModal()" class="mr-2 px-4 py-2 text-xs font-medium text-gray-700 bg-gray-200 rounded-md hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-400">Cancel</button>
                    <button type="submit" name="acquire_ticket" class="px-4 py-2 text-xs font-medium text-white bg-primary rounded-md hover:bg-primary-dark focus:outline-none focus:ring-2 focus:ring-primary">Acquire</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
function acceptTicketModal(ticketId) {
    const modal = document.getElementById('acquireModal');
    const ticketCodeDisplay = document.getElementById('ticket_code_display');
    if (modal) {
        document.getElementById('ticket_code').value = ticketId;
        ticketCodeDisplay.innerText = ticketId;
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }
}
function closeAcquireModal() {
    const modal = document.getElementById('acquireModal');
    if (modal) {
        modal.classList.remove('flex');
        modal.classList.add('hidden');
    }
}
</script>
<?php 
include('components/footer.php');
?>

