@props(['name', 'value' => '', 'placeholder' => 'dd-mm-yyyy', 'required' => false, 'class' => ''])

<div class="relative">
    <input 
        type="text" 
        name="{{ $name }}" 
        value="{{ $value }}" 
        placeholder="{{ $placeholder }}"
        {{ $required ? 'required' : '' }}
        class="w-full border rounded px-4 py-2 bg-white shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 {{ $class }}"
        data-date-picker
        autocomplete="off"
    />
    <button 
        type="button" 
        class="absolute right-2 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600"
        data-date-picker-trigger
    >
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
        </svg>
    </button>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize date pickers
    const datePickers = document.querySelectorAll('[data-date-picker]');
    
    datePickers.forEach(function(input) {
        const trigger = input.parentElement.querySelector('[data-date-picker-trigger]');
        
        // Create date picker container
        const pickerContainer = document.createElement('div');
        pickerContainer.className = 'absolute top-full left-0 mt-1 bg-white border rounded-lg shadow-lg z-50 hidden';
        pickerContainer.style.minWidth = '280px';
        pickerContainer.innerHTML = `
            <div class="p-3">
                <div class="flex justify-between items-center mb-3">
                    <button type="button" class="prev-month text-gray-600 hover:text-gray-800">&lt;</button>
                    <span class="current-month font-semibold"></span>
                    <button type="button" class="next-month text-gray-600 hover:text-gray-800">&gt;</button>
                </div>
                <div class="grid grid-cols-7 gap-1 text-sm">
                    <div class="text-center text-gray-500 font-medium">Su</div>
                    <div class="text-center text-gray-500 font-medium">Mo</div>
                    <div class="text-center text-gray-500 font-medium">Tu</div>
                    <div class="text-center text-gray-500 font-medium">We</div>
                    <div class="text-center text-gray-500 font-medium">Th</div>
                    <div class="text-center text-gray-500 font-medium">Fr</div>
                    <div class="text-center text-gray-500 font-medium">Sa</div>
                </div>
                <div class="calendar-days grid grid-cols-7 gap-1 mt-2"></div>
            </div>
        `;
        
        input.parentElement.appendChild(pickerContainer);
        
        let currentDate = new Date();
        let selectedDate = null;
        
        // Parse existing value
        if (input.value) {
            const parts = input.value.split('-');
            if (parts.length === 3) {
                selectedDate = new Date(parts[2], parts[1] - 1, parts[0]);
                currentDate = new Date(selectedDate);
            }
        }
        
        function formatDate(date) {
            const day = String(date.getDate()).padStart(2, '0');
            const month = String(date.getMonth() + 1).padStart(2, '0');
            const year = date.getFullYear();
            return `${day}-${month}-${year}`;
        }
        
        function renderCalendar() {
            const year = currentDate.getFullYear();
            const month = currentDate.getMonth();
            
            const firstDay = new Date(year, month, 1);
            const lastDay = new Date(year, month + 1, 0);
            const startDate = new Date(firstDay);
            startDate.setDate(startDate.getDate() - firstDay.getDay());
            
            const monthNames = ['January', 'February', 'March', 'April', 'May', 'June',
                              'July', 'August', 'September', 'October', 'November', 'December'];
            
            pickerContainer.querySelector('.current-month').textContent = `${monthNames[month]} ${year}`;
            
            const daysContainer = pickerContainer.querySelector('.calendar-days');
            daysContainer.innerHTML = '';
            
            for (let i = 0; i < 42; i++) {
                const date = new Date(startDate);
                date.setDate(startDate.getDate() + i);
                
                const dayElement = document.createElement('div');
                dayElement.className = 'text-center py-2 cursor-pointer hover:bg-blue-100 rounded';
                
                if (date.getMonth() !== month) {
                    dayElement.className += ' text-gray-300';
                } else if (selectedDate && date.toDateString() === selectedDate.toDateString()) {
                    dayElement.className += ' bg-blue-500 text-white hover:bg-blue-600';
                }
                
                dayElement.textContent = date.getDate();
                dayElement.addEventListener('click', function() {
                    if (date.getMonth() === month) {
                        selectedDate = date;
                        input.value = formatDate(date);
                        pickerContainer.classList.add('hidden');
                        input.focus();
                    }
                });
                
                daysContainer.appendChild(dayElement);
            }
        }
        
        // Event listeners
        trigger.addEventListener('click', function(e) {
            e.preventDefault();
            pickerContainer.classList.toggle('hidden');
            if (!pickerContainer.classList.contains('hidden')) {
                renderCalendar();
            }
        });
        
        pickerContainer.querySelector('.prev-month').addEventListener('click', function() {
            currentDate.setMonth(currentDate.getMonth() - 1);
            renderCalendar();
        });
        
        pickerContainer.querySelector('.next-month').addEventListener('click', function() {
            currentDate.setMonth(currentDate.getMonth() + 1);
            renderCalendar();
        });
        
        // Close picker when clicking outside
        document.addEventListener('click', function(e) {
            if (!input.parentElement.contains(e.target)) {
                pickerContainer.classList.add('hidden');
            }
        });
        
        // Handle manual input validation
        input.addEventListener('blur', function() {
            const value = input.value.trim();
            if (value) {
                const parts = value.split('-');
                if (parts.length === 3) {
                    const day = parseInt(parts[0]);
                    const month = parseInt(parts[1]) - 1;
                    const year = parseInt(parts[2]);
                    
                    if (!isNaN(day) && !isNaN(month) && !isNaN(year)) {
                        const date = new Date(year, month, day);
                        if (date.getDate() === day && date.getMonth() === month && date.getFullYear() === year) {
                            selectedDate = date;
                            currentDate = new Date(date);
                            input.value = formatDate(date);
                            return;
                        }
                    }
                }
                // Invalid date format
                input.value = '';
                selectedDate = null;
            }
        });
        
        // Initial render
        renderCalendar();
    });
});
</script>
