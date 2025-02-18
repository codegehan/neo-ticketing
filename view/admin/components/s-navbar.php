<?php 
$incomingSql = "SELECT COUNT(ticket_no) as incoming FROM ticket WHERE `status` = 'WAITING FOR ACTION'";
$incoming = $db->fetchAll($incomingSql);

$accountSql = "SELECT COUNT(account_no) as account FROM account WHERE is_verified != 'YES'";
$account = $db->fetchAll($accountSql);
?>
<div class="flex flex-1">
<!-- Left Sidebar -->
<aside id="sidebar" class="bg-secondary w-64 fixed top-12 left-0 bottom-0 p-4 hidden md:block overflow-y-auto">
    <nav>
        <ul class="space-y-2">
            <li>
                <a href="./" id="index-link" class="block p-2 rounded hover:bg-primary hover:text-white transition-colors duration-200">
                    <i class="fas fa-home mr-2"></i> Dashboard
                </a>
            </li>
            <li>
                <a href="ticket_incoming.php" id="incoming-link" class="block p-2 rounded hover:bg-primary hover:text-white transition-colors duration-200">
                    <i class="fas fa-ticket mr-2"></i> Incoming Ticket
                    <?php 
                        if($incoming[0]["incoming"] != 0) { ?>
                            <span class="text-[10px] bg-yellow-300 px-[4px] rounded-lg"><?=$incoming[0]["incoming"]?></span>
                    <?php } ?>
                </a>
            </li>
            <li>
                <a href="ticket_ongoing.php" id="ongoing-link" class="block p-2 rounded hover:bg-primary hover:text-white transition-colors duration-200">
                    <i class="fas fa-ticket mr-2"></i> Ongoing Ticket
                </a>
            </li>
            <li>
                <a href="ticket_completed.php" id="completed-link" class="block p-2 rounded hover:bg-primary hover:text-white transition-colors duration-200">
                    <i class="fas fa-ticket mr-2"></i> Completed Ticket
                </a>
            </li>
            <li>
                <a href="account.php" id="account-link" class="block p-2 rounded hover:bg-primary hover:text-white transition-colors duration-200">
                    <i class="fas fa-users mr-2"></i> Accounts
                    <?php 
                        if($account[0]["account"] != 0) { ?>
                            <span class="text-[10px] bg-yellow-300 px-[4px] rounded-lg"><?=$account[0]["account"]?></span>
                    <?php } ?>
                </a>
            </li>
            <li>
                <a href="settings.php" id="settings-link" class="block p-2 rounded hover:bg-primary hover:text-white transition-colors duration-200">
                    <i class="fas fa-cog mr-2"></i> Settings
                </a>
            </li>      
        </ul>
    </nav>
</aside>

<div class="w-64 hidden md:block flex-shrink-0"></div>