<?php 
$notificationSql = "SELECT COUNT(*) as incoming FROM ticket WHERE `status` = 'WAITING FOR ACTION'";
$notificationResult = $db->fetchAll($notificationSql);
?>
<div class="flex flex-1">
<!-- Left Sidebar -->
<aside id="sidebar" class="bg-secondary w-64 p-4 hidden md:block">
    <nav>
        <ul class="space-y-2">
            <li>
                <a href="./" id="index-link" class="block p-2 rounded hover:bg-primary hover:text-white transition-colors duration-200">
                    <i class="fas fa-home mr-2"></i> Dashboard
                </a>
            </li>
            <li>
                <a href="ticket_incoming.php" id="incoming-link" class="block p-2 rounded hover:bg-primary hover:text-white transition-colors duration-200">
                    <i class="fas fa-ticket mr-2"></i> Incoming 
                    <?php 
                        if($notificationResult[0]["incoming"] != 0) { ?>
                            <span class="text-[10px] bg-yellow-300 px-[4px] rounded-lg"><?=$notificationResult[0]["incoming"]?></span>
                       <?php } ?>
                </a>
            </li>
            <li>
                <a href="record.php" id="record-link" class="block p-2 rounded hover:bg-primary hover:text-white transition-colors duration-200">
                    <i class="fas fa-file mr-2"></i> Record
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