import './bootstrap';
import './validation';

import Alpine from 'alpinejs';
import Chart from 'chart.js/auto';

// Make Chart globally available
window.Chart = Chart;

window.Alpine = Alpine;

Alpine.start();

// Theme Toggle Functionality
document.addEventListener('DOMContentLoaded', function() {
    const themeToggle = document.getElementById('theme-toggle');
    const themeToggleMobile = document.getElementById('theme-toggle-mobile');
    
    if (themeToggle) {
        themeToggle.addEventListener('click', toggleTheme);
    }
    
    if (themeToggleMobile) {
        themeToggleMobile.addEventListener('click', toggleTheme);
    }
    
    function toggleTheme() {
        const html = document.documentElement;
        const isDark = html.classList.contains('dark');
        const newTheme = isDark ? 'light' : 'dark';
        
        // Update HTML class immediately
        if (newTheme === 'dark') {
            html.classList.add('dark');
        } else {
            html.classList.remove('dark');
        }
        
        // Update UI elements
        updateThemeUI(newTheme);
        
        // Save to database (non-blocking)
        saveThemeToDatabase(newTheme);
    }
    
    function updateThemeUI(theme) {
        const isDark = theme === 'dark';
        
        // Desktop icons
        const lightIcon = document.getElementById('theme-toggle-light-icon');
        const darkIcon = document.getElementById('theme-toggle-dark-icon');
        
        if (lightIcon && darkIcon) {
            if (isDark) {
                lightIcon.classList.add('hidden');
                darkIcon.classList.remove('hidden');
            } else {
                lightIcon.classList.remove('hidden');
                darkIcon.classList.add('hidden');
            }
        }
        
        // Mobile icons and text
        const lightIconMobile = document.getElementById('theme-toggle-light-icon-mobile');
        const darkIconMobile = document.getElementById('theme-toggle-dark-icon-mobile');
        const themeTextMobile = document.getElementById('theme-toggle-text-mobile');
        
        if (lightIconMobile && darkIconMobile && themeTextMobile) {
            if (isDark) {
                lightIconMobile.classList.add('hidden');
                darkIconMobile.classList.remove('hidden');
                themeTextMobile.textContent = 'Açık Temaya Geç';
            } else {
                lightIconMobile.classList.remove('hidden');
                darkIconMobile.classList.add('hidden');
                themeTextMobile.textContent = 'Koyu Temaya Geç';
            }
        }
        
        // Update button title
        if (themeToggle) {
            themeToggle.title = isDark ? 'Açık temaya geç' : 'Koyu temaya geç';
        }
    }
    
    function saveThemeToDatabase(theme) {
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        
        fetch('/profile/theme', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                theme: theme,
                theme_color: getCurrentThemeColor()
            })
        })
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP ${response.status}: ${response.statusText}`);
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                showThemeChangeMessage('Tema başarıyla güncellendi!', 'success');
            } else {
                console.warn('Theme update response:', data);
                showThemeChangeMessage('Tema güncellendi ama sunucu yanıtı beklenmedik', 'warning');
            }
        })
        .catch(error => {
            console.error('Theme update error:', error);
            showThemeChangeMessage('Tema güncellenirken hata oluştu!', 'error');
            
            // Don't revert theme change on error - keep user's choice
            // The theme will persist until page refresh
        });
    }
    
    function getCurrentThemeColor() {
        const body = document.body;
        const accent = body.getAttribute('data-accent');
        
        // Valid theme colors
        const validColors = ['blue', 'green', 'purple', 'orange'];
        
        // If current accent is valid, use it; otherwise default to blue
        if (validColors.includes(accent)) {
            return accent;
        }
        
        return 'blue';
    }
    
    function showThemeChangeMessage(message, type) {
        // Remove existing messages
        const existingMessages = document.querySelectorAll('.theme-message');
        existingMessages.forEach(msg => msg.remove());
        
        // Create message element
        const messageDiv = document.createElement('div');
        messageDiv.className = `theme-message fixed top-4 right-4 z-50 px-6 py-3 rounded-lg shadow-lg transition-all duration-300 transform translate-x-full ${
            type === 'success' 
                ? 'bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-200 border border-green-200 dark:border-green-800' 
                : type === 'warning'
                ? 'bg-yellow-100 dark:bg-yellow-900/30 text-yellow-800 dark:text-yellow-200 border border-yellow-200 dark:border-yellow-800'
                : 'bg-red-100 dark:bg-red-900/30 text-red-800 dark:text-red-200 border border-red-200 dark:border-red-800'
        }`;
        
        messageDiv.innerHTML = `
            <div class="flex items-center space-x-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="${
                        type === 'success' 
                            ? 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z' 
                            : type === 'warning'
                            ? 'M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.34 16.5c-.77.833.192 2.5 1.732 2.5z'
                            : 'M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z'
                    }"></path>
                </svg>
                <span class="font-medium">${message}</span>
            </div>
        `;
        
        document.body.appendChild(messageDiv);
        
        // Animate in
        setTimeout(() => {
            messageDiv.classList.remove('translate-x-full');
        }, 100);
        
        // Remove after 3 seconds
        setTimeout(() => {
            messageDiv.classList.add('translate-x-full');
            setTimeout(() => {
                if (messageDiv.parentNode) {
                    messageDiv.parentNode.removeChild(messageDiv);
                }
            }, 300);
        }, 3000);
    }
    
    // Changelog Modal Functionality
    window.showChangelog = function() {
        // Create modal
        const modal = document.createElement('div');
        modal.className = 'fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50';
        modal.id = 'changelog-modal';
        
        modal.innerHTML = `
            <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-md bg-white dark:bg-gray-800">
                <div class="mt-3">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white">Sürüm Notları - v${document.querySelector('[data-version]').getAttribute('data-version')}</h3>
                        <button onclick="closeChangelog()" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                    
                    <div class="max-h-96 overflow-y-auto text-sm text-gray-700 dark:text-gray-300">
                        <div class="space-y-4">
                            <div class="border-l-4 border-blue-500 pl-4">
                                <h4 class="font-semibold text-blue-600 dark:text-blue-400">🚀 Major Release - Sistem Yeniden Yapılandırması</h4>
                                <ul class="mt-2 space-y-1 text-gray-600 dark:text-gray-400">
                                    <li>• Mikroservis mimarisi</li>
                                    <li>• API öncelikli geliştirme</li>
                                    <li>• Docker container desteği</li>
                                    <li>• CI/CD Pipeline</li>
                                </ul>
                            </div>
                            
                            <div class="border-l-4 border-green-500 pl-4">
                                <h4 class="font-semibold text-green-600 dark:text-green-400">🔐 Enterprise Security</h4>
                                <ul class="mt-2 space-y-1 text-gray-600 dark:text-gray-400">
                                    <li>• OAuth 2.0 kimlik doğrulama</li>
                                    <li>• JWT Token desteği</li>
                                    <li>• Gelişmiş rol tabanlı erişim kontrolü</li>
                                    <li>• Detaylı denetim kayıtları</li>
                                </ul>
                            </div>
                            
                            <div class="border-l-4 border-yellow-500 pl-4">
                                <h4 class="font-semibold text-yellow-600 dark:text-yellow-400">🎨 UI/UX İyileştirmeleri</h4>
                                <ul class="mt-2 space-y-1 text-gray-600 dark:text-gray-400">
                                    <li>• Durum göstergeleri (renkli noktalar)</li>
                                    <li>• Müşteri sütunu genişletildi</li>
                                    <li>• Modern ve estetik arayüz</li>
                                    <li>• Responsive tasarım</li>
                                </ul>
                            </div>
                            
                            <div class="border-l-4 border-purple-500 pl-4">
                                <h4 class="font-semibold text-purple-600 dark:text-purple-400">📊 Performans Optimizasyonları</h4>
                                <ul class="mt-2 space-y-1 text-gray-600 dark:text-gray-400">
                                    <li>• Lazy loading</li>
                                    <li>• Revenue cache sistemi</li>
                                    <li>• Session hardening</li>
                                    <li>• Database optimizasyonları</li>
                                </ul>
                            </div>
                        </div>
                        
                        <div class="mt-6 pt-4 border-t border-gray-200 dark:border-gray-700">
                            <p class="text-xs text-gray-500 dark:text-gray-400">
                                Tüm sürüm notları için <a href="/changelog" class="text-blue-500 hover:text-blue-700 dark:text-blue-400 dark:hover:text-blue-300">CHANGELOG.md</a> dosyasını inceleyebilirsiniz.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        `;
        
        document.body.appendChild(modal);
        
        // Close modal when clicking outside
        modal.addEventListener('click', function(e) {
            if (e.target === modal) {
                closeChangelog();
            }
        });
    };
    
    window.closeChangelog = function() {
        const modal = document.getElementById('changelog-modal');
        if (modal) {
            modal.remove();
        }
    };
});
