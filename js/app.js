// Enhanced JavaScript for Mon Musée Musical

document.addEventListener('DOMContentLoaded', function() {
    // Initialize Lucide icons
    lucide.createIcons();
    
    // Initialize the application
    initApp();
});

function initApp() {
    // Add smooth animations and interactions
    addSmoothAnimations();
    
    // Initialize logout functionality
    initLogout();
    
    // Initialize bio editing functionality
    initBioEditing();
    
    // Initialize profile visibility functionality
    initProfileVisibility();
    
    // Initialize albums management functionality
    initAlbumsManagement();
    
    // Add music note interactions
    initMusicNotes();
    
    // Add loading states
    addLoadingStates();
}

function addSmoothAnimations() {
    // Animate elements on scroll
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };
    
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.opacity = '1';
                entry.target.style.transform = 'translateY(0)';
            }
        });
    }, observerOptions);
    
    // Observe cards and other elements
    document.querySelectorAll('.card, .stat-item').forEach(el => {
        el.style.opacity = '0';
        el.style.transform = 'translateY(20px)';
        el.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
        observer.observe(el);
    });
}

function initLogout() {
    const logoutBtn = document.getElementById('logoutBtn');
    if (logoutBtn) {
        logoutBtn.addEventListener('click', function(e) {
            e.preventDefault();
            
            // Add loading state
            this.classList.add('loading');
            this.innerHTML = '<i data-lucide="loader-2" class="animate-spin"></i><span>Déconnexion...</span>';
            
            // Re-initialize icons after content change
            lucide.createIcons();
            
            // Simulate loading delay for better UX
            setTimeout(() => {
                window.location.href = '/api/logout.php?redirect=/index.php';
            }, 500);
        });
    }
}

function initBioEditing() {
    const editBioBtn = document.getElementById('editBioBtn');
    const bioContent = document.getElementById('bioContent');
    const bioEditForm = document.getElementById('bioEditForm');
    const bioTextarea = document.getElementById('bioTextarea');
    const saveBioBtn = document.getElementById('saveBioBtn');
    const cancelBioBtn = document.getElementById('cancelBioBtn');
    
    if (!editBioBtn || !bioContent || !bioEditForm || !bioTextarea || !saveBioBtn || !cancelBioBtn) {
        return;
    }
    
    let originalBio = bioContent.innerHTML;
    
    // Show edit form
    editBioBtn.addEventListener('click', function() {
        bioContent.style.display = 'none';
        bioEditForm.style.display = 'block';
        bioTextarea.focus();
        
        // Re-initialize icons
        lucide.createIcons();
    });
    
    // Cancel editing
    cancelBioBtn.addEventListener('click', function() {
        bioEditForm.style.display = 'none';
        bioContent.style.display = 'block';
        bioTextarea.value = originalBio;
    });
    
    // Save bio
    saveBioBtn.addEventListener('click', function() {
        const newBio = bioTextarea.value.trim();
        
        if (newBio === originalBio) {
            bioEditForm.style.display = 'none';
            bioContent.style.display = 'block';
            return;
        }
        
        // Show loading state
        this.innerHTML = '<i data-lucide="loader-2" class="animate-spin"></i><span>Sauvegarde...</span>';
        this.disabled = true;
        
        // Re-initialize icons
        lucide.createIcons();
        
        fetch('/api/update_bio.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ bio: newBio })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Update bio content
                bioContent.innerHTML = `<p>${escapeHtml(newBio)}</p>`;
                originalBio = bioContent.innerHTML;
                
                // Hide form
                bioEditForm.style.display = 'none';
                bioContent.style.display = 'block';
                
                // Show success message
                showNotification('Bio mise à jour avec succès !', 'success');
            } else {
                showNotification(data.error || 'Erreur lors de la mise à jour', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('Erreur de connexion', 'error');
        })
        .finally(() => {
            // Reset button
            this.innerHTML = '<i data-lucide="save"></i><span>Sauvegarder</span>';
            this.disabled = false;
            
            // Re-initialize icons
            lucide.createIcons();
        });
    });
    
    // Ctrl+Enter shortcut for saving
    bioTextarea.addEventListener('keydown', function(e) {
        if (e.ctrlKey && e.key === 'Enter') {
            saveBioBtn.click();
        }
    });
}

function initProfileVisibility() {
    const visibilityToggle = document.getElementById('visibilityToggle');
    const pseudoModal = document.getElementById('pseudoModal');
    const pseudoInput = document.getElementById('pseudoInput');
    const pseudoFeedback = document.getElementById('pseudoFeedback');
    const savePseudoBtn = document.getElementById('savePseudoBtn');
    const cancelPseudoBtn = document.getElementById('cancelPseudoBtn');
    const switchLabel = document.querySelector('.switch-label');
    const switchLabelIcon = switchLabel.querySelector('i');
    const switchLabelText = switchLabel.querySelector('.switch-text');
    
    if (!visibilityToggle || !pseudoModal || !pseudoInput || !pseudoFeedback || !savePseudoBtn || !cancelPseudoBtn) {
        return;
    }
    
    let pseudoCheckTimeout;
    
    // Handle visibility toggle
    visibilityToggle.addEventListener('change', function() {
        const newVisibility = this.checked ? 'public' : 'private';
        
        // If trying to make public without pseudo, show modal
        if (newVisibility === 'public' && !hasPseudo()) {
            this.checked = false; // Revert toggle
            showPseudoModal();
            return;
        }
        
        // Update visibility
        updateProfileVisibility(newVisibility);
    });
    
    // Pseudo input validation
    pseudoInput.addEventListener('input', function() {
        const pseudo = this.value.trim();
        
        // Clear previous timeout
        clearTimeout(pseudoCheckTimeout);
        
        // Reset feedback
        pseudoFeedback.innerHTML = '';
        pseudoFeedback.className = 'feedback';
        savePseudoBtn.disabled = true;
        
        if (pseudo.length < 3) {
            pseudoFeedback.innerHTML = '<i data-lucide="alert-circle"></i> Le pseudo doit contenir au moins 3 caractères';
            pseudoFeedback.className = 'feedback unavailable';
            return;
        }
        
        if (pseudo.length > 45) {
            pseudoFeedback.innerHTML = '<i data-lucide="alert-circle"></i> Le pseudo ne peut pas dépasser 45 caractères';
            pseudoFeedback.className = 'feedback unavailable';
            return;
        }
        
        // Check pseudo availability after delay
        pseudoCheckTimeout = setTimeout(() => {
            checkPseudoAvailability(pseudo);
        }, 500);
    });
    
    // Save pseudo button
    savePseudoBtn.addEventListener('click', function() {
        const pseudo = pseudoInput.value.trim();
        
        if (pseudo.length < 3 || pseudo.length > 45) {
            return;
        }
        
        // Show loading state
        this.innerHTML = '<i data-lucide="loader-2" class="animate-spin"></i><span>Enregistrement...</span>';
        this.disabled = true;
        
        // Re-initialize icons
        lucide.createIcons();
        
        // Update pseudo
        updatePseudo(pseudo);
    });
    
    // Cancel pseudo button
    cancelPseudoBtn.addEventListener('click', function() {
        hidePseudoModal();
        // Revert visibility toggle
        visibilityToggle.checked = false;
        updateSwitchLabel('private');
    });
    
    // Close modal on outside click
    pseudoModal.addEventListener('click', function(e) {
        if (e.target === this) {
            hidePseudoModal();
            // Revert visibility toggle
            visibilityToggle.checked = false;
            updateSwitchLabel('private');
        }
    });
    
    // Close modal on escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && pseudoModal.style.display !== 'none') {
            hidePseudoModal();
            // Revert visibility toggle
            visibilityToggle.checked = false;
            updateSwitchLabel('private');
        }
    });
    
    // Helper functions
    function hasPseudo() {
        const pseudoDisplay = document.querySelector('.pseudo-display');
        return pseudoDisplay && pseudoDisplay.textContent.trim() !== '';
    }
    
    function showPseudoModal() {
        pseudoModal.style.display = 'flex';
        pseudoInput.focus();
        pseudoInput.value = '';
        pseudoFeedback.innerHTML = '';
        pseudoFeedback.className = 'feedback';
        savePseudoBtn.disabled = true;
        
        // Re-initialize icons
        lucide.createIcons();
    }
    
    function hidePseudoModal() {
        pseudoModal.style.display = 'none';
    }
    
    function updateSwitchLabel(visibility) {
        if (visibility === 'public') {
            // on retire l'icone déjé présente. 
            switchLabel.removeChild(switchLabel.children[0]); 

            
            //On crée et place une nouvelle icone
            const newIcon = document.createElement("i");
            newIcon.setAttribute('data-lucide', 'globe');
            
            //On insère l'icone avant le texte
            switchLabel.insertBefore(newIcon, switchLabel.firstChild);
            switchLabelText.textContent = 'Public';
        } else {
            // on retire l'icone déjé présente. 
            switchLabel.removeChild(switchLabel.children[0]); 
            
            //On crée et place une nouvelle icone
            const newIcon = document.createElement("i");
            newIcon.setAttribute('data-lucide', 'lock');

            //On insère l'icone avant le texte
            switchLabel.insertBefore(newIcon, switchLabel.firstChild);
            switchLabelText.textContent = 'Privé';
        }
        
        // Re-initialize icons
        lucide.createIcons();
    }
    
    function checkPseudoAvailability(pseudo) {
        pseudoFeedback.innerHTML = '<i data-lucide="loader-2" class="animate-spin"></i> Vérification...';
        pseudoFeedback.className = 'feedback checking';
        
        // Re-initialize icons
        lucide.createIcons();
        
        fetch('/api/check_pseudo.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ pseudo: pseudo })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                if (data.available) {
                    pseudoFeedback.innerHTML = '<i data-lucide="check-circle"></i> Pseudo disponible !';
                    pseudoFeedback.className = 'feedback available';
                    savePseudoBtn.disabled = false;
                } else {
                    pseudoFeedback.innerHTML = '<i data-lucide="x-circle"></i> Pseudo déjà pris';
                    pseudoFeedback.className = 'feedback unavailable';
                    savePseudoBtn.disabled = true;
                }
            } else {
                pseudoFeedback.innerHTML = '<i data-lucide="alert-circle"></i> Erreur de vérification';
                pseudoFeedback.className = 'feedback unavailable';
                savePseudoBtn.disabled = true;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            pseudoFeedback.innerHTML = '<i data-lucide="alert-circle"></i> Erreur de connexion';
            pseudoFeedback.className = 'feedback unavailable';
            savePseudoBtn.disabled = true;
        })
        .finally(() => {
            // Re-initialize icons
            lucide.createIcons();
        });
    }
    
    function updatePseudo(pseudo) {
        fetch('/api/update_pseudo.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ pseudo: pseudo })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Update pseudo display
                updatePseudoDisplay(pseudo);
                
                // Hide modal
                hidePseudoModal();
                
                // Now update visibility to public
                visibilityToggle.checked = true;
                updateProfileVisibility('public');
                
                // Show success message
                showNotification('Pseudo enregistré avec succès !', 'success');
            } else {
                pseudoFeedback.innerHTML = `<i data-lucide="alert-circle"></i> ${data.error || 'Erreur lors de l\'enregistrement'}`;
                pseudoFeedback.className = 'feedback unavailable';
                savePseudoBtn.disabled = true;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            pseudoFeedback.innerHTML = '<i data-lucide="alert-circle"></i> Erreur de connexion';
            pseudoFeedback.className = 'feedback unavailable';
            savePseudoBtn.disabled = true;
        })
        .finally(() => {
            // Reset button
            savePseudoBtn.innerHTML = '<i data-lucide="save"></i><span>Enregistrer</span>';
            savePseudoBtn.disabled = false;
            
            // Re-initialize icons
            lucide.createIcons();
        });
    }
    
    function updatePseudoDisplay(pseudo) {
        const visibilityStatus = document.querySelector('.visibility-status');
        
        if (visibilityStatus) {
            // Remove existing pseudo display if any
            const existingPseudo = visibilityStatus.querySelector('.pseudo-display');
            if (existingPseudo) {
                existingPseudo.remove();
            }
            
            // Add new pseudo display
            const pseudoDisplay = document.createElement('span');
            pseudoDisplay.className = 'pseudo-display';
            pseudoDisplay.textContent = `@${pseudo}`;
            visibilityStatus.appendChild(pseudoDisplay);
        }
    }
    
    function updateProfileVisibility(visibility) {
        fetch('/api/update_profile_visibility.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ visibility: visibility })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Update switch label
                updateSwitchLabel(visibility);
                
                // Show success message
                showNotification(`Profil maintenant ${visibility === 'public' ? 'public' : 'privé'} !`, 'success');
            } else {
                // Revert toggle on error
                visibilityToggle.checked = !visibilityToggle.checked;
                showNotification(data.error || 'Erreur lors de la mise à jour', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            // Revert toggle on error
            visibilityToggle.checked = !visibilityToggle.checked;
            showNotification('Erreur de connexion', 'error');
        });
    }
}

// Share buttons
document.addEventListener('DOMContentLoaded', function() {
    const shareOwn = document.getElementById('shareOwnProfileBtn');
    if (shareOwn && !shareOwn.disabled) {
        shareOwn.addEventListener('click', function() {
            const url = shareOwn.getAttribute('data-share-url');
            if (navigator.clipboard && navigator.clipboard.writeText) {
                navigator.clipboard.writeText(url).then(() => {
                    showNotification('Lien du profil copié !', 'success');
                }).catch(() => {
                    prompt('Copiez le lien', url);
                });
            } else {
                prompt('Copiez le lien', url);
            }
        });
    }
});

function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

function showNotification(message, type = 'info') {
    // Create notification element
    const notification = document.createElement('div');
    notification.className = `notification notification-${type}`;
    notification.innerHTML = `
        <span>${message}</span>
        <button class="notification-close">
            <i data-lucide="x"></i>
        </button>
    `;
    
    // Add to page
    document.body.appendChild(notification);
    
    // Re-initialize icons
    lucide.createIcons();
    
    // Show notification
    setTimeout(() => {
        notification.classList.add('show');
    }, 100);
    
    // Auto-hide after 3 seconds
    setTimeout(() => {
        hideNotification(notification);
    }, 3000);
    
    // Close button functionality
    const closeBtn = notification.querySelector('.notification-close');
    closeBtn.addEventListener('click', () => {
        hideNotification(notification);
    });
}

function hideNotification(notification) {
    notification.classList.remove('show');
    setTimeout(() => {
        if (notification.parentNode) {
            notification.parentNode.removeChild(notification);
        }
    }, 300);
}

function initMusicNotes() {
    // Add click interactions to music notes
    const musicNotes = document.querySelectorAll('.music-note');
    
    musicNotes.forEach(note => {
        note.addEventListener('click', function() {
            // Create a ripple effect
            createRipple(this);
            
            // Play a subtle sound effect (optional)
            playNoteSound();
        });
    });
}

function createRipple(element) {
    const ripple = document.createElement('div');
    ripple.style.position = 'absolute';
    ripple.style.width = '20px';
    ripple.style.height = '20px';
    ripple.style.borderRadius = '50%';
    ripple.style.background = 'rgba(99, 102, 241, 0.3)';
    ripple.style.transform = 'scale(0)';
    ripple.style.animation = 'ripple 0.6s linear';
    ripple.style.left = '50%';
    ripple.style.top = '50%';
    ripple.style.marginLeft = '-10px';
    ripple.style.marginTop = '-10px';
    
    element.appendChild(ripple);
    
    setTimeout(() => {
        ripple.remove();
    }, 600);
}

function playNoteSound() {
    // Create a simple oscillator for a musical note sound
    try {
        const audioContext = new (window.AudioContext || window.webkitAudioContext)();
        const oscillator = audioContext.createOscillator();
        const gainNode = audioContext.createGain();
        
        oscillator.connect(gainNode);
        gainNode.connect(audioContext.destination);
        
        oscillator.frequency.setValueAtTime(440, audioContext.currentTime); // A4 note
        oscillator.type = 'sine';
        
        gainNode.gain.setValueAtTime(0.1, audioContext.currentTime);
        gainNode.gain.exponentialRampToValueAtTime(0.01, audioContext.currentTime + 0.1);
        
        oscillator.start(audioContext.currentTime);
        oscillator.stop(audioContext.currentTime + 0.1);
    } catch (e) {
        // Fallback if Web Audio API is not supported
        console.log('Audio not supported');
    }
}

function addLoadingStates() {
    // Add loading states to buttons
    document.querySelectorAll('.btn').forEach(btn => {
        btn.addEventListener('click', function() {
            if (!this.classList.contains('btn-logout') && !this.classList.contains('btn-secondary')) {
                this.classList.add('loading');
                setTimeout(() => {
                    this.classList.remove('loading');
                }, 1000);
            }
        });
    });
}

// Add CSS for ripple animation
function addRippleStyles() {
    if (!document.getElementById('ripple-styles')) {
        const style = document.createElement('style');
        style.id = 'ripple-styles';
        style.textContent = `
            @keyframes ripple {
                to {
                    transform: scale(4);
                    opacity: 0;
                }
            }
            
            .animate-spin {
                animation: spin 1s linear infinite;
            }
            
            @keyframes spin {
                from {
                    transform: rotate(0deg);
                }
                to {
                    transform: rotate(360deg);
                }
            }
        `;
        document.head.appendChild(style);
    }
}

// Initialize ripple styles
addRippleStyles();

// Add keyboard navigation support
document.addEventListener('keydown', function(e) {
    // Escape key to close modals or go back
    if (e.key === 'Escape') {
        // Close bio edit form if open
        const bioEditForm = document.getElementById('bioEditForm');
        if (bioEditForm && bioEditForm.style.display !== 'none') {
            document.getElementById('cancelBioBtn').click();
        }
    }
    
    // Enter key for buttons
    if (e.key === 'Enter') {
        const focusedElement = document.activeElement;
        if (focusedElement && focusedElement.classList.contains('btn')) {
            focusedElement.click();
        }
    }
});

// Add touch support for mobile devices
if ('ontouchstart' in window) {
    document.body.classList.add('touch-device');
    
    // Add touch-specific interactions
    document.addEventListener('touchstart', function() {}, {passive: true});
}

// Performance optimization: Debounce scroll events
function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}

// Optimize scroll performance
const optimizedScroll = debounce(function() {
    // Handle scroll events efficiently
}, 16);

window.addEventListener('scroll', optimizedScroll, {passive: true});

// Add service worker for offline support (optional)
if ('serviceWorker' in navigator) {
    window.addEventListener('load', function() {
        // Register service worker for offline functionality
        // navigator.serviceWorker.register('/sw.js');
    });
}

function initAlbumsManagement() {
    const addAlbumBtn = document.getElementById('addAlbumBtn');
    const addAlbumModal = document.getElementById('addAlbumModal');
    const albumNameInput = document.getElementById('albumNameInput');
    const albumSuggestions = document.getElementById('albumSuggestions');
    const saveAlbumBtn = document.getElementById('saveAlbumBtn');
    const cancelAlbumBtn = document.getElementById('cancelAlbumBtn');
    const albumForm = document.getElementById('albumForm');
    
    if (!addAlbumBtn || !addAlbumModal || !albumNameInput || !albumSuggestions || !saveAlbumBtn || !cancelAlbumBtn || !albumForm) {
        return;
    }
    
    // Show add album modal
    addAlbumBtn.addEventListener('click', function() {
        addAlbumModal.classList.add('show');
        albumNameInput.focus();
        albumNameInput.value = '';
        
        // Re-initialize icons
        lucide.createIcons();
    });
    
    // Hide modal on cancel
    cancelAlbumBtn.addEventListener('click', function() {
        addAlbumModal.classList.remove('show');
        hideSuggestions();
    });
    
    // Handle form submission
    albumForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const albumName = albumNameInput.value.trim();
        
        if (albumName.length < 1) {
            showNotification('Le nom de l\'album ne peut pas être vide', 'error');
            return;
        }
        
        if (albumName.length > 255) {
            showNotification('Le nom de l\'album est trop long', 'error');
            return;
        }
        
        // Add album
        addAlbum(albumName);
    });
    
    // Close modal on outside click
    addAlbumModal.addEventListener('click', function(e) {
        if (e.target === this) {
            addAlbumModal.classList.remove('show');
            hideSuggestions();
        }
    });
    
    // Close modal on escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && addAlbumModal.classList.contains('show')) {
            addAlbumModal.classList.remove('show');
            hideSuggestions();
        }
    });
    // Suggestion search using iTunes Search API (no key required)
    let searchAbortController;
    albumNameInput.addEventListener('input', debounce(function() {
        const query = albumNameInput.value.trim();
        if (query.length < 2) {
            hideSuggestions();
            return;
        }
        fetchAlbumSuggestions(query);
    }, 300));

    albumNameInput.addEventListener('focus', function() {
        if (albumSuggestions.children.length > 0) {
            albumSuggestions.style.display = 'block';
        }
    });

    document.addEventListener('click', function(e) {
        if (!albumSuggestions.contains(e.target) && e.target !== albumNameInput) {
            hideSuggestions();
        }
    });

    function fetchAlbumSuggestions(query) {
        try {
            if (searchAbortController) {
                searchAbortController.abort();
            }
            searchAbortController = new AbortController();
            const params = new URLSearchParams({
                term: query,
                entity: 'album',
                media: 'music',
                limit: '8'
            });
            const url = `https://itunes.apple.com/search?${params.toString()}`;
            fetch(url, { signal: searchAbortController.signal })
                .then(r => r.json())
                .then(data => {
                    const results = Array.isArray(data.results) ? data.results : [];
                    renderSuggestions(results.map(r => ({
                        title: r.collectionName || '',
                        artist: r.artistName || '',
                        cover: r.artworkUrl100 || '',
                        collectionId: r.collectionId || '',
                        artistId: r.artistId || '',
                    })));
                })
                .catch(err => {
                    if (err.name !== 'AbortError') {
                        hideSuggestions();
                    }
                });
        } catch (_) {
            hideSuggestions();
        }
    }

    function renderSuggestions(items) {
        albumSuggestions.innerHTML = '';
        if (!items || items.length === 0) {
            hideSuggestions();
            return;
        }
        items.forEach(item => {
            const row = document.createElement('div');
            row.className = 'album-suggestion-item';
            const coverHtml = item.cover ? '<img src="' + item.cover + '" alt="cover">' : '<i data-lucide="disc"></i>';
            row.innerHTML = `
                <div class="album-suggestion-cover">${coverHtml}</div>
                <div class="album-suggestion-info">
                    <div class="album-suggestion-title">${escapeHtml(item.title)}</div>
                    <div class="album-suggestion-artist">${escapeHtml(item.artist)}</div>
                </div>
                <button type="button" class="album-suggestion-select" title="Sélectionner" aria-label="Sélectionner"><i data-lucide="plus"></i></button>
            `;
            row.addEventListener('click', (e) => {
                e.preventDefault();
                e.stopPropagation();
                if (item && item.title) {
                    albumNameInput.value = item.title;
                }
                // Attach selection metadata to form for submission
                albumNameInput.dataset.itunesCollectionId = item.collectionId || '';
                albumNameInput.dataset.itunesArtistId = item.artistId || '';
                albumNameInput.dataset.artistName = item.artist || '';
                albumNameInput.dataset.artwork60 = (item.cover || '').replace('100x100bb.jpg','60x60bb.jpg');
                albumNameInput.dataset.artwork100 = item.cover || '';
                hideSuggestions();
            });
            albumSuggestions.appendChild(row);
        });
        albumSuggestions.style.display = 'block';
        lucide.createIcons();
    }

    function hideSuggestions() {
        albumSuggestions.style.display = 'none';
        albumSuggestions.innerHTML = '';
    }
    
    // Helper functions
    function addAlbum(albumName) {
        // Show loading state
        saveAlbumBtn.innerHTML = '<i data-lucide="loader-2" class="animate-spin"></i><span>Ajout...</span>';
        saveAlbumBtn.disabled = true;
        
        // Re-initialize icons
        lucide.createIcons();
        
        fetch('/api/add_album.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                albumName: albumName,
                externalAlbumId: albumNameInput.dataset.itunesCollectionId || null,
                externalArtistId: albumNameInput.dataset.itunesArtistId || null,
                artistName: albumNameInput.dataset.artistName || null,
                imageUrl60: albumNameInput.dataset.artwork60 || null,
                imageUrl100: albumNameInput.dataset.artwork100 || null,
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Hide modal
                addAlbumModal.classList.remove('show');
                
                // Show success message
                showNotification('Album ajouté avec succès !', 'success');
                
                // Reload page to show new album
                setTimeout(() => {
                    window.location.reload();
                }, 1000);
            } else {
                showNotification(data.error || 'Erreur lors de l\'ajout de l\'album', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('Erreur de connexion', 'error');
        })
        .finally(() => {
            // Reset button
            saveAlbumBtn.innerHTML = '<i data-lucide="save"></i><span>Ajouter</span>';
            saveAlbumBtn.disabled = false;
            
            // Re-initialize icons
            lucide.createIcons();
        });
    }
}

// Global function for removing albums
function removeAlbum(albumId) {
    if (!confirm('Êtes-vous sûr de vouloir supprimer cet album ? Cette action est irréversible.')) {
        return;
    }
    
    fetch('/api/delete_album.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ albumId: albumId })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Remove album card from DOM
            const albumCard = document.querySelector(`[data-album-id="${albumId}"]`);
            if (albumCard) {
                albumCard.remove();
                
                // Update album count
                const albumCount = document.querySelector('.stat-number');
                if (albumCount) {
                    const currentCount = parseInt(albumCount.textContent);
                    albumCount.textContent = Math.max(0, currentCount - 1);
                }
                
                // Check if no albums left
                const albumsGrid = document.querySelector('.albums-grid');
                if (albumsGrid && albumsGrid.children.length === 0) {
                    // Show no albums message
                    const noAlbums = document.createElement('div');
                    noAlbums.className = 'no-albums';
                    noAlbums.innerHTML = `
                        <i data-lucide="music-off"></i>
                        <p>Aucun album dans votre collection pour le moment</p>
                    `;
                    albumsGrid.parentNode.appendChild(noAlbums);
                    albumsGrid.remove();
                    
                    // Re-initialize icons
                    lucide.createIcons();
                }
            }
            
            showNotification('Album supprimé avec succès !', 'success');
        } else {
            showNotification(data.error || 'Erreur lors de la suppression', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('Erreur de connexion', 'error');
    });
}
