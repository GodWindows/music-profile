// Category Albums Management JavaScript

// Initialize category albums management functionality
function initCategoryAlbumsManagement() {
    // Most played albums modal
    const addMostPlayedBtn = document.getElementById('addMostPlayedBtn');
    const addMostPlayedModal = document.getElementById('addMostPlayedModal');
    const mostPlayedInput = document.getElementById('mostPlayedInput');
    const mostPlayedSuggestions = document.getElementById('mostPlayedSuggestions');
    const saveMostPlayedBtn = document.getElementById('saveMostPlayedBtn');
    const cancelMostPlayedBtn = document.getElementById('cancelMostPlayedBtn');
    const mostPlayedForm = document.getElementById('mostPlayedForm');
    
    if (addMostPlayedBtn && addMostPlayedModal && mostPlayedInput && mostPlayedSuggestions && saveMostPlayedBtn && cancelMostPlayedBtn && mostPlayedForm) {
        // Show modal
        addMostPlayedBtn.addEventListener('click', function() {
            addMostPlayedModal.classList.add('show');
            mostPlayedInput.focus();
            mostPlayedInput.value = '';
            lucide.createIcons();
        });
        
        // Hide modal on cancel
        cancelMostPlayedBtn.addEventListener('click', function() {
            addMostPlayedModal.classList.remove('show');
            hideSuggestionsOfCategory(mostPlayedSuggestions);
        });
        
        // Handle form submission
        mostPlayedForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const albumName = mostPlayedInput.value.trim();
            
            if (albumName.length < 1) {
                showNotification('Le nom de l\'album ne peut pas être vide', 'error');
                return;
            }
            
            if (albumName.length > 255) {
                showNotification('Le nom de l\'album est trop long', 'error');
                return;
            }
            
            addAlbumToCategory(albumName, 'most_played', mostPlayedInput, mostPlayedSuggestions);
        });
        
        // Close modal on outside click
        addMostPlayedModal.addEventListener('click', function(e) {
            if (e.target === this) {
                addMostPlayedModal.classList.remove('show');
                hideSuggestionsOfCategory(mostPlayedSuggestions);
            }
        });
        
        // Close modal on escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && addMostPlayedModal.classList.contains('show')) {
                addMostPlayedModal.classList.remove('show');
                hideSuggestionsOfCategory(mostPlayedSuggestions);
            }
        });
        
        // Suggestion search
        let searchAbortController;
        mostPlayedInput.addEventListener('input', debounce(function() {
            const query = mostPlayedInput.value.trim();
            if (query.length < 2) {
                hideSuggestionsOfCategory(mostPlayedSuggestions);
                return;
            }
            fetchAlbumSuggestions(query, mostPlayedSuggestions, mostPlayedInput);
        }, 300));
        
        mostPlayedInput.addEventListener('focus', function() {
            if (mostPlayedSuggestions.children.length > 0) {
                mostPlayedSuggestions.style.display = 'block';
            }
        });
        
        document.addEventListener('click', function(e) {
            if (!mostPlayedSuggestions.contains(e.target) && e.target !== mostPlayedInput) {
                hideSuggestionsOfCategory(mostPlayedSuggestions);
            }
        });
    }
    
    // Guilty pleasure albums modal
    const addGuiltyPleasureBtn = document.getElementById('addGuiltyPleasureBtn');
    const addGuiltyPleasureModal = document.getElementById('addGuiltyPleasureModal');
    const guiltyPleasureInput = document.getElementById('guiltyPleasureInput');
    const guiltyPleasureSuggestions = document.getElementById('guiltyPleasureSuggestions');
    const saveGuiltyPleasureBtn = document.getElementById('saveGuiltyPleasureBtn');
    const cancelGuiltyPleasureBtn = document.getElementById('cancelGuiltyPleasureBtn');
    const guiltyPleasureForm = document.getElementById('guiltyPleasureForm');
    
    if (addGuiltyPleasureBtn && addGuiltyPleasureModal && guiltyPleasureInput && guiltyPleasureSuggestions && saveGuiltyPleasureBtn && cancelGuiltyPleasureBtn && guiltyPleasureForm) {
        // Show modal
        addGuiltyPleasureBtn.addEventListener('click', function() {
            addGuiltyPleasureModal.classList.add('show');
            guiltyPleasureInput.focus();
            guiltyPleasureInput.value = '';
            lucide.createIcons();
        });
        
        // Hide modal on cancel
        cancelGuiltyPleasureBtn.addEventListener('click', function() {
            addGuiltyPleasureModal.classList.remove('show');
            hideSuggestionsOfCategory(guiltyPleasureSuggestions);
        });
        
        // Handle form submission
        guiltyPleasureForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const albumName = guiltyPleasureInput.value.trim();
            
            if (albumName.length < 1) {
                showNotification('Le nom de l\'album ne peut pas être vide', 'error');
                return;
            }
            
            if (albumName.length > 255) {
                showNotification('Le nom de l\'album est trop long', 'error');
                return;
            }
            
            addAlbumToCategory(albumName, 'guilty_pleasure', guiltyPleasureInput, guiltyPleasureSuggestions);
        });
        
        // Close modal on outside click
        addGuiltyPleasureModal.addEventListener('click', function(e) {
            if (e.target === this) {
                addGuiltyPleasureModal.classList.remove('show');
                hideSuggestionsOfCategory(guiltyPleasureSuggestions);
            }
        });
        
        // Close modal on escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && addGuiltyPleasureModal.classList.contains('show')) {
                addGuiltyPleasureModal.classList.remove('show');
                hideSuggestionsOfCategory(guiltyPleasureSuggestions);
            }
        });
        
        // Suggestion search
        guiltyPleasureInput.addEventListener('input', debounce(function() {
            const query = guiltyPleasureInput.value.trim();
            if (query.length < 2) {
                hideSuggestionsOfCategory(guiltyPleasureSuggestions);
                return;
            }
            fetchAlbumSuggestions(query, guiltyPleasureSuggestions, guiltyPleasureInput);
        }, 300));
        
        guiltyPleasureInput.addEventListener('focus', function() {
            if (guiltyPleasureSuggestions.children.length > 0) {
                guiltyPleasureSuggestions.style.display = 'block';
            }
        });
        
        document.addEventListener('click', function(e) {
            if (!guiltyPleasureSuggestions.contains(e.target) && e.target !== guiltyPleasureInput) {
                hideSuggestionsOfCategory(guiltyPleasureSuggestions);
            }
        });
    }
}

// Helper function to add album to category - now uses the global function from app.js

// Helper function to hide suggestions
function hideSuggestionsOfCategory(suggestionsElement) {
    if (suggestionsElement) {
        suggestionsElement.style.display = 'none';
        suggestionsElement.innerHTML = '';
    }
}

// Initialize horizontal scroll functionality
function initHorizontalScroll() {
    const horizontalScrolls = document.querySelectorAll('.albums-horizontal-scroll');
    
    horizontalScrolls.forEach(scrollContainer => {
        // Add scroll indicators
        if (scrollContainer.children.length > 0) {
            scrollContainer.style.position = 'relative';
            
            // Add scroll shadow indicators
            const leftShadow = document.createElement('div');
            leftShadow.className = 'scroll-shadow scroll-shadow-left';
            leftShadow.style.cssText = `
                position: absolute;
                left: 0;
                top: 0;
                bottom: 0;
                width: 20px;
                background: linear-gradient(to right, rgba(0,0,0,0.3), transparent);
                pointer-events: none;
                z-index: 1;
                opacity: 0;
                transition: opacity 0.3s ease;
            `;
            
            const rightShadow = document.createElement('div');
            rightShadow.className = 'scroll-shadow scroll-shadow-right';
            rightShadow.style.cssText = `
                position: absolute;
                right: 0;
                top: 0;
                bottom: 0;
                width: 20px;
                background: linear-gradient(to left, rgba(0,0,0,0.3), transparent);
                pointer-events: none;
                z-index: 1;
                opacity: 0;
                transition: opacity 0.3s ease;
            `;
            
            scrollContainer.appendChild(leftShadow);
            scrollContainer.appendChild(rightShadow);
            
            // Update shadows on scroll
            const updateShadows = () => {
                const scrollLeft = scrollContainer.scrollLeft;
                const maxScroll = scrollContainer.scrollWidth - scrollContainer.clientWidth;
                
                leftShadow.style.opacity = scrollLeft > 0 ? '1' : '0';
                rightShadow.style.opacity = scrollLeft < maxScroll ? '1' : '0';
            };
            
            scrollContainer.addEventListener('scroll', updateShadows);
            updateShadows(); // Initial check
        }
    });
}

// Use the shared search function from app.js
// This function is now defined in app.js and can be used here

// renderSuggestions function is now handled by the shared function in app.js

// escapeHtml function is now handled by the shared function in app.js

// Initialize horizontal scroll when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    initHorizontalScroll();
});
