<?php 
ob_start();
include('components/header.php');
include('components/s-navbar.php');

$sql = "SELECT
        (SELECT COUNT(ticket_no) 
            FROM ticket
            WHERE `status` = 'WAITING FOR ACTION'
        ) AS Pending_Tickets,
        (SELECT COUNT(ticket_no) 
            FROM ticket
            WHERE `status` = 'ACCEPTED'
        ) AS Accepted_Tickets,
        (SELECT COUNT(ticket_no) 
            FROM ticket
            WHERE `status` = 'DONE'
        ) AS Completed_Tickets,
        (SELECT COUNT(account_no) 
            FROM account
            WHERE is_verified = 'YES'
        ) AS Verified_Accounts,
        (SELECT COUNT(account_no) 
            FROM account
            WHERE is_verified = 'NO'
        ) AS Pending_Accounts";
$result = $db->fetchAll($sql);
$d = $result[0];


$topPerformingEmployee = "SELECT 
                            a.account_no, 
                            a.fullname, 
                            a.position,
                            p.position_name,
                            COALESCE(ROUND(AVG(tf.rating), 1), 0) as rating
                            FROM account a
                            LEFT JOIN ticket_feedback tf ON a.account_no = tf.it_assigned
                            LEFT JOIN position p ON a.position = p.position_id
                            WHERE a.department = 1
                            GROUP BY a.account_no";
$topPerformingEmployeeList = $db->fetchAll($topPerformingEmployee);

$feedBackList = "SELECT 
                tf.feedback, 
                tf.rating, 
                d.department_name, 
                CASE 
                    WHEN TIMESTAMPDIFF(MINUTE, tf.date_added, NOW()) < 60 
                        THEN CONCAT(TIMESTAMPDIFF(MINUTE, tf.date_added, NOW()), ' minutes ago')
                    WHEN TIMESTAMPDIFF(HOUR, tf.date_added, NOW()) < 24 
                        THEN CONCAT(TIMESTAMPDIFF(HOUR, tf.date_added, NOW()), ' hour/s ago')
                    ELSE 
                        CONCAT(TIMESTAMPDIFF(DAY, tf.date_added, NOW()), ' day/s ago')
                END AS time_ago
                FROM ticket_feedback tf
                LEFT JOIN ticket t ON tf.ticket_code = t.ticket_code
                LEFT JOIN account a ON t.request_by = a.fullname
                LEFT JOIN department d ON a.department = d.department_id
                LIMIT 10;";
$feedBackListResult = $db->fetchAll($feedBackList);
?>
<div class="w-full px-8 py-8">
    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 2xl:grid-cols-5 gap-6 mb-8">
        <!-- Pending Tickets Card -->
        <div class="bg-white rounded-lg shadow-lg p-6 border-l-4 border-primary hover:shadow-xl transition duration-300">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">Pending Tickets</p>
                    <h3 class="text-2xl font-bold text-gray-700"><?=$d['Pending_Tickets']?></h3>
                </div>
                <div class="bg-primary/10 rounded-full p-3">
                    <i class="fas fa-ticket-alt text-2xl text-primary"></i>
                </div>
            </div>
        </div>

        <!-- Accepted Tickets Card -->
        <div class="bg-white rounded-lg shadow-lg p-6 border-l-4 border-secondary hover:shadow-xl transition duration-300">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">Accepted Tickets</p>
                    <h3 class="text-2xl font-bold text-gray-700"><?=$d['Accepted_Tickets']?></h3>
                </div>
                <div class="bg-secondary/10 rounded-full p-3">
                    <i class="fas fa-check-circle text-2xl text-primary"></i>
                </div>
            </div>
        </div>

        <!-- Completed Tickets Card -->
        <div class="bg-white rounded-lg shadow-lg p-6 border-l-4 border-green-500 hover:shadow-xl transition duration-300">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">Completed Tickets</p>
                    <h3 class="text-2xl font-bold text-gray-700"><?=$d['Completed_Tickets']?></h3>
                </div>
                <div class="bg-green-100 rounded-full p-3">
                    <i class="fas fa-check-double text-2xl text-primary"></i>
                </div>
            </div>
        </div>

        <!-- Pending Accounts Card -->
        <div class="bg-white rounded-lg shadow-lg p-6 border-l-4 border-primary hover:shadow-xl transition duration-300">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">Pending Accounts</p>
                    <h3 class="text-2xl font-bold text-gray-700"><?=$d['Pending_Accounts']?></h3>
                </div>
                <div class="bg-primary/10 rounded-full p-3">
                    <i class="fas fa-user-clock text-2xl text-primary"></i>
                </div>
            </div>
        </div>

        <!-- Verified Accounts Card -->
        <div class="bg-white rounded-lg shadow-lg p-6 border-l-4 border-secondary hover:shadow-xl transition duration-300">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">Verified Accounts</p>
                    <h3 class="text-2xl font-bold text-gray-700"><?=$d['Verified_Accounts']?></h3>
                </div>
                <div class="bg-secondary/10 rounded-full p-3">
                    <i class="fas fa-user-shield text-2xl text-primary"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Top Performing Employees Section -->
    <div class="bg-white rounded-lg shadow-lg p-6">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-xl font-bold text-gray-800"><i class="fas fa-star text-yellow-400"></i> Top Performing IT Employees</h2>
        </div>
        <div class="space-y-4">
            <?php foreach($topPerformingEmployeeList as $tp) : ?>
            <div class="flex items-center justify-between p-4 hover:bg-gray-50 rounded-lg transition duration-300">
                <div class="flex items-center space-x-4">
                    <div class="bg-primary/10 rounded-full p-3">
                        <i class="fas fa-user text-xl text-primary"></i>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-800"><?=$tp['fullname']?></h3>
                        <p class="text-sm text-gray-500"><?=$tp['position_name']?></p>
                    </div>
                </div>
                <div class="flex items-center">
                    <div class="flex space-x-1 mr-2">
                        <i class="fas fa-star text-yellow-400"></i>
                        <span class="font-bold text-gray-700"><?=$tp['rating']?></span>
                    </div>
                    <div class="w-24 h-2 bg-gray-200 rounded-full">
                        <div class="w-[<?=($tp['rating']/5)*100?>%] h-full bg-yellow-400 rounded-full"></div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Department Feedback Section -->
    <div class="bg-white rounded-lg shadow-lg p-6 mt-8">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-xl font-bold text-gray-800"><i class="fas fa-comments"></i> Department Feedback</h2>
        </div>

        <div class="space-y-6">
            <?php foreach($feedBackListResult as $fb) :?>
            <div class="border-b border-gray-100 pb-6">
                <div class="flex justify-between items-start mb-3">
                    <div class="flex items-center space-x-3">
                        <div class="bg-blue-100 rounded-full px-2 py-1">
                            <i class="fas fa-user text-blue-500"></i>
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-800"><?=$fb['department_name']?></h3>
                            <p class="text-sm text-gray-500"><?=$fb['time_ago']?></p>
                        </div>
                    </div>
                    <div class="flex items-center text-yellow-400">
                        <?php for($i=0;$fb['rating'] > $i;$i++) : ?>
                            <i class="fas fa-star"></i>
                        <?php endfor; ?>
                       <?php 
                        $feedbackRating = $fb['rating'];
                        $remaining = 5 - $feedbackRating;
                        for ($i=0; $remaining > $i;$i++) { ?>
                            <i class="fa-regular fa-star"></i>
                        <?php } ?>
                    </div>
                </div>
                <p class="text-gray-600"><?=$fb['feedback']?></p>
            </div>
            <?php endforeach; ?>
        </div>

        <!-- View More Button -->
        <!-- <div class="mt-6 text-center">
            <button class="text-primary hover:text-primary/80 transition-colors text-sm font-semibold">
                View All Feedback <i class="fas fa-chevron-right ml-1"></i>
            </button>
        </div> -->

    </div>
</div>
<?php 
include('components/footer.php');
?>