<?php 
ob_start();
include('components/header.php');
include('components/s-navbar.php');

$sql = "SELECT ticket_code, request_by, request_date, category FROM ticket WHERE status = 'DONE'";
$completedTicket = $db->fetchAll($sql);
ob_end_flush();
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
                </tr>
            </thead>
            <tbody>
                <?php if (count($completedTicket) > 0): ?>
                    <?php foreach ($completedTicket as $ticket): ?>
                        <tr class="border-b hover:bg-gray-100">
                            <td class="px-4 py-2"><?php echo $ticket['ticket_code']; ?></td>
                            <td class="px-4 py-2"><?php echo $ticket['request_by']; ?></td>
                            <td class="px-4 py-2"><?php echo $ticket['category']; ?></td>
                            <td class="px-4 py-2"><?php echo $ticket['request_date']; ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4" class="px-4 py-2 text-center">No record</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?php 
include('components/footer.php');
?>