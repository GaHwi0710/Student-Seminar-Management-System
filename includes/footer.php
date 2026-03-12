<script>
    function showToast(message, type = 'success') {
        const container = document.getElementById('toast-container');
        const toast = document.createElement('div');
        toast.className = 'toast ' + type;
        
        let icon = type === 'success' ? '✓' : '✕';
        toast.innerHTML = `
            <div style="width: 20px; text-align: center; font-weight: bold; color: var(--${type === 'success' ? 'success' : 'danger'});">${icon}</div>
            <div style="flex: 1; font-size: 14px;">${message}</div>
        `;
        
        container.appendChild(toast);
        
        setTimeout(() => {
            toast.style.animation = 'toastOut 0.3s ease forwards';
            setTimeout(() => toast.remove(), 300);
        }, 3000);
    }
</script>
</body>
</html>