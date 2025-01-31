</div>
<script>
// To be fixed
// const sidebarToggle = document.getElementById('sidebar-toggle');
// const sidebar = document.getElementById('sidebar');
// sidebarToggle.addEventListener('click', () => {
//     sidebar.classList.toggle('hidden');
// });


const path = window.location.pathname;
const linkMap = {
    "/ticketing/view/admin/" : "index-link",
    "/ticketing/view/admin/ticket_incoming.php" : "incoming-link",
    "/ticketing/view/admin/ticket_ongoing.php" : "ongoing-link",
    "/ticketing/view/admin/ticket_completed.php" : "completed-link",
    "/ticketing/view/admin/account.php" : "account-link",
    "/ticketing/view/admin/settings.php" : "settings-link",
}
const activeLinkId = linkMap[path];
if (activeLinkId) {
    const activeLink = document.getElementById(activeLinkId);
    if (activeLink) {
        activeLink.classList.add("active");
        activeLink.style.backgroundColor = "#4a7c7d";  // Set your preferred active background color
        activeLink.style.color = "white";  // Set text color to white for visibility
    } else {
        console.log("Element with ID not found:", activeLinkId);
    }
} else {
    console.log("No matching path found in linkMap.");
}
</script>
</body>
</html>