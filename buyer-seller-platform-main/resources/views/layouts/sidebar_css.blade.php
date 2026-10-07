<style>
    /* Shared Sidebar Dashboard & Product Styling */
    .sidebar-item-card {
        background: white;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.03);
        margin-bottom: 8px;
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .sidebar-item-card.active {
        background: #fff9f5;
        border: 1px solid #ff6a00;
        border-left-width: 4px;
        /* thick left border glow from Image 1 */
        box-shadow: 0 4px 6px -1px rgba(255, 106, 0, 0.1);
    }

    .submenu-container {
        position: relative;
        margin-left: 18px;
        padding-left: 14px;
        border-left: 1.5px dashed #fed7aa;
        margin-bottom: 12px;
        margin-top: -4px;
    }

    .submenu-item {
        position: relative;
        padding: 8px 0;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .submenu-dot {
        position: absolute;
        left: -19.5px;
        top: 50%;
        transform: translateY(-50%);
        width: 10px;
        height: 10px;
        background: #e2e8f0;
        border: 2px solid white;
        border-radius: 50%;
        z-index: 10;
        transition: background-color 0.2s;
    }

    .submenu-item.active .submenu-dot {
        background-color: #ff6a00;
    }
</style>