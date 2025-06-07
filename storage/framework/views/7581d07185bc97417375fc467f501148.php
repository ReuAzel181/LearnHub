<nav class="tool-navigation">
    <a href="/notes" class="nav-item">
        <i class="fas fa-sticky-note"></i>
        Notes
    </a>
    <a href="/calculator" class="nav-item">
        <i class="fas fa-calculator"></i>
        Calculator
    </a>
    <a href="/draw" class="nav-item">
        <i class="fas fa-paint-brush"></i>
        Draw
    </a>
    <a href="/dictionary" class="nav-item">
        <i class="fas fa-book"></i>
        Dictionary
    </a>
</nav>

<style>
.tool-navigation {
    display: flex;
    gap: 1rem;
    margin-bottom: 2rem;
    background: white;
    padding: 1rem;
    border-radius: 12px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.05);
}

.nav-item {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.75rem 1.5rem;
    border-radius: 8px;
    color: #2c3e50;
    text-decoration: none;
    transition: all 0.2s;
}

.nav-item:hover {
    background: #f8f9fa;
}

.nav-item.active {
    background: #3498db;
    color: white;
}

.nav-item i {
    font-size: 1.1rem;
}
</style>

<script>
// Update active state based on current URL
function updateActiveNavItem() {
    const path = window.location.pathname;
    document.querySelectorAll('.nav-item').forEach(item => {
        item.classList.toggle('active', item.getAttribute('href') === path);
    });
}

document.addEventListener('DOMContentLoaded', updateActiveNavItem);
window.addEventListener('popstate', updateActiveNavItem);
</script> <?php /**PATH D:\XAMPP\htdocs\LearnHub\resources\views/components/navigation.blade.php ENDPATH**/ ?>