@php
    $inputId = $id ?? 'skillsTagBox_' . uniqid();
    $hiddenName = $name ?? 'skills';
    $currentVal = $value ?? old($hiddenName, '');
    $isRequired = $required ?? true;
    $initialPlaceholder = $placeholder ?? 'Type skills (e.g. PHP, Python, React) & press Enter';
@endphp

<div class="skills-tag-input-wrapper" id="{{ $inputId }}_wrapper">
    <div class="skills-tag-box" id="{{ $inputId }}_box" onclick="document.getElementById('{{ $inputId }}_text').focus();">
        <div id="{{ $inputId }}_chips" class="skills-chips-list"></div>
        <input type="text"
               id="{{ $inputId }}_text"
               class="skills-text-input"
               placeholder="{{ $initialPlaceholder }}"
               autocomplete="off"
               spellcheck="false">
    </div>

    <!-- Hidden input holding actual comma-separated skills for standard form submission -->
    <input type="hidden"
           name="{{ $hiddenName }}"
           id="{{ $inputId }}_hidden"
           value="{{ $currentVal }}"
           @if($isRequired) required @endif>

    <!-- Autocomplete Suggestions Dropdown -->
    <div id="{{ $inputId }}_dropdown" class="skills-autocomplete-dropdown" style="display: none;"></div>
</div>

<style>
    .skills-tag-input-wrapper {
        position: relative;
        width: 100%;
    }
    .skills-tag-box {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        gap: 8px;
        background: #ffffff;
        border: 1.5px solid #cbd5e1;
        border-radius: 12px;
        padding: 8px 12px;
        min-height: 48px;
        cursor: text;
        transition: all 0.2s ease;
    }
    .skills-tag-box:hover {
        border-color: #94a3b8;
    }
    .skills-tag-box.is-focused,
    .skills-tag-box:focus-within {
        border-color: #00a884;
        box-shadow: 0 0 0 4px rgba(0, 168, 132, 0.12);
        background: #ffffff;
    }
    .skills-tag-box.is-invalid {
        border-color: #ef4444;
        box-shadow: 0 0 0 4px rgba(239, 68, 68, 0.12);
    }
    .skills-chips-list {
        display: inline-flex;
        flex-wrap: wrap;
        gap: 6px;
        align-items: center;
    }
    .skills-tag-chip {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: #eff6ff;
        color: #1d4ed8;
        border: 1px solid #bfdbfe;
        border-radius: 8px;
        padding: 5px 10px;
        font-size: 0.88rem;
        font-weight: 700;
        line-height: 1.2;
        user-select: none;
        animation: chipPopIn 0.15s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    }
    @keyframes chipPopIn {
        from { transform: scale(0.8); opacity: 0; }
        to { transform: scale(1); opacity: 1; }
    }
    .skills-tag-chip .chip-star-icon {
        color: #f59e0b;
        font-size: 0.8rem;
    }
    .skills-tag-chip .chip-label {
        font-weight: 700;
        letter-spacing: -0.2px;
    }
    .skills-tag-chip .chip-remove-btn {
        color: #93c5fd;
        font-size: 1.15rem;
        line-height: 1;
        cursor: pointer;
        margin-left: 2px;
        transition: color 0.15s ease, transform 0.15s ease;
    }
    .skills-tag-chip .chip-remove-btn:hover {
        color: #ef4444;
        transform: scale(1.15);
    }
    .skills-text-input {
        flex: 1;
        min-width: 140px;
        border: none !important;
        outline: none !important;
        background: transparent !important;
        box-shadow: none !important;
        font-size: 0.92rem;
        color: #0f172a;
        padding: 4px 6px;
        font-weight: 500;
    }
    .skills-text-input::placeholder {
        color: #94a3b8;
        font-size: 0.88rem;
    }

    /* Autocomplete Dropdown */
    .skills-autocomplete-dropdown {
        position: absolute;
        top: calc(100% + 4px);
        left: 0;
        right: 0;
        background: #ffffff;
        border: 1.5px solid #e2e8f0;
        border-radius: 12px;
        box-shadow: 0 12px 28px -4px rgba(15, 23, 42, 0.15);
        z-index: 99999;
        max-height: 240px;
        overflow-y: auto;
        padding: 6px;
    }
    .skills-dropdown-item {
        padding: 9px 14px;
        border-radius: 8px;
        font-size: 0.88rem;
        font-weight: 600;
        color: #1e293b;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: space-between;
        transition: background 0.12s ease;
    }
    .skills-dropdown-item:hover,
    .skills-dropdown-item.is-active {
        background: #f0fdf4;
        color: #15803d;
    }
    .skills-dropdown-item .badge-type {
        font-size: 0.72rem;
        background: #e2e8f0;
        color: #475569;
        padding: 2px 7px;
        border-radius: 6px;
        font-weight: 700;
    }
</style>

<script>
(function() {
    const COMPONENT_ID = '{{ $inputId }}';
    const box = document.getElementById(COMPONENT_ID + '_box');
    const chipsList = document.getElementById(COMPONENT_ID + '_chips');
    const textInput = document.getElementById(COMPONENT_ID + '_text');
    const hiddenInput = document.getElementById(COMPONENT_ID + '_hidden');
    const dropdown = document.getElementById(COMPONENT_ID + '_dropdown');

    const defaultPlaceholder = '{{ $initialPlaceholder }}';
    const hasMorePlaceholder = 'Type another skill...';

    // Comprehensive tech skills dictionary for instant autocomplete suggestions
    const POPULAR_SKILLS = [
        'PHP', 'Laravel', 'Python', 'Django', 'Flask', 'FastAPI', 'Java', 'Spring Boot', 'Microservices',
        'React.js', 'React Native', 'Node.js', 'Express.js', 'JavaScript', 'TypeScript', 'Angular', 'Vue.js',
        'Next.js', 'HTML/CSS', 'Bootstrap', 'Tailwind CSS', 'C#', '.NET', '.NET Core', 'ASP.NET', 'SQL',
        'MySQL', 'PostgreSQL', 'MongoDB', 'Redis', 'Oracle', 'AWS', 'Amazon Web Services', 'Azure', 'GCP',
        'Docker', 'Kubernetes', 'DevOps', 'CI/CD', 'Git', 'GitHub', 'Kafka', 'REST API', 'GraphQL', 'Flutter',
        'Dart', 'Android', 'iOS', 'Swift', 'Kotlin', 'Selenium', 'Data Science', 'Machine Learning', 'AI',
        'Pandas', 'NumPy', 'TensorFlow', 'PyTorch', 'C++', 'Golang', 'Ruby on Rails', 'Wordpress', 'Shopify',
        'J2EE', 'Hibernate', 'JUnit', 'Linux', 'Cyber Security', 'Full Stack Developer', 'Backend Developer',
        'Frontend Developer', 'UI/UX Design', 'Figma', 'Tableau', 'Power BI'
    ];

    let skills = [];
    let activeDropdownIndex = -1;
    let currentSuggestions = [];

    // Parse initial value from hidden input (e.g. "PHP, Python, Laravel")
    const initialRaw = (hiddenInput.value || '').trim();
    if (initialRaw) {
        skills = initialRaw.split(',').map(s => s.trim()).filter(Boolean);
        // Remove duplicates case-insensitive
        skills = skills.filter((item, index) => {
            return skills.findIndex(i => i.toLowerCase() === item.toLowerCase()) === index;
        });
    }

    function renderChips() {
        chipsList.innerHTML = '';
        skills.forEach((skill, index) => {
            const chip = document.createElement('span');
            chip.className = 'skills-tag-chip';
            chip.innerHTML = `
                <i class="fa-solid fa-star chip-star-icon"></i>
                <span class="chip-label">${escapeHtml(skill)}</span>
                <span class="chip-remove-btn" title="Remove ${escapeHtml(skill)}">&times;</span>
            `;

            chip.querySelector('.chip-remove-btn').addEventListener('click', function(e) {
                e.stopPropagation();
                removeSkill(index);
            });

            chipsList.appendChild(chip);
        });

        // Update hidden input value
        hiddenInput.value = skills.join(', ');

        // Update placeholder
        textInput.placeholder = skills.length > 0 ? hasMorePlaceholder : defaultPlaceholder;

        // Dispatch change event on hidden input
        hiddenInput.dispatchEvent(new Event('change', { bubbles: true }));
    }

    function addSkill(val) {
        const clean = (val || '').trim().replace(/^[\,\;]+|[\,\;]+$/g, '').trim();
        if (!clean) return;

        // Check if already exists (case-insensitive)
        const exists = skills.some(s => s.toLowerCase() === clean.toLowerCase());
        if (!exists) {
            skills.push(clean);
            renderChips();
        }
        textInput.value = '';
        hideDropdown();
    }

    function removeSkill(index) {
        skills.splice(index, 1);
        renderChips();
        textInput.focus();
    }

    function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    // Handle typing, comma, and Enter keys
    textInput.addEventListener('keydown', function(e) {
        if (dropdown.style.display !== 'none' && currentSuggestions.length > 0) {
            if (e.key === 'ArrowDown') {
                e.preventDefault();
                activeDropdownIndex = (activeDropdownIndex + 1) % currentSuggestions.length;
                highlightDropdownItem();
                return;
            } else if (e.key === 'ArrowUp') {
                e.preventDefault();
                activeDropdownIndex = (activeDropdownIndex - 1 + currentSuggestions.length) % currentSuggestions.length;
                highlightDropdownItem();
                return;
            } else if (e.key === 'Enter') {
                if (activeDropdownIndex >= 0 && activeDropdownIndex < currentSuggestions.length) {
                    e.preventDefault();
                    addSkill(currentSuggestions[activeDropdownIndex]);
                    return;
                }
            } else if (e.key === 'Escape') {
                hideDropdown();
                return;
            }
        }

        if (e.key === 'Enter' || e.key === ',') {
            e.preventDefault();
            if (this.value.trim()) {
                addSkill(this.value);
            }
        } else if (e.key === 'Backspace' && this.value === '' && skills.length > 0) {
            removeSkill(skills.length - 1);
        }
    });

    // Handle paste event (split comma-separated paste)
    textInput.addEventListener('paste', function(e) {
        e.preventDefault();
        const text = (e.clipboardData || window.clipboardData).getData('text');
        if (text) {
            const pastedTokens = text.split(/[,;\n\r\t]+/).map(s => s.trim()).filter(Boolean);
            pastedTokens.forEach(t => addSkill(t));
        }
    });

    // Live autocomplete dropdown search as user types
    textInput.addEventListener('input', function() {
        const query = this.value.trim().toLowerCase();
        if (query.length < 1) {
            hideDropdown();
            return;
        }

        // Search local popular skills
        let matches = POPULAR_SKILLS.filter(s => {
            return s.toLowerCase().includes(query) && !skills.some(existing => existing.toLowerCase() === s.toLowerCase());
        }).slice(0, 8);

        renderDropdown(matches, query);
    });

    function renderDropdown(items, query) {
        currentSuggestions = items;
        activeDropdownIndex = -1;

        if (items.length === 0) {
            hideDropdown();
            return;
        }

        dropdown.innerHTML = '';
        items.forEach((item, idx) => {
            const div = document.createElement('div');
            div.className = 'skills-dropdown-item';
            div.innerHTML = `
                <span><i class="fa-solid fa-star" style="color: #f59e0b; font-size: 0.75rem; margin-right: 8px;"></i>${highlightMatch(item, query)}</span>
                <span class="badge-type">Skill</span>
            `;

            div.addEventListener('click', function(e) {
                e.stopPropagation();
                addSkill(item);
                textInput.focus();
            });

            dropdown.appendChild(div);
        });

        dropdown.style.display = 'block';
    }

    function highlightMatch(text, query) {
        const regex = new RegExp(`(${query.replace(/[.*+?^${}()|[\]\\]/g, '\\$&')})`, 'gi');
        return escapeHtml(text).replace(regex, '<strong style="color: #00a884;">$1</strong>');
    }

    function highlightDropdownItem() {
        const items = dropdown.querySelectorAll('.skills-dropdown-item');
        items.forEach((item, idx) => {
            if (idx === activeDropdownIndex) {
                item.classList.add('is-active');
                item.scrollIntoView({ block: 'nearest' });
            } else {
                item.classList.remove('is-active');
            }
        });
    }

    function hideDropdown() {
        dropdown.style.display = 'none';
        currentSuggestions = [];
        activeDropdownIndex = -1;
    }

    // Close dropdown when clicked outside
    document.addEventListener('click', function(e) {
        if (!box.contains(e.target) && !dropdown.contains(e.target)) {
            hideDropdown();
        }
    });

    // Form submission validation: Ensure at least one skill if required
    const parentForm = box.closest('form');
    if (parentForm) {
        parentForm.addEventListener('submit', function(e) {
            // If user has typed something in text input but didn't press enter, add it before submit
            if (textInput.value.trim()) {
                addSkill(textInput.value.trim());
            }

            @if($isRequired)
            if (skills.length === 0) {
                e.preventDefault();
                box.classList.add('is-invalid');
                textInput.focus();

                // Show alert or shake
                let errorNotice = box.parentElement.querySelector('.skills-required-notice');
                if (!errorNotice) {
                    errorNotice = document.createElement('div');
                    errorNotice.className = 'skills-required-notice';
                    errorNotice.style.cssText = 'color: #ef4444; font-size: 0.82rem; font-weight: 600; margin-top: 6px; display: flex; align-items: center; gap: 6px;';
                    errorNotice.innerHTML = '<i class="fa-solid fa-circle-exclamation"></i> Please enter and select at least one skill.';
                    box.parentElement.appendChild(errorNotice);
                }
                return false;
            } else {
                box.classList.remove('is-invalid');
                const errorNotice = box.parentElement.querySelector('.skills-required-notice');
                if (errorNotice) errorNotice.remove();
            }
            @endif
        });
    }

    // Initial render
    renderChips();
})();
</script>
