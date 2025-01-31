<?php 
ob_start();
include('components/header.php');
include('components/s-navbar.php');

$sql = "SELECT ticket_code, request_by, request_date, category FROM ticket WHERE status = 'WAITING FOR ACTION'";
$incomingTickets = $db->fetchAll($sql);

$itDepartmentSql = "SELECT account_no, fullname, position.position_name FROM account LEFT JOIN position ON account.position = position.position_id WHERE department = 1";
$resultItDepartment = $db->fetchAll($itDepartmentSql);

if(isset($_POST['assign_ticket'])) {
    $ticketcode = $_POST['ticket_code'];
    $assignto = $_POST['assign_to']; 
    $priority = $_POST['priority'];

    $updateTiketAssigned = "UPDATE ticket SET assigned_to = ?, priority_level = ?,status= 'ACCEPTED', updated_date = CURRENT_TIMESTAMP() WHERE ticket_code = ?";
    $resultUpdateTicketAssigned = $db->query($updateTiketAssigned, [$assignto, $priority, $ticketcode]);

    if (!$resultUpdateTicketAssigned) {
        $_SESSION['notif_message'] = "Something went wrong assigning ticket.";
        $_SESSION['notif_status'] = "error";
        header("Location: ticket_incoming.php");
        exit();
    } else {
        $_SESSION['notif_message'] = "Ticket assigned successfully.";
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
                    <th class="px-4 py-2 text-left">Department</th>
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
                        <button class="bg-primary text-white px-3 py-1 rounded text-xs hover:bg-secondary-dark" onclick="openAssignModal(<?php echo $ticket['ticket_code']; ?>)">Assign</button>
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
    <div id="assignModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden items-center justify-center">
        <div class="bg-white p-8 rounded-lg shadow-xl w-96">
            <h2 class="text-xl font-bold text-primary mb-4">Assign Ticket</h2>
            <form id="assignForm" method="POST">
                <input type="hidden" id="ticket_code" name="ticket_code">
                <div class="mb-4">
                    <label for="assign_to" class="block text-sm font-medium text-gray-700 mb-2">Select IT Personnel</label>
                    <select id="assign_to" name="assign_to" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary">
                        <option value="">Select an IT personnel</option>
                        <?php 
                            foreach ($resultItDepartment as $it): ?>
                            <option value="<?=$it['account_no']?>"><?=$it['fullname']?> - <?=$it['position_name']?></option>
                        <?php endforeach ?>
                    </select>
                </div>
                <div class="mb-4">
                    <label for="priority" class="block text-sm font-medium text-gray-700 mb-2">Set Priority</label>
                    <select id="priority" name="priority" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary">
                        <option value="LOW">Low</option>
                        <option value="MEDIUM">Medium</option>
                        <option value="HIGN">High</option>
                    </select>
                </div>
                <div class="flex justify-end">
                    <button type="button" onclick="closeAssignModal()" class="mr-2 px-4 py-2 text-xs font-medium text-gray-700 bg-gray-200 rounded-md hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-400">Cancel</button>
                    <button type="submit" name="assign_ticket" class="px-4 py-2 text-xs font-medium text-white bg-primary rounded-md hover:bg-primary-dark focus:outline-none focus:ring-2 focus:ring-primary">Assign</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
function openAssignModal(ticketId) {
    const modal = document.getElementById('assignModal');
    if (modal) {
        document.getElementById('ticket_code').value = ticketId;
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }
}
function closeAssignModal() {
    const modal = document.getElementById('assignModal');
    if (modal) {
        modal.classList.remove('flex');
        modal.classList.add('hidden');
    }
}
</script>
<?php 
include('components/footer.php');
?>

