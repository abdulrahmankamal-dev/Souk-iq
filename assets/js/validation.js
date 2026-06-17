/**
 * SOUK.IQ Form Validation, Password Strength, and OTP Helpers
 */

const FormValidator = {
    // Check password strength and return score (0-4)
    checkPasswordStrength(password) {
        let score = 0;
        if (password.length >= 8) score++;
        if (/[A-Z]/.test(password)) score++;
        if (/[a-z]/.test(password)) score++;
        if (/\d/.test(password)) score++;
        if (/[^A-Za-z0-9]/.test(password)) score++;
        return Math.min(score, 4);
    },

    // Update password strength indicator segment colors in UI
    updateStrengthMeter(inputSelector, meterContainerSelector) {
        const input = document.querySelector(inputSelector);
        const container = document.querySelector(meterContainerSelector);
        
        if (!input || !container) return;

        input.addEventListener('input', () => {
            const password = input.value;
            const score = this.checkPasswordStrength(password);
            
            const bars = container.querySelectorAll('.strength-bar-segment');
            bars.forEach((bar, index) => {
                bar.className = 'strength-bar-segment flex-grow-1 rounded';
                if (index < score) {
                    if (score <= 1) {
                        bar.classList.add('bg-danger');
                    } else if (score <= 3) {
                        bar.classList.add('bg-warning');
                    } else {
                        bar.classList.add('bg-success');
                    }
                } else {
                    bar.classList.add('bg-light');
                }
            });
        });
    },

    // Debounce username availability check
    initUsernameCheck(inputSelector, feedbackSelector) {
        const input = document.querySelector(inputSelector);
        const feedback = document.querySelector(feedbackSelector);
        
        if (!input || !feedback) return;
        
        let timer = null;
        input.addEventListener('input', () => {
            feedback.innerHTML = '<span class="text-muted"><i class="bi bi-arrow-repeat spin"></i> جاري التحقق...</span>';
            clearTimeout(timer);
            
            const username = input.value.trim();
            if (username.length < 3) {
                feedback.innerHTML = '<span class="text-danger">اسم المستخدم قصير جداً</span>';
                return;
            }

            timer = setTimeout(() => {
                fetch(`${SITE_URL}/api/v1/search?check_username=${encodeURIComponent(username)}`)
                    .then(res => res.json())
                    .then(data => {
                        if (data.available) {
                            feedback.innerHTML = '<span class="text-success"><i class="bi bi-check-circle"></i> اسم المستخدم متاح</span>';
                        } else {
                            feedback.innerHTML = '<span class="text-danger"><i class="bi bi-x-circle"></i> اسم المستخدم غير متاح</span>';
                        }
                    })
                    .catch(() => {
                        feedback.innerHTML = '';
                    });
            }, 500);
        });
    },

    // Set up 6-digit OTP code auto-advance and paste support
    initOtpBoxes(boxClass) {
        const boxes = document.querySelectorAll(boxClass);
        if (boxes.length === 0) return;

        boxes.forEach((box, index) => {
            box.addEventListener('input', (e) => {
                // Remove non-numeric
                box.value = box.value.replace(/[^0-9]/g, '');
                
                if (box.value.length > 0 && index < boxes.length - 1) {
                    boxes[index + 1].focus();
                }
            });

            box.addEventListener('keydown', (e) => {
                if (e.key === 'Backspace' && box.value.length === 0 && index > 0) {
                    boxes[index - 1].focus();
                }
            });

            // Paste support
            box.addEventListener('paste', (e) => {
                const pasteData = e.clipboardData.getData('text').trim();
                if (pasteData.length === boxes.length && /^\d+$/.test(pasteData)) {
                    boxes.forEach((b, idx) => {
                        b.value = pasteData[idx];
                    });
                    boxes[boxes.length - 1].focus();
                }
            });
        });
    }
};

window.FormValidator = FormValidator;
