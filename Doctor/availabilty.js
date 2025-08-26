 // This function runs when the page loads
        document.addEventListener('DOMContentLoaded', function() {
            
            // Get references to the time input fields
            const startTimeInput = document.getElementById('start_time');
            const endTimeInput = document.getElementById('end_time');
            const dayCheckboxes = document.querySelectorAll('input[name="days_of_week[]"]');
            
            // When start time changes, make sure end time is after it
            startTimeInput.addEventListener('change', function() {
                if (startTimeInput.value && endTimeInput.value) {
                    if (startTimeInput.value >= endTimeInput.value) {
                        alert('⚠️ Start time must be before end time!');
                        startTimeInput.focus();
                    }
                }
            });
            
            // When end time changes, make sure it's after start time
            endTimeInput.addEventListener('change', function() {
                if (startTimeInput.value && endTimeInput.value) {
                    if (endTimeInput.value <= startTimeInput.value) {
                        alert('⚠️ End time must be after start time!');
                        endTimeInput.focus();
                    }
                }
            });
            
            // Add "Select All" and "Clear All" functionality
            const daysContainer = document.querySelector('.days-container');
            
            // Create helper buttons
            const helperButtons = document.createElement('div');
            helperButtons.style.textAlign = 'center';
            helperButtons.style.marginBottom = '15px';
            helperButtons.innerHTML = `
                <button type="button" id="selectAll" style="background: #27ae60; color: white; border: none; padding: 8px 15px; border-radius: 5px; margin: 0 5px; cursor: pointer;">
                    ✓ Select All Days
                </button>
                <button type="button" id="clearAll" style="background: #e74c3c; color: white; border: none; padding: 8px 15px; border-radius: 5px; margin: 0 5px; cursor: pointer;">
                    ✗ Clear All Days
                </button>
            `;
            
            daysContainer.parentNode.insertBefore(helperButtons, daysContainer);
            
            // Select All button functionality
            document.getElementById('selectAll').addEventListener('click', function() {
                dayCheckboxes.forEach(checkbox => {
                    checkbox.checked = true;
                });
            });
            
            // Clear All button functionality
            document.getElementById('clearAll').addEventListener('click', function() {
                dayCheckboxes.forEach(checkbox => {
                    checkbox.checked = false;
                });
            });
            
            // Form validation before submit
            document.querySelector('form').addEventListener('submit', function(e) {
                const checkedDays = document.querySelectorAll('input[name="days_of_week[]"]:checked');
                
                if (checkedDays.length === 0) {
                    e.preventDefault();
                    alert('⚠️ Please select at least one day of the week!');
                    return false;
                }
                
                if (!startTimeInput.value || !endTimeInput.value) {
                    e.preventDefault();
                    alert('⚠️ Please fill in both start and end times!');
                    return false;
                }
                
                if (startTimeInput.value >= endTimeInput.value) {
                    e.preventDefault();
                    alert('⚠️ Start time must be before end time!');
                    return false;
                }
            });
        });