<!-- Header Component -->
<header class="header">
    <div class="header-left">
        <button class="menu-toggle">
            <i class="fas fa-bars"></i>
        </button>
        <div class="logo">LEARNHUB</div>
    </div>
    
    <div class="search-container">
        <div class="search-wrapper">
            <i class="fas fa-search"></i>
            <input type="text" class="search-input" placeholder="Search">
        </div>
    </div>

    <div class="header-right">
        <button class="header-icon">
            <i class="fas fa-wrench"></i>
        </button>
        <button class="header-icon">
            <i class="fas fa-cog"></i>
        </button>
        <button class="header-icon">
            <i class="fas fa-user"></i>
        </button>
    </div>
</header>

<style>
.header {
    display: flex;
    align-items: center;
    padding: 0.75rem 1.5rem;
    background: white;
    border-bottom: 1px solid #eee;
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    z-index: 1000;
    height: 64px;
}

.header-left {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.menu-toggle {
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    border: none;
    background: transparent;
    border-radius: 8px;
    cursor: pointer;
    color: #2c3e50;
    transition: background-color 0.2s;
}

.menu-toggle:hover {
    background-color: #f8f9fa;
}

.logo {
    font-size: 1.25rem;
    font-weight: 700;
    color: #2c3e50;
    letter-spacing: 0.5px;
}

.search-container {
    flex: 1;
    max-width: 600px;
    margin: 0 2rem;
}

.search-wrapper {
    position: relative;
    width: 100%;
}

.search-wrapper i {
    position: absolute;
    left: 1rem;
    top: 50%;
    transform: translateY(-50%);
    color: #95a5a6;
}

.search-input {
    width: 100%;
    padding: 0.75rem 1rem 0.75rem 2.5rem;
    border: none;
    border-radius: 8px;
    background: #f8f9fa;
    font-size: 0.95rem;
    color: #2c3e50;
    transition: all 0.2s;
}

.search-input:focus {
    outline: none;
    background: #fff;
    box-shadow: 0 0 0 2px #3498db20;
}

.header-right {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.header-icon {
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    border: none;
    background: transparent;
    border-radius: 8px;
    cursor: pointer;
    color: #2c3e50;
    transition: all 0.2s;
}

.header-icon:hover {
    background-color: #f8f9fa;
    color: #3498db;
}

@media (max-width: 768px) {
    .search-container {
        margin: 0 1rem;
    }
    
    .header-right {
        display: none;
    }
}

@media (max-width: 480px) {
    .search-container {
        display: none;
    }
}
</style> <?php /**PATH D:\XAMPP\htdocs\LearnHub\resources\views/components/header.blade.php ENDPATH**/ ?>