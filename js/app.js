document.getElementById('logoutBtn').addEventListener('click', function() {
    // Redirige vers le script PHP de déconnexion
    window.location.href = '/api/logout.php?redirect=/index.php';
});
