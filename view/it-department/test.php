<?php
session_start();
if(isset($_POST['acquire_ticket'])) {
    $ticketcode = $_POST['ticket_code'];
    $acquiredBy = $_POST['acquired_by']; 


    echo $ticketcode.'-'.$acquiredBy;
    // $updateTiketAcquired = "UPDATE ticket SET assigned_to = ?, priority_level = 'HIGH', status= 'ACCEPTED', updated_date = CURRENT_TIMESTAMP() WHERE ticket_code = ?";
    // $resultUpdateTicketAcquired = $db->query($updateTiketAcquired, [$assignto, $ticketcode]);

    // if (!$resultUpdateTicketAcquired) {
    //     $_SESSION['notif_message'] = "Something went wrong acquiring ticket.";
    //     $_SESSION['notif_status'] = "error";
    //     header("Location: ticket_incoming.php");
    //     exit();
    // } else {
    //     $_SESSION['notif_message'] = "Ticket acquired successfully.";
    //     $_SESSION['notif_status'] = "success";
    //     header("Location: ticket_incoming.php");
    //     exit();
    // }
}